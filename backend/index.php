<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/rest/config.php';
require_once __DIR__ . '/data/roles.php';

// Load base + services
require_once __DIR__ . '/rest/services/BaseService.php';
require_once __DIR__ . '/rest/services/AuthService.php';
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/BarberService.php';
require_once __DIR__ . '/rest/services/ServiceService.php';
require_once __DIR__ . '/rest/services/AppointmentService.php';
require_once __DIR__ . '/rest/services/ReviewService.php';
require "middleware/AuthMiddleware.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// 🔧 Registracija servisa
Flight::register('auth_service',       'AuthService');
Flight::register('userService',        'UserService');
Flight::register('barberService',      'BarberService');
Flight::register('serviceService',     'ServiceService');
Flight::register('appointmentService', 'AppointmentService');
Flight::register('reviewService',      'ReviewService');
Flight::register('auth_middleware', "AuthMiddleware");

Flight::before('start', function(&$params, &$output){
    $url = Flight::request()->url;

    // public routes (no token required)
    if (
        str_starts_with($url, '/auth/login') ||
        str_starts_with($url, '/auth/register')
    ) {
        return TRUE;
    }

    try {
        $authHeader = Flight::request()->getHeader("Authorization");
        /*
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Flight::halt(401, "Missing or invalid Authorization header");
        }

        $token = $matches[1];
        $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

        // Save user info globally
        Flight::set('user', $decoded_token->user);
        Flight::set('jwt_token', $token);
        return TRUE;
        */

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Flight::halt(401, "Missing or invalid Authorization header");
        }

        $token = $matches[1];

        Flight::auth_middleware()->verifyToken($token);

    } catch (Exception $e) {
        Flight::halt(401, "Invalid or expired token: " . $e->getMessage());
    }
});

// 🔀 Učitaj sve route fajlove
require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/BarberRoutes.php';
require_once __DIR__ . '/rest/routes/ServiceRoutes.php';
require_once __DIR__ . '/rest/routes/AppointmentRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';

Flight::route('GET /test', function () {
    // Bilo koja autorizacija će natjerati middleware da pročita token
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    $user = Flight::get('user');

    Flight::json([
        'message' => 'Protected route OK',
        'user'    => $user
    ]);
});


Flight::start();

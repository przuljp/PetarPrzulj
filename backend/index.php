<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

// Load all services
require_once 'rest/services/UserService.php';
require_once 'rest/services/BarberService.php';
require_once 'rest/services/ServiceService.php';
require_once 'rest/services/AppointmentService.php';
require_once 'rest/services/ReviewService.php';

Flight::register('userService', 'UserService');
Flight::register('barberService', 'BarberService');
Flight::register('serviceService', 'ServiceService');
Flight::register('appointmentService', 'AppointmentService');
Flight::register('reviewService', 'ReviewService');

// Include ALL route files
require_once 'rest/routes/userRoutes.php';
require_once 'rest/routes/barberRoutes.php';
require_once 'rest/routes/serviceRoutes.php';
require_once 'rest/routes/appointmentRoutes.php';
require_once 'rest/routes/reviewRoutes.php';

// Test route
Flight::route('GET /test', function(){
    echo 'ALL ROUTES LOADED - TEST WORKS!';
});

Flight::start();
?>
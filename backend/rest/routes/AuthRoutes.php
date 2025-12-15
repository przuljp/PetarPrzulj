<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @OA\Post(
 *     path="/auth/register",
 *     summary="Register new user.",
 *     description="Add a new user to the database.",
 *     tags={"auth"},
 *     @OA\RequestBody(
 *         description="User registration data",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={"email", "password", "name"},
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     example="Petar Przulj",
 *                     description="Full name of the user"
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     example="demo@gmail.com",
 *                     description="User email address"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string",
 *                     example="some_password",
 *                     description="User password"
 *                 ),
 *                 @OA\Property(
 *                     property="role",
 *                     type="string",
 *                     example="user",
 *                     description="User role: 'admin' or 'user' (default: 'user')"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User successfully registered"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     )
 * )
 */
Flight::route("POST /auth/register", function () {
    $data = Flight::request()->data->getData();

    // API-level validation (fallback if Swagger is ignored)
    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        Flight::halt(400, "Name, email and password are required.");
    }

    // ROLE HANDLING
    // Ako role nije poslana → default je user
    if (empty($data['role'])) {
        $data['role'] = Roles::USER;
    } else {
        // Normalizacija unosa (admin / ADMIN / Admin → uvijek ista vrijednost)
        $role = strtolower($data['role']);

        if ($role === 'admin') {
            $data['role'] = Roles::ADMIN;
        } else if ($role === 'user') {
            $data['role'] = Roles::USER;
        } else {
            // Ako pošalju nešto treće → možeš ili:
            // 1) vratiti grešku:
            Flight::halt(400, "Invalid role. Allowed values are 'admin' or 'user'.");
            // 2) ili fallback na user (ako više voliš tako, umjesto halt):
            // $data['role'] = Roles::USER;
        }
    }

    $response = Flight::auth_service()->register($data);

    if ($response['success']) {
        Flight::json([
            'message' => 'User registered successfully',
            'data'    => $response['data']
        ]);
    } else {
        Flight::halt(400, $response['error']);
    }
});


/**
 * @OA\Post(
 *      path="/auth/login",
 *      tags={"auth"},
 *      summary="Login using email and password",
 *      @OA\RequestBody(
 *          description="Login credentials",
 *          required=true,
 *          @OA\JsonContent(
 *              required={"email","password"},
 *              @OA\Property(property="email", type="string", example="demo@gmail.com"),
 *              @OA\Property(property="password", type="string", example="some_password")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="User data and JWT token"
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Invalid credentials"
 *      )
 * )
 */
Flight::route('POST /auth/login', function() {
    $data = Flight::request()->data->getData();

    if (empty($data['email']) || empty($data['password'])) {
        Flight::halt(400, "Email and password are required.");
    }

    $response = Flight::auth_service()->login($data);

    if ($response['success']) {
        Flight::json([
            'message' => 'User logged in successfully',
            'data'    => $response['data']
        ]);
    } else {
        Flight::halt(400, $response['error']);
    }
});

<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API",
 *     description="Barbershop API",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="web2001programming@gmail.com",
 *         name="Web Programming"
 *     )
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost/PetarPrzulj/backend",
 *     description="API server"
 * )
 */
/**
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Paste your JWT token here in the format: Bearer {token}"
 * )
*/

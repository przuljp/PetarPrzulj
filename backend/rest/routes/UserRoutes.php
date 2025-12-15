<?php

/**
 * @OA\Get(
 *      path="/user/{id}",
 *      tags={"users"},
 *      summary="Get user by ID",
 *      description="Return user data for a specific user ID.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="User ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="User data returned"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden - only admin can access this resource"
 *      )
 * )
 */
Flight::route('GET /user/@id', function($id){
    // SAMO admin može vidjeti bilo kojeg korisnika
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    Flight::json(Flight::userService()->getUserById($id));
});


/**
 * @OA\Get(
 *      path="/user",
 *      tags={"users"},
 *      summary="Get all users",
 *      description="Returns a list of all registered users.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Response(
 *          response=200,
 *          description="List of all users"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden - only admin can access this resource"
 *      )
 * )
 */
Flight::route('GET /user', function(){
    // SAMO admin vidi listu svih korisnika
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    Flight::json(Flight::userService()->getAll());
});


/**
 * @OA\Post(
 *      path="/user",
 *      tags={"users"},
 *      summary="Create new user",
 *      description="Add a new user into the system.",
 *      security={{"BearerAuth":{}}},
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"email", "password", "name"},
 *              @OA\Property(property="email", type="string", example="example@mail.com"),
 *              @OA\Property(property="password", type="string", example="strongPass123"),
 *              @OA\Property(property="name", type="string", example="John Doe"),
 *              @OA\Property(property="role", type="string", example="customer", description="Possible values: admin, customer")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="User successfully created"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden - only admin can access this resource"
 *      )
 * )
 */
Flight::route('POST /user', function(){
    // Kreiranje usera – SAMO admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->insert($data));
});


/**
 * @OA\Put(
 *      path="/user/{id}",
 *      tags={"users"},
 *      summary="Update an existing user",
 *      description="Replace full user record with new data.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="User ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"email", "name", "role"},
 *              @OA\Property(property="email", type="string", example="updated@mail.com"),
 *              @OA\Property(property="name", type="string", example="Updated Name"),
 *              @OA\Property(property="role", type="string", example="admin")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="User successfully updated"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden - only admin can access this resource"
 *      )
 * )
 */
Flight::route('PUT /user/@id', function($id){
    // Full update usera – SAMO admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->update($id, $data));
});


/**
 * @OA\Patch(
 *      path="/user/{id}",
 *      tags={"users"},
 *      summary="Partially update user",
 *      description="Update only specific user fields.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="User ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="email", type="string", example="partially@mail.com"),
 *              @OA\Property(property="name", type="string", example="Partial Update")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="User partially updated"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden - only admin can access this resource"
 *      )
 * )
 */
Flight::route('PATCH /user/@id', function($id){
    // Partial update usera – SAMO admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->update($id, $data));
});


/**
 * @OA\Delete(
 *      path="/user/{id}",
 *      tags={"users"},
 *      summary="Delete user by ID",
 *      description="Remove a user account from the database.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="User ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="User successfully deleted"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden - only admin can access this resource"
 *      )
 * )
 */
Flight::route('DELETE /user/@id', function($id){
    // Brisanje usera – SAMO admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    Flight::json(Flight::userService()->delete($id));
});

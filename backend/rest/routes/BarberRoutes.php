<?php

/**
 * @OA\Get(
 *      path="/barber/{id}",
 *      tags={"barbers"},
 *      summary="Get barber by ID",
 *      description="Returns detailed data about a specific barber.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Barber ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(response=200, description="Barber data returned")
 * )
 */
Flight::route('GET /barber/@id', function($id){
    // Admin + User mogu čitati pojedinačnog barbera
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    Flight::json(Flight::barberService()->getById($id));
});


/**
 * @OA\Get(
 *      path="/barber",
 *      tags={"barbers"},
 *      summary="Get all barbers",
 *      description="Returns a list of all barbers.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Response(response=200, description="List of barbers")
 * )
 */
Flight::route('GET /barber', function(){
    // Admin + User mogu vidjeti listu barbera
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    Flight::json(Flight::barberService()->getAll());
});


/**
 * @OA\Post(
 *      path="/barber",
 *      tags={"barbers"},
 *      summary="Create a new barber",
 *      description="Add a new barber to the barbershop.",
 *      security={{"BearerAuth":{}}},
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"name","specialty"},
 *              @OA\Property(property="name", type="string", example="Marko Barber"),
 *              @OA\Property(property="specialty", type="string", example="Fade haircut")
 *          )
 *      ),
 *      @OA\Response(response=200, description="Barber created successfully")
 * )
 */
Flight::route('POST /barber', function(){
    // Kreiranje barbera – samo admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    Flight::json(Flight::barberService()->insert($data));
});


/**
 * @OA\Put(
 *      path="/barber/{id}",
 *      tags={"barbers"},
 *      summary="Update barber fully",
 *      description="Replace all fields of an existing barber.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Barber ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"name","specialty"},
 *              @OA\Property(property="name", type="string", example="Updated Barber"),
 *              @OA\Property(property="specialty", type="string", example="Beard trim")
 *          )
 *      ),
 *      @OA\Response(response=200, description="Barber updated successfully")
 * )
 */
Flight::route('PUT /barber/@id', function($id){
    // Full update – samo admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    Flight::json(Flight::barberService()->update($id, $data));
});


/**
 * @OA\Patch(
 *      path="/barber/{id}",
 *      tags={"barbers"},
 *      summary="Partially update barber",
 *      description="Modify only some fields for a barber.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Barber ID",
 *          @OA\Schema(type="integer", example=2)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="name", type="string", example="Partial Edit"),
 *              @OA\Property(property="specialty", type="string", example="Line-up")
 *          )
 *      ),
 *      @OA\Response(response=200, description="Barber partially updated")
 * )
 */
Flight::route('PATCH /barber/@id', function($id){
    // Partial update – samo admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    Flight::json(Flight::barberService()->update($id, $data));
});


/**
 * @OA\Delete(
 *      path="/barber/{id}",
 *      tags={"barbers"},
 *      summary="Delete barber",
 *      description="Remove a barber from the system.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Barber ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(response=200, description="Barber deleted successfully")
 * )
 */
Flight::route('DELETE /barber/@id', function($id){
    // Brisanje barbera – samo admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    Flight::json(Flight::barberService()->delete($id));
});

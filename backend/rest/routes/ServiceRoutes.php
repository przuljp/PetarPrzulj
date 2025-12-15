<?php
/**
 * @OA\Get(
 *      path="/service/{id}",
 *      tags={"services"},
 *      summary="Get service by ID",
 *      description="Retrieve details for a specific service using its ID.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Service ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Service data for the given ID"
 *      )
 * )
 */
Flight::route('GET /service/@id', function($id){
    // Admin + User mogu čitati servis
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    Flight::json(Flight::serviceService()->getById($id));
});

/**
 * @OA\Get(
 *      path="/service",
 *      tags={"services"},
 *      summary="Get all services or filter by name",
 *      description="Retrieve a list of all services, or filter by name using a query parameter.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="name",
 *          in="query",
 *          required=false,
 *          description="Filter services by name",
 *          @OA\Schema(type="string", example="Haircut")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="List of services (optionally filtered)"
 *      )
 * )
 */
Flight::route('GET /service', function(){
    // Admin + User mogu gledati listu servisa
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    $name = Flight::request()->query['name'] ?? null;
    if ($name) {
        Flight::json(Flight::serviceService()->getByName($name));
    } else {
        Flight::json(Flight::serviceService()->getAll());
    }
});

/**
 * @OA\Post(
 *      path="/service",
 *      tags={"services"},
 *      summary="Add a new service",
 *      description="Create a new service offered by the barber shop.",
 *      security={{"BearerAuth":{}}},
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"name", "price", "duration"},
 *              @OA\Property(property="name", type="string", example="Haircut"),
 *              @OA\Property(property="price", type="number", format="float", example=15.99),
 *              @OA\Property(property="duration", type="integer", example=30, description="Duration in minutes"),
 *              @OA\Property(property="description", type="string", example="Standard men's haircut.")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="New service successfully created"
 *      )
 * )
 */
Flight::route('POST /service', function(){
    // Kreiranje servisa – samo admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    Flight::json(Flight::serviceService()->insert($data));
});

/**
 * @OA\Put(
 *      path="/service/{id}",
 *      tags={"services"},
 *      summary="Update service by ID",
 *      description="Fully update an existing service's information.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Service ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"name", "price", "duration"},
 *              @OA\Property(property="name", type="string", example="Beard Trim"),
 *              @OA\Property(property="price", type="number", format="float", example=10.00),
 *              @OA\Property(property="duration", type="integer", example=20),
 *              @OA\Property(property="description", type="string", example="Trimming and shaping of beard.")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Service successfully updated"
 *      )
 * )
 */
Flight::route('PUT /service/@id', function($id){
    // Update servisa – samo admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    Flight::json(Flight::serviceService()->update($id, $data));
});

/**
 * @OA\Patch(
 *      path="/service/{id}",
 *      tags={"services"},
 *      summary="Partially update service by ID",
 *      description="Update selected fields of a service without overwriting all data.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Service ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="price", type="number", format="float", example=12.50),
 *              @OA\Property(property="duration", type="integer", example=40)
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Service partially updated"
 *      )
 * )
 */
Flight::route('PATCH /service/@id', function($id){
    // Partial update – isto admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    Flight::json(Flight::serviceService()->update($id, $data));
});

/**
 * @OA\Delete(
 *      path="/service/{id}",
 *      tags={"services"},
 *      summary="Delete service by ID",
 *      description="Remove a service from the system using its ID.",
 *      security={{"BearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Service ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Service successfully deleted"
 *      )
 * )
 */
Flight::route('DELETE /service/@id', function($id){
    // Brisanje servisa – samo admin
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    Flight::json(Flight::serviceService()->delete($id));
});

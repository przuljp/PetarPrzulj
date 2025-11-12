<?php
/**
 * @OA\Get(
 *      path="/appointment/{id}",
 *      tags={"appointments"},
 *      summary="Get appointment by ID",
 *      description="Returns a specific appointment based on its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Appointment ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Appointment data for the given ID"
 *      )
 * )
 */
Flight::route('GET /appointment/@id', function($id){
    Flight::json(Flight::appointmentService()->get_appointment_by_id($id));
});

/**
 * @OA\Get(
 *      path="/appointment",
 *      tags={"appointments"},
 *      summary="Get all appointments or filter by user_id/barber_id",
 *      description="Fetch all appointments, or filter by user_id or barber_id using query parameters.",
 *      @OA\Parameter(
 *          name="user_id",
 *          in="query",
 *          required=false,
 *          description="Filter appointments by user ID",
 *          @OA\Schema(type="integer", example=2)
 *      ),
 *      @OA\Parameter(
 *          name="barber_id",
 *          in="query",
 *          required=false,
 *          description="Filter appointments by barber ID",
 *          @OA\Schema(type="integer", example=3)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="List of appointments (optionally filtered)"
 *      )
 * )
 */
Flight::route('GET /appointment', function(){
    $user_id = Flight::request()->query['user_id'] ?? null;
    $barber_id = Flight::request()->query['barber_id'] ?? null;
    
    if ($user_id) {
        Flight::json(Flight::appointmentService()->get_appointment_by_user_id($user_id));
    } elseif ($barber_id) {
        Flight::json(Flight::appointmentService()->get_appointment_by_barber_id($barber_id));
    } else {
        Flight::json(Flight::appointmentService()->getAll());
    }
});

/**
 * @OA\Post(
 *      path="/appointment",
 *      tags={"appointments"},
 *      summary="Create a new appointment",
 *      description="Add a new appointment to the database.",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"user_id", "barber_id", "appointment_date", "status"},
 *              @OA\Property(property="user_id", type="integer", example=2),
 *              @OA\Property(property="barber_id", type="integer", example=5),
 *              @OA\Property(property="appointment_date", type="string", format="date-time", example="2025-11-15T14:00:00"),
 *              @OA\Property(property="status", type="string", example="scheduled")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="New appointment successfully created"
 *      )
 * )
 */
Flight::route('POST /appointment', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::appointmentService()->create($data));
});

/**
 * @OA\Put(
 *      path="/appointment/{id}",
 *      tags={"appointments"},
 *      summary="Update an existing appointment by ID",
 *      description="Replace all fields of an existing appointment with new values.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Appointment ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"user_id", "barber_id", "appointment_date", "status"},
 *              @OA\Property(property="user_id", type="integer", example=2),
 *              @OA\Property(property="barber_id", type="integer", example=5),
 *              @OA\Property(property="appointment_date", type="string", format="date-time", example="2025-11-15T14:00:00"),
 *              @OA\Property(property="status", type="string", example="completed")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Appointment successfully updated"
 *      )
 * )
 */
Flight::route('PUT /appointment/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::appointmentService()->update($id, $data));
});

/**
 * @OA\Patch(
 *      path="/appointment/{id}",
 *      tags={"appointments"},
 *      summary="Partially update appointment by ID",
 *      description="Update only specific fields of an existing appointment.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Appointment ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="appointment_date", type="string", format="date-time", example="2025-11-18T10:30:00"),
 *              @OA\Property(property="status", type="string", example="cancelled")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Appointment partially updated"
 *      )
 * )
 */
Flight::route('PATCH /appointment/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::appointmentService()->update($id, $data));
});

/**
 * @OA\Delete(
 *      path="/appointment/{id}",
 *      tags={"appointments"},
 *      summary="Delete appointment by ID",
 *      description="Remove an appointment record from the database.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Appointment ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Appointment successfully deleted"
 *      )
 * )
 */
Flight::route('DELETE /appointment/@id', function($id){
    Flight::json(Flight::appointmentService()->delete($id));
});

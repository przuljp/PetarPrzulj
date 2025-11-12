<?php
/**
 * @OA\Get(
 *      path="/review/{id}",
 *      tags={"reviews"},
 *      summary="Get review by ID",
 *      description="Fetch a single review using its unique ID.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Review ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Review data for the given ID"
 *      )
 * )
 */
Flight::route('GET /review/@id', function($id){
    Flight::json(Flight::reviewService()->get_review_by_id($id));
});

/**
 * @OA\Get(
 *      path="/review",
 *      tags={"reviews"},
 *      summary="Get all reviews or filter by user_id/barber_id",
 *      description="Retrieve all reviews, or filter by user_id or barber_id using query parameters.",
 *      @OA\Parameter(
 *          name="user_id",
 *          in="query",
 *          required=false,
 *          description="Filter reviews by user ID",
 *          @OA\Schema(type="integer", example=2)
 *      ),
 *      @OA\Parameter(
 *          name="barber_id",
 *          in="query",
 *          required=false,
 *          description="Filter reviews by barber ID",
 *          @OA\Schema(type="integer", example=5)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="List of reviews (optionally filtered)"
 *      )
 * )
 */
Flight::route('GET /review', function(){
    $user_id = Flight::request()->query['user_id'] ?? null;
    $barber_id = Flight::request()->query['barber_id'] ?? null;
    
    if ($user_id) {
        Flight::json(Flight::reviewService()->get_review_by_user_id($user_id));
    } elseif ($barber_id) {
        Flight::json(Flight::reviewService()->get_review_by_barber_id($barber_id));
    } else {
        Flight::json(Flight::reviewService()->getAll());
    }
});

/**
 * @OA\Post(
 *      path="/review",
 *      tags={"reviews"},
 *      summary="Add a new review",
 *      description="Create a new review for a barber by a user.",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"user_id", "barber_id", "rating", "comment"},
 *              @OA\Property(property="user_id", type="integer", example=2),
 *              @OA\Property(property="barber_id", type="integer", example=5),
 *              @OA\Property(property="rating", type="number", format="float", example=4.5),
 *              @OA\Property(property="comment", type="string", example="Great haircut and friendly service!")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="New review successfully created"
 *      )
 * )
 */
Flight::route('POST /review', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->create($data));
});

/**
 * @OA\Put(
 *      path="/review/{id}",
 *      tags={"reviews"},
 *      summary="Update a review by ID",
 *      description="Fully update an existing review with new data.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Review ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"user_id", "barber_id", "rating", "comment"},
 *              @OA\Property(property="user_id", type="integer", example=2),
 *              @OA\Property(property="barber_id", type="integer", example=5),
 *              @OA\Property(property="rating", type="number", format="float", example=5.0),
 *              @OA\Property(property="comment", type="string", example="Updated review comment.")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Review successfully updated"
 *      )
 * )
 */
Flight::route('PUT /review/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->update($id, $data));
});

/**
 * @OA\Patch(
 *      path="/review/{id}",
 *      tags={"reviews"},
 *      summary="Partially update review by ID",
 *      description="Update one or more fields of a review without overwriting all fields.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Review ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="rating", type="number", format="float", example=3.5),
 *              @OA\Property(property="comment", type="string", example="Changed my mind, average experience.")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Review partially updated"
 *      )
 * )
 */
Flight::route('PATCH /review/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->update($id, $data));
});

/**
 * @OA\Delete(
 *      path="/review/{id}",
 *      tags={"reviews"},
 *      summary="Delete review by ID",
 *      description="Remove a review from the system using its ID.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Review ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Review successfully deleted"
 *      )
 * )
 */
Flight::route('DELETE /review/@id', function($id){
    Flight::json(Flight::reviewService()->delete($id));
});

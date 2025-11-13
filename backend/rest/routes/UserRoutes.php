<?php
// Get a specific user by ID
Flight::route('GET /user/@id', function($id){
    Flight::json(Flight::userService()->get_user_by_id($id));
});

// Get users with optional email query
Flight::route('GET /user', function(){
    $email = Flight::request()->query['email'] ?? null;
    if ($email) {
        Flight::json(Flight::userService()->get_by_email($email));
    } else {
        Flight::json(Flight::userService()->getAll());
    }
});

// Add a new user (registration) - MAKE SURE THIS ROUTE EXISTS
Flight::route('POST /user', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->create($data));
});

// Update user by ID
Flight::route('PUT /user/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->update($id, $data));
});

// Partially update user by ID
Flight::route('PATCH /user/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->update($id, $data));
});

// Delete user by ID
Flight::route('DELETE /user/@id', function($id){
    Flight::json(Flight::userService()->delete($id));
});
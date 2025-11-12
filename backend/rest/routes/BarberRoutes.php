<?php
// Get a specific barber by ID
Flight::route('GET /barber/@id', function($id){
    Flight::json(Flight::barberService()->getById($id));
});

// Get barbers with optional name query
Flight::route('GET /barber', function(){
    $name = Flight::request()->query['name'] ?? null;
    if ($name) {
        Flight::json(Flight::barberService()->get_by_name($name));
    } else {
        Flight::json(Flight::barberService()->getAll());
    }
});

// Add a new barber
Flight::route('POST /barber', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::barberService()->create($data));
});

// Update barber by ID
Flight::route('PUT /barber/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::barberService()->update($id, $data));
});

// Partially update barber by ID
Flight::route('PATCH /barber/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::barberService()->update($id, $data));
});

// Delete barber by ID
Flight::route('DELETE /barber/@id', function($id){
    Flight::json(Flight::barberService()->delete($id));
});
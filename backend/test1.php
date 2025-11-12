<?php
require 'vendor/autoload.php';

// Set base path if your project is in a subdirectory
Flight::set('flight.base_url', '/PetarPrzulj/backend/');

Flight::route('/', function(){
    echo 'ROOT WORKS!';
});

Flight::route('/hello', function(){
    echo 'HELLO WORKS!';
});

Flight::start();
?>
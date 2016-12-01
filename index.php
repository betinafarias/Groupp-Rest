<?php

require 'vendor/autoload.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');
header('Access-Control-Allow-Origin: *');

$app->get('/', function () {
	echo "Groupp";
});


// -----------------------------------
// Musician
// -----------------------------------

//Get all musicians
$app->get('/musicians', function() use ($app){
	(new \controllers\Musician($app))->getAll();
});


//Get all musicians and their compatibility with the logged user
$app->get('/musicians/:id/compatibilities', function($id) use ($app){
	(new \controllers\Musician($app))->getCompatibilities($id);
});

//Get a musician
$app->get('/musicians/:id', function($id) use ($app){
	(new \controllers\Musician($app))->get($id);
});

//Create musician
$app->post('/musicians', function() use ($app){
	(new \controllers\Musician($app))->add();
});

//Update musician
$app->post('/musicians/:id', function($id) use ($app){
	(new \controllers\Musician($app))->save($id);
});

//Delete musician
$app->delete('/musicians/:id', function($id) use ($app){
	(new \controllers\Musician($app))->delete($id);
});

//Login 
$app->post('/login', function() use ($app){
	(new \controllers\Musician($app))->login();
});





// -----------------------------------
// Artist
// -----------------------------------

//Relates an spotify artist with an musician 
$app->post('/artists/:musicianId', function($musicianId) use ($app){
	(new \controllers\Artist($app))->add($musicianId);
});

// -----------------------------------
// States
// -----------------------------------

// Get all states
$app->get('/states', function() use ($app){
	(new \controllers\State($app))->getAll();
});

// -----------------------------------
// Cities
// -----------------------------------

// Get all cities from a state
$app->get('/cities/:stateId', function($stateId) use ($app){
	(new \controllers\City($app))->getAllFromState($stateId);
});

// -----------------------------------
// Instruments
// -----------------------------------

// Get all instruments
$app->get('/instruments', function() use ($app){
	(new \controllers\Instrument($app))->getAll();
});


$app->run();



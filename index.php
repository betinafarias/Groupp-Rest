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

//Get a musician
$app->get('/musicians/:id', function() use ($app){
	(new \controllers\Musician($app))->get();
});

//Create musician
$app->post('/musicians', function() use ($app){
	(new \controllers\Musician($app))->add();
});

//Update musician
$app->post('/musicians/:id', function() use ($app){
	(new \controllers\Musician($app))->save();
});

//Delete musician
$app->delete('/musicians/:id', function() use ($app){
	(new \controllers\Musician($app))->delete();
});

//Login 
$app->post('/login', function() use ($app){
	(new \controllers\Musician($app))->login();
});


// -----------------------------------
// Artist
// -----------------------------------

//Relates an spotify artist with an musician 
$app->post('/artists/:musicianId', function() use ($app){
	(new \controllers\Artist($app))->add();
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
$app->get('/cities/:stateId', function() use ($app){
	(new \controllers\City($app))->getAll();
});

// -----------------------------------
// Instruments
// -----------------------------------

// Get all instruments
$app->get('/instruments', function() use ($app){
	(new \controllers\Instrument($app))->getAll();
});


$app->run();




/*

function getMusiciansCompatibility ($id) {
	$statement = getConn()->query("SELECT * FROM musician_full_view");
	$musicians = $statement->fetchAll(PDO::FETCH_OBJ);

	// foreach ($musicians as $musician) { 
	// 	$sql = "SELECT 
	// 				count(*)
	// 			FROM
	// 				musician_artist
	// 			WHERE 
	// 				musician_artist.id_musician = :id_musician
	// 			 	AND 
	// 			    musician_artist.id_artist IN (SELECT
	// 				id_artist	
	// 			FROM
	// 				musician_artist
	// 			WHERE 
	// 				musician_artist.id_musician = :id_logged_userd)";

	// 	$stmt = $conn->prepare($sql);
	// 	$stmt->bindParam("id_musician", $musician->id);
	// 	$stmt->bindParam("id_logged_userd", $id);
	// 	$stmt->execute();
	// 	$count = $stmt->fetch();
	// 	$compatibility = ($count * 100) / 20 //20 = num total de artistas (100%)
	// 	$musician->compatibility = $compatibility
	// }
	
	echo json_encode($musicians);

}

*/








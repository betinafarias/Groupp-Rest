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
$app->get('/musicians','getMusicians');
$app->get('/musicians/:id','getMusician');
$app->post('/musicians', 'addMusician');
$app->post('/musicians/:id','saveMusician');
$app->delete('/musicians/:id','deleteMusician');


// -----------------------------------
// States
// -----------------------------------
$app->get('/states','getStates');

// -----------------------------------
// Cities
// -----------------------------------
$app->get('/cities/:stateId','getCities');

// -----------------------------------
// Instruments
// -----------------------------------
$app->get('/instruments','getInstruments');

// -----------------------------------
// Login
// -----------------------------------
$app->post('/login','login');


$app->run();

function getConn() {
	return new PDO('mysql:host=localhost;dbname=groupp',
	'root',
	'',
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
	);
}

function login() {
	$request = \Slim\Slim::getInstance()->request();
	$musician = json_decode($request->getBody());
	$sql = "SELECT id FROM musician WHERE email = :email AND password = :password";
	$conn = getConn();
	$stmt = $conn->prepare($sql);

	$stmt->bindParam("email", $musician->email);
	$stmt->bindParam("password", $musician->password);

	$stmt->execute();
	$id = $stmt->fetchObject();

	if($id){
		$response['error'] = false;
		$response['id'] = $id->id;
		echo json_encode($response);
	}
	else{
		$response['message'] = "Usuário inválido.";
		$response['error'] = true;
		echo json_encode($response);
	}

	
}

function getInstruments() {
	$statement = getConn()->query("SELECT * FROM instrument");
	$states = $statement->fetchAll(PDO::FETCH_OBJ);
	echo json_encode($states);
}

function getStates() {
	$statement = getConn()->query("SELECT * FROM state");
	$states = $statement->fetchAll(PDO::FETCH_OBJ);
	echo json_encode($states);
}

function getCities($stateId) {
	$conn = getConn();
	$sql = "SELECT * FROM city WHERE id_state = :id_state";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id_state", $stateId);
	$stmt->execute();
	$cities = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo json_encode($cities);
}

function getMusicians() {
	$statement = getConn()->query("SELECT * FROM musician_full_view");
	$musicians = $statement->fetchAll(PDO::FETCH_OBJ);
	echo json_encode($musicians);
}

function getMusician($id) {
	$conn = getConn();
	$sql = "SELECT * FROM musician WHERE id=:id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$musician = $stmt->fetchObject();
	echo json_encode($musician);
}


function saveMusician($id) {
	$request = \Slim\Slim::getInstance()->request();
	$musician = json_decode($request->getBody());
	$sql = "UPDATE 
				musician 
			SET 
				name = :name, 
				password = :password, 
				email = :email, 
				age = :age,
				id_city = :id_city  
			WHERE  
				id=:id";

	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("name", $musician->name);
	$stmt->bindParam("password", $musician->password);
	$stmt->bindParam("email", $musician->email);
	$stmt->bindParam("age", $musician->age);
	$stmt->bindParam("id_city", $musician->id_city);
	$stmt->bindParam("id",$id);
	$stmt->execute();

	echo json_encode($musician);
}

function addMusician() {
	$request = \Slim\Slim::getInstance()->request();
	$musician = json_decode($request->getBody());
	$sql = "INSERT INTO musician (name, password, email, age, id_city) values (:name, :password, :email, :age, :id_city) ";
	$conn = getConn();
	$stmt = $conn->prepare($sql);

	$stmt->bindParam("name", $musician->name);
	$stmt->bindParam("password", $musician->password);
	$stmt->bindParam("email", $musician->email);
	$stmt->bindParam("age", $musician->age);
	$stmt->bindParam("id_city", $musician->id_city);
	$stmt->execute();
	$musician->id = $conn->lastInsertId();

	foreach ($musician->instruments as $instrument) {
		$sql = "INSERT INTO musician_instrument (id_musician, id_instrument) values (:id_musician, :id_instrument) ";
		$conn = getConn();
		$stmt = $conn->prepare($sql);
		$stmt->bindParam("id_musician", $musician->id);
		$stmt->bindParam("id_instrument", $instrument);
		$stmt->execute();	
	}

	echo json_encode($musician);
}

function deleteMusician($id) {
	$sql = "DELETE FROM musician WHERE id=:id";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$response['message'] = "Músico deletado.";
	echo json_encode($response);
}

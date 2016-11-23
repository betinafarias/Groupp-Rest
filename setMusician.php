<?php 

//get data
require_once('database/database_connection.php'); 
mysql_select_db($database_excal, $excal);

$json_result = array();
$json_result['message'] = '';
$json_result['error'] = 0;


if(validSetUser()) {
	$email = $_POST['email'];
	$name = $_POST['name'];
	$password = $_POST['password'];
	$cityId = $_POST['cityId'];
	$age = $_POST['age'];

	//Validate if already exists a user with this email
	$query_validation = "SELECT count(*) FROM musician WHERE email = ".$email;
	$response_validation = db_query($query_validation);
	$users_count = $response_validation [0];

	//user has an account
	if($users_count > 0){
		$json_result['message'] = 'Usuário já cadastrado.';
	}
	else {
		//build query
		$query = 
		  "INSERT INTO musician (email, name, password, city_id, age)
		  VALUES (".$email.", ".$name.", ".$password.", ".$cityId.", ".$age.")";

		if (mysqli_query(mysql_query($query, $excal))) {
		    $json_result['message'] = 'Usuário cadastrado com sucesso.';

		} else {
			$json_result['message'] = 'Erro ao cadastrar usuário';
			$json_result['error'] = 1;
		   // echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}

}
else{
	$json_result['message'] = 'Invalid parameters.';
	$json_result['error'] = 1;
}


echo json_encode($json_result);

//Validate service parameters
function validSetUser() {
	if(empty($_POST['email']) || empty($_POST['name']) || empty($_POST['password']) || empty($_POST['cityId']) || empty($_POST['age'])) {

		return false;
	}
	else{
		return true;
	}
}



?>

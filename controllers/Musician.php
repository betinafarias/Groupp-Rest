<?php
namespace controllers {
	require('/utils.php');

	class Musician {

		public function getAll() {
			$statement = getConn()->query("SELECT * FROM musician_full_view");
			$musicians = $statement->fetchAll(\PDO::FETCH_OBJ);
			echo json_encode($musicians);
		}



	public function getCompatibilities ($id) {
		$conn = getConn();
		$statement = $conn->query("SELECT * FROM musician_full_view WHERE id <> " . $id);
		$musicians = $statement->fetchAll(\PDO::FETCH_OBJ);

		 foreach ($musicians as $musician) { 
		 	$sql = "SELECT 
		 				count(*) as num_matches
					FROM
	 				musician_artist
		 			WHERE 
		 				musician_artist.id_musician = :id_musician
		 			 	AND 
		 			    musician_artist.id_artist IN (SELECT
		 				id_artist	
		 			FROM
		 				musician_artist
		 			WHERE 
		 				musician_artist.id_musician = :id_logged_userd)";

		 	$stmt = $conn->prepare($sql);
		 	$stmt->bindParam("id_musician", $musician->id);
		 	$stmt->bindParam("id_logged_userd", $id);
		 	$stmt->execute();
		 	$count = $stmt->fetchObject();
		 	$numberOfMatches = $count->num_matches;

		 	$compatibility = ($numberOfMatches * 100) / 20; //20 = num total de artistas (100%)
		 	$musician->compatibility = $compatibility;
		 }
		
		echo json_encode($musicians);

	}



		public function get($id) {
			$conn = getConn();
			$sql = "SELECT * FROM musician WHERE id=:id";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam("id",$id);
			$stmt->execute();
			$musician = $stmt->fetchObject();
			echo json_encode($musician);
		}		

		public function save($id) {
			global $app;
			$musician = json_decode($app->request->getBody());	
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


		public function add() {
			global $app;
			$musician = json_decode($app->request->getBody());			
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

		public function delete($id) {
			$sql = "DELETE FROM musician WHERE id=:id";
			$conn = getConn();
			$stmt = $conn->prepare($sql);
			$stmt->bindParam("id",$id);
			$stmt->execute();
			$response['message'] = "Músico deletado.";
			echo json_encode($response);
		}



		function login() {
			global $app;
			$musician = json_decode($app->request->getBody());	
			$sql = "SELECT * FROM musician_full_view WHERE email = :email AND password = :password";
			$conn = getConn();
			$stmt = $conn->prepare($sql);

			$stmt->bindParam("email", $musician->email);
			$stmt->bindParam("password", $musician->password);

			$stmt->execute();
			$loggedUser = $stmt->fetchObject();

			if($loggedUser->id){
				$response['error'] = false;
				$response['user'] = $loggedUser;
				echo json_encode($response);
			}
			else{
				$response['message'] = "Usuário inválido.";
				$response['error'] = true;
				echo json_encode($response);
			}
		}
		

	}
}
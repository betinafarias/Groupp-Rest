<?php

namespace controllers {
	include('/utils.php');
	class City {

		function getAllFromState($stateId) {
			$conn = getConn();
			$sql = "SELECT * FROM city WHERE id_state = :id_state";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam("id_state", $stateId);
			$stmt->execute();
			$cities = $stmt->fetchAll(\PDO::FETCH_OBJ);
			echo json_encode($cities);
		}
	}

}
<?php
namespace controllers {
	include('/utils.php');
	class State {
		function getAll() {
			$statement = getConn()->query("SELECT * FROM state");
			$states = $statement->fetchAll(\PDO::FETCH_OBJ);
			echo json_encode($states);
		}
	}

}
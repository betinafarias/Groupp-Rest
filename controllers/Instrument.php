<?php
namespace controllers {
	include('/utils.php');
	class Instrument {

		function getAll() {
			$statement = getConn()->query("SELECT * FROM instrument");
			$states = $statement->fetchAll(\PDO::FETCH_OBJ);
			echo json_encode($states);
		}
	}

}
<?php
$requesturi = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$pos = strpos($requesturi, "explorecalifornia");

$hostname_excal = "localhost";
$database_excal = "groupp";
$username_excal = "root";
$password_excal = "";

$excal = mysql_pconnect($hostname_excal, $username_excal, $password_excal) or trigger_error(mysql_error(),E_USER_ERROR); 

header('Content-type: application/json');
header('Content-Type: text/html; charset=utf-8');

function db_query($sql){
	$query = mysql_query($sql) or die(mysql_error());
	$i = 0;
	$list = array();
	while($dado = mysql_fetch_array($query)) {
		$list[$i] = $dado;
		$i++;
	}
	
	return $list;
}
?>

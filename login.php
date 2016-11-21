<?php 

//get data
require_once('database_connection.php'); 
mysql_select_db($database_excal, $excal);

//build query
$query_rsTours = 
  "SELECT *
   FROM musician_full";

//Email parameter
if (isset($_GET['email']))
  $email = $_GET['email'];
else if (isset($_POST['email']))
  $email = $_POST['email'];

if (isset($email))
  $query_rsTours .= " WHERE email = " . $email;

//Password parameter
if (isset($_GET['password']))
  $password = $_GET['password'];
else if (isset($_POST['password']))
  $password = $_POST['password'];

if (isset($password))
  $query_rsTours .= " AND password = " . $password;


//execute query
$response = mysql_query($query_rsTours, $excal) or 
  die(mysql_error());

$arRows = array();
while ($row_rsTours = mysql_fetch_assoc($response)) {
  array_push($arRows, $row_rsTours);
}

header('Content-type: application/json');
echo json_encode($arRows);

?>

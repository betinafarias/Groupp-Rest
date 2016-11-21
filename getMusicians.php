<?php 

//get data
require_once('database_connection.php'); 
mysql_select_db($database_excal, $excal);

//build query
$query_rsTours = 
  "SELECT *
   FROM musician_full";

if (isset($_GET['userId']))
  $userId = $_GET['userId'];
else if (isset($_POST['userId']))
  $userId = $_POST['userId'];

if (isset($userId))
  $query_rsTours .= " WHERE id <> " . $userId;

$query_rsTours .= " ORDER BY name";

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

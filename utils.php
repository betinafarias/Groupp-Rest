<?php
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
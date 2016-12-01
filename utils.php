<?php
function getConn() {
	return new PDO('mysql:host=localhost;dbname=groupp',
	'root',
	'',
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
	);
}
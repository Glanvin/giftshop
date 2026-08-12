<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_CUGiftshop";

$con = mysqli_connect($servername, $username, $password, $dbname);

if ($con) {
	echo "connection was established";
} else {
	echo "error";
}

?>
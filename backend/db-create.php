<?php
require __DIR__ . '/connection.php';

$sql = "CREATE DATABASE db_CUGiftshop";

	if (mysqli_query($con, $sql)) {
		echo "database was created";
	} else {
		echo "failed";
	}

?>
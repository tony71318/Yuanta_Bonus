<?php

	$connection = mysqli_connect("localhost", "root", "database");
	mysqli_query($connection, "SET NAMES 'utf8'");
	$db = mysqli_select_db($connection, "yuanta");

	// bank address

	$to_bank = $_POST['to_bank'];

	$query = "SELECT address, rate FROM bank WHERE bank like '%".$to_bank."%'";	//中文要用like %% !!!!!!!!!!!!!!!!!!!!!

	$result = mysqli_query($connection, $query);

	$bank_data = mysqli_fetch_assoc($result);

	echo json_encode($bank_data);
	
	mysqli_close($connection); // Closing Connection
?>
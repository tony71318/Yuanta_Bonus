<?php
	
	// Establishing Connection with Server by passing server_name, user_id and password as a parameter
	$connection = mysqli_connect("localhost", "root", "database");
	mysqli_query($connection, "SET NAMES 'utf8'");
	// Selecting Database
	$db = mysqli_select_db($connection, "yuanta");

	$sql = " SELECT bank,rate FROM bank " ;
	$result = mysqli_query($connection,$sql);
	$total_records = mysqli_num_rows($result);
		
	for($i=1;$i<=$total_records;$i++){
		$rs=mysqli_fetch_assoc($result);

		$data[$rs['bank']]['bank'] = $rs['bank'];
		$data[$rs['bank']]['rate'] = $rs['rate'];
	}

	echo json_encode($data);;
?>		
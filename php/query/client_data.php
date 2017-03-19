<?php

	$username = $_SESSION['login_user'];
	$id = $_SESSION['id'];

	$connection = mysqli_connect("localhost", "root", "database");
	mysqli_query($connection, "SET NAMES 'utf8'");
	$db = mysqli_select_db($connection, "yuanta");

	// user data

	$query = "SELECT account.* FROM account WHERE account.id=$id";
	$result = mysqli_query($connection, $query);

	$user_data = mysqli_fetch_assoc($result);

	// account list
	
	$query = "SELECT yuanta.* FROM account INNER JOIN yuanta ON yuanta.account = account.user_id WHERE account.id=$id
				UNION
				SELECT cathay.* FROM account INNER JOIN cathay ON cathay.account = account.user_id WHERE account.id=$id
				UNION
				SELECT citi.* FROM account INNER JOIN citi ON citi.account = account.user_id WHERE account.id=$id			
			";

	$result = mysqli_query($connection, $query);
	$rows = mysqli_num_rows($result);

	$i=-1;
	while ($temp = mysqli_fetch_assoc($result)) {
		$i++;

		$rs[$i]['id'] = $temp['id'];
		$rs[$i]['bank_id'] = $temp['bank_id'];
		$rs[$i]['account'] = $temp['account'];
		$rs[$i]['name'] = $temp['name'];
		$rs[$i]['value'] = $temp['value'];
		$rs[$i]['regist_time'] = $temp['regist_time'];
}

	mysqli_close($connection); // Closing Connection

?>
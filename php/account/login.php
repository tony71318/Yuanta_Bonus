<?php
	session_start(); // Starting Session
	$error=''; // Variable To Store Error Message

	if (isset($_POST['submit'])) {
		if (empty($_POST['username']) || empty($_POST['password'])) {
			$error = "Username or Password is invalid";
			echo $error;
		}
		else{

			// Define $username and $password
			$username=$_POST['username'];
			$password=$_POST['password'];
			// Establishing Connection with Server by passing server_name, user_id and password as a parameter
			$connection = mysqli_connect("localhost", "root", "database");
			mysqli_query($connection, "SET NAMES 'utf8'");
			// To protect MySQL injection for Security purpose
			$username = stripslashes($username);
			$password = stripslashes($password);
			$username = mysqli_real_escape_string($connection, $username);
			$password = mysqli_real_escape_string($connection,$password);
			// Selecting Database
			$db = mysqli_select_db($connection, "yuanta");
			// SQL query to fetch information of registerd users and finds user match.
			$query = "select * from account where password='$password' AND username='$username'";
			$result = mysqli_query($connection, $query);
			$rows = mysqli_num_rows($result);
			if ($rows == 1) {
				$rs=mysqli_fetch_assoc($result);
				$_SESSION['login_user']=$username; // Initializing Session
				$_SESSION['id']=$rs['id'];
				$_SESSION['user_id']=$rs['user_id'];
				header("location: ../../client.php"); // Redirecting To Other Page
			} 
			else {
				$error = "Username or Password is invalid";
				echo $error;
			}
			mysqli_close($connection); // Closing Connection
		}
	}

?>
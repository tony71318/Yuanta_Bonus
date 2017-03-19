<?php
	
    $hostname = "localhost";//主機名稱
	$username = "root";//資料庫使用者名稱
	$password = "database";//密碼
	$db = mysqli_connect($hostname,$username,$password);
		if(!$db){
	
		echo "資料庫連接失敗QQQ";
		exit;
	}else{

		// //將連線方式或參數都指定編碼為UTF8
		// mysqli_query($db ,"SET CHARACTER SET 'utf8'");
		// mysqli_query($db ,'SET NAMES utf8');
		// mysqli_query($db ,'SET CHARACTER_SET_CLIENT=utf8');
		// mysqli_query($db ,'SET CHARACTER_SET_RESULTS=utf8');
		//選擇連線的資料庫
		mysqli_select_db($db,"yuanta");
		//echo "success";
	}
	
	?>
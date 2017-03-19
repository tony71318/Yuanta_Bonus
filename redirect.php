<?php 
	include("functions.php");    // include 另一個php檔案 才能使用裡頭的 function
	
	$id = $_POST['id'];		// 以 post 過來的 id 決定要執行哪一個function

	if($id==1){
		$account = $_POST['account'];
		searchAccount($account);
	}
	else if($id==2)
		listBonus();
		
	else if($id==3){
		$name = $_POST['name'];
		balanceofBank($name);
	}
	else if($id==4){
		$amount = $_POST['amount'];
		$bank = $_POST['bank'];
		searchRate($amount,$bank);
	}
	else if($id==5){
		$account = $_POST['account'];
		$_fromBonus = $_POST['_fromBonus'];
		$_fromBank = $_POST['_fromBank'];
		$_toBank = $_POST['_toBank'];
		$_toBonus = $_POST['_toBonus'];
		updateDatabase($account,$_fromBonus,$_fromBank,$_toBank,$_toBonus);
	}
?>
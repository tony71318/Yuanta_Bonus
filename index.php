<?php
	include('php/account/login.php'); // Includes Login Script

	if(isset($_SESSION['login_user'])){
	header("location: client.php");
	}
	# test
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>元大紅利 | 首頁</title>

	<!-- 套用bootstrap -->		<!-- 更改網址來更新css -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<!-- 套用自訂css -->
	<link type="text/css" href="./css/index.css?ver=2" rel="stylesheet"/>

	<!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- 套用bootstrap.js --> <!-- 一定要擺在jquery之後 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<!-- 套用 Ethereum 相關 api -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bignumber.js/4.0.0/bignumber.min.js"></script>
	<script type="text/javascript" src="./dist/web3-light.js"></script>
	<!-- my contract -->
	<script src="js/contract.js"></script>
	<!-- 用在 f_prompt(result) -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
	<!-- 分頁上的 小icon -->
	<link rel="shortcut icon" type="image/png" href="./images/logo.jpg"/>	
</head>

<script>	

	function sendTransaction(){		// 在銀行之間轉移元大幣
	
		var fromAddress = '0x6D623fe432B1e48d57b2A804cee71f1d4eE4b2A1';	//由哪個銀行送出元大幣，暫時寫死
		var from_Password = 'tony70431';
	
		// var main_Address = '0x2D281b1eB066EB14Dc769Ec2fcfB2819c8F046Ef'; //寫死Main account	= 元大
		// var main_Password = '206207209' ;
		
		var ToAddress = document.getElementById("address").innerText;	// 從 td 取該欄位的值 要使用innerText
		var value = document.getElementById("coin_value").innerText;
		
		console.log("address:", fromAddress, ToAddress, "value:" , value);
		
		web3.personal.unlockAccount(fromAddress, from_Password, 300);	//解鎖要執行 function 的 account
		
		var res = myContractInstance.transfer(	// transfer 是 contract 裡 的一個 function
				ToAddress,	//input
				value,	//input
				{
					from: fromAddress,	//從哪個ethereum帳戶執行
					'gas': myContractInstance.transfer.estimateGas() *5 //執行function所需的gas (發現*5比較不會有錯誤)
				},
				function(err, result) {	//callback 的 function
					if (!err){
						console.log(result);
						console.log('success');
						f_prompt(result);	//瀏覽器 會 顯示 交易成功視窗
					}
					else {
						console.log(err);
						alert(err);
					}
				}
			);
			//alert(res);
		
		var _fromBonus = document.getElementById("amount").value;
		var _fromBank = document.getElementById("bank").value;
		var _toBank = document.getElementById("_toBank").innerText;;	// 從 td 取該欄位的值 要使用innerText
		var _toBonus = document.getElementById("_toBonus").innerText;;
		
		console.log(_fromBonus + " " + _fromBank + " " + _toBank + " " + _toBonus);
			
		updateDatabase(_fromBonus,_fromBank,_toBank,_toBonus);	// ********之後應該改成讀取到event之後，才更新資料庫********
	}
	
	window.onload = function listBonusRate(sender,e) {	//列出點數之間的匯率
		// window.onload = 在網頁開啟、重整時 執行function
	
		$.ajax( { type : 'POST',
				  data : {id: 2},
				  url  : 'redirect.php',              // <=== CALL THE PHP FUNCTION HERE.
				  success: function ( data ) {
					//console.log( data );               // <=== VALUE RETURNED FROM FUNCTION.
					$('#listBonusRate').html(data); 	// 在 div id = 'listBonusRate' 的地方 顯示
				  },
				  error: function ( xhr ) {
					console.log(xhr);
				  }
				});
	}

	function updateDatabase (_fromBonus,_fromBank,_toBank,_toBonus) {	//更新交易雙方的資料庫
	
		var account = document.getElementById("account").value;
	
		$.ajax( { type : 'POST',
				  data : {id: 5 , account: account , _fromBonus: _fromBonus, _fromBank:_fromBank , _toBank:_toBank , _toBonus:_toBonus},
				  url  : 'redirect.php',              // <=== CALL THE PHP FUNCTION HERE.
				  success: function ( data ) {
					//console.log( data );               // <=== VALUE RETURNED FROM FUNCTION.
				  },
				  error: function ( xhr ) {
					console.log(xhr);
				  }
				});
	}

	function searchAccount (sender,e) {		//搜尋此帳號 總共有幾個銀行的紅利，列出表
	
		var account = document.getElementById("account").value;
	
		e.preventDefault();
		$.ajax( { type : 'POST',	// <=== Jquery
				  data : {id: 1 , account: account},
				  url  : 'redirect.php',              // <=== CALL THE PHP FUNCTION HERE.
				  success: function ( data ) {
					//console.log( data );               // <=== VALUE RETURNED FROM FUNCTION.
					$('#searchResult').html(data); 		// 在 div id = 'searchResult' 的地方 顯示
				  },
				  error: function ( xhr ) {
					console.log(xhr);
				  }
				});
	}
	
	function searchRate (sender,e) {	//從哪個銀行換多少紅利點數
	
		var amount = document.getElementById("amount").value;
		var bank = document.getElementById("bank").value;
	
		e.preventDefault();
		$.ajax( { type : 'POST',
				  data : {id: 4 , amount: amount , bank: bank},
				  url  : 'redirect.php',              // <=== CALL THE PHP FUNCTION HERE.
				  success: function ( data ) {
					//console.log( data );               // <=== VALUE RETURNED FROM FUNCTION.
					$('#searchRate_result').html(data); 	// 在 div id = 'searchRate_result' 的地方 顯示
				  },
				  error: function ( xhr ) {
					console.log(xhr);
				  }
				});
	}	

	function f_prompt(result){ //交易成功視窗
		bootbox.alert({
		message: "轉換成功! 交易編號"+ result,
		size: 'large'
		});
	}
	
	function fee_cal(){  //計算手續費(0.5%)
		var value = document.getElementById("amount").value;
		document.getElementById("fee").innerText = "將扣除手續費:"+value*0.005 +"點";
	}
</script>

<body id="myPage" data-spy="scroll" data-target="#myNavbar" data-offset="70">	<!-- ScrollSpy -->
	<!-- 導覽列 -->
	<nav class="navbar navbar-inverse  navbar-fixed-top">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>                        
		  </button>
		  <a class="navbar-brand" href="#myPage"><img src="images/logo.png" width="150" alt=""></a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
		  <ul class="nav navbar-nav navbar-right">
		  	<li><a href="#intro">簡介</a></li>
        	<li><a href="#bonus">紅利</a></li>
        	<li><a href="http://140.113.65.49/bank.php">銀行端</a></li>
			<li>
				<a href="php/account/regist.php">
					<span class="glyphicon glyphicon-user"></span>
						註冊
				</a>
			</li>
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<span class="glyphicon glyphicon-log-in"></span> 
						登入
					<span class="caret"></span>
				</a>
				<ul id="login-dp" class="dropdown-menu dropdown-menu-right">
					<li>
						 <div class="row">
								<div class="col-md-12">
									 <form class="form" role="form" method="post" action="php/account/login.php" accept-charset="UTF-8" id="login-nav">
											<div class="form-group">
												 <label class="sr-only" for="exampleInputEmail2">Username</label>
												 <input type="username" name="username" class="form-control" id="exampleInputEmail2" placeholder="Username" required>
											</div>
											<div class="form-group">
												 <label class="sr-only" for="exampleInputPassword2">Password</label>
												 <input type="password" name="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
											</div>
											<div class="form-group">
												 <button type="submit" name="submit" id="sign" class="btn btn-primary btn-block">登入</button>
											</div>
											<div class="checkbox">
												 <label>
												 	<input type="checkbox"> 保持登入
												 </label>
											</div>
									 </form>
								</div>
						 </div>
					</li>
				</ul>
			</li>
		  </ul>
		</div>
	  </div>
	</nav>
	<!-- 導覽列結束 -->
	
	<!-- 頂層圖片 -->
	<div class="container">
		<div class="row">
			<div class="col-sm-12" style="padding-top: 30px;">
				<img class="img-responsive center-block" src="images/process.png" alt="示意圖" title="示意圖" style=" opacity: 0.8;">
			</div>
		</div>
	</div>

	<!-- 廣告區塊 -->
	<div id="intro" class="jumbotron">
	  <div class="container">
	  	<div class="row">
	  		<hr/>
	  		<div id="intro-left" class="col-sm-6">
	  			<!-- 16:9 aspect ratio -->
				<div class="embed-col embed-responsive embed-responsive-16by9">
				  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/tIR2_-DPgi0"></iframe>
				  <!-- <iframe width="560" height="315" src="https://www.youtube.com/embed/tIR2_-DPgi0" frameborder="0" allowfullscreen></iframe> -->
				</div>
	  		</div>
	  		<div id="intro-right" class="col-sm-6">
	  			<h1>紅利積點之區塊鏈技術</h1>
				<p>本研究是利用區塊鏈的技術，將不同發行單位之紅利點數進行整合，藉以提供一個安全可靠的兌換平台。另外，我們也結合線上與線下之通路，以增加可兌換禮品之多元性。</p>
				<p><a class="btn btn-default btn-lg" href="#" role="button"><span class="glyphicon glyphicon-play-circle"></span> 點擊前往 YOUTUBE 頻道</a></p>
	  		</div>
	  	</div>
	  </div>
	</div>
	<!-- 廣告區塊結束 -->
		
	<!-- 主要內容 -->
	<div id="bonus" class="container">
		<div id="main" class="row">
			<!-- 左半部 -->
			<div id="main-left" class="col-sm-6">
			  <h3><b>*我的紅利</b></h3>
			  <!-- <form> -->
			  <form action="php/account/login.php" method="POST" role="form">
				<div class="form-group">
				  <label for="username">帳號:</label>
				  <div class="input-group">
				  	<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				  	<input type="username" name="username" class="form-control" id="username" placeholder="Enter username" required>
				  </div>
				  <label for="password">密碼:</label>
				  <div class="input-group">
				  	<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				  	<input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
				  </div>
				</div>
				<div class="checkbox">
				  <label><input type="checkbox"> 記住我 </label>
				</div>
				<!-- <button type="submit" class="btn btn-warning" onclick='searchAccount(this,event)'>登入後查看紅利</button> -->
				<button type="submit" name="submit" id="submit" class="btn btn-warning">登入後查看紅利</button>
				<!-- <input name="submit" type="submit" value=" Login "> -->
			  </form>
			  <div id="searchResult"></div>
			</div>
			<!-- 右半部 -->
			<div class="col-sm-6"  id="listBonusRate"></div>
		</div>
	</div>
	<!-- 主要內容結束 -->

	<!-- 合作銀行列表開始 -->
	<div id="coop" class="container">
		<div class="row">
			<div class="col-xs-12">
				<h3 class="text-center"><b>合作銀行</b></h3>
				<hr />
			</div>
		</div>
		<div id="coop-bank" class="row">
			<div class="col-xs-6 col-sm-2">
				<div class="thumbnail text-center">
					<img alt="" src="images/yuanta_logo.png" />
					<div class="caption">
						<h3 class="h4">元大銀行</h3>						
						<p><a href="https://ebank.yuantabank.com.tw/ib/" class="btn btn-primary btn-lg" role="button">進入網站</a></p>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-2">
				<div class="thumbnail text-center">
					<img alt="" src="images/cathay_logo.png"/>
					<div class="caption">
						<h3 class="h4">國泰世華銀行</h3>						
						<p><a href="https://www.mybank.com.tw/mybank" class="btn btn-primary btn-lg" role="button">進入網站</a></p>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-2">
				<div class="thumbnail text-center">
					<img alt="" src="images/citi_logo.png" />
					<div class="caption">
						<h3 class="h4">花旗銀行</h3>						
						<p><a href="https://www.citibank.com.tw/" class="btn btn-primary btn-lg" role="button">進入網站</a></p>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-2">
				<div class="thumbnail text-center">
					<img alt="" src="images/yuanta_logo.png" />
					<div class="caption">
						<h3 class="h4">元大銀行</h3>						
						<p><a href="https://ebank.yuantabank.com.tw/ib/" class="btn btn-primary btn-lg" role="button">進入網站</a></p>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-2">
				<div class="thumbnail text-center">
					<img alt="" src="images/cathay_logo.png"/>
					<div class="caption">
						<h3 class="h4">國泰世華銀行</h3>						
						<p><a href="https://www.mybank.com.tw/mybank" class="btn btn-primary btn-lg" role="button">進入網站</a></p>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-2">
				<div class="thumbnail text-center">
					<img alt="" src="images/citi_logo.png" />
					<div class="caption">
						<h3 class="h4">花旗銀行</h3>						
						<p><a href="https://www.citibank.com.tw/" class="btn btn-primary btn-lg" role="button">進入網站</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 合作銀行列表結束 -->

	<!-- 頁尾  -->
	<footer id="footer" class="container-fluid text-center">
		<hr/>
		<a href="#myPage" title="To Top">
			<span class="glyphicon glyphicon-chevron-up"></span>
			<div>
				<label for="To Top">To Top</label>
			</div>
		</a>
	</footer>	
	<!-- 頁尾結束  -->

</body>
</html>


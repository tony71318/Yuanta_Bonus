<?php
	session_start();
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>元大紅利 | 銀行專區</title>

	<!-- 套用bootstrap -->		<!-- 更改網址來更新css -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<!-- 套用自訂css -->
	<link type="text/css" href="./css/bank.css?ver=3" rel="stylesheet"/>

	<!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- 套用bootstrap.js --> <!-- 一定要擺在jquery之後 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<!-- 套用 Ethereum 相關 api -->
	<script type="text/javascript" src="./node_modules/bignumber.js/bignumber.min.js"></script>
	<script type="text/javascript" src="./dist/web3-light.js"></script>
	<!-- my contract -->
	<script src="js/contract.js"></script>
	<!-- 用在 f_prompt(result) -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

	<link rel="shortcut icon" type="image/png" href="./images/logo.jpg"/>	<!-- 分頁上的 小icon -->
</head>

<script>	
	
	function balanceofBank(){	//搜尋某銀行的元大幣餘額

		var bankName = document.getElementById("bankName").value; 

		console.log("bankName:", bankName);
		
		var bankAddress = "";
		
		$.ajax( { 
					async : false,	// 改成false 會等ajax執行完 才往下
					type : 'POST',
					data : {id: 3 , name: bankName},
					url  : 'redirect.php',              // <=== CALL THE PHP FUNCTION HERE.
					dataType: 'json',
					success: function ( data ) {
						//console.log(data);               // <=== VALUE RETURNED FROM FUNCTION.
						bankAddress = data;
					},
					error: function ( xhr ) {
						console.log(xhr);
					}
				});		
		
		var res = myContractInstance.balanceOf(			//沒有 callback = sync
				bankAddress								//有 callback = async
			);
			
			document.getElementById("bankName_title").innerText = "銀行";	// 似乎 innerText 是用在取 <></>之間的文字
			document.getElementById("bankValue_title").innerText = "數量";
			
			if (bankName=="yuanta") {
				bankName = "元大";
			}
			else if (bankName=="cathay") {
				bankName = "國泰";
			}
			else if (bankName=="citi") {
				bankName = "花旗";
			}
			document.getElementById("bankName_result").innerText = bankName;
			document.getElementById("bankValue_result").innerText = res.c[0];	// 要取 res.c[0] 這個c是一個陣列 不知道為甚麼 
																				// 似乎ethereum 查詢 mapping 會自動產生function

			$("#searchResult").fadeIn();

			//console.log(res.c[0]);
	}
	
	window.onload = function listBonus(sender,e) {	//列出點數之間的匯率
		// window.onload = 在網頁開啟、重整時 執行function
	
		// 隱藏表格
		$("#searchResult").hide();

		$.ajax( { type : 'POST',
				  data : {id: 2},
				  url  : 'redirect.php',              // <=== CALL THE PHP FUNCTION HERE.
				  success: function ( data ) {
					//console.log(data);               // <=== VALUE RETURNED FROM FUNCTION.
					$('#listBonus').html(data); 
				  },
				  error: function ( xhr ) {
					console.log(xhr);
				  }
				});
	}
				
				
	function sell() {

		var main_Address = '0x6D623fe432B1e48d57b2A804cee71f1d4eE4b2A1'; //寫死Main account	= 元大
		var main_Password = 'tony70431' ;
		
		var ToAddress = document.getElementById("ToAddress").value;	//要購買元大幣的銀行的address
		var value = document.getElementById("send_value").value;	//購買多少元大幣
		
		console.log("address:", main_Address, ToAddress, "value:" , value);
		
		web3.personal.unlockAccount(main_Address, main_Password, 300);	//解鎖要執行 function 的 account
		
		var res = myContractInstance.transfer(
				ToAddress,	//input
				value,	//input
				{
					from: main_Address,	//從哪個ethereum帳戶執行
					'gas': myContractInstance.transfer.estimateGas() *5 	//執行function所需的gas (發現*5比較不會有錯誤)
				},
				function(err, result) {	//callback 的 function
					if (!err){
						//console.log(result);
						//console.log('success');
						f_prompt(result);	//瀏覽器 會 顯示 交易成功視窗
					}
					else {
						console.log(err);
						alert(err);
					}
				}
			);
	}
	
	function f_prompt(result){ //交易成功視窗
		bootbox.alert({
		message: "轉換成功! 交易編號"+ result,
		size: 'large'
		});
	}
</script>

<body>
	<body id="myPage" data-spy="scroll" data-target="#myNavbar" data-offset="50">	<!-- ScrollSpy -->
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
		  	<!-- <li><a href="#intro">簡介</a></li>
        	<li><a href="#bonus">紅利</a></li> -->
        	<li><a href="http://140.113.65.49/index.php">用戶端</a></li>
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<span class="glyphicon glyphicon-user"></span>
						註冊
					<span class="caret"></span>
				</a>
				<ul id="login-dp" class="dropdown-menu dropdown-menu-right">
					<li>
						 <div class="row">
								<div class="col-md-12">
									 <form class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-nav">
											<div class="form-group">
												 <label class="sr-only" for="exampleInputEmail2">Email address</label>
												 <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email address" required>
											</div>
											<div class="form-group">
												 <label class="sr-only" for="exampleInputPassword2">Password</label>
												 <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
											</div>
											<div class="form-group">
												 <button type="submit" id="sign" class="btn btn-primary btn-block">註冊</button>
											</div>
									 </form>
								</div>
						 </div>
					</li>
				</ul>
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
									 <form class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-nav">
											<div class="form-group">
												 <label class="sr-only" for="exampleInputEmail2">Email address</label>
												 <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email address" required>
											</div>
											<div class="form-group">
												 <label class="sr-only" for="exampleInputPassword2">Password</label>
												 <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
											</div>
											<div class="form-group">
												 <button type="submit" id="sign" class="btn btn-primary btn-block">登入</button>
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

	<!-- 會員資料 -->
	<div class="container">
		<div class="jumbotron" id="member">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-1">
					<img src="images/citi_logo.png" alt="..." class="img-thumbnail center-block">
					<button type="submit" id="sign" class="btn btn-primary btn-md center-block" style="margin-top: 10px">更換圖片</button>
				</div>
				<div class="col-sm-6 col-sm-offset-1">
			  		<table class="table table-user-information table-hover">
                    <tbody>
   		 			  <tr>
                        <td>名稱:</td>
                        <td>花旗銀行</td>
                      </tr>
                      <tr>
                        <td>帳號:</td>
                        <td>citi</td>
                      </tr>
                      <tr>
                        <td>密碼:</td>
                        <td>****</td>
                      </tr>
                      <tr>
                        <td>代號:</td>
                        <td>1234</td>
                      </tr>
                      <tr>
                        <td>花旗點數總額:</td>
                        <td>2134242</td>
                      </tr>
                      <tr>
                        <td>元大幣餘額:</td>
                        <td>34223</td>
                      </tr>
                    </tbody>
                  </table>
                  <button type="submit" id="sign" class="btn btn-primary btn-md center-block" style="margin-top: 10px">編輯資料</button>
			  	</div>
			</div>
		</div>
		<hr/>
	</div>
	<!-- 會員資料結束 -->

	<!-- 帳戶管理 -->
	<div class="container">
		<div class="row" id="div_account">
			<h1 style="margin-left: 10px">帳戶管理</h1>
	        <div class="table-responsive col-md-12">
	            <table class="table table-hover">
	               <thead>
	                    <tr>
	                    	<th>#</th>
	                        <th>帳戶號碼</th>
	                        <th>帳戶名稱</th>
	                        <th>擁有者姓名</th>
	                        <th>剩餘點數</th>
                        	<th>新增日期</th>
	                        <th>動作</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <tr>
	                    	<td>1</td>
	                        <td><p>F123456789</p></td>
	                        <td><p>花旗銀行</p></td>
	                        <td><p>趙振傑</p></td>
	                        <td><p><span class="label label-success">4235</span></p></td>
	                        <td><p>2017-03-12</p></td>
	                        <td class="col-md-2"><p>
	                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" >詳細資料</button>
	                            <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button>
	                        </p></td>
	                    </tr>
	                    <tr>
	                    	<td>2</td>
	                        <td><p>B123456789</p></td>
	                        <td><p>國泰銀行</p></td>
	                        <td><p>林書豪</p></td>
	                        <td><p><span class="label label-danger">0</span></p></td>
	                        <td><p>2017-03-10</p></td>
	                        <td class="col-md-2"><p>
	                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">詳細資料</button>
	                            <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button>
	                        </p></td>
	                    </tr>
	                </tbody>
	            </table>
	        </div>
	        <div class="pull-right col-md-3 col-md-offset-9">
	            <ul class="pagination">
	                <li><a href="#">«</a></li>
	                <li class="active"><a href="#">1</a></li>
	                <li><a href="#">2</a></li>
	                <li><a href="#">3</a></li>
	                <li><a href="#">»</a></li>
	            </ul>
	        </div>
		</div>
		<hr>
	</div>

	<!-- 最近交易 -->
	<div class="container" id="div_history">
		<div class="row">
			<h1 style="margin-left: 10px">最近交易</h1>
	        <div class="table-responsive col-md-12">
	            <table class="table table-hover">
	            	<thead>
	                    <tr>
	                    	<th>#</th>
	                        <th>轉出帳戶</th>
	                        <th>花費點數</th>
	                        <th>轉入帳戶</th>
                        	<th>獲得點數</th>
                        	<th>手續費</th>
	                        <th>交易日期</th>
	                        <th>動作</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <tr>
	                    	<td>1</td>
	                        <td><p>花旗帳戶</p></td>
	                        <td><p><span class="label label-warning">500</span></p></td>		                        
	                        <td><p>國泰帳戶</p></td>	                        
	                        <td><p><span class="label label-success">450</span></p></td>
	                        <td><p><span class="label label-info">5</span></p></td>
	                        <td><p>2017-03-12</p></td>
	                        <td class="col-md-2"><p>
	                            <button class="btn btn-info btn-sm">詳細資料</button>
	                            <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button>
	                        </p></td>
	                    </tr>
	                    <tr>
	                    	<td>2</td>
	                        <td><p>花旗帳戶</p></td>
	                        <td><p><span class="label label-warning">500</span></p></td>		                        
	                        <td><p>國泰帳戶</p></td>	                        
	                        <td><p><span class="label label-success">450</span></p></td>
	                        <td><p><span class="label label-info">5</span></p></td>
	                        <td><p>2017-03-12</p></td>
	                        <td class="col-md-2"><p>
	                            <button class="btn btn-info btn-sm">詳細資料</button>
								<button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button>
	                        </p></td>
	                    </tr>
	                    
	                </tbody>
	            </table>
	        </div>
	        <div class="pull-right col-md-3 col-md-offset-9">
	            <ul class="pagination">
	                <li><a href="#">«</a></li>
	                <li class="active"><a href="#">1</a></li>
	                <li><a href="#">2</a></li>
	                <li><a href="#">3</a></li>
	                <li><a href="#">»</a></li>
	            </ul>
	        </div>
		</div>
	</div>
	<!-- 最近交易結束 -->

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
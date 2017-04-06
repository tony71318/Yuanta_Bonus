
<?php
	include('php/account/session.php');
	include('php/query/client_data.php');
	//echo("<script>console.log('PHP: ".$rows."');</script>")

	//yuanta 0x2D281b1eB066EB14Dc769Ec2fcfB2819c8F046Ef	
	//citi 0x6D623fe432B1e48d57b2A804cee71f1d4eE4b2A1
	//cathay 0x701c97Fb87fBaC9729897A9b6E3Df7dFd6CB68be
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>元大紅利 | 會員專區</title>

	<!-- 套用bootstrap -->		<!-- 更改網址來更新css -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<!-- 套用自訂css -->
	<link type="text/css" href="./css/client.css?ver=2" rel="stylesheet"/>

	<!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- 套用bootstrap.js --> <!-- 一定要擺在jquery之後 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<!-- 套用 Ethereum 相關 api -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bignumber.js/4.0.0/bignumber.min.js"></script>
	<script type="text/javascript" src="./dist/web3.js"></script>
	
	<!-- my contract -->
	<script src="js/contract.js"></script>
	<!-- 用在 f_prompt(result) -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
	<!-- loading overlay -->
	<script src="js/loadingoverlay.min.js"></script>

	<!-- 分頁上的 小icon -->
	<link rel="shortcut icon" type="image/png" href="./images/logo.jpg"/>	
</head>

<script>	

	window.onload = function(){
		// var event = myContractInstance.Transfer({},{fromBlock: 50000, toBlock: 'latest'} , function(error, result){
		//   if (!error)
		//     console.log(result);
		// });
		var event = myContractInstance.Exchange({},{fromBlock: 50000, toBlock: 'latest'} , function(error, result){
		  if (!error)
		    console.log(result);
		});
	};

	function sendTransaction(){		// 在銀行之間轉移元大幣
	
		$.LoadingOverlay("show");	// loading 動畫

		var fromAddress = '0x6D623fe432B1e48d57b2A804cee71f1d4eE4b2A1';	//由哪個銀行送出元大幣，暫時寫死
		var from_Password = 'tony70431';
		
		var from_bank_value = document.getElementById("amount").value;
		var from_bank_rate = 0;

		var to_bank = exchange_form.to_bank.value;

		var to_bank_address = "";
		$.ajax( { 
			type : 'POST',
			data : {to_bank: to_bank},
			url  : 'php/query/get_bank_address.php',              // <=== CALL THE PHP FUNCTION HERE.
			dataType: 'json',
			success: function ( data ) {
				//console.log(data['cathay']);               // <=== VALUE RETURNED FROM FUNCTION.
				to_bank_address = data['address'];
				from_bank_rate = data['rate'];

				var value = from_bank_value/from_bank_rate;

				var customer = 'A123456789';
				console.log("customer:", customer, "address:", fromAddress, to_bank_address, "value:" , value);
		
				web3.personal.unlockAccount(fromAddress, from_Password, 300);	//解鎖要執行 function 的 account
				
				
				var res = myContractInstance.transfer(	// transfer 是 contract 裡 的一個 function
						customer,			//input    String Length Issue
						to_bank_address,	//input
						value,	//input
						{
							from: fromAddress,	//從哪個ethereum帳戶執行
							'gas': myContractInstance.transfer.estimateGas() *5 //執行function所需的gas (發現*5比較不會有錯誤)
						},
						function(err, result) {	//callback 的 function
							if (!err){
								console.log(result);
								f_prompt(result);	//瀏覽器 會 顯示 交易成功視窗
							}
							else {
								console.log(err);
								alert(err);
							}
						}
					);


				alert('Success');
				$.LoadingOverlay("hide");
			},
			error: function ( xhr ) {
				console.log(xhr);
				$.LoadingOverlay("hide");
			}
		});	
		
	}

	function calculate(){  //計算手續費(0.5%)

		var value = document.getElementById("amount").value;
		var from_bank = document.getElementById("from_bank").innerText; 
		var to_bank = exchange_form.to_bank.value;

		$.LoadingOverlay("show");	// loading 動畫
		$.ajax( { 
			type : 'POST',
			data : {},
			url  : 'php/query/rate.php',              // <=== CALL THE PHP FUNCTION HERE.
			dataType: 'json',
			success: function ( data ) {
				//console.log(data['cathay']);               // <=== VALUE RETURNED FROM FUNCTION.
				rate = data;

				document.getElementById("get_amount").innerText = value*rate[from_bank]['rate']/rate[to_bank]['rate'] +"點";
				document.getElementById("fee").innerText = value*0.005 +"點";

				$.LoadingOverlay("hide");
			},
			error: function ( xhr ) {
				console.log(xhr);
				$.LoadingOverlay("hide");
			}
		});	
				
	}

	function set_account_modal(element){

		//clear previous input
		document.getElementById("amount").value = ""; 

		var table = document.getElementById("account_table");

		// set from_bank's value
		var select_row = element.parentNode.parentNode.rowIndex;
		var account_name = table.rows[select_row].cells[2].innerHTML;
		document.getElementById("from_bank").innerText = account_name; 

   		// reset select's option
   		var modal_select = document.getElementById("to_bank");
   		var select_length = modal_select.options.length;
   		for (i = select_length - 1; i >= 0; i--) { 
		    modal_select.remove(i);
		}

   		// set select'option
		var row_length = document.getElementById("account_table").rows.length;
		var count = 0;	//排除選自己的狀況
		for (i = 1; i < row_length; i++) { 
		    var option_text = table.rows[i].cells[2].innerHTML;
		    if (account_name == option_text) {
		    	count = 1;
		    	continue;
		    }
		    modal_select.options[i-1-count] = new Option(option_text, option_text);
		}
	}
	
</script>

<body id="myPage" data-spy="scroll" data-target="#myNavbar" data-offset="80">	<!-- ScrollSpy -->
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
		  	<li><a href="#member">個人</a></l>
		  	<li><a href="#div_account">帳戶</a></li>
        	<li><a href="#div_history">紀錄</a></li>
        	<li><a href="http://140.113.65.49/shopping_example/details.html">購物</a></li>
        	<li><a href="http://140.113.65.49/bank.php">銀行端</a></li>
			<li>
				<a href="php/account/logout.php"><span class="glyphicon glyphicon-log-out"></span> 
					登出
				</a>
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
					<img src="images/default_userimg.png" alt="..." class="img-thumbnail center-block">
					<button type="submit" id="sign" class="btn btn-primary btn-md center-block" style="margin-top: 10px">更換大頭貼</button>
				</div>
				<div class="col-sm-6 col-sm-offset-1">
			  		<table class="table table-user-information">
                    <tbody>
   		 			  <tr>
                        <td>姓名:</td>
                        <td><?php echo $user_data['name'] ?></td>
                      </tr>
                      <tr>
                        <td>帳號:</td>
                        <td><?php echo $user_data['username'] ?></td>
                      </tr>
                      <tr>
                        <td>密碼:</td>
                        <td>
                        	<?php 
                        		$length = strlen($user_data['password']);
                        		for ($i=0; $i < $length ; $i++) { 
                        			if($i == 0 || $i == $length-1 ){
                        				echo $user_data['password'][$i];
                        			}else
                        				echo '*';
                        		} 
                        	?> 	
                        </td>
                      </tr>
                      <tr>
                        <td>性別:</td>
                        <td><?php echo $user_data['gender'] ?></td>
                      </tr>
                      <tr>
                        <td>身分證字號:</td>
                        <td><?php echo $user_data['user_id'] ?></td>
                      </tr>
                      <tr>
                        <td>註冊時間:</td>
                        <td><?php echo $user_data['regist_time'] ?></td>
                      </tr>
                      
                      <tr>
                        <td>Email:</td>
                        <td><a href="mailto:<?php echo $user_data['email'] ?>"><?php echo $user_data['email'] ?></a></td>
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
			<h1>帳戶管理</h1>
			<span class="btn-group pull-right">
		      <button class="btn btn-sm btn-warning">新增帳戶</button>
	      	</span>
	        <div class="table-responsive col-md-12">
	            <table class="table table-hover" id = "account_table">
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
	                	<?php 
	                		include('php/part/client/client_account.php');
	                	 ?>
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
	
	<!-- popup -->	
	<div class="container">
	  <!-- Account Modal -->
	  <div class="modal fade" id="account_modal" role="dialog">
	    <div class="modal-dialog">
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">交換點數</h4>
	        </div>
	        <form class="form-horizontal" method="post" id="exchange_form" name="exchange_form" action="javascript:sendTransaction();">
		        <div class="modal-body">
					<div class="form-group">
						<label for="bank" class="col-sm-3 control-label">轉出帳戶:</label>
						<div class="col-sm-9">
							<p id="from_bank" class="p_form"></p>
						</div>
					</div>
					<div class="form-group">
						<label for="number" class="col-sm-3 control-label">欲換點數:</label>
						<div class="col-sm-9">
					    	<input type="number" id="amount" placeholder="輸入點數" class="form-control" onkeyup="calculate()" required="">
						</div>
					</div>
					<div class="form-group">
						<label for="bank" class="col-sm-3 control-label">轉入帳戶:</label>
						<div class="col-sm-9">
							<select id="to_bank" class="form-control">
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="number" class="col-sm-3 control-label">可得點數:</label>
						<div class="col-sm-9">
					    	<p id="get_amount" class="p_form">0點</p>
						</div>
					</div>
					<div class="form-group">
						<label for="number" class="col-sm-3 control-label">手續費:</label>
						<div class="col-sm-9">
					    	<p id="fee" class="p_form">0點</p>
						</div>
					</div>
		        </div>
		        <div class="modal-footer">
		        	 <button type="submit" class="btn btn-primary" id="sign">確認交換</button>
		        </div>
	        </form>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- popup 結束-->

	<!-- 帳戶管理結束 -->

	<!-- 最近交易 -->
	<div class="container" id="div_history">
		<div class="row">
			<h1>最近交易</h1>
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
	                        <td>花旗帳戶</td>
	                        <td><span class="label label-warning">500</span></td>		                        
	                        <td>國泰帳戶</td>	                        
	                        <td><span class="label label-success">450</span></td>
	                        <td><span class="label label-info">5</span></td>
	                        <td>2017-03-12</td>
	                        <td class="col-md-2">
	                            <button class="btn btn-info btn-sm">詳細資料</button>
	                            <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button>
	                        </td>
	                    </tr>
	                    <tr>
	                    	<td>2</td>
	                        <td>花旗帳戶</td>
	                        <td><span class="label label-warning">500</span></td>		                        
	                        <td>國泰帳戶</td>	                        
	                        <td><span class="label label-success">450</span></td>
	                        <td><span class="label label-info">5</span></td>
	                        <td>2017-03-12</td>
	                        <td class="col-md-2">
	                            <button class="btn btn-info btn-sm">詳細資料</button>
								<button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button>
	                        </td>
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
		<?php include('php/part/cooperate_banklist.php'); ?>
	<!-- 合作銀行列表結束 -->

	<!-- 頁尾  -->
		<?php include('php/part/footer.php'); ?>	
	<!-- 頁尾結束  -->

</body>
</html>
<?php
	function searchAccount($account) {
	
		include("config.php");	//負責連結資料庫的檔案
				
			$sql_1=" SELECT * FROM yuanta Where account= '" . $account . "' " ;		//變數的用法 '".$變數."'
			$sql_2=" SELECT * FROM cathay Where account= '" . $account . "' " ;
			$sql_all = $sql_1." ".'UNION'." ".$sql_2;	//將兩個sql合併
			$result = mysqli_query($db,$sql_all);
			
			$total_records = mysqli_num_rows($result);	//總共有幾列
			
			//在php 中使用 *** echo '任何html code';  *** 來顯示html
			echo '<div class="table-responsive">';
			echo '<table class="table table-striped">';
				echo '<tr align="center";>';	//一個row
					echo '<td >Id</td>';	//一個column
					echo '<td >Bank</td>';
					echo '<td >Account</td>';
					echo '<td >Name</td>';
					echo '<td >Value</td>';
				echo '</tr>';
			  
			
			for($i=1;$i<=$total_records;$i++){		// 利用 for 迴圈 顯示所有 $result 的結果
				$rs=mysqli_fetch_row($result);
			
				echo '<tr>';
				
					echo '<td>';
						echo $rs[0];
					echo '</td>';
					echo '<td>';
						if($rs[1]==1)
							echo "國泰";
						else if($rs[1]==2)
							echo "元大";
					echo '</td>';
					echo '<td>';
						echo $rs[2];
					echo '</td>';
					echo '<td>';
						echo $rs[3];
					echo '</td>';
					echo '<td>';
						echo $rs[4];
					echo '</td>';
				
				echo '</tr>';
			  
			}
			echo '</table>';	
		echo '</div>';

		echo '<br>';
			echo '<h4>*交換紅利點數</h4>';
				echo '<div class="padding">';
					echo '<form class="form-inline" name="form1" method="post" action="">';
						echo '選擇銀行:  <select class="form-control" name="bank" id="bank">';
							
							$result = mysqli_query($db,$sql_all);
							
							while ($row = mysqli_fetch_assoc($result))   
								{ 
									if($row['bank_id']==1)
										{echo "<option value=\"cathay\">";
										 echo "國泰";
										 echo "</option>";}
										 
									else if($row['bank_id']==2)
										{echo "<option value=\"yuanta\">";
										 echo "元大";
										 echo "</option>";}				
										
									else if($row['bank_id']==3)
										{echo "<option value=\"citi\">";
										 echo "花旗";
										 echo "</option>";}				
								}	
												 
							
							echo '</select>';
						 
							echo '  交換數量:  <input type="Text" name="amount" id="amount" onkeyup="fee_cal()">';
							echo '<div id="fee"></div><br>';
					echo '</form>';
					
						echo '<button class="btn btn-warning" onclick="searchRate(this,event)">查詢對換比率	</button><br><br>';		// 使用 javascrpt 的 function 不可放在 <form></form> 之間，網頁會重新整理
						
				echo '</div>';
				echo '<div id="searchRate_result"></div>';
							
  }
  
	function searchRate($amount,$bank){		//從哪個銀行換多少紅利點數
	
		include("config.php");	//負責連結資料庫的檔案
	
		$sql=" SELECT * FROM bank Where bank= '" . $bank . "' ";
		$result = mysqli_query($db,$sql);
		
		while($row = mysqli_fetch_array($result)){	
			$want =  $row['rate'];
		}

		$sql = "SELECT * FROM bank";
		$result = mysqli_query($db,$sql);
			
		echo '<h4>';
			if ($bank==="yuanta") {
					$bank_name = "元大";
				}
				elseif ($bank==="cathay") {
					$bank_name = "國泰";
				}
				elseif ($bank==="citi") {
					$bank_name = "花旗";
				}
			echo $amount . "點 " . $bank_name . "紅利點數可以兌換成:";	//用 . 來連接
		echo '</h4>';

		echo '<div class="table-responsive">';
		echo '<table class="table table-striped">';
		
		echo "<tr>";
			echo '<td id="_toBank_title">';echo "銀行";echo '</td>';
			echo '<td id="_coin_value_title">';echo "點數價值";echo '</td>';
			echo '<td id="_toBonus">';echo "紅利價值";echo '</td>';
			echo '<td></td>';
		echo "</tr>";

		while($row = mysqli_fetch_array($result)){
			if($row['bank']!=$bank){  //不顯示自己換自己
				
				$_toBank = $row['bank'];		//要轉過去的銀行的名稱
				if ($_toBank==="yuanta") {
					$_toBank = "元大";
				}
				elseif ($_toBank==="cathay") {
					$_toBank = "國泰";
				}
				elseif ($_toBank==="citi") {
					$_toBank = "花旗";
				}

				$address = $row['address'];		//要轉過去銀行的 ethereum address
				$coin_value = $amount/$want;		//要轉過去銀行的yuanta coin數
				$_toBonus = $amount*$row['rate']/$want;		//要轉過去銀行的紅利點數

				echo '<tr>';
					echo '<td id="_toBank">';echo $_toBank;echo '</td>';	
					echo "<td id='address' style='display:none'>";echo $address;echo "</td>";
					echo '<td id="coin_value">';echo $coin_value;echo '</td>';
					echo '<td id="_toBonus">';echo $_toBonus;echo '</td>';
					echo '<td>';echo '<button class="btn btn-info" onclick="sendTransaction()"> 兌換</button>';echo '</td>';
				echo '</tr>';
			}
		}

		echo '</div>'; 
	}
	
	function updateDatabase($account,$_fromBonus,$_fromBank,$_toBank,$_toBonus){
	
		include("config.php");	//負責連結資料庫的檔案
		
		if($_fromBank==="yuanta")
			$sql = " SELECT value FROM yuanta Where account = '".$account."' ";		// 不知為何 從哪個表query 似乎不能用變數寫在 $sql 中

		$result = mysqli_query($db,$sql);
		$rs=mysqli_fetch_row($result);
		$_from_orginal_value = $rs[0];
		$_from_updated_value = $_from_orginal_value - $_fromBonus;	//轉出的銀行 紅利點數 減少
		
		if($_toBank==="cathay")
			$sql = " SELECT value FROM cathay Where account = '".$account."' ";		// 不知為何 從哪個表query 似乎不能用變數寫在 $sql 中
			
		//$sql = " SELECT value FROM '".$_toBank."' Where account = '".$account."' ";
		$result = mysqli_query($db,$sql);
		$rs=mysqli_fetch_row($result);
		$_to_orginal_value = $rs[0];
		$_to_updated_value = $_to_orginal_value + $_toBonus;	//轉入的銀行 紅利點數 增加
		
		if($_fromBank==="yuanta")
			$sql = "UPDATE yuanta SET value = '".$_from_updated_value."' Where yuanta.account = '".$account."' ";	//表的名稱都不能用變數
		$result1 = mysqli_query($db,$sql);
		
		if($_toBank==="cathay")
			$sql = "UPDATE cathay SET value = '".$_to_updated_value."' Where cathay.account = '".$account."' ";		//表的名稱都不能用變數
		$result2 = mysqli_query($db,$sql);
		
		if($result1 && $result2)
			echo "update success";	//簡單的check 是否有 update 成功
		else
			echo $rs[0];
	}
  
	function listBonus() {
	
		include("config.php");	//負責連結資料庫的檔案
		
		$sql=" SELECT bank,rate FROM bank " ;
		$result = mysqli_query($db,$sql);
		$total_records = mysqli_num_rows($result);
		
		echo '<h3><b>紅利點數對照表</b></h3>';
			echo '<table class="table table-striped">';
				echo '<tr>';
					echo '<td>　　</td>';
					echo '<td>紅利點數</td>';
					echo '<td>元大幣</td>';
				echo '</tr>';
			
			for($i=1;$i<=$total_records;$i++){
					$rs=mysqli_fetch_row($result);
				
					echo '<tr>';
						
						echo '<td>';
							if($rs[0]=="cathay")
								echo '國泰';
							else if($rs[0]=="yuanta")
								echo '元大';
							else if($rs[0]=="citi")
								echo '花旗';
						echo '</td>';
						echo '<td>';
							echo $rs[1];
						echo '</td>';
						echo '<td>1</td>';

					echo '</tr>';
				  
				}
			echo '</table>';
			
	}
	
	function balanceofBank($name) {
	
		include("config.php");	//負責連結資料庫的檔案
		
		$sql=" SELECT address FROM bank Where bank= '" . $name . "' " ;		
		$result = mysqli_query($db,$sql);
		$rs=mysqli_fetch_row($result);
		
		echo json_encode($rs);	//要回傳給 javascript 整個 query 的結果 必須 encode 成 json 的格式
	
	}
?>
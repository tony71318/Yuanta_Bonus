<?php

	for ($i=0; $i < $rows; $i++) { 
		echo '<tr>';
        	echo '<td>';
        		echo $i+1;
    		echo '</td>';
            echo '<td>';
           		echo $citi_data[$i]['account'];
            echo '</td>';
            echo '<td>';
            	if ($citi_data[$i]['bank_id'] == 1) 
            		$bank_name = '國泰銀行';
            	else if ($citi_data[$i]['bank_id'] == 2) 
            		$bank_name = '元大銀行';
            	else if ($citi_data[$i]['bank_id'] == 3) 
            		$bank_name = '花旗銀行';		
            	echo $bank_name;
            echo '</td>';
            echo '<td>';
            	echo $citi_data[$i]['name'];
            echo '</td>';
            echo '<td><span class="label label-success">';
            	echo $citi_data[$i]['value'];
            echo '</span></td>';
            echo '<td>';
            	echo $citi_data[$i]['regist_time'];
            echo '</td>';
            echo '<td class="col-md-2">';
                echo '<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#account_modal" style="margin-right: 3px"onclick="set_account_modal(this)">詳細資料</button>';
                echo '<button class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
            echo '</td>';
        echo '</tr>';
	}
	
?>
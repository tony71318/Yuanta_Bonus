<?php

	for ($i=0; $i < $rows; $i++) { 
		echo '<tr>';
        	echo '<td>';
        		echo $i+1;
    		echo '</td>';
            echo '<td>';
            echo '</td>';
            echo '<td>';
            		$bank_name = '國泰銀行';
            		$bank_name = '元大銀行';
            		$bank_name = '花旗銀行';		
            	echo $bank_name;
            echo '</td>';
            echo '<td>';
            echo '</td>';
            echo '<td><span class="label label-success">';
            echo '</span></td>';
            echo '<td>';
            echo '</td>';
            echo '<td class="col-md-2">';
                echo '<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#account_modal" style="margin-right: 3px"onclick="set_account_modal(this)">交換點數</button>';
                echo '<button class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
            echo '</td>';
        echo '</tr>';
	}
	
?>

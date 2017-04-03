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
		        	<div class="form-group">
		        	  <button type="submit" class="btn btn-primary" id="sign">確認交換</button>
		        	</div>
		        </div>
	        </form>
	      </div>
	    </div>
	  </div>
	</div>
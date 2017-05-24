{% extends "/headfooter.volt" %}
{% block title %}<title>AutoBid</title>{% endblock %}
{% block scriptimport %}    
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>
	<script src="//js/vendor/jquery.validate.min.js"></script>
	
	<script src="/js/autobid/main.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/autobid.css" />

	
	<script src="/js/datepickerjst/moment.min.js"></script>
	<script src="/js/datepickerjst/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/datepickerjst/daterangepicker.css" />
	
	<script type="text/javascript" src="/js/jquery.sumoselect.min.js"></script>
	<link href="/css/sumoselect.css" rel="stylesheet"/>
	
{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrap">
         <div class="container">
			<div class="row">
				<p class="titles">Auto Bid</p>
			</div>
			<div class="row">
				<div class="col-md-12" style="    margin-bottom: 55px;"> 
					<table>
						
						<tr>
							<form role="form" class="well" action="/autobid/downloadReport">
								<td>
									<div class="form-group formgroupinvest">
										<label for="file">Date:</label>
										<input name="datepicker" type="text" class="form-control" id="datepicker">
									</div>
								</td>
								
								<td>
									<div class="form-group formgroupinvest">
										<label for="file">Account:</label>
										<select class="form-control" id="accounts" name="account">
											<?php echo $selectAccounts; ?>
										</select>
									</div>
								</td>
								<td>
									<div class="form-group formgroupinvest">
										<button id="downloadreport" type="submit" class="btn btn-info btn-sm" data-toggle="modal" >REPORT</button>
									</div>
								</td>
							</form>
						</tr>
						
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12"> 
					<button id="addCampaignButtonOpenModal" style="float: right;" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalCreateCampaign">ADD OFFER</button>
					
					
					<?php 
						if($this->session->get('auth')['navtype'] == 1) {
							echo '<button id="addAccountButtonOpenModal" style="float: right;" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalCreateAccount">ADD ACCOUNT</button>';
							echo '<button id="addResetPasswordOpenModal" style="float: right; margin-right: 40px;" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalResetPassword">RESET PASSWORD</button>';
						} else {
							echo '';
						}
					?>
					
					
				</div>
			</div>
			
			<div class="row userside">
				<div class="col-md-12"> 
					<button id="buttonrefresh" class="btn btn-primary" type="button"><i class="icon-refresh-animate"></i> Refresh</button>
					<table style="width: 100%;" id="tableCampaignBid" class="table-striped table-bordered">
						<thead>
							<tr>
								<td>Offer ID</td>
								<td>Offer Name</td>
								<td>Actual Bid</td>
								<td>MAX Bid</td>
								<td>Limit Bid</td>
								<td>Diff Bid</td>
								<td>Ad Rate</td>
								<td>MAX Ad Rate</td>
								<td>Process type</td>
								<td>Rank</td>
								<td>Status</td>
								<td>Last change</td>
								<td>Account</td>
								<td>Edit</td>
							</tr>
						<tbody id="tbodyCamp">
							<?php echo $tableCampaigns; ?> 
						</tbody>
					</table>	
				</div>
			</div>
			
			
			<div class="row itside">
				<div class="col-md-12"> 
			
				</div>
			</div>

		</div>
	</div>
	
	<div id="modalCreateAccount" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button id="closeModal2" type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Account</h4>
					</div>
					<div class="modal-body">
							Account email:<br>
							<input class="inputModal" type="text" style="width: 100%" id="emailAccount"><br><br>
							
							Account password:<br>
							<input class="inputModal" type="text" style="width: 100%" id="passwordAccount"><br><br>
					
							Users:<br>
							<select multiple="multiple" class="selectsRow search-box-sel-all" id="selectboxUsers" name="selectboxUsers">
								<?php echo $usersAutobid; ?> 
							</select>							
							<br><br>
							
							<br><button id="addAccountButtonModal" style="float: right;" type="button" class="btn btn-success btn-sm addcampaign buttonsmodals2" typeButton="1">Add</button>
					</div>
					<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>
	
	<div id="modalResetPassword" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button id="closeModal3" type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Account</h4>
					</div>
					<div class="modal-body">
							Account:<br>
							<select class="form-control" id="accountsReset" name="account">
								<?php echo $selectAccounts; ?>
							</select><br><br>
							
							New password:<br>
							<input class="inputModal" type="text" style="width: 100%" id="passwordAccountReset"><br><br>
					
							<br><button id="resetPasswordButtonModal" style="float: right;" type="button" class="btn btn-success btn-sm addcampaign buttonsmodals2" typeButton="1">RESET</button>
					</div>
					<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>
	
	<div id="modalCreateCampaign" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button id="closemodal" type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Offer</h4>
					</div>
					<div class="modal-body">
						<form id="addCampaignForm">
							Offer ID:<br>
							<input class="inputModal" disabled type="text" name="campaignid"><br>
							<span id="spancampaignid">
							Offer Name:<br>
							<input class="inputModal" type="text" style="width: 100%" name="campaignName"><br><br>
							</span>
							Limit bid:<br>
							<input class="inputModal" type="text" name="maxbid"><br><br>
							<input type="checkbox" id="Uponly">  Run just Process 1
							<br><br>
							Account:<br>
							<select class="selectsRow" id="selectboxAccounts" name="selectboxAccounts">
								<?php echo $selectAccounts; ?> 
							</select>							
							<br><br>
							
							<br><button id="addcampaignButtonModal" style="float: right;" type="button" class="btn btn-success btn-sm addcampaign buttonsmodals" typeButton="1"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate savingEdit"></span><span class="savingEdit" style="margin-right: 34px;">getting offer name</span>Add</button>
							
							
							<button id="editcampaignButtonModal" style="float: right;" type="button" class="btn btn-info btn-sm editcampaign buttonsmodals" typeButton="2">Edit</button>
							<button id="deletecampaignButtonModal" style="float: right;" type="button" class="btn btn-warning btn-sm editcampaign buttonsmodals" typeButton="3">Delete</button>
							
							
							
						</form>
					</div>
					<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>

				

			</div>
		</div>
	</div>
<script>
$(document).ready(function () {
	 $('#datepicker').daterangepicker({
            format: 'YYYY-MM-DD',
            separator: ' to ',
			startDate: moment().subtract(3, 'days'),
			endDate: moment().subtract(1, 'days')	,
			alwaysShowCalendars: true,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 3 Days': [moment().subtract(3, 'days'), moment().subtract(1, 'days')],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
        });    
});

</script>
{% endblock %}
{% block simplescript %}
{% endblock %}
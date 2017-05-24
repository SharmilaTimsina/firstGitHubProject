{% extends "/headfooter.volt" %}
{% block title %}<title>Ticket System</title>{% endblock %}
{% block scriptimport %}    

	
	<link rel="stylesheet" type="text/css" href="/css/ticketsystem.css" />

	<script src="/js/datepickerjst/moment.min.js"></script>
	<script src="/js/datepickerjst/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/datepickerjst/daterangepicker.css" />


	<script type="text/javascript" src="/js/jquery.sumoselect.min.js"></script>
    <link href="/css/sumoselect.css" rel="stylesheet"/>

    <script type="text/javascript" src="/js/vendor/bootstrap-filestyle.min.js"></script> 

      <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    

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
				<div class="col-md-12"> 
					<h5 style="width: 790px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis; font-size: 25px;">TICKET (<span id="id_ticket"></span>) - <span id="id_subject"></span></h3>
				</div>
			</div>
			<div class="row" style="margin-top: 40px;margin-bottom: 47px;">
				<div class="col-md-12"> 
					<div class="col-md-3"> 
						<table>
							<tr>
								<td>
									<div class="form-group formgroupinvest">
										<label  for="tdate">Requester:</label>
										<p class="requesteds" id="requester"></p><span id="requesterid" style="display:none;"></span>
										<p class="requesteds" id="dateRequested">requested at <span id="daterequested"></span></p>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="form-group formgroupinvest">
										<label  for="tdate">Incharge:</label>
										<p class="requesteds" id="incharge"></p>
										<p class="requesteds" id="dateIncharged">incharged at <span id="dateincharged"></span></p>
									</div>
								</td>
							</tr>
						</table>
					</div>
					<div class="col-md-3"> 
						<select style="display: none;" id="statusSBHide">
							<option value="0">Open</option>
							<option value="1">Request</option>
							<option value="2">Assigned</option>
							<option value="3">In progress</option>
							<option value="4">Validation</option>
							<option value="5">Closed</option>
							<option value="6">On hold</option>
							<option value="7">Refused</option>
						</select>
						<p class="pp" id="pstatus">status: <span id="id_status"></span></p>
						<p class="pp" id="pdeadline">deadline: <span id="id_deadline"></span></p>
						<input style="display: none;" type="input" id="buttonChangedeadline" class="datepicker" />
					</div>
					<div class="col-md-3"> 
						<div id="pickStatus" STYLE="MARGIN-LEFT: 74PX;">
							<table>
								<tr>
									<td>
										<input hidden type="input" id="buttonPickTicket" class="datepicker" />
										
										<style>
											div#ui-datepicker-div {
											    width: 288px;
											}
										</style>
									</td>
								</tr>
							</table>
						</div>
						<div style="display: none;MARGIN-LEFT: 74PX;" id="waitingStatus">
							<p id="pwaiting">Waiting for reply.</p>
							<style>
								#pwaiting {
									border: 1px solid red;
							    	color: red;
								    width: 164px;
								    padding: 5px;
								    border-style: dashed;
								    font-size: 20px;
								}
							</style>
						</div>
						<div id="pickedStatus" STYLE="MARGIN-LEFT: 74PX;">
							<table>
								<tr>
									<td>
										<button style="" id="buttonValidationTicket" type="button" name="submit" class="btn btn-success buttonsInvest">Send to Validation</button>
									</td>
								</tr>
								<tr>
									<td>
										<button style="" id="buttonHoldTicket" type="button" name="submit" class="btn btn-warning buttonsInvest">Put on Hold</button>
									</td>
									<td>
										<button style="" id="buttonProgressTicket" type="button" name="submit" class="btn btn-warning buttonsInvest">Put in Progress</button>
									</td>
								</tr>
								<tr>
									<td>
										<button style="" id="buttonRefuseTicket" type="button" name="submit" class="btn btn-danger buttonsInvest">Refuse Ticket</button>
									</td>
								</tr>
								<tr>
									<td>
										<button style="" id="buttonRequestTicket" type="button" name="submit" class="btn btn-primary buttonsInvest">Request Content</button>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="col-md-3"> 
						<table id="assignCol">
							<tr>
								<td>
									<div class="form-group formgroupinvest">
										<label for="tdate">Users to be assigned:</label><br>
										<select class="search-box-sel-all2" id="usersToBA">
											<option value="111">- - -</option>
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<button disabled style="" id="buttonAssignTicket" type="button" name="submit" class="btn btn-info buttonsInvest">ASSIGN</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div style="margin-top: 28px;" class="row">
				<div class="col-md-6"> 
					<table>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label  for="tdate">Subject:</label>
									<input style="width: 470px;" type="text" class="form-control datepicker" id="subjectticket" name="subjectticket">
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="tdate">Details:</label><br>
									<textarea style="padding: 9px; width: 470px; max-width: 470px; height: 381px;" class="textAreaModal valid" id="requestDetails" name="requestDetails" aria-required="true" aria-invalid="false"></textarea>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="col-md-6"> 
					<table>
					<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="tdate">Type*:</label><br>
									<select class="search-box-sel-all2" id="typeSB">
										<option value="0">IT Request</option>
										<option value="1">Design Request</option>
									</select>
								</div>
							</td>
						</tr>
					<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="tdate">Who can see the ticket:</label><br>
									<select multiple class="search-box-sel-all2" id="usersSB">
										
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="tdate">Priority*:</label><br>
									<select class="search-box-sel-all2" id="prioritySB">
										<option value="0">Low</option>
										<option value="1">Normal</option>
										<option value="2">High</option>
										<option value="3">Urgent</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group">
									<label for="periodReport">Required period*:</label>
									<input style="width: 201px;" class="form-control selectFilter" title="Date" id="periodReport" type="text" name="periodReport" >
								</div>
							</td>
						</tr>
						
					</table>
				</div>
				<style>
				
				</style>
			</div>
			<div class="row" style="margin-top: 12px;">
				<div class="col-md-6">
					<table>	
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="tdate">Files:</label><br>
									<input style="width: 287px;" name="files[]" type="file" accept="">
									<script>	
										$(":file").filestyle({
											placeholder: "No files",
											buttonText: "Choose file to upload"
										});
										$(".bootstrap-filestyle").find('input[type="text"]').css('width', '360px');
										$(".bootstrap-filestyle").find('input[type="text"]').css('display', 'none');
									</script>
									<div id="files">
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="col-md-6">
					<table>	
						<tr>
							<td>

								<div style="margin-top: 19px;" class="row">
        <div style="margin-left: 14px; width: 474px; height: 360px;">
            <div class="panel panel-primary" style="border-radius: 0px;">
                <div class="panel-heading" id="accordion">
                    <span class="glyphicon glyphicon-comment"></span> History and Chat
                    <img id="imagerefresh" src="/img/refresh.png">
                    <a href="<?php echo (isset($downloadExcel)) ? $downloadExcel : '' ?>"><img id="downloadexcel" src="/img/excel.png"></a>
                </div>
		            <div class="panel-collapse collapse in" id="collapseOne">
		                <div id="chatpabelbody" class="panel-body">
		                    <ul id="chatbody" class="chat">
		                    
		                    </ul>
		                </div>
		                <div class="panel-footer">
		                    <div class="input-group">
		                        <input id="btn-inputchat" type="text" class="form-control input-sm" placeholder="Type your message here..." />
		                        <span class="input-group-btn">
		                            <button id="sendMessage" class="btn btn-warning btn-sm" id="btn-chat">
		                                Send</button>
		                        </span>
		                    </div>
		                </div>
		            </div>
		            </div>
		        </div>
		    </div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script>
		var ticketInfo = <?php echo (isset($ticketInfo)) ? "'" . $ticketInfo . "';" : "'';"; ?>
		var ticketChat = <?php echo (isset($ticketChat)) ? "'" . $ticketChat . "';" : "'';"; ?>
		var ticketFiles = <?php echo (isset($ticketFiles)) ? "'" . $ticketFiles . "';" : "'';"; ?>
		var usersApi = <?php echo (isset($users)) ? $users : "''"; ?>;
		var usersMyArea = <?php echo (isset($usersMyArea)) ? $usersMyArea : "''"; ?>;
	</script>

	<script src="/js/ticketsystem/editticket_itsdes.js"></script>

	<style>
	p#pdeadline {
	    margin-top: -13px;
	    font-size: 15px;
	}
	button#buttonAssignTicket {
	    width: 100%;
	}
	.buttonsInvest {
	    margin: 2px;
	    width: 151px;
	}
	span#id_status {
	    color: darkgrey;
	    text-transform: uppercase;
	    font-weight: 900;
	}
	span#id_subject {
	    font-size: 17px;
	}
	.pp {
	    font-size: 20px;
	    font-weight: 900;
	    margin-top: 38px;
	}
	.imgCircle {
	    width: 44px;
	    height: 40px;
	    background-color: red;
	    padding-top: 10px;
	    text-align: center;
	    color: white;
	    font-weight: bold;
	    border-radius: 50%;
	}
	img#imagerefresh {
	    width: 20px;
	    float: right;
	    cursor: pointer;
	}
	img#downloadexcel {
	    width: 25px;
	    float: right;
	    margin-right: 20px;
	    margin-top: -4px;
	    cursor: pointer;
	}
	span#id_deadline {
	    color: blueviolet;
	}
	</style>
	<script>
	$(document).ready(function() {
		 setTimeout(function(){  
	    	$( '.search-box-sel-all' ).unbind();
			$( '.search-box-sel-all2' ).unbind(); 
		}, 1000);
	});
	</script>
	<script>
		var idAuth = <?php echo (isset($authID)) ? $authID : "''"; ?>
	</script>

{% endblock %}
{% block simplescript %}
{% endblock %}
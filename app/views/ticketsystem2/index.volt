{% extends "/headfooter.volt" %}
{% block title %}<title>Ticket System</title>{% endblock %}
{% block scriptimport %}    

	<link rel="stylesheet" type="text/css" href="/css/ticketsystem.css" />
	

	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>
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
				<p class="titles">Ticket System</p>
				<div class="col-md-12"> 
					<a style="margin-left: 20px; float: right;" target="_blank" href="/js/ticketsystem/TICKET_SYSTEM_TUTORIAL.pdf" style="float: right;" type="button" class="">Documentation</a>
					<a href="/ticketsystem2/createTicket" style="float: right;" type="button" class="btn btn-info btn-sm">Create Ticket</a>
				</div>
			</div>
			<div class="row userside">
				<div class="col-md-12"> 
					<table>
						<tr>
							<td>
								<div class="form-group">
									<label for="periodReport">Priority:</label>
									<select multiple class="search-box-sel-all" id="prioritySB">
										<option value="0">Low</option>
										<option value="1">Normal</option>
										<option value="2">High</option>
										<option value="3">Urgent</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group selectsBoxes">
									 <label for="sourcesSB">Status:</label>
									 <select multiple class="search-box-sel-all" id="statusSB">
										<option value="0">Open</option>
										<option value="1">Request</option>
										<option value="2">Assigned</option>
										<option value="3">In progress</option>
										<option value="4">Validation</option>
										<option value="5">Closed</option>
										<option value="6">On hold</option>
										<option value="7">Refused</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group selectsBoxes">
									<label for="sourcesSB">Type:</label>
									<select multiple class="search-box-sel-all" id="typeSB">
										<option value="0">IT Request</option>
										<option value="1">Design Request</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group selectsBoxes">
									<button id="buttonFilterTickets" type="button" class="btn btn-success">FILTER</button>									
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="row userside">
				<div class="col-md-12"> 
					<table style="width: 100%;" id="tableTicketsUserSide" class="table-striped table-bordered">
						<thead>
							<tr>
								<th>ID</th>
								<th>Requester</th>
								<th>Assigned to</th>
								<th>Incharge</th>
								<th>Subject</th>
								<th>Priority</th>
								<th>Status</th>
								<th>Created at</th>
								<th>Deadline</th>
								<th>Type</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tbodyTicket">
							
						</tbody>
					</table>	
				</div>
			</div>
			<script>
			$(document).ready(function() {
				 setTimeout(function(){  
			    	$( '.search-box-sel-all' ).unbind();
					$( '.search-box-sel-all2' ).unbind(); 
				}, 1000);
			});
			</script>
			<script>
				var jsonTable = <?php echo (isset($ticketTable)) ? "'" . $ticketTable . "';" : "'';"; ?>
				var typeEdit = <?php echo (isset($typeEdit)) ? "'" . $typeEdit . "';" : "'';"; ?>
			</script>
			<script src="/js/ticketsystem/ticketsystem2.js"></script>
		</div>
	</div>
	<style>
		td {
			padding: 20px;
		}
		button#buttonFilterTickets {
		    margin-bottom: -21px;
		}
	    
	</style>

{% endblock %}
{% block simplescript %}
{% endblock %}
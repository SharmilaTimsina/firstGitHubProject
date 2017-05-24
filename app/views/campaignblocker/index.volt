{% extends "/headfooter.volt" %}
{% block title %}<title>Offer Blocker</title>{% endblock %}
{% block scriptimport %}    
	
	<script src="/js/campaignblocker/index.js"></script>

	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>

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
					<h2>Offer Blocker</h2>
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#block">Block</a></li>
						<li><a data-toggle="tab" href="#backup">Restore</a></li>
						<li><a data-toggle="tab" href="#tempblock">Temporary offer blocker</a></li>
					</ul>
				</div>
			</div>
			<div class="tab-content">
  				<div id="block" class="tab-pane fade in active">
  					<div class="row">
						<!-- <div class="col-md-12"> 
							<div class="form-group formgroupinvest">
								<label for="campaign">Offer name:</label>
								<input  class="form-control" name="campaign" type="text">
								<button id="buttonSearchCampaign" type="button" name="submit" class="btn btn-success buttonsInvest buttonsN">SEARCH</button>
							</div>
						</div>-->						<div class="col-md-4 form-group" style="padding-top: 1%;">							<label class="simple-label">Client</label>							<input class="form-control" style="width: 100%;" id="aggfilter" type="text">							<select class="form-control" id="aggNames" name="agg" required="required" size="5">								<?php echo $aggregatorsList ?>							</select>							<button id="buttonSearchCampaign" type="button" name="submit" class="btn btn-success buttonsInvest buttonsN">SEARCH</button>						</div>						<div class="col-md-4 form-group" style="padding-top: 1%;">														<label class="simple-label">Offers</label>														<select class="form-control" id="campaigns" name="campaigns" required="required">								<option><option>							</select>													</div>
						<div class="row firststep">
							<div class="col-md-12"> 
								<h3 id="titleChanges">This will be affected</h3>
							</div>
						</div>
						<div class="row firststep">
							<div class="col-md-6"> 
								<table width="100%" class="table-striped table-bordered tableHeadReport">
									<thead>
										<tr>
											<td id="njumpsID">NJump's</td>
										</tr>
									</thead>
									<tbody id="tbodynjumps">
										
									</tbody>
								</table>
							</div>
							<div class="col-md-6"> 
								<table width="100%" class="table-striped table-bordered tableHeadReport">
									<thead>
										<tr>
											<td  id="mjumpsID">MJump's</td>
										</tr>
									</thead>
									<tbody id="tbodymjumps">
										
									</tbody>
								</table>
							</div>
						</div>
						<div class="row firststep">
							<div class="col-md-12" style="margin-left: 15px;"> 
								<div class="form-group formgroupinvest">
									<label for="backupName">Backup name:</label>
									<input name="backupName" class="form-control" type="text">
								</div>
								<div class="form-group formgroupinvest">
									<label for="backupDescription">Backup description:</label>
									<input name="backupDescription"  class="form-control" type="text">
									<button id="buttonBlockCampaign" type="button" name="backupDescription" class="btn btn-warning buttonsInvest buttonsN">BLOCK</button>
								</div>
							</div>
							
						</div>

						<div class="row secondstep">
							<div class="col-md-12"> 
								<h3 id="titleChanges2"></h3>
							</div>
						</div>
						<div class="row secondstep">
							<div class="col-md-6"> 
								<table width="100%" class="table-striped table-bordered tableHeadReport">
									<thead>
										<tr>
											<td id="njumpsID2">NJump's</td>
										</tr>
									</thead>
									<tbody id="tbodynjumps2">
										
									</tbody>
								</table>
							</div>
							<div class="col-md-6"> 
								<table width="100%" class="table-striped table-bordered tableHeadReport">
									<thead>
										<tr>
											<td id="mjumpsID2">MJump's</td>
										</tr>
									</thead>
									<tbody id="tbodymjumps2">
									
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

  				<div id="backup" class="tab-pane fade in">
  					<div class="row">
						<div class="col-md-12"> 
							<table id="tableBackups" width="100%" class="table-striped table-bordered tableHeadReport">
								<thead>
									<tr>
										<td>Date</td>
										<td>Name</td>
										<td>Description</td>
										<td>Offer</td>
										<td>Restore</td>
									</tr>
								</thead>
								<tbody id="tbodytableBackups">
									<?php echo $tableBackups; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div id="tempblock" class="tab-pane fade in">
  					<div class="row">
  						<input id="inputNamesOffers" placeholder="Ex: OFFER_NAME1,OFFER_NAME2,OFFER_NAME3"></input>
  						<button id="blockoffersinput">BLOCK</button>
  					</div>
  					<div class="row">
						<div class="col-md-12"> 
							<table id="tableTemBlocker" width="100%" class="table-striped table-bordered tableHeadReport">
								<thead>
									<tr>
										<td>Offers</td>
										<td>Date period</td>
										<td>Restore</td>
									</tr>
								</thead>
								<tbody id="tbodytableTempBlocker">
									<?php echo $tableTempBlocker; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<style>
	#inputNamesOffers {
		margin-top: 40px;
	    margin-bottom: 40px;
	    margin-left: 28px;
	    width: 80%;
	}
	div#tableBackups_wrapper {
	    margin-top: 40px;
	}
	.firststep {
		display: none;
	}
	.secondstep {
		display: none;
	}

	#titleChanges {
	    text-align: center;
    	color: red;
   		margin-bottom: 30px;
	}
	#titleChanges2 {
	    text-align: center;
    	color: red;
   		margin-bottom: 30px;
	}
	input.form-control {
	    width: 300px;
	}
	.tableHeadReport tr td {
	    padding: 5px;
	}
	.form-group.formgroupinvest {
	    margin-top: 40px;
	}
	button.buttonsN {
		margin-top: 20px;
	}
	.tableHeadReport thead tr {
	    background-color: #01A9DB;
	    color: black;
	    font-weight: 700;
	}
	table.table-striped.table-bordered.tableHeadReport {
	    margin-left: 15px;
	}
	.modalIcon {
	    width: 21px;
	    cursor: pointer;
	}
	td.iconEdit {
	    text-align: center;
	}
	</style>

	
{% endblock %}
{% block simplescript %}
{% endblock %}
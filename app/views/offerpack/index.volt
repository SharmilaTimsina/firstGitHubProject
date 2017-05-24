{% extends "/headfooter.volt" %}
{% block title %}<title>Offers</title>{% endblock %}
{% block scriptimport %}    
	
	

	<script type="text/javascript" src="http://mobisteinreport.com/js/jquery.sumoselect.min.js"></script>
	<link href="http://mobisteinreport.com/css/sumoselect.css" rel="stylesheet"/>
	
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.1/js/dataTables.fixedColumns.min.js"></script>

	<script type="text/javascript" src="http://mobisteinreport.com/js/vendor/bootstrap-filestyle.min.js"></script> 

	<script type="text/javascript" src="http://mobisteinreport.com/js/clipboard.min.js"></script> 

	<script src="http://mobisteinreport.com/js/offerspacks/index.js"></script>

	<script src="http://mobisteinreport.com/js/offerspacks/jquery.qtip.min.js"></script>
	<link href="http://mobisteinreport.com/js/offerspacks/jquery.qtip.min.css" rel="stylesheet"/>
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
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<form style="width: 382px; margin-bottom: 93px; display: none;" name="formjump" id="uploadCsv" enctype="multipart/form-data">
						<tr>
							<td>
								<div class="form-group">
									<label for="">File insert:</label>
									<input name="files[]" type="file" accept=".csv">
								</div>
							</td>
						</tr>
					</form>	
						<tr>
							<td>
								<div class="form-group" style="text-align: center;">
									<a href="./offerpackedit" type="button" name="submit" class="btn btn-info ">+ Create Jump</a>
								</div>
							</td>
						</tr>
				</div>
				<div class="col-md-4"></div>
			</div>
			<div class="row">
				<div class="col-md-12"> 
					<table id="tablefilters">	
						
						<tr>
							<td>
								<div class="form-group formgroupinvest formcountry">
									<label>Country:</label>
									<select id="countrySB" multiple name="geoFilter[]" placeholder="" typee="1" data-selectoutside="1" class="form-control search-box-sel-all2">
										<option value="ALL">All</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest">
									<label>Carrier:</label>
									<select id="carrierSB" disabled multiple name="carrierFilter[]" typee="1" placeholder="" class="form-control search-box-sel-all">
										<option value="ALL">ALL</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest formgroupadvertiser">
									<label>Client:</label>
									<select id="aggsSB" multiple name="advertiserFilter[]" placeholder="" typee="1" class="form-control search-box-sel-all">
										<option value="ALL">All</option>
									</select>
								</div>
							</td>

							<td style="display: none;">
								<div class="form-group formgroupinvest">
									<label>Campaign Name:</label>
									<select id="campaignName" multiple name="trafficFilter[]" placeholder="" typee="1" class="form-control search-box-sel-all">
										<option value="ALL">All</option>
									</select>
								</div>
							</td>

							<td>
								<div class="form-group formgroupinvest">
									<label>Area:</label>
									<select id="areaSB" multiple name="offernameFilter[]" placeholder="" typee="1" class="form-control search-box-sel-all">
										<option value="ALL">All</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest">
									<label>Search:</label>
									<input id="searchInput" type="text" placeholder="Jump or Campaign name" name="searchInput">
								</div>
							</td>
							</tr>
							<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label>Vertical:</label>
									<select id="verticalSB" multiple name="offerstatusFilter[]" placeholder="" typee="1" class="form-control search-box-sel-all">
										<option value="ALL">All</option>
									</select>
								</div>
							</td>
							
							<td>
								<div class="form-group formgroupinvest">
									<label>Model:</label>
									<select id="modelSB" multiple name="categoryFilter[]" placeholder="" typee="1" class="form-control search-box-sel-all">
										<option value="ALL">All</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest">
									<label>Status:</label>
									<select id="statusSB" multiple name="amFilter[]" placeholder="" typee="1" class="form-control search-box-sel-all">
										<option value="ALL">All</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest">
									<label>AM:</label>
									<select id="accountSB" multiple name="amFilter[]" placeholder="" typee="1" class="form-control search-box-sel-all">
										<option value="ALL">All</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest">
									<button id="buttonFilter" type="button" name="submit" class="btn btn-success ">GO</button>
								</div>
								<div class="form-group formgroupinvest">
									<button id="buttonResetFilter" type="button" name="submit" class="btn btn-warning ">RESET</button>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>

    	</div>
        <div class="container containerf">
        	
			<div class="row">
				<div class="col-md-12" style="margin-top: 100px; width: 100%;"> 
					<table width="100%" class="table-striped table-bordered" id="tablePackOffers">
						<thead>
							<th style="padding-left: 5px;">Edit selected <br> <input id="checkall" type="checkbox" name="all" value="all"></th>
							<th>Data</th>	
							<th>Country</th>	
							<th>Carrier</th>	
							<th>Advertiser</th>	
							<th>Offer Name</th>	
							<th>Area</th>	
							<th>Vertical</th>	
							<th>Jump</th>	
							<th>Model</th>	
							<th>Payout</th>	
							<th>Currency</th>	
							<th>Daily Cap</th>
							<th>AM</th>	
							<th>CM</th>		
							<th>Status</th>
							<th>OPTN</th>
						</thead>
						<tbody id="tableBodyPackOffers">

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div id="modalScreenshot" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	      </div>
	      <div id="modalBodyScreen" class="modal-body">
	      	<img id="screenShotMo" class="imagModal" src=""></img>
	      	<p id="labelShotMo"></p>
	      </div>
	      <div class="modal-footer">
	       
	      </div>
	    </div>

	  </div>
	</div>
	<script>
		var jsonVar = <?php echo (isset($offervar)) ? $offervar : "'';"; ?>		
	</script>
	<style>
		.jumpnamecopyclicpcampaign {
			cursor: pointer;
			font-weight: 700;
		}
		.jumpnamecopyclicpcampaign:hover {
			cursor: pointer;
			color: green;
			font-weight: 700;
		}
		.jumpnamecopyclicp {
			cursor: pointer;
			font-weight: 700;
		}
		.jumpnamecopyclicp:hover {
			cursor: pointer;
			color: green;
			font-weight: 700;
		}
		.form-group.formgroupinvest {
		    width: 202px;
		    padding-right: 11px;
		}
		.SumoSelect {
			width: 169px;
		}
		button#buttonFilter {
		    margin-top: 23px;
		}
		p.CaptionCont.SelectBox.search {
		    height: 31px;
		}
		input#searchInput {
		    height: 30px;
		    padding-left: 5px;
		}
		table#tablePackOffers tr td {
			padding: 5px;
		}
		#tablePackOffers {
			width: 100%;
			border: 1px solid #ddd;
		}
		table#tablePackOffers thead tr {
			background-color: #01A9DB;
			color: black;
			font-weight: 700;
		}
		input[type="checkbox"] {
		    margin-right: 10px;
		    margin-top: -3px;
			margin-left: 16px;
		}
		.sorting {
		    padding: 3px;
		    padding-left: 10px;
		}
		.icontable {
		    width: 18px;
		    padding: 1px;
		}
		.imagModal {
			width: 100%;
   			height: 100%;
		}
		div#modalBodyScreen {
		    text-align: center;
		}
		a.infoToolTip {
		    all: unset;
		}
		span.spanTooltip {
		    font-weight: 700;
		}
		.qtip-default {
		    border: 1px solid #F1D031;
		    background-color: cornsilk;
		    color: #555;
		}
		.qtip {
		    position: absolute;
		    left: -28000px;
		    top: -28000px;
		    display: none;
		    max-width: 371px;
		    min-width: 50px;
		    font-size: 10.5px;
		    line-height: 12px;
		    direction: ltr;
		    box-shadow: none;
		    padding: 0;
		}
		.formgroupadvertiser .optWrapper.multiple {
		    width: 300px;
		}
		#buttonFilter {    
		    background-image: url(http://mobisteinreport.com/img/packofferssearch.png);
		    background-repeat: no-repeat;
		    padding-left: 39px;
		    background-position: 10px 3px;
		 }
		 button#buttonResetFilter {
			    padding-left: 12px;
			    padding-right: 14px;
			}
		.containerf {
		    width: 100%;
		}
		tbody#tableBodyPackOffers tr {
		    height: 72px;
		}
	</style>
	<script>
	$(document).ready(function() {
	    
	    setTimeout(function(){  
	    	$( '.search-box-sel-all' ).unbind();
			$( '.search-box-sel-all2' ).unbind(); 
		}, 1000);
	    

	    $('body').on('click', ".opt", function(e) {
	  
	        var string = $(this).find('label').text();
	        if(string.indexOf('All') !== -1) {
	                	
		        if($(this).hasClass('selected')) {
		        	$(this).closest('.SumoSelect').find('.options li').removeClass('selected');
		        	$(this).closest('.SumoSelect').find('select option:selected').removeAttr("selected");
		        	$(this).closest('.SumoSelect').find('.options li:eq(0)').addClass('selected');
		        	$(this).closest('.SumoSelect').find('select option:eq(0)').prop("selected", true)
		        } else {
		        	$(this).closest('.SumoSelect').find('.options li').removeClass('selected')
		        	$(this).closest('.SumoSelect').find('select option:selected').removeAttr("selected");
		        }
		        
	   	 	} else {
		        $(this).closest('.SumoSelect').find('select option:eq(0)').removeAttr("selected");
		        $(this).closest('.SumoSelect').find('.options li:eq(0)').removeClass('selected')
	   	 	}

	   	 	$($(this).closest('.SumoSelect').find('select'))[0].sumo.setText();
	    });

	    $("body").on("click", "#buttonResetFilter", function() {
			$(".search-box-sel-all, .search-box-sel-all2").each(function() { $(this)[0].sumo.unSelectAll(); });
			//$("#tablefilters .optWrapper .options li").each(function() { $(this).removeClass('selected'); });
			//$("#tablefilters .CaptionCont").each(function() { $(this).empty(); });
		});

    });
	</script>

	
{% endblock %}
{% block simplescript %}
{% endblock %}
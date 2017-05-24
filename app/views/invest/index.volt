{% extends "/headfooter.volt" %}
{% block title %}<title>Investment</title>{% endblock %}
{% block scriptimport %}    
    <script src="/js/invest/main.js"></script>
	
 <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker-bs3.css" />
    <script type="text/javascript" src="/js/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src="/js/FileSaver.min.js"></script> 
	<script type="text/javascript" src="/js/investment.js"></script>
		
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
			
			<?php 
				$auth = $this->session->get('auth');
				if(($auth['userlevel'] == 2 && $auth['utype'] == 2) || ($auth['userlevel'] == 1) ) {
					echo '';
				} else {
					echo '<style>.row.mainstreamers {
							display: none;
						}</style>';
				}
			?>
			
			
			
            <div class="row">
             <div class="col-md-12"> 
                <table>
				<tr>
					<form role="form" action="/invest/upload" method="post" enctype="multipart/form-data">
						<td>
							<div class="form-group formgroupinvest">
								<label for="file">File:</label>
								<input type="file" class="form-control" id="file" name="file">
							</div>
						</td>
						<td>
							<div class="form-group formgroupinvest">
								<label  for="tdate">Date:</label>
								<input type="text" class="form-control datepicker" id="tdate" name="tdate">
							</div>
						</td>
						<td>
							<div class="form-group formgroupinvest">
								<label for="source">Sources:</label>
								<select class="form-control" id="source" name="source">
									<?php echo $combo; ?>
								</select>
							</div>
						</td>
						<td>
							<button type="submit" name="submit" class="btn btn-success buttonsInvest">Upload</button>
						</td>
					</form>
				</tr>
				<tr>
					<td>
						<div class="form-group formgroupinvest">
                            <label  for="sdate">Start Date:</label>
                            <input type="text" class="form-control datepicker" id="sdate" name="sdate">
                        </div>
					</td>
					<td>
						<div class="form-group formgroupinvest">
                            <label for="edate">End Date:</label>
                            <input type="text" class="form-control datepicker" id="edate" name="edate">
                        </div>
					</td>
					<td>
						  <div class="form-group formgroupinvest">
                           <label for="selectboxMulti">Sources:</label>
                           										<select id="sourcesMultipleReport" name="sources[]" class="selectFilter search-box-sel-all"  multiple="multiple">
                           											<?php echo $combo; ?>
                           										</select>
                        </div>
					</td>
					<td>
						  <button id="downloadReportInvest" type="submit" name="submit" class="btn btn-default buttonsInvest">Report</button>
					</td>
				</tr>
				</table>
				
				<div class="row mainstreamers" style="margin-top: 100px;">
					 <div class="col-md-12"> 
						<table>
							<tr>
								<td>
									 <div class="form-group formgroupinvestFilter">
										<label for="countryF">Country:</label>
										<select multiple class="form-control selectFilter search-box-sel-all" id="countryF" name="countryFilter">
											 <?php echo $countriesCombo; ?>
										</select>
									</div>
								</td>
								<td>
									 <div class="form-group formgroupinvestFilter">
										<label for="sourceF">Source:</label>
										<select multiple class="form-control selectFilter search-box-sel-all" id="sourceF" name="sourceFilter">
											 <?php echo $sourcesCombo; ?>
										</select>
									</div>
								</td>
								<!--
								<td>	
									 <div class="form-group formgroupinvestFilter">
										<label for="campaignF">Campaign (njump):</label>
										<select class="form-control selectFilter" id="campaignF" name="campaignFilter">
											<option value="">Select</option>
											<?php //echo $campaignsCombo; ?> 
										</select>
									</div>
								</td>
								-->
								<td>
									 <div class="form-group formgroupinvestFilter">
										<label for="dateF">Date:</label>
										<input class="form-control selectFilter" title="Start day" id="convPeriod2" type="text" name="convP2" >
									</div>
								</td>
								<!--
								<td>
									 <div class="form-group formgroupinvestFilter">
										<label for="domainF">Domain:</label>
										<select class="form-control selectFilter"  id="domainF" name="domainFilter">
											 <option value="">Select</option>
											<?php //echo $domainsCombo; ?>
										</select>
									</div>
								</td>
								-->
								<td>
									 <div class="form-group formgroupinvestFilter">
										<label for="selectboxMulti">Agregation:</label>
										<select id="selectboxMulti" class="selectFilter search-box-sel-all" multiple="multiple">
											<option value="1">Country</option>
											<option value="2">Source</option>
											<option value="3">Domain</option>
											<option value="4">Njump</option>
											<option value="5">Day</option>
											<option value="6">Platform</option>
											<option value="7">Gender</option>
										</select>
									</div>
								</td>
								<td>
									 <div class="form-group formgroupinvestFilter">
										<label for="selectboxMulti">OS:</label>
										<select multiple class="form-control selectFilter search-box-sel-all"  id="selectboxOS">
											<option value="1">Android</option>
											<option value="2">iOS</option>
										</select>
									</div>
								</td>
								
								</tr>
								<tr>
								<td>
									 <div style="margin-left: 4px;" class="form-group formgroupinvestFilter">
										<label for="selectboxMulti">Platform:</label>
										<select multiple class="form-control selectFilter search-box-sel-all" id="selectboxPlatform" >
											<option value="0">Desktop</option>
											<option value="1">Mobile</option>
											<option value="2">Instagram</option>
										</select>
									</div>
								</td>
								<td>
									 <div style="margin-left: 4px;" class="form-group formgroupinvestFilter">
										<label for="selectboxMulti">Gender:</label>
										<select multiple class="form-control selectFilter search-box-sel-all" id="selectboxGender" >
											<option value="0">M</option>
											<option value="1">F</option>
										</select>
									</div>
								</td>
								<td>
									 <button type="submit" id="buttonFilter" name="submit" class="btn btn-default buttonsInvest">GO</button>
								</td>
								</tr>
							
						</table>
					</div>
				</div>
				<div class="row mainstreamers" style="margin-bottom: 20px;     margin-left: 2px;">
					
						<div style="width: 179px;display: -webkit-inline-box;margin-left: 9px;margin-top: 30px;margin-bottom: 30px;">
							<input placeholder="Search by banner ID" type="text" class="form-control" id="searchInput">
							<button type="submit" id="buttonSearch" name="submit" class="btn btn-default">SEARCH</button>
						</div>
						<div class="row">
							<div class="col-md-6"> 
								<button type="submit" id="buttonDownloadReportExcel" name="submit" class="btn btn-default buttonsInvest">Download Report</button>
							</div>
							<div class="col-md-6"> 
								<div class="row" style="float: right;">
									<p id="numPages">Page <span id="currentPage">-</span> of <span id="totalPages">-</span></p>
									<button type="submit" id="buttonPreviousPage" name="submit" class="btn btn-default">PREVIOUS</button>
									<button type="submit" id="buttonNextPage" name="submit" class="btn btn-default">NEXT</button>
								</div>
							</div>
							
						</div>
						
				
					
				</div>
				<div class="row mainstreamers" style="margin-top: 30px;">
					 <div style="text-align: center;" class="col-md-12"> 
						<table width="100%" class="table-striped table-bordered" id="tableTable">
							<img src="/img/status.gif" id="spin"/>
							<thead id="theadtable">
								
							</thead>
							<tbody id="tbodytable">
								
							</tbody>
							<tfoot id="tfoottable">
									
							</tfoot>
						</table>
					</div>
				</div>
				
                 
<!--                <div class="col-md-3 col-md-offset-1">
                    exform
                </div>-->
            </div>
			<!-- Modal -->
				<div id="modaldetails" class="modal fade" role="dialog">
				  <div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Details</h4>
					  </div>
					  <div class="modal-body">
						<p><b>TITLE:  </b><span id="titlebulk"></span></p>
						<p><b>DESCRIPTION:  </b><span id="descriptionbulk"></span></p>
						<p><b>PLATFORM:  </b><span id="platformbulk"></span></p>
						<p><b>OS:  </b><span id="osbulk"></span></p>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
			
        </div>
    </div>
	<style>
		.imgTable {
			cursor: pointer;
		}
		.ms-drop input[type="checkbox"] {
			width: 13px;
			height: 13px;
			padding: 0;
			margin: 0;
			vertical-align: bottom;
			position: relative;
			top: -3px;
		}

		.ms-drop ul li label {
			display: block;
			padding-left: 15px;
			text-indent: -15px;
		}
		button#buttonNextPage {
			margin-right: 30px;
		}
		button#buttonFilter {
		    padding-left: 40px;
		    padding-right: 40px;
		    margin-left: 10px;
		    margin-bottom: 10px;
		}
		body {
			overflow-y: scroll;
		}
		img#spin {
			margin-bottom: 100px;
			margin-top: 100px;
			display: none;
		}
		tfoot#tfoottable {
			background-color: #01A9DB;
		}
		.headsort {
			cursor: pointer;
		}
		input#searchInput {
		    width: 207px;
		    margin-right: 10px;
		}
		.imgTable {
			width: 168px;
		}
		table#tableTable tr td {
			padding: 5px;
		}
		#tableTable {
			width: 100%;
			border: 1px solid #ddd;
		}
		button#buttonSearch {
		    margin-left: 7px;
		}
		table#tableTable thead tr {
			background-color: #01A9DB;
			color: black;
			font-weight: 700;
		}
		.form-group.formgroupinvest {
			padding-right: 90px;
		}
		.buttonsInvest {
			margin-top: 10px;
		}
		.selectFilter {
			width: 150px;
		}
		.form-group.formgroupinvestFilter {
			padding: 10px;
		}
		button#buttonDownloadReportExcel {
		    margin-left: 10px;
		}
	</style>
	<script>
		$('input[name="convP2"]').daterangepicker({
			format: 'YYYY-MM-DD',
			"opens": "right"
		});

		window.searchSelAll = $('.search-box-sel-all').SumoSelect({ csvDispCount: 3, selectAll:true, search: true, searchText:'Enter here.', okCancelInMulti:false });
		$('select.search-box-sel-all')[1].sumo.selectAll();
		$('select.search-box-sel-all')[4].sumo.selectAll();
		$('select.search-box-sel-all')[5].sumo.selectAll();
		$('select.search-box-sel-all')[6].sumo.selectAll();
		//$('select.search-box-sel-all')[2].sumo.selectAll();
	</script>
{% endblock %}
{% block simplescript %}
{% endblock %}
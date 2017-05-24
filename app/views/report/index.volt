{% extends "/headfooter.volt" %}
{% block scriptimport %}    
<?php date_default_timezone_set('Europe/Lisbon');?> 

<script src="/js/report/index.js"></script>

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<script src="/js/datepickerjst/daterangepicker.js"></script>
<script src="/js/datepickerjst/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="/js/datepickerjst/daterangepicker.css" />


<script type="text/javascript" src="/js/jquery.sumoselect.min.js"></script>
	<link href="/css/sumoselect.css" rel="stylesheet"/>

{% endblock %}
{% block title %}<title>Mobistein Reporting</title>{% endblock %}
{% block preloader %}
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
{% endblock %}
{% block content %}
<div id="wrap">
    <div class="container">
        <div class="row">
        <?php if($userLevel < 3) { ?>
            <div class="col-md-12"> 
                <div class="col-md-3">

                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">Main Report</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="well" action="/report/main">
                            <fieldset>
                                <div class="form-group">
                                    <input  class="form-control sdate datescurrentdate"  name="s" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control edate datescurrentdate"  name="e" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <select id="cc" name="cc[]" multiple="multiple"  class="search-box-sel-all">
                                    </select>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="c" type="checkbox" checked>View Clicks
                                    </label>
                                </div>
								<div class="checkbox" style="display: none;">
                                    <label>
                                        <input name="testing" type="checkbox"  checked>Accurate results
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" name="action" value="excel" class="btn btn-primary">Excel</button>
                                <button title = "Browse" style = "margin-top: 4px;" type="submit" name="action" value="prev" class="btn btn-primary">Preview results</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">Carrier Report</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="well" action="/report/operator">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control sdate datescurrentdate"  name="s" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control edate datescurrentdate"  name="e" type="text" value="">
                                </div>
                                <div class="form-group">
                                        <select name="cc[]" id="ccOP"  multiple="multiple"  class="search-box-sel-all">
                                    </select>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="simple" type="checkbox">Simple Report
                                    </label>
                                </div>
								<div class="checkbox" style="display: none;">
                                    <label>
                                        <input name="testing" type="checkbox"  checked>Accurate results
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-primary">Excel</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">Mjump Report</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="well" action="/report/mjump">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control sdate datescurrentdate"  id="startdatemjump" name="s" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control edate datescurrentdate"  id="enddatejump" name="e" type="text" value="">
                                </div>
								
                                    <div class="form-group">
                                        <select name="cc[]" multiple="multiple"  id="ccOP2" class="search-box-sel-all">
									
                                    </select>
                                </div>
                                <div class="form-group" style="display: none;">
                                        <input name="testing" type="checkbox"  checked>Accurate results
                                </div>    
								
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-primary">Excel</button>
                            </fieldset>
                        </form>
                    </div>    
                </div>
                <div class="col-md-3">

                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">Source Report</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="well" action="/report/network">
                            <fieldset>
                                <div class="form-group">
                                    <input  class="form-control sdate datescurrentdate" id="datepicker1" name="s" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control edate datescurrentdate" id="datepicker2" name="e" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <select id="so" name="so" class="search-box-sel-all">
                                        <?php echo $sourcesvar; ?>                                  
                                                                                
                                    </select>    
                                </div>
                                <div class="form-group">
                                    <select id="ccN" name="cc[]" class="search-box-sel-all">
                                    </select>
                                </div>

                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" name="action" value="excel" class="btn btn-primary">Excel</button>

                            </fieldset>
                        </form>
                    </div>
                </div>
                <!--                <div class="col-md-3 col-md-offset-1">
                                    exform
                                </div>-->
            </div>
        <?php } ?>
            <div class="col-md-12">
                <?php if($userLevel < 3) { ?>
                <!-- <div class="col-md-3">
                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">Affiliate Report</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="well" action="/report/payoutreport">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control sdate datescurrentdate"  name="s" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control edate datescurrentdate"  name="e" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <label class="simple-label">Source</label>
                                    <select multiple="multiple"  id="soid2" name="src[]" class="search-box-sel-all">
										<option value="allsrc">All sources</option>
                                       
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Excel</button>
                            </fieldset>
                        </form>
                    </div>   
                </div> -->
                <div class="col-md-3">
                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">Reports by hour</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="well" action="/report/reportbyhour">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control datetimepicker1" name="s" type="text" value='<?php echo $current_date . " 00:00:00" ;?>'>
                                </div>
                                <div class="form-group">
                                    <input class="form-control datetimepicker1" name="e" type="text" value='<?php echo date("Y-m-d H:00:00",strtotime("-1 hour")); ?>'>
                                </div>
								<div class="form-group">
                                    <label class="simple-label">Source</label>
                                    <select multiple="multiple"  id="srcbyhour" name="source[]" class="search-box-sel-all">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="simple-label">Country</label>
                                    <select multiple="multiple"  id="rbyhourccID" name="country[]" class="search-box-sel-all">
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Excel</button>
                            </fieldset>
                        </form>
                    </div>   
                </div>
				<div class="col-md-3">
                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">LP report</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="well" action="/report/lpreport">
                            <fieldset>
                                <div class="form-group">
                                    <input  class="form-control sdate datescurrentdate" name="s" type="text" value='<?php echo date('Y-m-d');?>'>
                                </div>
                                <div class="form-group">
                                    <input  class="form-control sdate datescurrentdate" name="e" type="text" value='<?php echo date('Y-m-d'); ?>'>
                                </div>
                                <div class="form-group">
                                    <label class="simple-label">Country</label>
                                    <select multiple="multiple"  id="lpreportcountriesid" name="country[]" class="search-box-sel-all">
                                    </select>
                                </div>
								<div class="checkbox">
                                    <label>
                                        <input name="c" type="checkbox" checked>Simple
                                    </label>
                                </div>
                                <button type="submit" value="submit" class="btn btn-primary">Excel</button>
                            </fieldset>
                        </form>
                    </div>   
                </div>
                <?php } ?>
                <?php if($userLevel > 2) { ?>
                <div class="col-md-2"></div>
                <?php } ?>
                <div class="col-md-3">

                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">General statistics</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="well" action="/report/statistics">
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="simple-label">First Period</label>
                                            <input class="form-control" title="First period date" id="fperiod" type="text" name="fperiod" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="simple-label">Second Period</label>
                                            <input class="form-control" title="Second period date" id="speriod" type="text" name="speriod" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="simple-label">Time Period</label>
                                        </div>
                                        <div class="col-md-10">
											<div class="col-xs-1"> 
												<p class="simple-label" style="padding-top:50%"> From </p>
											</div>
											<div class=" col-xs-12"></div>
											<div class=" col-xs-12"> 
                                            <select class="form-control" id="hour1" name="hoursStart">    
                                                <option value="00" selected="selected">00:00</option>
                                                <option value="01">01:00</option>
                                                <option value="02">02:00</option>
                                                <option value="03">03:00</option>
                                                <option value="04">04:00</option>
                                                <option value="05">05:00</option>
                                                <option value="06">06:00</option>
                                                <option value="07">07:00</option>
                                                <option value="08">08:00</option>
                                                <option value="09">09:00</option>
                                                <option value="10">10:00</option>
                                                <option value="11">11:00</option>
                                                <option value="12">12:00</option>
                                                <option value="13">13:00</option>
                                                <option value="14">14:00</option>
                                                <option value="15">15:00</option>
                                                <option value="16">16:00</option>
                                                <option value="17">17:00</option>
                                                <option value="18">18:00</option>
                                                <option value="19">19:00</option>
                                                <option value="20">20:00</option>
                                                <option value="21">21:00</option>
                                                <option value="22">22:00</option>
                                                <option value="23">23:00</option>
                                            </select>
                                        </div>
									</div>
                                        
                                        <div class=" col-md-10">
											<div class=" col-xs-1"> 
												<p class="simple-label" style="padding-top:75%"> To </p>
											</div>
											<div class=" col-xs-12"></div>
											<div class=" col-xs-12"> 
                                            <select class="form-control" id="hour2" name="hoursEnd">    
                                                <option value="00">00:59</option>
                                                <option value="01">01:59</option>
                                                <option value="02">02:59</option>
                                                <option value="03">03:59</option>
                                                <option value="04">04:59</option>
                                                <option value="05">05:59</option>
                                                <option value="06">06:59</option>
                                                <option value="07">07:59</option>
                                                <option value="08">08:59</option>
                                                <option value="09">09:59</option>
                                                <option value="10">10:59</option>
                                                <option value="11">11:59</option>
                                                <option selected="selected" value="12">12:59</option>
                                                <option value="13">13:59</option>
                                                <option value="14">14:59</option>
                                                <option value="15">15:59</option>
                                                <option value="16">16:59</option>
                                                <option value="17">17:59</option>
                                                <option value="18">18:59</option>
                                                <option value="19">19:59</option>
                                                <option value="20">20:59</option>
                                                <option value="21">21:59</option>
                                                <option value="22">22:59</option>
                                                <option value="23">23:59</option>
                                            </select>
										</div>
                                        </div>
                                    </div>
                                </div>
                                <?php if($userLevel < 3) { ?>
                                <div class="form-group">
                                    <label class="simple-label">Source</label>
                                    <select multiple="multiple"  id="soID" name="src[]" class="search-box-sel-all"> 
                                            <option value="allsrc">All sources</option>
										
                                    </select>
                                </div>    
                                     <?php }?>
                                <div class="form-group">
                                    <label class="simple-label">Display Results</label>
                                    <select class="form-control" id="aggFunction" name="aggFunction">    
                                        <option value="AVG" selected="selected">Averages</option>
                                        <option value="SUM">Totals</option>
                                    </select>
                                </div>     
                                <div class="form-group">
                                    <label class="simple-label">Country</label>
									<select multiple="multiple" class="form-control search-box-sel-all" id="ccID" name="cc[]">    
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="simple-label">Clients</label>
                                    <select multiple="multiple"  class="form-control search-box-sel-all" id="aggID" name="agg[]">    
                                        <option value="allaggs">All clients</option>
                                      
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="simple-label">Carriers</label>
                                    <select class="form-control" id="opID" name="selectedOperator">
                                        <option value="allops">All carriers</option>
                                        <?php echo $operatorsList2 ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="simple-label">Offers</label>
                                        <select multiple="multiple" class="form-control search-box-sel-all" id="campID" name="selectedCampaign[]">    
                                        <option value="allcampaigns">All offers</option>
                                        <?php echo ''; /*"'" .  $campaignSelectList . "'"*/ ?>
                                    </select>
                                </div>
								<?php if($userLevel < 2) { ?>
								<div class="form-group">
                                    <label class="simple-label">Source type</label>
                                    <select class="form-control" id="sourcetypeID" name="selectedSourceType">
                                        <option value="allsourcestypes">All source types</option>
                                        <option value="0">Adult sources</option>
                                        <option value="1">Affiliates</option>
                                        <option value="2">Mainstream</option>
                                        <option value="3">Old affiliates</option>
                                        
                                    </select>
                                </div>
								<?php } ?>
								<div class="checkbox" style="display: none;">
                                    <label>
                                        <input name="testing" type="checkbox" checked>Accurate results
                                    </label>
                                </div>
                                <button title = "browse selected filter" type="submit" name="action" value="prev" class="btn btn-primary">Browse</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <?php if($userLevel > 2) { ?>
                <!-- <div class="col-md-1"></div>-->
                <div class="col-md-4">
                <?php } ?>
                <?php if($userLevel < 3) { ?>
                <div class="col-md-3">
                <?php } ?>
                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">Clients Report</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="well" action="/report/aggSummary">
                            <fieldset>
                                <div class="form-group">
                                    <label class="simple-label">Begins at</label>
                                    <input class="form-control sdate datescurrentdate"  name="sdate" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <label class="simple-label">Ending</label>
                                    <input class="form-control edate datescurrentdate"  name="edate" type="text" value="">
                                </div>
                                <div class="form-group">
                                   <label class="simple-label">Country</label>
                                   <input class="form-control" placeholder="search country" id="countriesfilter" type="text">
                                   <select class="form-control" id="ccsid" name="ccsid[]" multiple>
                                   </select>
                                </div>
                                <div class="form-group">
                                    <label class="simple-label">Offers</label>
                                    <input class="form-control" placeholder="search offer" id="campaignsfilter" type="text">
                                    <select class="form-control" id="aggCampaignid" name="campaignsid[]" multiple>
                                        <option value="allcampaigns" selected="selected">All Offers</option>
                                       
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="simple-label"">Clients</label>
                                    <input class="form-control" placeholder="search aggregator" id="aggregatorsfilter" type="text">
                                    <select class="form-control" id="aggAggsID" name="aggsid[]" multiple>
                                        <option value="allaggs" selected="selected">All clients</option>
                                      
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="simple-label">Carriers</label>
                                    <input class="form-control" id="operatorsfilter" placeholder="search carrier" type="text">
                                    <select class="form-control" id="operatorsid" name="operatorsid[]" multiple>
                                        <option value="alloperators" selected="selected">All Carriers</option>
                                        <?php echo $operatorslist ?>
                                    </select>
                                </div>
                                <label class="simple-label">Group by:</label>
                                <div class="checkbox">
                                    <label>
                                        <input name="selectcountry" type="checkbox"> Countries
                                    </label>
                                </div>
								<div class="checkbox">
                                    <label>
                                        <input name="selectsource" type="checkbox"> SourceIDs
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="selectusercountry" type="checkbox"> UserCountry
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="selectoperator" type="checkbox"> Carrier
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="selectaggregator" type="checkbox" checked> Client
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="selectcampaign" type="checkbox" checked> Offer
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="selecturl" type="checkbox"> Offer Url
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="excel" type="checkbox"> Excel
                                    </label>
                                </div>
								<div class="checkbox" style="display: none;">
                                    <label>
                                        <input name="testing" type="checkbox" checked> Accurate Results
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="simple-label">Order by</label>
                                    <select class="form-control" id="ordermyagg" name="orderaggreport" multiple>
                                        <option value="clicks" selected="selected">Clicks</option>
                                        <option value="conversions">Conversions</option>
                                        <option value="rev">Revenue</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {% endblock %}
{% block simplescript %}
<style>
	select#lpreportcountriesid {
	    display: none;
	}
</style>
<script>
    var countries = "<?php echo $countries; ?>";
    $("#cc").append(countries);
    $("#ccOP").append(countries);
    $("#ccOP2").append(countries);
    $("#ccN").append(countries);
    $("#ccID").append(countries);
    $("#rbyhourccID").append(countries);
    $("#ccsid").append(countries);
	$("#lpreportcountriesid").append(countries);
	var current_date = <?php echo "'" . $current_date . "'" ?>;
	$(".datescurrentdate").val(current_date);


	var campaings = '<?php echo "" ?>' ;
	
	$("#aggCampaignid").append(campaings);
	//$("#campID").append(campaings);

	
	var agre = <?php echo "'" .  $aggregatorsList . "'" ?> ;
	
	$("#aggAggsID").append(agre);
	$("#aggID").append(agre);
	
	var sources = <?php echo "'" .  $srclist . "'" ?> ;
	$("#srcbyhour").append(sources);
	$("#soID").append(sources);
	$("#soid2").append(sources);
	$("#soid2").append(sources);

    $(document).ready(function () {
        $('#hour2').val(<?php echo '"'.date("H",strtotime("-1 hour")).'"'; ?>);
        
        //$('#fperiod').val(<?php echo '"'.date("Y-m-d",strtotime("-3 days")).' to '.date("Y-m-d",strtotime("-1 day")).'"'; ?>);
        //$('#speriod').val(<?php echo '"'.date("Y-m-d").'"'; ?>);
        
        $('#fperiod').daterangepicker({
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
        $('#speriod').daterangepicker({
            format: 'YYYY-MM-DD',
            separator: ' to ',
			startDate: moment()	,
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
    
        $(function () {
            $('.datetimepicker1').datetimepicker({
                    format: 'YYYY-MM-D HH:mm:ss'
                });
				
			 $('#datepicker1').datepicker({
					format: 'YYYY-MM-D HH:mm:ss'
				});
				
			 $('#datepicker2').datepicker({
					format: 'YYYY-MM-D HH:mm:ss'
				});
        });
		
		//$('#ccOP2').multipleSelect();
		$(".ms-parent.form-control").css('width', '100%');
		
		
    });
	
	
	/*$("#formmjumpreport").submit(function(e) {       
      e.preventDefault();

	  /*
	  formData = new FormData();
	  formData.append('s',  $("#startdatemjump").val());
	  formData.append('e',  $("#enddatejump").val());
	  formData.append('cc',  $('#ccOP2').multipleSelect("getSelects"));
	 

			$.ajax({
				url: '/report/mjump',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					
						var link = document.createElement('a');
						link.download = "file.xls";
						link.href = data.file;
						link.click();
				},
				error: function() {
					
				},
				cache: false,
				contentType: false,
				processData: false
			});
	  
	  
	   window.location.assign('/report/mjump?s=' + $("#startdatemjump").val() + "&e=" + $("#enddatejump").val() + "&cc=" + getSumoSelects('#ccOP2', 0));
    });
*/    
	

	
							

</script>
<style>
.SumoSelect {
    width: 100%;
}
select#aggID {
    display: none;
}
</style>
{% endblock %}  
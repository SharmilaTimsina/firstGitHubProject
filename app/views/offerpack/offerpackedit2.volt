{% extends "/headfooter.volt" %}
{% block title %}<title>Offers</title>{% endblock %}
{% block scriptimport %}    
	
	<script src="/js/offerpacknew/offeredit.js"></script>

	<script src="/js/njump/chosen.jquery.min.js"></script>

	<script type="text/javascript" src="/js/clipboard.min.js"></script>

	<script src="/js/offerpacknew/FileSaver.min.js"></script>

	<link href="/css/chosen.min.css" rel="stylesheet"/>
	
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700|Roboto:400,500,700" rel="stylesheet">

	

<style>
	/* ---------------------------------------------------------------------------------------------------------------- CSS NEW OFFERS */
	*{
		font-family: 'Roboto', sans-serif;
	}

	body{overflow-x: hidden;}

	h3{
		font-family: 'Roboto Condensed', sans-serif;
		line-height: 0;
		padding-bottom: 1%;
	}

	h5{
		font-family: 'Roboto Condensed', sans-serif;
		margin-left: 1%;
	}

	table{
		width:100%;
	}

	td{
		width:10%;
	}

	hr{
	  width: 97%
	}

	img{
		cursor: pointer;
	}

	p.titlesFilters {
	    font-size: 14px;
	    color: #fff;
	    font-family: 'Roboto Condensed', sans-serif;
	    margin-top: 2px;
	    margin-left: 5px;
	}

	.parameter-div{
		background: #d6d6d6;
		border-bottom-left-radius: 5px;
		border-bottom-right-radius: 5px;
		padding: 3%;
		}
	.parameter-div p{
		font-size: 12px;
		font-family: 'Roboto Condensed', sans-serif;
	}

	.savetop{
		padding: 5% 20%;
	    background: mediumseagreen;
	    color: white;
	    font-weight: 700;
	    border: none;
	    border-radius: 5px;
	    width: 100%;
	}

	.savebot{
		margin-top: 30%;
		padding: 5% 20%;
	    background: mediumseagreen;
	    color: white;
	    font-weight: 700;
	    border: none;
	    border-radius: 5px;
	    width: 100%;
	}

	.marginrow{
		margin: 0;
	}

	.mandatoryfield{
		width:90%;
		margin: auto;
	}

	.mandatoryfield input{
		background: #ebebeb;
		border-radius: 0px 0px 5px 5px;
		border: none;
		box-shadow: none;
		height: 25px;
	}

	.mandatoryfield textarea{
		background: #ebebeb;
		border-radius: 0px 0px 5px 5px;
		border: none;
		box-shadow: none;
		height: 88px;
	}

	.optionalfield{
		width:90%;
		margin: auto;
	}
	.optionalfield select{
	  background: transparent;
	  outline: none;
	  border: none;
	  box-shadow: none;
	}

	.optionalfield input{
	  background: #ebebeb;
		border-radius: 0px 0px 5px 5px;
		border: none;
		box-shadow: none;
		height: 25px;
	}

	.optionalbox{
	  background: #ebebeb;
	  border-radius: 0px 0px 5px 5px;
	}

	.inline-title{
		display: inline-block;
	}
	.inline-titleicon{
		display: inline-block;
		float: right;
	}
	.inline-titleicon img{
		width: 20px;
		cursor: pointer;
	}

	.titleFiltersDiv {
	    width: 100%;
	    height: 32px;
	    padding: 5px;
	    background-color: #777;
	    border-top-left-radius: 7px;
	    border-top-right-radius: 7px;

	}

	.divspace-gray{
		height: 150px;
		background: #ebebeb;
		border-bottom-left-radius: 10px;
		border-bottom-right-radius: 10px;
	}

	.divspace-gray textarea{
	  margin:3%;
	  width: 80%;
	  background: transparent;
	  border: none;
	  resize: none;
	  height: 100%;
	  outline: none;
	  font-size: 12px;
	}

	.table-trafficregulations-div{
		padding:4% 5%;
	}

	.screens-banners-title {
	    margin-top: 5px;
	    padding-bottom: 5px;
	    font-weight: 700;
	}

	.screens-banners-top-div{
		width: 508px;
		height: 85px;
		overflow: hidden;
	}

	.screens-banners-div{
		width: 100%;
		height: 100%;
		overflow-y: scroll;
		padding-right: 17px;
				border-top: 1px solid #ccc;
		border-bottom: 1px solid #ccc;
		
	}

	.screens-banners-div .row{
		margin-top:5px;
		padding-bottom: 3px;
		border-bottom: 1px solid #e0e0e0;
		font-size: 12px;
		font-weight: 700;
	}

	.screens-banners-div .row .col-md-1{
	  width: 10px;
	}

	.screens-banners-div span{
	    white-space: nowrap;
	    text-overflow: ellipsis;
	    width: 340px;
	    display: block;
	    overflow: hidden;
	}

	.screens-banners-all{

		margin-right: 1%;
		padding-bottom: 1%;
	}

	.dz{
	    margin-top: 10px;
	    padding-top: 40%;
		background: transparent;
		background-image: url(/img/njumppage/plus_empty.svg);
		background-repeat: no-repeat;
		background-position: 50% 35%;
		background-size: 20%;
		width: 100%;
		height: 130px;
		border-radius: 10px;
		border: 1px solid #999;
		font-size: 10px;
		text-align:center;
		color: #999;
		font-weight: 900;
	}

	.dz input {
			    /* position: absolute; */
			    cursor: pointer;
			    /* left: 0px; */
			    top: 0px;
			    opacity: 0;
			    /* padding: 98px 5px 75px 99px; */
			    margin-top: -184px;
			    margin-left: -2px;
			    width: 300px;
			    height: 205px;
			}

	/*Important*/
	.dz.mouse-over {
		        border: 2px dashed rgba(0,0,0,.5);
		        color: rgba(0,0,0,.5);
	}

	.deleteScreenshot { cursor: pointer;}

	.historytitle{
		margin-top:5px;
		padding-bottom: 5px;
		font-weight: 700;
		border-bottom: 1px solid #ccc;
	}

	.history-top-div{
		width:100%;
		height: 100px;
		overflow: hidden;
		border-bottom: 1px solid #ccc;
	}

	.history-div{
		width: 100%;
		height: 100%;
		overflow-y: scroll;
		padding-right: 17px;
		font-size: 10px;
		font-weight: 700;
	}

	.history-div .row{
		border-bottom: 1px solid #e0e0e0;
	}

	/* Let's get this party started */
	::-webkit-scrollbar {
	    width: 0.3vw;
	}
	 
	/* Track */
	::-webkit-scrollbar-track {
	    background: #eaeaea; 
	    -webkit-border-radius: 10px;
	    border-radius: 10px;
	}
	 
	/* Handle */
	::-webkit-scrollbar-thumb {
	    -webkit-border-radius: 10px;
	    border-radius: 10px;
	    background: #999; 
	    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
	}
	::-webkit-scrollbar-thumb:window-inactive {
	    background: #999; 
	}

	.chosen-single {
	   border-radius: 0px 0px 5px 5px;
	   border: none;
	}

	.chosen-container-single .chosen-single {
	    position: relative;
	    display: block;
	    overflow: hidden;
	    outline: none;
	    padding: 0 0 0 8px;
	    height: 25px;
	    border: none;
	    background-color: #ebebeb;
	    background-clip: padding-box;
	    box-shadow: none;
	    color: #444;
	    text-decoration: none;
	    white-space: nowrap;
	    line-height: 24px;
	}

	.chosen-container-active > .chosen-single {
	    border-bottom-left-radius: 0px;
	    border-bottom-right-radius: 0px;
	}

	#aggsSB_chosen .chosen-single{
	    border-bottom-left-radius: 0px;
	    border-bottom-right-radius: 0px;	
	}

	.chosen-container{
		display: block;
	}


	



	/* ---------------------------------------------------------------------------------------------------------------- END CSS NEW OFFERS */
</style>


{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status2">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrap">
    	<div class="col-md-12">
        	<div class="col-md-11">
        			<h3>OFFERS // <span id="typeedit">NEW</span></h3>
        	</div>
        	<div class="col-md-1">
        			<button class="savetop buttonsaveOfferPack">SAVE</button>
        	</div>
        </div>

        	<div class="col-md-12"><h5>MANDATORY:</h5></div>
        	<div class="row marginrow">

    			<div class="col-md-12">

    			<table>
    				<tbody>
    					<tr>
    						<td>
    							<div class="mandatoryfield"><div class="titleFiltersDiv"><p class="titlesFilters">COUNTRY</p></div>
					    		<select id="countrySB" class="form-control"></select></div>
    						</td>
    						<td rowspan="2">
    							<div class="mandatoryfield"><div class="titleFiltersDiv"><p class="titlesFilters">CLIENTS</p></div>
					    		<select  id="aggsSB"  class="form-control"></select>
					    		<div class="parameter-div"><p>PARAMETER // aff_sub</p><p>PUB ID PARAMETER // pub_id</p></div></div>
    						</td>
    						<td>
    							<div class="mandatoryfield"><div class="titleFiltersDiv"><p class="titlesFilters">OFFER NAME</p></div>
					    		<input id="offname" class="form-control"></div>
    						</td>
    						<td rowspan="2">
    							<div class="mandatoryfield">
    								<div class="titleFiltersDiv">
    									<div class="inline-title"><p class="titlesFilters">CLIENT URL</p></div>
    									<div class="inline-titleicon"><img id="copy-curl" src="/img/njumppage/copy.svg"></div>
    							</div>
					    		<textarea id="jumpurl" class="form-control clienturl-div" style="resize: none"></textarea></div>
    						</td>
    						<td>
    							<div class="mandatoryfield"><div class="titleFiltersDiv"><p class="titlesFilters">CURRENCY</p></div>
					    		<select id="currencySB" class="selects-all form-control"></select></div>
    						</td>
    					</tr>
    					<tr>
    						<td>
    							<div class="mandatoryfield"><div class="titleFiltersDiv"><p class="titlesFilters">CARRIER</p></div>
					    		<select id="carrierSB" class="form-control"></select></div>
    						</td>
    						<td>
    							<div class="mandatoryfield"><div class="titleFiltersDiv"><p class="titlesFilters">AREA</p></div>
					    		<select id="areaSB" class="form-control"></select></div>
    						</td>
    						<td>
    							<div class="mandatoryfield"><div class="titleFiltersDiv"><p class="titlesFilters">CPA</p></div>
					    		<input id="cpa" type="number" step="0.1" class="form-control"></div>
    						</td>
    					</tr>
    				</tbody>
    			</table>
				</div>
			</div>
			
			<hr>

			<div class="col-md-12"><h5>OPTIONAL:</h5></div>
        	<div class="row marginrow">

    			<div class="col-md-12">
    			<table>
    				<tbody>
    					<tr>
    						<td>
    							<div class="optionalfield"><div class="titleFiltersDiv"><p class="titlesFilters">STATUS</p></div>
					    		<div class="optionalbox"><select id="statusSB" class="selects-all form-control"></select></div></div>
    						</td>
    						<td>
    							<div class="optionalfield"><div class="titleFiltersDiv"><p class="titlesFilters">AM</p></div>
					    		<div class="optionalbox"><select id="accountSB" class="selects-all form-control"></select></div></div>
    						</td>
    						<td>
    							<div class="optionalfield"><div class="titleFiltersDiv"><p class="titlesFilters">CM</p></div>
					    		<div class="optionalbox"><select id="cmSB" class="selects-all form-control"></select></div></div>
    						</td>
    						<td>
    							<div class="optionalfield"><div class="titleFiltersDiv"><p class="titlesFilters">MODEL</p></div>
					    		<div class="optionalbox"><select id="modelSB" class="selects-all form-control"></select></div></div>
    						</td>
    						<td>
    							<div class="optionalfield"><div class="titleFiltersDiv"><p class="titlesFilters">VERTICAL</p></div>
					    		<div class="optionalbox"><select id="verticalSB" class="selects-all form-control"></select></div></div>
    						</td>
    						<td>
    							<div class="optionalfield"><div class="titleFiltersDiv"><p class="titlesFilters">OWNERSHIP</p></div>
					    		<div class="optionalbox"><select id="ownerSB" class="selects-all form-control"></select></div></div>
    						</td>
    						<td>
    							<div class="optionalfield"><div class="titleFiltersDiv"><p class="titlesFilters">FLOW</p></div>
					    		<div class="optionalbox"><select id="flowSB" class="selects-all form-control"></select></div></div>
    						</td>
    						<td>
    							<div class="optionalfield"><div class="titleFiltersDiv"><p class="titlesFilters">DAILY CAP</p></div>
					    		<div class="optionalbox"><input id="daylicap" type="number" class="form-control"></div></div>
    						</td>
    						<td>
    							<div class="optionalfield"><div class="titleFiltersDiv"><p class="titlesFilters">EXCLUSIVE</p></div>
					    		<div class="optionalbox"><select id="exclusiveSB" class="selects-all form-control"></select></div></div>
    						</td>
    					</tr>
    				</tbody>
    			</table>
    			</div>
    		</div>
			
			<hr>


			<div class="row marginrow" style="margin-left: 10px; margin-right: 10px">
				<div class="col-md-6">
					<div class="titleFiltersDiv">
						<p class="titlesFilters">SCREENSHOTS</p>
					</div>
					<div class="divspace-gray">
						<div class="col-md-4">
							<div id="drop-zone"  class="dz">
                                        DROP FILES HERE OR CLICK TO SELECT
                                        <input multiple="" id="screenshot" type="file" name="replyfiles" vk_1e5c4="subscribed">
                                        <div id="clickHere">
                                            
                                            
                                        </div>
                                    </div>
						</div>
						<div class="col-md-8">
							<div class="row screens-banners-title">Uploaded Screenshots</div>
							<div class="row screens-banners-all">
								<div class="col-md-1"><input id="checkallscreenshots" type="checkbox" name="all" value="all"></div>
								<div class="col-md-9">All</div>
								<div class="col-md-1"><img id="copyurlscreenshots" width="20px" src="/img/njumppage/copy.svg"></div>
								<div class="col-md-1"><img id="downloadZipScreenshots" width="20px" src="/img/njumppage/dload_circle.svg"></div>
							</div>
							<div class="col-md-12 screens-banners-top-div">
								<div class="row screens-banners-div" id="listscreenshot">
								
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="titleFiltersDiv">
						<p class="titlesFilters">BANNERS</p>
					</div>
					<div class="divspace-gray">
						<div class="col-md-4">
							<div id="drop-zone-banners" class="dz">
                                        DROP FILES HERE OR CLICK TO SELECT
                                        <input multiple="" id="banners" type="file" name="replyfiles" vk_1e5c4="subscribed">
                                        <div id="clickHere2">
                                            
                                            
                                        </div>
                                    </div>
						</div>
						<div class="col-md-8">
							<div class="row screens-banners-title">Uploaded Banners</div>
							<div class="row screens-banners-all">
								<div class="col-md-1"><input id="checkallbanners" type="checkbox" name="all" value="all"></div>
								<div class="col-md-9">All</div>
								<div class="col-md-1"><img id="copyurlbanners" width="20px" src="/img/njumppage/copy.svg"></div>
								<div class="col-md-1"><img id="downloadZipBanners" width="20px" src="/img/njumppage/dload_circle.svg"></div>
							</div>
							<div class="col-md-12 screens-banners-top-div">
								<div class="row screens-banners-div" id="listbanners">
								
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<hr>

			<div class="row marginrow" style="margin-left: 10px; margin-right: 10px">
				<div class="col-md-6">
					<div class="titleFiltersDiv">
						<p class="titlesFilters">ACCEPTED TRAFFIC AND REGULATIONS</p>
					</div>
					<div class="divspace-gray">
						<div class="table-trafficregulations-div">
						<table >
							<tbody class="tbodyregulations">
								
							</tbody>
						</table>
						</div>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="titleFiltersDiv">
						<div class="inline-title"><p class="titlesFilters">PAYOUT HISTORY</p></div>
						<div class="inline-titleicon"><img id="cpadownloadhistory" src="/img/njumppage/dload_circle.svg"></div>
					</div>
					<div class="divspace-gray">
						<div class="col-md-12">
							<div class="historytitle">History</div>
							
							<div class="history-top-div">
								<div class="history-div" id="cpahistorydiv">
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="titleFiltersDiv">
						<div class="inline-title"><p class="titlesFilters">STATUS HISTORY</p></div>
						<div class="inline-titleicon"><img id="statusdownloadhistory" src="/img/njumppage/dload_circle.svg"></div>
					</div>
						<div class="divspace-gray">
							<div class="col-md-12">
								<div class="historytitle">History</div>
							
							<div class="history-top-div">
								<div class="history-div" id="statushistorydiv">
								
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="titleFiltersDiv">
						<p class="titlesFilters">COMMENTS</p>
					</div>
					<div class="divspace-gray">
						<textarea id="description" maxlength="250" placeholder="Type your comment here. (Max: 250)"></textarea>
					</div>
				</div>
			</div>


		<div class="col-md-12">
        	<div class="col-md-11">
        	</div>
        	<div class="col-md-1">
        			<button class="savebot buttonsaveOfferPack">SAVE</button>
        	</div>
        </div>



        <div class="container">
        	
			
	<script>
		var jsonVar = <?php echo (isset($offervar)) ? $offervar : "'';"; ?>		
				
	</script>
	<style>
	.form-group.formgroupinvest2 {
	    margin-left: 158px;
	    width: 400px;
	}
	.form-group.formgroupinvest3 {
	    margin-left: 158px;
	    width: 100px;
	}
	.form-group.formgroupinvest4 {
	    width: 100px;
	}
	textarea#description {
	    width: 220px;
	    height: 130px;
	    resize: none;
	}
	input[type="checkbox"] {
	    margin: 5px;
	}
	.downloadBanners {
		cursor: pointer;
		margin-left: 15px;
	}
	</style>

	<style>	


		/*If you dont want the button*/
		#clickHere {
		    position: absolute;
		    cursor: pointer;
		    left: 50%;
		    top: 50%;
		    margin-left: -50px;
		    margin-top: 20px;
		    line-height: 26px;
		    color: white;
		    font-size: 12px;
		    width: 100px;
		    height: 26px;
		    border-radius: 4px;
		    background-color: #3b85c3;
		    display: none;

		}

	    #clickHere:hover {
	        background-color: #4499DD;

	    }
	    div#filelist {
		    border: 1px solid blue;
		    padding: 5px;
		    margin-top: 12px;
		    width: 200px;
		    margin-left: 54px;
		}
		.filename {
		    cursor: pointer;
		}
		.filename:hover {
		    cursor: pointer;
		    color: red;
		}

		.info {
		    color: blue;
		    font-weight: 700;
		}



		#drop-zone2 {
		    width: 300px;
		    height: 200px;
		    position: absolute;
		    /* left: 50%; */
		    /* top: 100px; */
		    /* margin-left: -150px; */
		    border: 2px dashed rgba(0,0,0,.3);
		    border-radius: 20px;
		    font-family: Arial;
		    text-align: center;
		    position: relative;
		    line-height: 180px;
		    font-size: 20px;
		    color: rgba(0,0,0,.3);
		}

		     #drop-zone2 input {
			    /* position: absolute; */
			    cursor: pointer;
			    /* left: 0px; */
			    top: 0px;
			    opacity: 0;
			    /* padding: 98px 5px 75px 99px; */
			    margin-top: -184px;
			    margin-left: -2px;
			    width: 300px;
			    height: 205px;
			}

		    /*Important*/
		    #drop-zone2.mouse-over {
		        border: 2px dashed rgba(0,0,0,.5);
		        color: rgba(0,0,0,.5);
		    }


		/*If you dont want the button*/
		#clickHere2 {
		    position: absolute;
		    cursor: pointer;
		    left: 50%;
		    top: 50%;
		    margin-left: -50px;
		    margin-top: 20px;
		    line-height: 26px;
		    color: white;
		    font-size: 12px;
		    width: 100px;
		    height: 26px;
		    border-radius: 4px;
		    background-color: #3b85c3;
		    display: none;

		}
	    #clickHere2:hover {
	        background-color: #4499DD;

	    }
	    div#filelist2 {
		    border: 1px solid blue;
		    padding: 5px;
		    margin-top: 12px;
		    width: 200px;
		    margin-left: 54px;
		}

	</style>
	


	
{% endblock %}
{% block simplescript %}
<script>
var downloadScreenshots = '<?php echo $downloadScreenshots;?>';
var downloadBanners = '<?php echo $downloadBanners;?>';
var eachscreenshot = '<?php echo $eachscreenshot;?>';
var eachbanner = '<?php echo $eachbanner;?>';
</script>
{% endblock %}
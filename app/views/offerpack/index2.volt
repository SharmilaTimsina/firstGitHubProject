{% extends "/headfooter.volt" %}
{% block title %}<title>Offers</title>{% endblock %}
{% block scriptimport %}    
	
	
	<script src="https://cdn.datatables.net/v/bs/dt-1.10.13/datatables.min.js"></script>

	
	
	<script type="text/javascript" src="/js/vendor/bootstrap-filestyle.min.js"></script> 
	
	<script type="text/javascript" src="/js/clipboard.min.js"></script> 

	<link href="/css/chosen.min.css" rel="stylesheet"/>

	<script src="/js/offerpacknew/index2.js?v2=4"></script>

	<script src="/js/offerspacks/jquery.qtip.min.js"></script>

	<link href="/js/offerspacks/jquery.qtip.min.css" rel="stylesheet"/>

	<script src="/js/njump/notify.js"></script>

	<script src="/js/njump/chosen.jquery.min.js"></script>

	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700|Roboto:400,500,700" rel="stylesheet">

	<script src="/js/offerpacknew/FileSaver.min.js"></script>

	

	
	<style>
	/* ---------------------------------------------------------------------------------------------------------------- CSS NEW OFFERS */
	*{
		font-family: 'Roboto', sans-serif;
	}

	h3{
		font-family: 'Roboto Condensed', sans-serif;
		font-weight: 700;
	}

	.divider{
		border-right: 1px solid #95989A;
	}

	.divider div{
		float: right;
		width: 50%;
	}

	.newof_btn{
		width: 54px;
	    margin-right: -10px;
	    cursor: pointer;
	    padding-right: 10px;

	}

	.newof_label {
	    font-family: 'Roboto', sans-serif;
	    font-size: 18px;
		font-weight:700;
		margin-top: 2%;
		margin-left: 6%;

	}

	.default{
		color: red;
	}

	.firstcol{
		margin-left: -15px;
	}

	.tabcol{
		margin: 0px;
	}

	.filterrow{
		margin-right: -25px;
	}


	.search_bar{
		border-radius: 5px;
		background-color: #ebebeb;
		margin-top: 1%;
    	padding: 0px 20px;
	}

	.search_bar input{
		background: transparent;
		border: none;
		outline: none;
	}

	ul.chosen-choices {
	    border-bottom-left-radius: 5px;
	    border-bottom-right-radius: 5px;
	    
	}

	.chosen-container-active > .chosen-choices {
	    border-bottom-left-radius: 0px;
	    border-bottom-right-radius: 0px;
	}

	.filter{
	    padding: 4px 0px; 
	    background-color: #222;
	    border: none;
	    border-radius: 0.5vw;
	    color: white;
	    width: 100%;
	    
	}

	.clearfilter{
		margin-top: 7px;
	    padding: 4px 0px; 
	    background-color: #ff0000;
	    border: none;
	    border-radius: 0.5vw;
	    color: white;
	    width: 100%;


	}

	.titleFiltersDiv {
	    width: 100%;
	    height: 32px;
	    padding: 5px;
	    background-color: #777;
	    border-top-left-radius: 7px;
	    border-top-right-radius: 7px;

	}

	.chosen-container-multi .chosen-choices {
	    position: relative;
	    overflow: hidden;
	    margin: 0;
	    padding: 0 5px;
	    width: 100%;
	    height: auto;
	    border: 1px solid #ebebeb;
	    background-color: #ebebeb;
	    color: #222;
	    color: #000;
	    background-image: none;
	    background-image: none;
	    background-image: -moz-linear-gradient(#eee 1%,#fff 15%);
	    background-image: -o-linear-gradient(#eee 1%,#fff 15%);
	    background-image: red;
	    cursor: text;
	}

	.chosen-container-multi .chosen-choices li.search-choice {
	    position: relative;
	    margin: 3px 5px 3px 0;
	    padding: 3px 20px 3px 5px;
	    border: 1px solid #aaa;
	    max-width: 100%;
	    border-radius: 3px;
	    background-color: #eee;
	    background-image: -webkit-gradient(linear,50% 0,50% 100%,color-stop(20%,#f4f4f4),color-stop(50%,#f0f0f0),color-stop(52%,#e8e8e8),color-stop(100%,#eee));
	    background-image: -webkit-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
	    background-image: -moz-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
	    background-image: -o-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
	    background-image: linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
	    background-size: 100% 19px;
	    background-repeat: repeat-x;
	    background-clip: padding-box;
	    box-shadow: 0 0 2px #fff inset, 0 1px 0 rgba(0,0,0,.05);
	    color: #333;
	    line-height: 13px;
	    cursor: default;
	}

	.chosen-container-multi .chosen-choices li.search-choice {
	    position: relative;
	    margin: 3px 5px 3px 0;
	    padding: 3px 20px 3px 5px;
	    border: 0px;
	    font-size: 15px;
	    max-width: 100%;
	    border-radius: 3px;
	    background-color: white;
	    /* background-image: -webkit-gradient(linear,50% 0,50% 100%,color-stop(20%,#f4f4f4),color-stop(50%,#f0f0f0),color-stop(52%,#e8e8e8),color-stop(100%,#eee)); */
	    /* background-image: -webkit-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%); */
	    background-image: -moz-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
	    background-image: -o-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
	    background-image: none;
	    /* background-size: 100% 19px; */
	    /* background-repeat: repeat-x; */
	    /* background-clip: padding-box; */
	    box-shadow: none;
	    /* color: #333; */
	    line-height: 13px;
	    /* cursor: default; */
	}

	.chosen-container-multi .chosen-choices li.search-field input[type=text]{
		color: #000;
	}

	.colclient, .colvertical{
		  width: 13.94%;
		}

		.colfilter{
		 width: 13.78%;
		}

	p.titlesFilters {
    font-size: 18px;
    color: #fff;
    font-family: 'Roboto Condensed', sans-serif;
    margin-top: 0px;
    margin-left: 5px;
	}

	.datatab{
		border-radius: 5px;
		-moz-border-radius:5px;
        -webkit-border-radius:5px;
        background-color: #777;
		

	}

	thead th{
		font-family: 'Roboto Condensed', sans-serif;
		color: #fff;
		font-size: 13px;
		/*text-align: center;*/ 
	}

	tbody tr{
		border-bottom: 1px solid #ccc;
		padding: 0px;
	}


	tbody td{
		border-right: 1px solid #ccc;
		text-align: center;
		font-size: 13px;
		padding: 0px 3px;
		text-align: center; 
	}

	.even{
		background-color: #d6d6d6;
		border-bottom: 1px solid #ccc;
		padding: 0px;
	}


	.td_countrystyle, .td_verticalstyle{
		width: 80px
		}
	.td_advstyle{
		width: 100px
		}
	.td_modelstyle, .td_paystyle{
		width: 75px
		}

	.td_curstyle{
	  width:70px;
	}

	.td_capstyle{
		width: 4%;
	}

	.td_optstyle{
	  border-right: none;
	}

	.td_jumpstyle{
		text-align: left;
	}

	.cpaEditable {
		margin:auto;
		background-color: #fff;
		width:80%;
		border: 1px solid #ccc;
		border-radius: 5px;
	}

	.dataTables_filter input{
		border-radius: 5px;
		background-color: #ebebeb;
		margin-top: 1%;
    	padding: 0px 20px;
    	border: none;
	}

	.modal-dialog{
		width: 400px;
	}

	.modal-header{
		border: none;
	}

	.modal-icons{
		width: 30px;
		cursor: pointer;
	}

	.imagModal{
		border-radius: 5px;
	}

	.selectBoxTable{
		font-size: 13px;
	}

	@media (max-width: 2900px) {

	}

	@media (max-width: 2300px) {

	}

	@media (max-width: 1900px) {
		p.titlesFilters {
		    font-size: 17px;
		    color: white;
		    font-family: 'Roboto Condensed', sans-serif;
		    margin-top: 3px;
		    margin-left: 5px;
		}

		.newof_btn{
			width: 54px;
			margin-left: -60px;
		    margin-right: -10px;
		    cursor: pointer;
		    padding-right: 10px;

		}

		.chosen-container-multi .chosen-choices li.search-choice {
		    margin: 3px 5px 3px 0;
		    padding: 3px 20px 3px 5px;
		    font-size: 10px; 
		    border-radius: 3px;
		    line-height: 13px;
		}

		.chosen-container-multi .chosen-choices li.search-field input[type=text]{
			color: #000;
			font-size: 12px;
		}

		.colclient, .colvertical{
		  width: 13.94%;
		}

		.colfilter{
		 width: 13.78%;
		}

		.filter{
		    padding: 5px 8px; 
		    background-color: #222;
		    border: none;
		    border-radius: 0.5vw;
		    width: 100%;
		    font-size: 12px;
		}

		.clearfilter{
		    padding: 5px 8px; 
		    background-color: #ff0000;
		    border: none;
		    border-radius: 0.5vw;
		    width: 100%;
		    font-size: 12px;
		}

		thead th{
			color: #fff;
			font-size: 11px;
		}

		tbody td{

			border-right: 1px solid #ccc;
			font-size: 11px;
			padding: 0px 3px;
		}

		.selectBoxTable{
			font-size: 10px;
		}
	}

	@media (max-width: 1440px) {
		p.titlesFilters {
		    font-size: 15px;
		    color: white;
		    margin-top: 3px;
		    margin-left: 5px;
		}

		.newof_btn{
			width: 54px;
			margin-left: -60px;
		    margin-right: -10px;
		    padding-right: 10px;

		}

		.chosen-container-multi .chosen-choices li.search-choice {
		    margin: 3px 5px 3px 0;
		    padding: 3px 20px 3px 5px;
		    font-size: 10px; 
		    max-width: 100%;
		    border-radius: 3px;
		    line-height: 13px;
		}

		.chosen-container-multi .chosen-choices li.search-field input[type=text]{
			color: #000;
			font-size: 12px;
		}

		.colclient, .colvertical{
		  width: 13.94%;
		}

		.colfilter{
		 width: 13.78%;
		}

		.filter{
		    padding: 5px 8px; 
		    background-color: #222;
		    border: none;
		    border-radius: 0.5vw;
		    color: white;
		    width: 100%;
		    font-size: 12px;
		}

		.clearfilter{
		    padding: 5px 8px; 
		    border-radius: 0.5vw;
		    font-size: 12px;
		}

		thead th{
			font-size: 11px;
		}

		tbody td{
			font-size: 9px;
			padding: 0px 3px; 
		}

		.selectBoxTable{
			font-size: 9px;
		}

		.td_datastyle{
			width: 4%;
		}
	}

	@media (max-width: 1300px) {
		p.titlesFilters {
		    font-size: 10px;
		    color: white;
		    margin-top: 3px;
		    margin-left: 3px;
		}

		.newof_btn{
			width: 40px;
			margin-left: -60px;
		    margin-right: -10px;
		    padding-right: 10px;

		}

		.newof_label {
		    font-size: 13px;
			margin-top: 5%;
		}

		.chosen-container-multi .chosen-choices li.search-choice {
		    margin: 3px 5px 3px 0;
		    padding: 3px 20px 3px 5px;
		    font-size: 10px; 
		    max-width: 100%;
		    border-radius: 3px;
		    background-image: -moz-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
		    background-image: -o-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
		    line-height: 13px;
		}

		.chosen-container-multi .chosen-choices li.search-field input[type=text]{
			color: #000;
			font-size: 12px;
		}

		.colclient, .colvertical{
		  width: 13.94%;
		}

		.colfilter{
		 width: 13.78%;
		}

		.filter{
		    padding: 5px 8px; 
		    border-radius: 0.5vw;
		    width: 100%;
		    font-size: 12px;
		}

		.clearfilter{
		    padding: 5px 8px; 
		    border-radius: 0.5vw;
		    font-size: 12px;
		}

		thead th{
			font-size: 7px;
		}

		tbody td{
			font-size: 6px;
			padding: 0px 3px; 
		}

		.selectBoxTable{
			font-size: 9px;
		}
	}



	



	/* ---------------------------------------------------------------------------------------------------------------- END CSS NEW OFFERS */

	</style>


	


{% endblock %}
{% block preloader %}
    <div id="preloader2">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrap">

   		

		

    	<div class="container containerf" style="margin-bottom: 200px;">
    		<div class="row" style="margin: 20px 0px 50px 0px">

	    		<div class="col-md-6">
	    			<h3>OFFERS //</h3>
	    		</div>
	    		<div class="col-md-2 divider">
	    			<div><a style="text-decoration: none;color: black;" href="/offerpack/offerpackedit2"><img class="newof_btn" src="/img/njumppage/plus.svg">
	    			<span class="newof_label">New Offer</span></a></div>
	    		</div>
	    		<div class="col-md-2 divider">
	    			<div style="width:70%"><a style="text-decoration: none;color: black;cursor:pointer" id="downloadwxcel"><img class="newof_btn" src="/img/njumppage/d_excel.svg">
	    			<span class="newof_label">Download Results</span></a></div>
	    		</div>
	    		<div class="col-md-2">
	    			<div class="search_bar">
	    				<input id="searchInput" type="text" name="search" placeholder="Search..." style="width:100%">
	    			</div>
	    		</div>

    		</div>
    		<!--<div class="row" >
    			<div class="col-md-11">
    				<div class="col-md-3 firstcol">
						<div class="col-md-6">
					    	<div class="titleFiltersDiv"><p class="titlesFilters">COUNTRY</p></div>
					    	<select id="countrySB" multiple name="geoFilter[]" typee="1" class="form-control">
								
							</select>
						</div>
						<div class="col-md-6">
							<div class="titleFiltersDiv"><p class="titlesFilters">CARRIER</p></div>
							<select id="carrierSB" multiple name="" typee="1" class="form-control selects-all">
						
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="col-md-7">
					    	<div class="titleFiltersDiv"><p class="titlesFilters">CLIENT</p></div>
					    	<select id="aggsSB" multiple name="geoFilter[]" typee="1" class="form-control selects-all">
								
							</select>
						</div>
						<div class="col-md-5">
							<div class="titleFiltersDiv"><p class="titlesFilters">AREA</p></div>
							<select id="areaSB" multiple name="" typee="1" class="form-control selects-all">
						
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="col-md-6">
					    	<div class="titleFiltersDiv"><p class="titlesFilters">VERTICAL</p></div>
					    	<select id="verticalSB" multiple name="geoFilter[]" typee="1" class="form-control selects-all">
								
							</select>
						</div>
						<div class="col-md-6">
							<div class="titleFiltersDiv"><p class="titlesFilters">MODEL</p></div>
							<select id="modelSB" multiple name="" typee="1" class="form-control selects-all">
						
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="col-md-6">
					    	<div class="titleFiltersDiv"><p class="titlesFilters">STATUS</p></div>
					    	<select id="statusSB" multiple name="geoFilter[]" typee="1" class="form-control selects-all">
								
							</select>
						</div>
						<div class="col-md-6">
							<div class="titleFiltersDiv"><p class="titlesFilters">ACCOUNT</p></div>
							<select id="accountSB" multiple name="" typee="1" class="form-control selects-all">
						
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-1">
					<div class="col-md-12">
						<button class="filter" id="buttonFilter">Filter</button>
					</div>
					<div class="col-md-12">
						<button class="clearfilter" style="width:100%" id="buttonResetFilter">Clear Filters</button>
					</div>
				</div>
			</div>-->
			<div class="row filterrow">
    			<div class="col-md-12">
    				
						<div class="col-md-1">
					    	<div class="titleFiltersDiv"><p class="titlesFilters">COUNTRY</p></div>
					    	<select id="countrySB" multiple name="geoFilter[]" typee="1" class="form-control">
								
							</select>
						</div>
						<div class="col-md-1">
							<div class="titleFiltersDiv"><p class="titlesFilters">CARRIER</p></div>
							<select id="carrierSB" multiple name="" typee="1" class="form-control selects-all">
						
							</select>
						</div>
					
					
						<div class="col-md-2 colclient">
					    	<div class="titleFiltersDiv"><p class="titlesFilters">CLIENT</p></div>
					    	<select id="aggsSB" multiple name="geoFilter[]" typee="1" class="form-control selects-all">
								
							</select>
						</div>
						<div class="col-md-1">
							<div class="titleFiltersDiv"><p class="titlesFilters">AREA</p></div>
							<select id="areaSB" multiple name="" typee="1" class="form-control selects-all">
						
							</select>
						</div>
					
					
						<div class="col-md-2 colvertical">
					    	<div class="titleFiltersDiv"><p class="titlesFilters">VERTICAL</p></div>
					    	<select id="verticalSB" multiple name="geoFilter[]" typee="1" class="form-control selects-all">
								
							</select>
						</div>
						<div class="col-md-1">
							<div class="titleFiltersDiv"><p class="titlesFilters">MODEL</p></div>
							<select id="modelSB" multiple name="" typee="1" class="form-control selects-all">
						
							</select>
						</div>
					
					
						<div class="col-md-1">
					    	<div class="titleFiltersDiv"><p class="titlesFilters">STATUS</p></div>
					    	<select id="statusSB" multiple name="geoFilter[]" typee="1" class="form-control selects-all">
								
							</select>
						</div>
						<div class="col-md-1">
							<div class="titleFiltersDiv"><p class="titlesFilters">ACCOUNT</p></div>
							<select id="accountSB" multiple name="" typee="1" class="form-control selects-all">
						
							</select>
						</div>
						<div class="col-md-1">
							<div class="titleFiltersDiv"><p class="titlesFilters">EXCLUSIVE</p></div>
							<select id="exclusiveSB" multiple name="" typee="1" class="form-control selects-all">
						
							</select>
						</div>
					
				
				<div class="col-md-1 colfilter" >
					<div class="col-md-12">
						<button class="filter" id="buttonFilter">Filter</button>
					</div>
					<div class="col-md-12">
						<button class="clearfilter" id="buttonResetFilter">Clear Filters</button>
					</div>
				</div>
				</div>
			</div>
			
			<hr>

			<div class="row  tabcol">
				<div class="col-md-12">
					<table width="100%" class="datatab">
						<thead >
							<tr>
								<th><input id="checkall" type="checkbox" name="all" value="all"></th>
								<th><div>DATE</div></th>	
								<th><div>COUNTRY</div></th>	
								<th><div>CARRIER</div></th>	
								<th><div>ADVERTISER</div></th>	
								<th><div>OFFER NAME</div></th>	
								<th><div>AREA</div></th>	
								<th><div>VERTICAL</div></th>	
								<th><div>JUMP</div></th>	
								<th><div>MODEL</div></th>	
								<th><div>PAYOUT</div></th>	
								<th><div>CUR</div></th>	
								<th><div>D. CAP</div></th>
								<th><div>AM</div></th>	
								<th><div>CM</div></th>		
								<th><div>STATUS</div></th>
								<th><div>OPTIONS</div></th>
							</tr>
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
	      	<img id="copytoscreenshotimage" class="modal-icons" src="/img/njumppage/copy.svg">
	      	<a id="downloadZipScreenshot" target="_blank" href=""><img class="modal-icons" src="/img/njumppage/dload_circle.svg"></a>
	      	<a id="downloadZipBanner" target="_blank" href=""><img id="bannersdownload" class="modal-icons" src="/img/njumppage/banners.svg"></a>
	        <button type="button" class="close" data-dismiss="modal"><img class="modal-icons" src="/img/njumppage/close_red.svg"></button>
	      </div>
	      <div id="modalBodyScreen" class="modal-body">
	      	<img id="screenShotMo" class="imagModal" src=""></img>
	      	<p id="labelShotMo"></p>
	      </div>
	      <!--<div class="modal-footer">
	       
	      </div>-->
	    </div>

	  </div>
	</div>

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
			margin-left: 5%;
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
		
		p.CaptionCont.SelectBox.search {
		    height: 31px;
		}
		input#searchInput {
		    height: 35px;
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
			border-top-left: 5px;
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
		    width: 20px;
		    
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
		
		.containerf {
		    width: 100%;
		}
		tbody#tableBodyPackOffers tr {
		    height: 30px;
		}
		
		.iconCopylinkjump {
		    cursor: pointer;
		    float: right;
		    margin-top: -4px;
		    margin-right: 5px;
		    /* padding: 0px; */
		    width: 22px;
		}
		div#DataTables_Table_0_filter {
		    float: right;
		}
		.even {
		    background-color: #d6d6d6;
		    border-bottom: 1px solid #ccc;
		    padding: 0px;
		}
		.odd {
		    background-color: #ebebeb;
		}

		.form-control {
		    height: 22px;
		    padding-top: 1px;
		    padding-bottom: 4px;
		}

		table.datatab thead .sorting { background: url('/img/iconssort/sort_both.png') no-repeat center right; }
        table.datatab thead .sorting_asc { background: url('/img/iconssort/sort_asc.png') no-repeat center right; }
        table.datatab thead .sorting_desc { background: url('/img/iconssort/sort_desc.png') no-repeat center right; }

        table.datatab thead .sorting {
                background-size: 16px;
    			/*background-position-y: 4px;*/
        }

        table.datatab thead .sorting_asc {
                background-size: 16px;
   				/*background-position-y: 4px;*/
        }

        table.datatab thead .sorting_desc {
                background-size: 16px;
    			/*background-position-y: 4px;*/
        }
	</style>

	<script>
		var jsonVar = <?php echo (isset($offervar)) ? $offervar : "'';"; ?>		
	</script>
	
	
	
{% endblock %}
{% block simplescript %}
{% endblock %}
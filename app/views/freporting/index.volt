{% extends "/headfooter.volt" %}
{% block title %}<title>Financial Reporting</title>{% endblock %}
{% block scriptimport %}    
	
	
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>

	<script src="/js/dashboard/chart.min.js"></script>	
	
	<script src="/js/freporting/main.js"></script>
	<script src="/js/FileSaver.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="/css/freporting.css" />
		
	<script type="text/javascript" src="/js/vendor/bootstrap-filestyle.min.js"></script> 
	
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>
	
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	
	<script type="text/javascript" src="/js/jquery.sumoselect.min.js"></script>
	<link href="/css/sumoselect.css" rel="stylesheet"/>

	<script src="/js/datepickerjst/moment.min.js"></script>
	<script src="/js/datepickerjst/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/datepickerjst/daterangepicker.css" />
	
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
			$navtype = $this->session->get('auth')['navtype'];
			if($navtype == 5 || $navtype == 1)
				echo '<div class="row">';
			else 
				echo '<div class="row" style="display: none;">';
			?>
				<div class="col-md-12"> 
					<table>
					<tr style="display: none;">
						<form id="formuploadcsv" role="form" enctype="multipart/form-data">
							<td>
								<div class="form-group formgroupinvest">
									<label for="file">File without clients IDs:</label>
									<input name="files[]" type="file" accept=".csv">
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
									<label for="source">Clients:</label>
									<select  id="aggclient" name="name_will_become_class agregator " placeholder="" class="search-box form-control">
										<?php echo $combo; ?>
									</select>
								</div>
							</td>
							<td>
								<button id="buttonUploadCsv" type="button" name="submit" class="btn btn-success buttonsInvest">Upload</button>
							</td>
						</form>
					</tr>

					<tr>
						<form id="formuploadcsvMultipleAgregator" role="form" enctype="multipart/form-data">
							<td>
								<div class="form-group formgroupinvest">
									<label for="file">File with clients IDs:</label>
									<input name="files[]" type="file" accept=".csv">
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest">
									<label  for="tdate">Date:</label>
									<input type="text" class="form-control" id="tdateMulti" name="tdate">
								</div>
							</td>
							<td>
								<button id="buttonUploadCsvMultiAgg" type="button" name="submit" class="btn btn-success buttonsInvest">Upload</button>
							</td>
						</form>
					</tr>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12"> 
					<h4 style="margin-top: 40px;text-decoration: underline;">REPORT's</h4>
					<table>
					<tr>
						<td>
							<div class="form-group formgroupinvestFilter">
								<label for="dateF">Date:</label>
								<input class="form-control selectFilter" title="Date" id="convPeriod2" type="text" name="convP2" >
							</div>
						</td>
						<td>
							<div style="margin-left: 50px;" class="form-group formgroupinvestFilter">
								<label for="selectboxMulti">Countries:</label>
								
								<select id="selectCountries" name="name_will_become_class" multiple="multiple"  class="search-box-sel-all">
									<option value="af">Afghanistan</option><option value="ax">Åland Islands</option><option value="al">Albania</option><option value="dz">Algeria</option><option value="as">American Samoa</option><option value="ad">Andorra</option><option value="ao">Angola</option><option value="ai">Anguilla</option><option value="aq">Antarctica</option><option value="ag">Antigua and Barbuda</option><option value="ar">Argentina</option><option value="am">Armenia</option><option value="aw">Aruba</option><option value="au">Australia</option><option value="at">Austria</option><option value="az">Azerbaijan</option><option value="bs">Bahamas</option><option value="bh">Bahrain</option><option value="bd">Bangladesh</option><option value="bb">Barbados</option><option value="by">Belarus</option><option value="be">Belgium</option><option value="bz">Belize</option><option value="bj">Benin</option><option value="bm">Bermuda</option><option value="bt">Bhutan</option><option value="bo">Bolivia</option><option value="ba">Bosnia and Herzegovina</option><option value="bw">Botswana</option><option value="bv">Bouvet Island</option><option value="br">Brazil</option><option value="io">British Indian Ocean Territory</option><option value="bn">Brunei Darussalam</option><option value="bg">Bulgaria</option><option value="bf">Burkina Faso</option><option value="bi">Burundi</option><option value="kh">Cambodia</option><option value="cm">Cameroon</option><option value="ca">Canada</option><option value="cv">Cape Verde</option><option value="ky">Cayman Islands</option><option value="cf">Central African Republic</option><option value="td">Chad</option><option value="cl">Chile</option><option value="cn">China</option><option value="cx">Christmas Island</option><option value="cc">Cocos (Keeling) Islands</option><option value="co">Colombia</option><option value="km">Comoros</option><option value="cg">Congo</option><option value="cd">Congo, The Democratic Republic of The</option><option value="ck">Cook Islands</option><option value="cr">Costa Rica</option><option value="ci">Cote D"ivoire</option><option value="hr">Croatia</option><option value="cu">Cuba</option><option value="cy">Cyprus</option><option value="cz">Czech Republic</option><option value="dk">Denmark</option><option value="dj">Djibouti</option><option value="dm">Dominica</option><option value="do">Dominican Republic</option><option value="ec">Ecuador</option><option value="eg">Egypt</option><option value="sv">El Salvador</option><option value="gq">Equatorial Guinea</option><option value="er">Eritrea</option><option value="ee">Estonia</option><option value="et">Ethiopia</option><option value="fk">Falkland Islands (Malvinas)</option><option value="fo">Faroe Islands</option><option value="fj">Fiji</option><option value="fi">Finland</option><option value="fr">France</option><option value="gf">French Guiana</option><option value="pf">French Polynesia</option><option value="tf">French Southern Territories</option><option value="ga">Gabon</option><option value="gm">Gambia</option><option value="ge">Georgia</option><option value="de">Germany</option><option value="gh">Ghana</option><option value="gi">Gibraltar</option><option value="gr">Greece</option><option value="gl">Greenland</option><option value="gd">Grenada</option><option value="gp">Guadeloupe</option><option value="gu">Guam</option><option value="gt">Guatemala</option><option value="gg">Guernsey</option><option value="gn">Guinea</option><option value="gw">Guinea-bissau</option><option value="gy">Guyana</option><option value="ht">Haiti</option><option value="hm">Heard Island and Mcdonald Islands</option><option value="va">Holy See (Vatican City State)</option><option value="hn">Honduras</option><option value="hk">Hong Kong</option><option value="hu">Hungary</option><option value="is">Iceland</option><option value="in">India</option><option value="id">Indonesia</option><option value="ir">Iran, Islamic Republic of</option><option value="iq">Iraq</option><option value="ie">Ireland</option><option value="im">Isle of Man</option><option value="il">Israel</option><option value="it">Italy</option><option value="jm">Jamaica</option><option value="jp">Japan</option><option value="je">Jersey</option><option value="jo">Jordan</option><option value="kz">Kazakhstan</option><option value="ke">Kenya</option><option value="ki">Kiribati</option><option value="kp">Korea, Democratic People\"s Republic of</option><option value="kr">Korea, Republic of</option><option value="kw">Kuwait</option><option value="kg">Kyrgyzstan</option><option value="la">Lao People"s Democratic Republic</option><option value="lv">Latvia</option><option value="lb">Lebanon</option><option value="ls">Lesotho</option><option value="lr">Liberia</option><option value="ly">Libyan Arab Jamahiriya</option><option value="li">Liechtenstein</option><option value="lt">Lithuania</option><option value="lu">Luxembourg</option><option value="mo">Macao</option><option value="mk">Macedonia, The Former Yugoslav Republic of</option><option value="mg">Madagascar</option><option value="mw">Malawi</option><option value="my">Malaysia</option><option value="mv">Maldives</option><option value="ml">Mali</option><option value="mt">Malta</option><option value="mh">Marshall Islands</option><option value="mq">Martinique</option><option value="mr">Mauritania</option><option value="mu">Mauritius</option><option value="yt">Mayotte</option><option value="mx">Mexico</option><option value="fm">Micronesia, Federated States of</option><option value="md">Moldova, Republic of</option><option value="mc">Monaco</option><option value="mn">Mongolia</option><option value="me">Montenegro</option><option value="ms">Montserrat</option><option value="ma">Morocco</option><option value="mz">Mozambique</option><option value="mm">Myanmar</option><option value="na">Namibia</option><option value="nr">Nauru</option><option value="np">Nepal</option><option value="nl">Netherlands</option><option value="an">Netherlands Antilles</option><option value="nc">New Caledonia</option><option value="nz">New Zealand</option><option value="ni">Nicaragua</option><option value="ne">Niger</option><option value="ng">Nigeria</option><option value="nu">Niue</option><option value="nf">Norfolk Island</option><option value="mp">Northern Mariana Islands</option><option value="no">Norway</option><option value="om">Oman</option><option value="pk">Pakistan</option><option value="pw">Palau</option><option value="ps">Palestinian Territory, Occupied</option><option value="pa">Panama</option><option value="pg">Papua New Guinea</option><option value="py">Paraguay</option><option value="pe">Peru</option><option value="ph">Philippines</option><option value="pn">Pitcairn</option><option value="pl">Poland</option><option value="pt">Portugal</option><option value="pr">Puerto Rico</option><option value="qa">Qatar</option><option value="re">Reunion</option><option value="ro">Romania</option><option value="ru">Russian Federation</option><option value="rw">Rwanda</option><option value="sh">Saint Helena</option><option value="kn">Saint Kitts and Nevis</option><option value="lc">Saint Lucia</option><option value="pm">Saint Pierre and Miquelon</option><option value="vc">Saint Vincent and The Grenadines</option><option value="ws">Samoa</option><option value="sm">San Marino</option><option value="st">Sao Tome and Principe</option><option value="sa">Saudi Arabia</option><option value="sn">Senegal</option><option value="rs">Serbia</option><option value="sc">Seychelles</option><option value="sl">Sierra Leone</option><option value="sg">Singapore</option><option value="sk">Slovakia</option><option value="si">Slovenia</option><option value="sb">Solomon Islands</option><option value="so">Somalia</option><option value="za">South Africa</option><option value="gs">South Georgia and The South Sandwich Islands</option><option value="es">Spain</option><option value="lk">Sri Lanka</option><option value="sd">Sudan</option><option value="sr">Suriname</option><option value="sj">Svalbard and Jan Mayen</option><option value="sz">Swaziland</option><option value="se">Sweden</option><option value="ch">Switzerland</option><option value="sy">Syrian Arab Republic</option><option value="tw">Taiwan, Province of China</option><option value="tj">Tajikistan</option><option value="tz">Tanzania, United Republic of</option><option value="th">Thailand</option><option value="tl">Timor-leste</option><option value="tg">Togo</option><option value="tk">Tokelau</option><option value="to">Tonga</option><option value="tt">Trinidad and Tobago</option><option value="tn">Tunisia</option><option value="tr">Turkey</option><option value="tm">Turkmenistan</option><option value="tc">Turks and Caicos Islands</option><option value="tv">Tuvalu</option><option value="ug">Uganda</option><option value="ua">Ukraine</option><option value="ae">United Arab Emirates</option><option value="gb">United Kingdom</option><option value="us">United States</option><option value="um">United States Minor Outlying Islands</option><option value="uy">Uruguay</option><option value="uz">Uzbekistan</option><option value="vu">Vanuatu</option><option value="ve">Venezuela</option><option value="vn">Viet Nam</option><option value="vg">Virgin Islands, British</option><option value="vi">Virgin Islands, U.S.</option><option value="wf">Wallis and Futuna</option><option value="eh">Western Sahara</option><option value="WW">World Wide</option><option value="ye">Yemen</option><option value="zm">Zambia</option><option value="zw">Zimbabwe</option>
								</select>
								<script>
									/*
									$('#selectboxMulti').multipleSelect({
										width: '200px'
									});
									*/
									 
								</script>
								<style>
									.ms-parent.selectFilter {
										display: inherit;
									}
								</style>
							</div>
						</td>
						<td>
							<div style="margin-left: 50px;" class="form-group formgroupinvestFilter">
								<label for="selectboxMulti">Accounts:</label>
								<select id="selectAccount" multiple="multiple" name="name_will_become_class" multiple="multiple" placeholder=""  class="search-box-sel-all">
									<?php echo $accountsCombo; ?>
								</select>
							
							</div>
						</td>
						<td>
							<div style="margin-left: 50px;" class="form-group formgroupinvestFilter">
								<label for="selectboxMulti">Agregators:</label>
								<select id="selectAgreg" name="name_will_become_class" multiple="multiple" placeholder=""  class="search-box-sel-all">
									<?php echo $aggregatorsCombo; ?>
								</select>
							
							</div>
						</td>
					</tr>
					<tr>
						<table id="tableCheckBoxes">
							<form id="formCheckBoxes">
							<tr>
								<td>
									 <input checked class="checkboxFilter disableIfAffs" type="checkbox" name="Mobistein Conversions" value="1">Mobistein Conversions
								</td>
								<td>
									 <input class="checkboxFilter disableIfAffs" type="checkbox" name="Client Conversions" value="2">Client Conversions
								</td>
									<td>
									 <input class="checkboxFilter disableIfAffs" type="checkbox" name="Dif. Conversions" value="3">Dif. Conversions
								</td>
								<td>
									  <input checked class="checkboxFilter disableIfAffs" type="checkbox" name="Dif. Conversions %" value="4">Dif. Conversions %
								</td>
								<td>
									 <input class="checkboxFilter disableIfAffs" type="checkbox" name="Duplicated" value="5">Duplicated
								</td>
								<td>
									  <input checked class="checkboxFilter disableIfAffs" type="checkbox" name="Invoiced" value="12">Invoiced
								</td>
							</tr>
							<tr>
								<td>
									 <input checked class="checkboxFilter disableIfAffs" type="checkbox" name="Mobistein Revenue" value="7">Mobistein Revenue
								</td>
								<td>
									 <input class="checkboxFilter disableIfAffs" type="checkbox" name="Client Revenue" value="8">Client Revenue
								</td>
								<td>
									 <input class="checkboxFilter disableIfAffs" type="checkbox" name="Dif. Revenue" value="9">Dif. Revenue
								</td>
								<td>
									  <input checked class="checkboxFilter disableIfAffs" type="checkbox" name="Dif. Revenue %" value="10">Dif. Revenue %
								</td>
								<td>
									 <input class="checkboxFilter disableIfAffs" type="checkbox" name="Dif. duplicated %" value="6">Dif. duplicated %
								</td>
								<td>
									  <input class="checkboxFilter disableIfAffs" type="checkbox" name="Total amount Invoiced" value="11">Total amount Invoiced
								</td>
							</tr>
							</form>
							<tr >
									<td style="background-color: #ddd;">
										 <input class="disableIfAffs" type="radio" name="typeOfInfo" value="1"> Biweekly<br>
										 <input class="disableIfAffs" checked type="radio" name="typeOfInfo" value="2"> Monthly<br>
									</td>
									
									<td style="background-color: #aaa9dc;">
										 <input class="disableIfAffs" id="checkAllCheckBox" type="checkbox" name="" value="">Check All
									</td>
									<td style="background-color: #a9dcb2;">
										 <input class="disableIfAffs" id="chartJsAtivate" type="checkbox" name="" value="">Chart
									</td>
									<td style="background-color: #aae5ff;">
										 <input class="disableIfAffs" id="agregatebycountry" type="checkbox" name="" value="">Aggregate by country
									</td>
									<?php
										$navtype = $this->session->get('auth')['navtype'];
										if($navtype == 5 || $navtype == 1)
											echo '<td style="background-color: #f8cc33; width: 216px;">
													<input id="affiliatesCheck" type="checkbox" name="" value="">Affiliates<br>
													<input id="groupbyaffCheck" class="groupsbyAffiliates" type="checkbox" value="1"> Aggregate by Client<br>
													<input id="groupbymonthCheck" class="groupsbyAffiliates" type="checkbox" value="2"> Aggregate by Month<br>
													<input id="groupbyaffiliateCheck" class="groupsbyAffiliates" type="checkbox" value="2"> Aggregate by Affiliate
												</td>';
									?>
							</tr>
							<tr>
								<td>
									<button id="buttonSetFilter" type="button" name="submit" class="btn btn-info buttonsInvest">RUN REPORT</button>
								</td>
								<td>
									<button id="buttonDownloadExcel" type="button" name="submit" class="btn btn-warning buttonsInvest">DOWNLOAD REPORT</button>
								</td>
							</tr>
						</table>
					</tr>
					</table>
				</div>
			</div>
			<div id="rowChart" class="row">
				<div class="col-md-12"> 
					<div class="row">
						<div id="canvasdiv">
							<canvas width='1091' height='491' id="chartDashboard"></canvas>
						</div>
					  </div>
					  <div class="row">
						<p class="circle" id="circleClicks" onclick="runChart(0, false)"></p>
						<p class="graphicsLabel" id="clickGraph" onclick="runChart(0, false)">Total Amount Invoiced</p>
						<p class="circle" id="circleConversions" onclick="runChart(1, false)"></p>
						<p class="graphicsLabel" id="conversionsGraph" onclick="runChart(1, false)">Def. Conversions %</p>
					  </div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12"> 
					<table width="100%" class="table-striped table-bordered" id="tableHeadReport">
						
					</table>
				</div>
			</div>
		</div>
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
				<textarea id="textareaDetails"></textarea>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button id="editDetails" type="button" class="btn btn-default" data-dismiss="modal">Edit</button>
		  </div>
		</div>

	  </div>
	</div>
	
	<script>
		var d = new Date();
	
		var currDate = d.getDate();
		var currMonth = d.getMonth() + 1;
		var currYear = d.getFullYear();

		if (currMonth < 10) { currMonth = '0' + currMonth; }
		if (currDate < 10) { currDate = '0' + currDate; }
		
		var strDate = currDate + "-" + currMonth + "-" + currYear;
		
		var finalDate =  currYear + "-" + currMonth + "-01" + " - " + currYear + "-" + currMonth + "-" + currDate;
		

		/*
		$('input[name="convP2"]').val(finalDate);
	
		$('input[name="convP2"]').daterangepicker({
			format: 'YYYY-MM-DD',
			"opens": "right",
			setDate: strDate
		});
		*/

		$('input[name="convP2"]').daterangepicker({
            format: 'YYYY-MM-DD',
            separator: ' - ',
			startDate: moment().startOf('month'),
			endDate: moment().endOf('month').subtract(15, 'day'),
			alwaysShowCalendars: true,
			ranges: {
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				'First 15 days': [moment().startOf('month'), moment().endOf('month').subtract(15, 'day')]
			}
        });
		
		$('#aggclient option[value="ALL"]').remove();
		
		window.searchSelAll = $('.search-box-sel-all').SumoSelect({ csvDispCount: 3, selectAll:true, search: true, searchText:'Enter here.', okCancelInMulti:false });
		window.searchSelAll = $('.search-box').SumoSelect({ csvDispCount: 3, search: true, searchText:'Enter here.' });
		$('select.search-box-sel-all')[0].sumo.selectAll();
		$('select.search-box-sel-all')[1].sumo.selectAll();
		$('select.search-box-sel-all')[2].sumo.selectAll();
	</script>

	
{% endblock %}
{% block simplescript %}
{% endblock %}
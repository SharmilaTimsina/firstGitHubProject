
    {% extends "/headfooter.volt" %}
	{% block scriptimport %}    
	
        

        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/font-awesome.css">
        <link rel="stylesheet" href="/css/custom-sky-forms.css">
        
        <link rel="stylesheet" href="/css/main.css">

        <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <!--[if lt IE 9]>
            <link rel="stylesheet" href="o/css/sky-forms-ie8.css">
        <![endif]-->
    {% endblock %}
{% block title %}<title>Njumps</title>{% endblock %}
{% block content %}    
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
     

<!-- Modal -->
<div class="modal fade" id="nnamem" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    Name Alteration
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                
                <form id="nnamef" class="form-horizontal" role="form">
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="nname">New Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" 
                        id="nname" name="nname" placeholder="New Name"/>
                    </div>
                  </div>
                </form>
                
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
                <button id="nnameb" type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>



<!--//////////////////////////////////////////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="clonem" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    Clone
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                
                <form id="clonef" class="form-horizontal" role="form">
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="clonen">Clone Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" 
                        id="clonen" name="clonen" placeholder="Clone Name"/>
                    </div>
                  </div>
                </form>
                
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
                <button id="cloneb" type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>


<!--//////////////////////////////////////////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="delm" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    Clone
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                
                <form id="delf" class="form-horizontal" role="form">
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="clonen">Warning:</label>
                   <label  class="col-sm-10 control-label"
                              for="clonen"> Are you sure you want to delete this njump? </label>
                  </div>
                </form>
                
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
                <button id="delb" type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>




<!--//////////////////////////////////////////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="nnjm" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    New Njump
                </h4>
                
                                <!--/////ERRRORRRRRWARNING/////-->
                    <div id="ew" class="alert alert-danger" role="alert" style="display:none;margin-top:10px">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>
                       
                    </div>


            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                
                <form id="nnjf" class="form-horizontal" role="form">
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="ngroup">Group Name</label>
                     
    
                    <div class="col-sm-10">
                        <input type="text" class="form-control" 
                        id="ngroup" name="ngroup" placeholder="Group Name"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="nlurl">Url</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" 
                        id="nlurl" name="nlurl" placeholder="Jump or Mjump Link"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"
                          for="nlname" >Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                            id="nlname" name="nlname" placeholder="Page Name"/>
                    </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-2 control-label"
                          for="nlop" >Operator</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                            id="nlop" name="nlop" placeholder="Operator"/>
                    </div>
                  </div>
                    <div class="form-group">
                     <label class="col-sm-2 control-label"
                          for="bhour" >Starting Period</label>
                    <div class="col-sm-10">
                        <input type="time" class="form-control"
                            id="bhour" name="bhour" placeholder="Begins at"/>
                    </div>
                  </div>
                    <div class="form-group">
                     <label class="col-sm-2 control-label"
                          for="ehour" >Ending Period</label>
                    <div class="col-sm-10">
                        <input type="time" class="form-control"
                            id="ehour" name="ehour" placeholder="Ends at"/>
                    </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-2 control-label"
                          for="dev" >Device</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                            id="dev" name="dev" placeholder="Device"/>
                    </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-2 control-label"
                          for="nlisp" >Isp</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                            id="nlisp" name="nlisp" placeholder="Isp"/>
                    </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-2 control-label"
                          for="nlper" >Percentage</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                            id="nlper" name="nlper" placeholder="Percentage"/>
                    </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-2 control-label" for="nlsback">Special</label>
                            <select class="form-control" id="nlsback" name="nlsback">
                                    <option value="0">Default</option>
                                    <option value="1">Rotation</option>
                                    <option value="2">Back</option>
                                    <option value="3">B-R</option>
                            </select>
                  </div>
                </form>           
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
                <button id="nnjs" type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>




<!--END MODAL -->






    <!-- Preloader -->

    <div id="wrap">
        <div class="container">
            <div class="row">
             <div class="col-md-14"> 
                <div class="col-md-3" style="padding-top:12px">
                 
                   
                   <input id="textbox" type="text" />
                   <select class="form-control" id="gcombo" size="25"  style="width:173px;overflow: auto;"> 
                    <?php echo $group_list ?>
                   </select> 
                   
                 </div>
                 
                 <div class="col-md-8">
                     <div class="panel-heading">
                        <h3 id="ntitle" class="panel-title well">Njump</h3>
                        <h3>
                            <button id="nnj" type="button" class="btn btn-success"  data-toggle="modal" data-target="#nnjm">New Njump</button>
                            <button id="newn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#nnamem">Alter Name</button>
                            <button id="newc" type="button" class="btn btn-warning" data-toggle="modal" data-target="#clonem">Clone</button>
                            <button id="ndel" type="button" class="btn btn-danger"  data-toggle="modal" data-target="#delm">Delete</button>
							<select id="epc">
																		 <option value="<?php echo date("Y-m-d") ?>">Select period</option>
																		 <option value="<?php echo date("Y-m-d") ?>">Today</option>
																		 <option value="<?php echo date("Y-m-d", strtotime('-1 days')) ?>">Last 2 days</option>
																		 <option value="<?php echo date("Y-m-d", strtotime('-7 days')) ?>">Last 7 days</option>
                                                                         <option value="<?php echo date("Y-m-d", strtotime('-15 days')) ?>">Last 15 days</option>
																		 <option value="<?php echo date("Y-m-d", strtotime('-30 days')) ?>">Last 30 days</option>
                                                                  </select>
							<?php 									  
							$auth = $this->session->get('auth');
							if($auth['id'] == 3 || $auth['id'] == 20) {									  
								echo '<div id="divcountries">';
							} else {
								echo '<div style="display: none">';
							}
                            ?>
							<select id="countries">
									<option value="none">None</option>
									<option value="af">Afghanistan</option>
									<option value="ax">Åland Islands</option>
									<option value="al">Albania</option>
									<option value="dz">Algeria</option>
									<option value="as">American Samoa</option>
									<option value="ad">Andorra</option>
									<option value="ao">Angola</option>
									<option value="ai">Anguilla</option>
									<option value="aq">Antarctica</option>
									<option value="ag">Antigua and Barbuda</option>
									<option value="ar">Argentina</option>
									<option value="am">Armenia</option>
									<option value="aw">Aruba</option>
									<option value="au">Australia</option>
									<option value="at">Austria</option>
									<option value="az">Azerbaijan</option>
									<option value="bs">Bahamas</option>
									<option value="bh">Bahrain</option>
									<option value="bd">Bangladesh</option>
									<option value="bb">Barbados</option>
									<option value="by">Belarus</option>
									<option value="be">Belgium</option>
									<option value="bz">Belize</option>
									<option value="bj">Benin</option>
									<option value="bm">Bermuda</option>
									<option value="bt">Bhutan</option>
									<option value="bo">Bolivia</option>
									<option value="ba">Bosnia and Herzegovina</option>
									<option value="bw">Botswana</option>
									<option value="bv">Bouvet Island</option>
									<option value="br">Brazil</option>
									<option value="io">British Indian Ocean Territory</option>
									<option value="bn">Brunei Darussalam</option>
									<option value="bg">Bulgaria</option>
									<option value="bf">Burkina Faso</option>
									<option value="bi">Burundi</option>
									<option value="kh">Cambodia</option>
									<option value="cm">Cameroon</option>
									<option value="ca">Canada</option>
									<option value="cv">Cape Verde</option>
									<option value="ky">Cayman Islands</option>
									<option value="cf">Central African Republic</option>
									<option value="td">Chad</option>
									<option value="cl">Chile</option>
									<option value="cn">China</option>
									<option value="cx">Christmas Island</option>
									<option value="cc">Cocos (Keeling) Islands</option>
									<option value="co">Colombia</option>
									<option value="km">Comoros</option>
									<option value="cg">Congo</option>
									<option value="cd">Congo, The Democratic Republic of The</option>
									<option value="ck">Cook Islands</option>
									<option value="cr">Costa Rica</option>
									<option value="ci">Cote D'ivoire</option>
									<option value="hr">Croatia</option>
									<option value="cu">Cuba</option>
									<option value="cy">Cyprus</option>
									<option value="cz">Czech Republic</option>
									<option value="dk">Denmark</option>
									<option value="dj">Djibouti</option>
									<option value="dm">Dominica</option>
									<option value="do">Dominican Republic</option>
									<option value="ec">Ecuador</option>
									<option value="eg">Egypt</option>
									<option value="sv">El Salvador</option>
									<option value="gq">Equatorial Guinea</option>
									<option value="er">Eritrea</option>
									<option value="ee">Estonia</option>
									<option value="et">Ethiopia</option>
									<option value="fk">Falkland Islands (Malvinas)</option>
									<option value="fo">Faroe Islands</option>
									<option value="fj">Fiji</option>
									<option value="fi">Finland</option>
									<option value="fr">France</option>
									<option value="gf">French Guiana</option>
									<option value="pf">French Polynesia</option>
									<option value="tf">French Southern Territories</option>
									<option value="ga">Gabon</option>
									<option value="gm">Gambia</option>
									<option value="ge">Georgia</option>
									<option value="de">Germany</option>
									<option value="gh">Ghana</option>
									<option value="gi">Gibraltar</option>
									<option value="gr">Greece</option>
									<option value="gl">Greenland</option>
									<option value="gd">Grenada</option>
									<option value="gp">Guadeloupe</option>
									<option value="gu">Guam</option>
									<option value="gt">Guatemala</option>
									<option value="gg">Guernsey</option>
									<option value="gn">Guinea</option>
									<option value="gw">Guinea-bissau</option>
									<option value="gy">Guyana</option>
									<option value="ht">Haiti</option>
									<option value="hm">Heard Island and Mcdonald Islands</option>
									<option value="va">Holy See (Vatican City State)</option>
									<option value="hn">Honduras</option>
									<option value="hk">Hong Kong</option>
									<option value="hu">Hungary</option>
									<option value="is">Iceland</option>
									<option value="in">India</option>
									<option value="id">Indonesia</option>
									<option value="ir">Iran, Islamic Republic of</option>
									<option value="iq">Iraq</option>
									<option value="ie">Ireland</option>
									<option value="im">Isle of Man</option>
									<option value="il">Israel</option>
									<option value="it">Italy</option>
									<option value="jm">Jamaica</option>
									<option value="jp">Japan</option>
									<option value="je">Jersey</option>
									<option value="jo">Jordan</option>
									<option value="kz">Kazakhstan</option>
									<option value="ke">Kenya</option>
									<option value="ki">Kiribati</option>
									<option value="kp">Korea, Democratic People's Republic of</option>
									<option value="kr">Korea, Republic of</option>
									<option value="kw">Kuwait</option>
									<option value="kg">Kyrgyzstan</option>
									<option value="la">Lao People's Democratic Republic</option>
									<option value="lv">Latvia</option>
									<option value="lb">Lebanon</option>
									<option value="ls">Lesotho</option>
									<option value="lr">Liberia</option>
									<option value="ly">Libyan Arab Jamahiriya</option>
									<option value="li">Liechtenstein</option>
									<option value="lt">Lithuania</option>
									<option value="lu">Luxembourg</option>
									<option value="mo">Macao</option>
									<option value="mk">Macedonia, The Former Yugoslav Republic of</option>
									<option value="mg">Madagascar</option>
									<option value="mw">Malawi</option>
									<option value="my">Malaysia</option>
									<option value="mv">Maldives</option>
									<option value="ml">Mali</option>
									<option value="mt">Malta</option>
									<option value="mh">Marshall Islands</option>
									<option value="mq">Martinique</option>
									<option value="mr">Mauritania</option>
									<option value="mu">Mauritius</option>
									<option value="yt">Mayotte</option>
									<option value="mx">Mexico</option>
									<option value="fm">Micronesia, Federated States of</option>
									<option value="md">Moldova, Republic of</option>
									<option value="mc">Monaco</option>
									<option value="mn">Mongolia</option>
									<option value="me">Montenegro</option>
									<option value="ms">Montserrat</option>
									<option value="ma">Morocco</option>
									<option value="mz">Mozambique</option>
									<option value="mm">Myanmar</option>
									<option value="na">Namibia</option>
									<option value="nr">Nauru</option>
									<option value="np">Nepal</option>
									<option value="nl">Netherlands</option>
									<option value="an">Netherlands Antilles</option>
									<option value="nc">New Caledonia</option>
									<option value="nz">New Zealand</option>
									<option value="ni">Nicaragua</option>
									<option value="ne">Niger</option>
									<option value="ng">Nigeria</option>
									<option value="nu">Niue</option>
									<option value="nf">Norfolk Island</option>
									<option value="mp">Northern Mariana Islands</option>
									<option value="no">Norway</option>
									<option value="om">Oman</option>
									<option value="pk">Pakistan</option>
									<option value="pw">Palau</option>
									<option value="ps">Palestinian Territory, Occupied</option>
									<option value="pa">Panama</option>
									<option value="pg">Papua New Guinea</option>
									<option value="py">Paraguay</option>
									<option value="pe">Peru</option>
									<option value="ph">Philippines</option>
									<option value="pn">Pitcairn</option>
									<option value="pl">Poland</option>
									<option value="pt">Portugal</option>
									<option value="pr">Puerto Rico</option>
									<option value="qa">Qatar</option>
									<option value="re">Reunion</option>
									<option value="ro">Romania</option>
									<option value="ru">Russian Federation</option>
									<option value="rw">Rwanda</option>
									<option value="sh">Saint Helena</option>
									<option value="kn">Saint Kitts and Nevis</option>
									<option value="lc">Saint Lucia</option>
									<option value="pm">Saint Pierre and Miquelon</option>
									<option value="vc">Saint Vincent and The Grenadines</option>
									<option value="ws">Samoa</option>
									<option value="sm">San Marino</option>
									<option value="st">Sao Tome and Principe</option>
									<option value="sa">Saudi Arabia</option>
									<option value="sn">Senegal</option>
									<option value="rs">Serbia</option>
									<option value="sc">Seychelles</option>
									<option value="sl">Sierra Leone</option>
									<option value="sg">Singapore</option>
									<option value="sk">Slovakia</option>
									<option value="si">Slovenia</option>
									<option value="sb">Solomon Islands</option>
									<option value="so">Somalia</option>
									<option value="za">South Africa</option>
									<option value="gs">South Georgia and The South Sandwich Islands</option>
									<option value="es">Spain</option>
									<option value="lk">Sri Lanka</option>
									<option value="sd">Sudan</option>
									<option value="sr">Suriname</option>
									<option value="sj">Svalbard and Jan Mayen</option>
									<option value="sz">Swaziland</option>
									<option value="se">Sweden</option>
									<option value="ch">Switzerland</option>
									<option value="sy">Syrian Arab Republic</option>
									<option value="tw">Taiwan, Province of China</option>
									<option value="tj">Tajikistan</option>
									<option value="tz">Tanzania, United Republic of</option>
									<option value="th">Thailand</option>
									<option value="tl">Timor-leste</option>
									<option value="tg">Togo</option>
									<option value="tk">Tokelau</option>
									<option value="to">Tonga</option>
									<option value="tt">Trinidad and Tobago</option>
									<option value="tn">Tunisia</option>
									<option value="tr">Turkey</option>
									<option value="tm">Turkmenistan</option>
									<option value="tc">Turks and Caicos Islands</option>
									<option value="tv">Tuvalu</option>
									<option value="ug">Uganda</option>
									<option value="ua">Ukraine</option>
									<option value="ae">United Arab Emirates</option>
									<option value="gb">United Kingdom</option>
									<option value="us">United States</option>
									<option value="um">United States Minor Outlying Islands</option>
									<option value="uy">Uruguay</option>
									<option value="uz">Uzbekistan</option>
									<option value="vu">Vanuatu</option>
									<option value="ve">Venezuela</option>
									<option value="vn">Viet Nam</option>
									<option value="vg">Virgin Islands, British</option>
									<option value="vi">Virgin Islands, U.S.</option>
									<option value="wf">Wallis and Futuna</option>
									<option value="eh">Western Sahara</option>
									<option value="ye">Yemen</option>
									<option value="zm">Zambia</option>
									<option value="zw">Zimbabwe</option>
								</select>
								<button id="setCountry" type="button" class="btn btn-success">SET</button>
								<button id="unsetCountry" type="button" class="btn btn-warning">UNSET</button>
<style>
div#divcountries {
	margin-top: 20px;
}
button#setCountry {
	margin-bottom: 4px;
}
button#unsetCountry {
    margin-bottom: 4px;
}
</style>
<script>
	$("body").on("click", "#gcombo", function() {
		if($(this).find(":selected").attr('linkref') == '1') { 
			var country = $(this).find(":selected").attr('ccountry');
		
			$("#countries").val();
			$('#countries').val(country);
		
		} else {
		
			$("#countries").val();
			$('#countries').val('none');
		}
	 });

	 $("body").on("click", "#setCountry", function() {
		var country = $('#countries').find(":selected").val();
		var hashMask = $('#gcombo').find(":selected").val();
		
		if(country != undefined && country != 'none' && hashMask != undefined) {
			
			var values = {};
			values['country'] = country;
			values['hashMask'] = hashMask;
			
			$.ajax({
				url: '/slink/set_country',
				type: 'POST',
				data: values,
				crossDomain: true,
				dataType: 'text',
				success: function(data){
					alert("Save complete");
				},
				error: function(){
					alert("Please try again.");
				}
			});
			
			
			
		}
	});

	$("body").on("click", "#unsetCountry", function() {
		var hashMask = $('#gcombo').find(":selected").val();
		
		if(hashMask != undefined) {
			
			var values = {};
			values['hashMask'] = hashMask;
			
			$.ajax({
				url: '/slink/unset_country',
				type: 'POST',
				data: values,
				crossDomain: true,
				dataType: 'text',
				success: function(data){
					alert("Save complete");
				},
				error: function(){
					alert("Please try again.");
				}
			});
			
			
			
		}
	});

	$(document).ready(function() {
		$(".dropdown-toggle").dropdown();
	});
</script>
							</div>
                        </h3>
                        </div>
                      
                        <div id="mtable" class="panel-body">
                            &nbsp;
                            
                    </div>    
                 </div>
                 
<!--                <div class="col-md-3 col-md-offset-1">
                    exform
                </div>-->
            </div>
        </div>
    </div>
     
    </div>
    <!-- // <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
    <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.0.min.js"><\/script>')</script>
    <script src="/js/vendor/bootstrap.min.js"></script>
    <script src="/plugin/sky-forms/js/jquery-ui.min.js"></script>
        
    <script src="/js/slink/main.js"></script>
    <script type="text/javascript">        
        </script>
        <!--[if lt IE 10]>
            <script src="/plugin/sky-forms/js/jquery.placeholder.min.js"></script>
        <![endif]-->        
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <script src="/plugin/sky-forms/js/sky-forms-ie8.js"></script>
        <![endif]-->
    {% endblock %}
{% block preloader %}
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
{% endblock %}
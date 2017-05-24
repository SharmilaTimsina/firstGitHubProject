{% extends "/headfooter.volt" %}
{% block title %}<title>Operations</title>{% endblock %}
{% block scriptimport %}    
<script src="/js/operation/main.js?version=1"></script>
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
            <div id="completed"></div>
            <?php if(isset($createJump)) echo $createJump; else echo ''; ?>
            <div class="col-md-12"> 
                <div class="col-md-4">

                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">Clients and conversions</h3>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="form-group well permissions">
                                <label class="simple-label">New Client</label><br>
                                <label class="simple-label" style="font-size: 12px; font-weight: 200">Client</label>
                                <input class="form-control form-group" id="newAgg" >
                                <label class="simple-label" style="font-size: 12px; font-weight: 200">Tracking</label>
                                <input class="form-control form-group" id="newTracking" >


                                <input checked type="radio" name="companyname" value="0"> 0<br>
                                <input type="radio" name="companyname" value="1"> 1<br>
								<input type="radio" name="companyname" value="2"> 2<br>

                                <div style="margin-top: 20px;" class="form-group">
                                    <button class="btn btn-primary" id="newaggbutton">Create</button>
                                </div>
                            </div>
                        </fieldset>
                        <div class="permissions form-group well">
                    <label class="simple-label">Check latest conversion</label><br>
                    <label class="simple-label" style="font-size: 12px; font-weight: 200">Insert Offer Name</label>
                    <input class="form-control form-group" id="convcampaignname" >
                    <div id="conversionresult" ></div>
                    <br>
                    <div class="form-group">
                        <button class="btn btn-primary" id="conversionbutton">Find</button>
                    </div>
                </div>
                    </div>
                </div>

                <div class="col-md-4 permissions">
                  <div class="panel-heading">
                    <h3 class="panel-title simple-title">Sources</h3>
                </div>
                <div class="panel-body">

                <div class="form-group well">
                    <label class="simple-label">New Source</label>
                    <input class="form-control form-group" id="newSrc" >
                    <div id="sourcetype">
                        <input checked type="radio" name="source" value="0"> Adult<br>
                        <input type="radio" name="source" value="2"> Mainstream<br>
                    </div>
                    <div id="copysource" style="margin-bottom: 30px;">
                        <input id="investment" type="checkbox" name="sourceC" value="0"> Investment<br>
                        <input id="bulks" type="checkbox" name="sourceC" value="2"> Bulks<br>
                        Copy from:
                        <select id="copyfromthis">
                            <?php echo $selectboxsourcestocopy; ?>
                        </select>
                    </div>
                    <script>
                          $("#copysource").hide();
                          $('input[type=radio][name=source]').change(function() {
                            if (this.value == '0') {
                                $("#copysource").hide();
                            }
                            else if (this.value == '2') {
                                $("#copysource").show();
                            }
                        });
                    </script>
                    <div class="form-group">
                        <button class="btn btn-primary" id="newsrcbutton">Create</button>
                    </div>
                    <div id="copysource2" style="margin-bottom: 30px;">
                        <label class="simple-label">Copy source config</label><br>
                        <input id="investment2" type="checkbox" name="sourceC" value="0"> Investment<br>
                        <input id="bulks2" type="checkbox" name="sourceC" value="2"> Bulks<br>
                        Copy from:<br>
                        <select id="copyfromthis2">
                            <?php echo $selectboxsourcestocopy; ?>
                        </select><br>
                        to:<br>
                        <select id="copytothis">
                            <?php echo $selectboxsourcestocopy2; ?>
                        </select><br><br>
                        <button class="btn btn-primary" id="copysrcbutton">COPY</button>
                    </div>
                </div> 
				<?php if($userLevel < 3 || $userid == 7 || $userid == 8 || $userid == 25 || $userid == 39 ) { ?>
                <div class="form-group well">
                    <label class="simple-label">Sources</label>
                    <input class="form-control" id="sourcefilter" type="text">
                    <select class="form-control" id="sourcesList" size="7">
                        <?php echo $sourcesList ?>
                    </select>
                </div>
				<?php }?>
				<?php if($userLevel < 2 || $userid == 21) { ?>
				<div class="form-group well">
					<form action="/operation/mainstreamsourcenewname" method="GET">
					<label class="simple-label">Mainstream Sources</label>
                    <select class="form-control" name="sourceid" id="msources" >
                        <?php echo $msources ?>
                    </select>
					<br>
					<input type="text" id="newname" name="newname" value="">
					<br>
					<br>
					<input type="submit" value="Change name">
					<br>
					
					</form>

                </div>
				<?php }?>
        </div>
							<!--
                            <div class="panel-heading">
                                <h3 class="panel-title simple-title">New Jump</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" class="well" action="/operation/jump" method="POST">
                                    <fieldset>

                                        <div class="form-group">
                                            <label class="simple-label">Client</label>
                                            <input class="form-control" id="aggfilter" type="text">
                                            <select class="form-control" id="aggNames" name="agg" required="required" size="7">
                                                <?php echo $aggregatorsList ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">Connector</label>
                                            <p id="aggconnector"> -- </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">Parameter</label>
                                            <p id="aggparameter"> -- </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">Select country</label>
                                            <select class="form-control" id="countryCode" name="country" required="required">
                                                <option value="ww">World Wide</option>
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
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">New Offer name</label>
                                            <input class="form-control" id="campaignN" name="campaignName" required="required">
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">Offer URL </label>
                                            <input class="form-control" id="campaignURLs" name="campaignURL" required="required">
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">CPA</label>
                                            <input class = "form-control" name="campaigncpa" type="number" id="campaigncpa" step="0.01" min="0.00">
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">Currency</label>
                                            <select name="campaigncurrency" required="required" style="position: absolute; right: 25%;">
                                                <option value="USD">USD</option>
                                                <option value="EUR">EUR</option>
                                                <option value="GBP">GBP</option>
                                                <option value="BRL">BRL</option>
												<option value="MXN">MXN</option>
                                            </select>
                                        </div> 
										<div class="form-group">
                                            <input type="checkbox" name="mainstreamtype" id ="newcampaignmainstream" value="1">
											<label class="simple-label">Mainstream Offer</label>
                                        </div>
										<div class="form-group" style="display:none" id="newJumpCategory">
                                            <select class="form-control" id="countryCode" name="newjumpcategory" required="required">
												<?php echo $categoriesList; ?>
											</select>
                                        </div>
										<div class="form-group" style="display:none" id="newVuclipCampaign">
                                            <label class="simple-label">Vuclips Offer Name</label>
                                            <input class="form-control" id="vuclipcname" name="vuclipcname" >
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">Affiliate Carrier</label>
                                            <input class="form-control" id="affiliateC" name="affiliate" >
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Create Jump</button>
                                        </div>
                                    </fieldset>
                                </form>
								<div class="form-group well">
									<label class="simple-label">Categories</label>
									<input class="form-control form-group" id="newCategory" placeholder="New Category" >
									<div class="form-group">
										<button class="btn btn-primary" id="newcatbutton">Create</button>
									</div>
									<select class="form-control" id="categoriesList" size="7">
										<?php echo $categoriesList; ?>
									</select>
								</div>
                            </div> -->
                        </div>
                        <div class="col-md-4 permissions">


							
                            <div class="panel-heading">
                                <h3 class="panel-title simple-title">Link testing</h3>
                            </div>
                            <div class="panel-body">

                                <div class="permissions form-group well">
                    <label class="simple-label">Link Test</label><br>
                    <label class="simple-label" style="font-size: 12px; font-weight: 200">Client</label>     
                    <select class="form-control" id="tagr">
                     <option value="0">New Client</option>
                     <?php echo $aggregatorsList ?>
                 </select>
                 <label class="simple-label" style="font-size: 12px; font-weight: 200">Url</label>
                 <input class="form-control form-group" id="turl" >
                 <label class="simple-label" id="paramhide" style="font-size: 12px;font-weight: 200">Parameter</label>
                 <input class="form-control form-group"  id="tparam" >
                 <textarea id="linktresult" rows="8" class="form-control"></textarea>
                 <br>
                 <div class="form-group">
                    <button class="btn btn-primary" id="tlinkbutton">Test</button>
                </div>
            </div>
                                <!-- <form role="form" id="formcampaignupdater" class="well" action="/operation/updateCampaign" method="POST">
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="simple-label">Offer</label>
                                            <input class="form-control" id="campaignfilter" type="text">
                                            <select class="form-control" id="campaignupdater" name="selectedCampaign" required="required" size="7">
                                                <?php echo $campaignSelectList ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">Link</label>
                                            <input class="form-control" id="linkC" type="text" name="linkCamp">
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">Affiliate Carrier</label>
                                            <input class="form-control" id="affiliateCa" type="text" name="affiliateCarrier">
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">CPA</label>
                                            <input class = "form-control" name="campaigncpa" type="number" id="campaigncpaID" step="0.01" min="0.00">
                                            <input type="checkbox" name="updatecpa" value="1"><label class="simple-label">Update CPA</label>
                                        </div>
										<div class="form-group">
											<input id="mainstreamcheck" type="checkbox" name="mainstreamtype" value="1">
											<label class="simple-label" >Mainstream Offer</label>
										    <select style="display:none" class="form-control" id="campaignCategory" name="campaignCategory" required="required">
												<?php echo $categoriesList; ?>
											</select>
										</div>
                                        <div class="form-group">
                                            <label class="simple-label">Currency</label>
                                            <select id="cpaCurrencyID" name="cpaCurrency" required="required" style="position: absolute; right: 25%;">
                                                <option value="USD">USD</option>
                                                <option value="EUR">EUR</option>
                                                <option value="GBP">GBP</option>
                                                <option value="BRL">BRL</option>
												<option value="MXN">MXN</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Update Offer</button>
                                        </div>
                                        <div class="form-group">
                                            <textarea id="linkgenerate" rows="4" class="form-control">
                                            </textarea>
                                        </div>
                                    </fieldset>
                                </form> 
                                <div class="form-group well">
                                    <label class="simple-label">Offer by Url</label><br>
                                    <label class="simple-label" style="font-size: 12px; font-weight: 200">Client Url</label>
                                    <input class="form-control form-group" id="clienturl" >
                                    <textarea id="clienturlresult" rows="8" class="form-control"></textarea>
                                    <br>
                                    <div class="form-group">
                                        <button class="btn btn-primary" id="clienturlbutton">Find</button>
                                    </div>
                                </div>
                                -->
                            </div>
                        </div> 
                        <!-- <div class="col-md-3">

                            <div class="panel-heading">
                                <h3 class="panel-title simple-title">Update past data CPA</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" class="well" action="/operation/index">
                                    <fieldset>

                                        <div class="form-group">
                                            <label class="simple-label">Start Date</label>
                                            <input  class="form-control sdate"  name="s" type="text" value="<?php echo date('Y-m-d', strtotime('-7 day', strtotime(date('Y-m-d')))); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="simple-label">End Date</label>
                                            <input class="form-control edate"  name="e" type="text" value="<?php echo date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="simple-label">Select Offer</label>
                                            <input class="form-control" id="campaignfilter2" type="text">
                                            <select class="form-control" id="campaignNames" name="c" required="required" size="14">
                                                
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="simple-label">New CPA</label>
                                            <input class = "form-control" name="val" type="number" id="newcpavalue" step="0.01" min="0.00" >
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary ">Update CPA value</button>
                                        </div>
                                        <?php if(isset($cmdCompleted)) echo $cmdCompleted; else echo ''; ?>
                                    </fieldset>
                                </form>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
        {% endblock %}
        {% block simplescript %}
        <script>
			$( "#msources" ).change(function() {
			var a = $( "#msources option:selected" ).text().indexOf("-");
			$( "#msources option:selected" ).text();
			 $("#newname").val($( "#msources option:selected" ).text().substring(0,a));
			});
            var lvl = <?php echo $lvl; ?>;
            var nav = <?php echo $navtype; ?>;
            var uid = <?php echo $uid; ?>;
            $(document).ready(function () {

                $( "#campaignupdater option" ).clone().appendTo( "#campaignNames" );

                if(nav == 6) {
                    $(".permissions").hide();
                }

                if(nav == 1 || nav == 2 || uid == 7 || uid == 14) {
                    if(uid != 21 && uid != 14 && nav != 1) {
	                    $("#copysource2").remove();
                    }
                } else {
                    $("#copysource2").remove();                    
                    $("#copysource").remove();
                }
            });

        </script>
        {% endblock %}
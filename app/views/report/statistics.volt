{% extends "/headfooter.volt" %}
{% block scriptimport %}    
    <script src="/js/report/main2.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>

    <script src="/js/datepickerjst/moment.min.js"></script>
    <script src="/js/datepickerjst/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="/js/datepickerjst/daterangepicker.css" />

    <script type="text/javascript" src="/js/jquery.sumoselect.min.js"></script>
    <link href="/css/sumoselect.css" rel="stylesheet"/>
{% endblock %}
{% block title %}<title>General Reporting</title>{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrap">
        <div class="container">
            <form role="form" class="well" id="formdataBrowse" action="/report/statistics">
                <fieldset>
                    <div class="row">
                        <div class="panel-heading">
                            <h3 class="panel-title simple-title">General reporting</h3>
                        </div>
                        <div class="col-md-12">

                            <div class="row">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label class="beta blue">First Period</label>
                                            <input class="form-control" title="Start date" id="fperiod" type="text" name="fperiod" >
                                            <!-- <input class="form-control sdate"  title="Start date" placeholder="Start date" name="s" type="text" value='<?php echo date("Y-m-d", strtotime("-1 day"));?>'>-->
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="beta blue">Second Period</label>
                                            <input class="form-control" title="Start date" id="speriod" type="text" name="speriod" >
                                            <!-- <input class="form-control sdate"  title="Start date" placeholder="Start date" name="s" type="text" value='<?php echo date("Y-m-d", strtotime("-1 day"));?>'>-->
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="beta blue">Display results</label>
                                            <select class="form-control" id="aggFunction" name="aggFunction">    
                                                <option value="AVG" selected="selected">Averages</option>
                                                <option value="SUM">Totals</option>
                                            </select>
                                        </div>
                                        <!-- <div class="col-xs-6">
                                            <label class="beta black">To: </label>
                                            <input class="form-control sdate"  title="End date" placeholder="End date" name="e" type="text" value='<?php echo date("Y-m-d"); ?>'>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="beta blue">Time Period</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <div class="form-group col-md-2"> 
                                            <label class="beta black"></label>
                                        </div>
                                        <div class="form-group col-md-2"> 
                                            <label class="beta black"> Starting at </label>
                                        </div>
                                        <div class="form-group col-md-3">
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
                                        <div class="form-group col-md-1"> 
                                            <label class="beta black"> till </label>
                                        </div>
                                        <div class="form-group col-md-3">

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
                                                <option value="12">12:59</option>
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
                        </div>
                        <div class="row">
                            <div class="panel-body">
                                <?php if($userLevel < 3) { ?>
                                <div class="form-group col-md-4">
                                    <label class="beta blue">Source</label>
                                    <select id="so" name="src[]" multiple="multiple"  class="search-box-sel-all">
                                        <option value="allsrc">All sources</option>
                                        <?php echo $srclist ?>
                                    </select>  
                                </div>
                                <?php } ?>
                                <div class="form-group col-md-4">
                                    <label class="beta blue">Country</label>
                                    <select id="ccID" name="cc[]" multiple="multiple"  class="search-box-sel-all">
                                        <option value="ALL">All countries</option>
                                        <option value="af">Afghanistan</option>
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
                                <div class="form-group col-md-4">
                                    <label class="beta blue">Clients</label>
                                    <select id="aggID" name="agg[]" multiple="multiple"  class="search-box-sel-all">
                                        <option value="allaggs">All clients</option>
                                        <?php echo $aggregatorsList ?>
                                    </select>  
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="panel-body">
                                <div class="form-group col-md-4">
                                    <label class="beta blue">Carriers</label>
                                    <select class="form-control" id="opID" name="selectedOperator">
                                        <option value="allops">All carriers</option>
                                        <?php echo $operatorsList2 ?>
                                    </select>  
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="beta blue">Offers</label>
                                    <select id="campID" name="selectedCampaign[]" multiple="multiple"  class="search-box-sel-all">
                                        <option value="allcampaigns">All offers</option>
                                        <?php echo ''; /*$campaignSelectList*/ ?>
                                    </select>  
                                </div>
                                <?php if($userLevel < 2 || $userLevel == 3 || $userLevel == 4) { ?>
                                <div class="form-group col-md-4"> 
                                    <label class="beta blue">Source Types</label>
                                    <select class="form-control" id="sourcetypeID" name="selectedSourceType">
                                        <option value="allsourcestypes">All source types</option>
                                        <option value="0">Adult sources</option>
                                        <option value="1">Affiliates</option>
                                        <option value="2">Mainstream</option>
                                        <option value="3">Old affiliates</option>									
                                    </select>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="panel-body">
                                <div class="col-md-2 col-md-offset-5">
                                    <label class="beta blue"></label>
                                    <button title = "browse selected filter" id="browseit" name="action" value="statRequest" class="btn btn-primary" style="margin-left: 40%;">Browse</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div id="global">
            <?php echo $resTable; ?>
        </div>
    {% endblock %}
    {% block simplescript %}
        <script>
            $(document).ready(function () {

                $('#fperiod').daterangepicker({
                    format: 'YYYY-MM-DD',
                    separator: ' to ',
                    startDate: moment().subtract(3, 'days'),
                    endDate: moment().subtract(1, 'days'),
                    alwaysShowCalendars: true,
                    opens: "right",
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                });
                $('#speriod').daterangepicker({
                    format: 'YYYY-MM-DD',
                    separator: ' to ',
                    startDate: moment(),
                    alwaysShowCalendars: true,
                    opens: "right",
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                });




                

                /*
                 $('#fperiod').daterangepicker({
                 format: 'YYYY-MM-DD',
                 separator: ' to '
                 });
                 $('#speriod').daterangepicker({
                 format: 'YYYY-MM-DD',
                 separator: ' to '
                 });
                 */

                $('#fperiod').daterangepicker({
                    format: 'YYYY-MM-DD',
                    separator: ' to ',
                    startDate: moment().subtract(3, 'days'),
                    endDate: moment().subtract(1, 'days'),
                    alwaysShowCalendars: true,
                    opens: "right",
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                });
                $('#speriod').daterangepicker({
                    format: 'YYYY-MM-DD',
                    separator: ' to ',
                    startDate: moment(),
                     opens: "right",
                    alwaysShowCalendars: true,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                });

                $('#fperiod').val(decodeURIComponent(getUrlParameter('fperiod').replace(/\+/g, '%20')));
                $('#speriod').val(decodeURIComponent(getUrlParameter('speriod').replace(/\+/g, '%20')));
                $('#hour1').val(decodeURIComponent(getUrlParameter('hoursStart').replace(/\+/g, '%20')));
                $('#hour2').val(decodeURIComponent(getUrlParameter('hoursEnd').replace(/\+/g, '%20')));

            });

            function getUrlParameter(sParam)
            {
                var sPageURL = window.location.search.substring(1);
                var sURLVariables = sPageURL.split("&");
                for (var i = 0; i < sURLVariables.length; i++)
                {
                    var sParameterName = sURLVariables[i].split('=');
                    if (sParameterName[0] == sParam)
                    {
                        return sParameterName[1];
                    }
                }
                return '';
            }
            function rebindGoto(){
                $(".goto > td:nth-child(1)").click(function(){
                    var attrid=$(this).closest('.goto').attr('attrid');
                    var type=$(this).closest('.container').attr('id');
                    var oldUrl=window.location.href;
                    var newUrl='';
                    var newUrl = './statistics?testing=1&action=statRequest&'+$("#formdataBrowse").serialize();
                    if(type=='aggregatortable'){
                        paramName='agg%5B%5D=';
                        //newUrl=oldUrl.replaceAll(paramName,'');
                        newUrl=newUrl+'&'+paramName+attrid;
                        console.log('aggregatortable'+newUrl);
                    }else if(type=='sourcetable'){
                        paramName='src%5B%5D=';
                        //newUrl=oldUrl.replaceAll(paramName,'');
                        newUrl=newUrl+'&'+paramName+attrid;
                        console.log('sourcetable'+newUrl);
                    }else if(type=='countrytable'){
                        paramName='cc%5B%5D=';
                        //newUrl=oldUrl.replaceAll(paramName,'');
                        newUrl=newUrl+'&'+paramName+attrid;
                        console.log('finalcountry'+newUrl);
                    } else if (type == 'hourtabletable') {
                        paramName = 'hoursStart=';
                        paramName2 = 'hoursEnd=';
                        //newUrl = oldUrl.replaceAll(paramName, '');
                        //newUrl = oldUrl.replaceAll(paramName2, '');
                        newUrl = newUrl+'&'+paramName+attrid;
                        newUrl = newUrl+'&'+paramName2+attrid;
                    }

                    window.open(newUrl+"&action=prev",'_blank');
                });
            }
            $(".goto > td:nth-child(1)").click(function () {
                var attrid = $(this).closest('.goto').attr('attrid');
                var type = $(this).closest('.container').attr('id');

                var oldUrl = window.location.href;
                var newUrl = './statistics?testing=1&action=statRequest&'+$("#formdataBrowse").serialize();
                if (type == 'aggregatortable') {
                    paramName = 'agg%5B%5D=';
                    //newUrl = oldUrl.replaceAll(paramName, '');
                    newUrl = newUrl+'&'+paramName+attrid;
                    console.log('aggregatortable'+newUrl);
                } else if (type == 'sourcetable') {
                    paramName = 'src%5B%5D=';
                    //newUrl = oldUrl.replaceAll(paramName, '');
                    newUrl = newUrl+'&'+paramName+attrid;
                    console.log('sourcetable'+newUrl);
                } else if (type == 'countrytable') {
                    paramName = 'cc%5B%5D=';
                    //newUrl = oldUrl.replaceAll(paramName, '');
                    newUrl = newUrl+'&'+paramName+attrid;
                    //console.log('finalcountry'+newUrl);
                } else if (type == 'hourtabletable') {
                    paramName = 'hoursStart=';
                    paramName2 = 'hoursEnd=';
                    //newUrl = oldUrl.replaceAll(paramName, '');
                    //newUrl = oldUrl.replaceAll(paramName2, '');
                    newUrl = newUrl+'&'+paramName+attrid;
                    newUrl = newUrl+'&'+paramName2+attrid;
                } else if(type == 'operatortable') {
                	var res = attrid.split("_");

                	paramName = 'cc=';
                    paramName2 = 'selectedOperator=';
                    //newUrl = oldUrl.replaceAll(paramName, '');
                    //newUrl = oldUrl.replaceAll(paramName2, '');
                    newUrl = newUrl+'&'+paramName+res[0];
                    newUrl = newUrl+'&'+paramName2+res[1];
                } else if(type =='sourcetable2'){
                    
                    newUrl = newUrl+'&selectedSourceType='+attrid; 
                }

                window.open(newUrl+"&action=prev", '_blank');
            });


            function replaceUrlParam(url, paramName, paramValue) {
                if (paramValue == null)
                    paramValue = '';
                var pattern = new RegExp('\\b(' + paramName + '=).*?(&|$)')
                if (url.search(pattern) >= 0) {
                    return url.replace(pattern, '$1' + paramValue + '$2');
                }
                return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue
            }
            
            String.prototype.replaceAll = function(search, replacement) {
                var target = this;
                console.log(target);
                
                return target.replace(new RegExp('\&('+search+'.*)(&cc)', 'g'), ''+'$2').replace(new RegExp(search, 'g'), '');
                //return target.replace(new RegExp('&'+ search +'.*&', 'g'), '');
            };
        </script>
        <style>
            .goto > td:nth-child(1) {
                text-decoration: underline;
                cursor: pointer;
            }
            .SumoSelect {
                width: 100%;
            }
            }
        </style>

    {% endblock %}    

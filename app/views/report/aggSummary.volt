{% extends "/headfooter.volt" %}
{% block scriptimport %}    
    
    <script src="/js/report/mainaggsummary.js"></script>
{% endblock %}
{% block title %}<title>Clients Summary Report</title>{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div class="container">
        <div class="row">
            <div class="panel-heading">
                <h3 class="panel-title simple-title">Clients report</h3>
            </div>
        </div>
        <div class="panel-body">
            <form id="aform" role="form" class="well" action="/report/aggSummary">
                <fieldset>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="beta blue">First Period</label>
                                        <input class="form-control sdate" id="sdate" name="sdate" type="text" value="<?php echo date('Y-m-d');?>">
                                        <!-- <input class="form-control sdate"  title="Start date" placeholder="Start date" name="s" type="text" value='<?php echo date("Y-m-d", strtotime("-1 day"));?>'>-->
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="beta blue">Second Period</label>
                                        <input class="form-control edate" id="edate" name="edate" type="text" value="<?php echo date('Y-m-d'); ?>">
                                        <!-- <input class="form-control sdate"  title="Start date" placeholder="Start date" name="s" type="text" value='<?php echo date("Y-m-d", strtotime("-1 day"));?>'>-->
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label class="simple-label">Campaigns</label>
                                        <input class="form-control" id="campaignsfilter" placeholder="search campaign">
                                        <select class="form-control" id="aggCampaignid" name="campaignsid[]" size=15 multiple>
                                            <option value="allcampaigns">All Offers</option>
                                            <?php echo $campaignSelectList ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="simple-label">Country</label>
                                        <input class="form-control" placeholder="search country" id="countriesfilter">
                                        <select class="form-control" id="ccsid" name="ccsid[]" size = 15 multiple>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="simple-label">Clients</label>
                                        <input class="form-control" id="aggregatorsfilter" placeholder="search client">
                                        <select class="form-control" id="aggAggsID" name="aggsid[]" size=15 multiple>
                                            <option value="allaggs">All clients</option>
                                            <?php echo $aggregatorsList ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="simple-label">Operators</label>
                                        <input class="form-control" id="operatorsfilter" placeholder="search operator">
                                        <select class="form-control" id="operatorsid" name="operatorsid[]" size=15 multiple>
                                            <option value="alloperators">All Carriers</option>
                                            <?php echo $operatorslist ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <label class="simple-label">Show:</label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <div class="checkbox">
                                            <label>
                                                <input id="ccheckbox" name="selectcountry" type="checkbox"> Countries
                                            </label>
                                        </div>
                                    </div>
									<div class="form-group col-md-2">
                                        <div class="checkbox">
                                            <label>
                                                <input id="sidscheckbox" name="selectsource" type="checkbox"> SourceIDs
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="checkbox">
                                            <label>
                                                <input id="cucheckbox" name="selectusercountry" type="checkbox"> UserCountry
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="checkbox">
                                            <label>
                                                <input id="ocheckbox" name="selectoperator" type="checkbox"> Carrier
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-1">
                                        <div class="checkbox">
                                            <label>
                                                <input id="acheckbox" name="selectaggregator" type="checkbox"> Client
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-1">
                                        <div class="checkbox">
                                            <label>
                                                <input id="cacheckbox" name="selectcampaign" type="checkbox"> Offer
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="checkbox">
                                            <label>
                                                <input id="echeckbox" name="excel" type="checkbox"> Excel
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="form-group col-md-4">
                                <div class="form-group">
                                    <label class="simple-label">Order by</label>
                                    <select class="form-control" id="ordermyagg" name="orderaggreport" multiple="">
                                        <option value="clicks" selected="selected">Clicks</option>
                                        <option value="conversions">Conversions</option>
                                        <option value="rev">Revenue</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="form-group col-md-2">
                                </div>
                            </div>
                                <div class="form-group col-md-4">
                                    <div class="form-group col-md-2">
                                        <button id="butao" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>              
    </div>              
    <div id="global">
        <?php echo $resTable; ?>
    </div>

{% endblock %}
{% block simplescript %}
    <script>
        $(document).ready(function () {
             var countries = '<option value="ALL">All countries</option><option value="af">Afghanistan</option><option value="ax">Åland Islands</option><option value="al">Albania</option><option value="dz">Algeria</option><option value="as">American Samoa</option><option value="ad">Andorra</option><option value="ao">Angola</option><option value="ai">Anguilla</option><option value="aq">Antarctica</option><option value="ag">Antigua and Barbuda</option><option value="ar">Argentina</option><option value="am">Armenia</option><option value="aw">Aruba</option><option value="au">Australia</option><option value="at">Austria</option><option value="az">Azerbaijan</option><option value="bs">Bahamas</option><option value="bh">Bahrain</option><option value="bd">Bangladesh</option><option value="bb">Barbados</option><option value="by">Belarus</option><option value="be">Belgium</option><option value="bz">Belize</option><option value="bj">Benin</option><option value="bm">Bermuda</option><option value="bt">Bhutan</option><option value="bo">Bolivia</option><option value="ba">Bosnia and Herzegovina</option><option value="bw">Botswana</option><option value="bv">Bouvet Island</option><option value="br">Brazil</option><option value="io">British Indian Ocean Territory</option><option value="bn">Brunei Darussalam</option><option value="bg">Bulgaria</option><option value="bf">Burkina Faso</option><option value="bi">Burundi</option><option value="kh">Cambodia</option><option value="cm">Cameroon</option><option value="ca">Canada</option><option value="cv">Cape Verde</option><option value="ky">Cayman Islands</option><option value="cf">Central African Republic</option><option value="td">Chad</option><option value="cl">Chile</option><option value="cn">China</option><option value="cx">Christmas Island</option><option value="cc">Cocos (Keeling) Islands</option><option value="co">Colombia</option><option value="km">Comoros</option><option value="cg">Congo</option><option value="cd">Congo, The Democratic Republic of The</option><option value="ck">Cook Islands</option><option value="cr">Costa Rica</option><option value="ci">Cote D"ivoire</option><option value="hr">Croatia</option><option value="cu">Cuba</option><option value="cy">Cyprus</option><option value="cz">Czech Republic</option><option value="dk">Denmark</option><option value="dj">Djibouti</option><option value="dm">Dominica</option><option value="do">Dominican Republic</option><option value="ec">Ecuador</option><option value="eg">Egypt</option><option value="sv">El Salvador</option><option value="gq">Equatorial Guinea</option><option value="er">Eritrea</option><option value="ee">Estonia</option><option value="et">Ethiopia</option><option value="fk">Falkland Islands (Malvinas)</option><option value="fo">Faroe Islands</option><option value="fj">Fiji</option><option value="fi">Finland</option><option value="fr">France</option><option value="gf">French Guiana</option><option value="pf">French Polynesia</option><option value="tf">French Southern Territories</option><option value="ga">Gabon</option><option value="gm">Gambia</option><option value="ge">Georgia</option><option value="de">Germany</option><option value="gh">Ghana</option><option value="gi">Gibraltar</option><option value="gr">Greece</option><option value="gl">Greenland</option><option value="gd">Grenada</option><option value="gp">Guadeloupe</option><option value="gu">Guam</option><option value="gt">Guatemala</option><option value="gg">Guernsey</option><option value="gn">Guinea</option><option value="gw">Guinea-bissau</option><option value="gy">Guyana</option><option value="ht">Haiti</option><option value="hm">Heard Island and Mcdonald Islands</option><option value="va">Holy See (Vatican City State)</option><option value="hn">Honduras</option><option value="hk">Hong Kong</option><option value="hu">Hungary</option><option value="is">Iceland</option><option value="in">India</option><option value="id">Indonesia</option><option value="ir">Iran, Islamic Republic of</option><option value="iq">Iraq</option><option value="ie">Ireland</option><option value="im">Isle of Man</option><option value="il">Israel</option><option value="it">Italy</option><option value="jm">Jamaica</option><option value="jp">Japan</option><option value="je">Jersey</option><option value="jo">Jordan</option><option value="kz">Kazakhstan</option><option value="ke">Kenya</option><option value="ki">Kiribati</option><option value="kp">Korea, Democratic People\"s Republic of</option><option value="kr">Korea, Republic of</option><option value="kw">Kuwait</option><option value="kg">Kyrgyzstan</option><option value="la">Lao People"s Democratic Republic</option><option value="lv">Latvia</option><option value="lb">Lebanon</option><option value="ls">Lesotho</option><option value="lr">Liberia</option><option value="ly">Libyan Arab Jamahiriya</option><option value="li">Liechtenstein</option><option value="lt">Lithuania</option><option value="lu">Luxembourg</option><option value="mo">Macao</option><option value="mk">Macedonia, The Former Yugoslav Republic of</option><option value="mg">Madagascar</option><option value="mw">Malawi</option><option value="my">Malaysia</option><option value="mv">Maldives</option><option value="ml">Mali</option><option value="mt">Malta</option><option value="mh">Marshall Islands</option><option value="mq">Martinique</option><option value="mr">Mauritania</option><option value="mu">Mauritius</option><option value="yt">Mayotte</option><option value="mx">Mexico</option><option value="fm">Micronesia, Federated States of</option><option value="md">Moldova, Republic of</option><option value="mc">Monaco</option><option value="mn">Mongolia</option><option value="me">Montenegro</option><option value="ms">Montserrat</option><option value="ma">Morocco</option><option value="mz">Mozambique</option><option value="mm">Myanmar</option><option value="na">Namibia</option><option value="nr">Nauru</option><option value="np">Nepal</option><option value="nl">Netherlands</option><option value="an">Netherlands Antilles</option><option value="nc">New Caledonia</option><option value="nz">New Zealand</option><option value="ni">Nicaragua</option><option value="ne">Niger</option><option value="ng">Nigeria</option><option value="nu">Niue</option><option value="nf">Norfolk Island</option><option value="mp">Northern Mariana Islands</option><option value="no">Norway</option><option value="om">Oman</option><option value="pk">Pakistan</option><option value="pw">Palau</option><option value="ps">Palestinian Territory, Occupied</option><option value="pa">Panama</option><option value="pg">Papua New Guinea</option><option value="py">Paraguay</option><option value="pe">Peru</option><option value="ph">Philippines</option><option value="pn">Pitcairn</option><option value="pl">Poland</option><option value="pt">Portugal</option><option value="pr">Puerto Rico</option><option value="qa">Qatar</option><option value="re">Reunion</option><option value="ro">Romania</option><option value="ru">Russian Federation</option><option value="rw">Rwanda</option><option value="sh">Saint Helena</option><option value="kn">Saint Kitts and Nevis</option><option value="lc">Saint Lucia</option><option value="pm">Saint Pierre and Miquelon</option><option value="vc">Saint Vincent and The Grenadines</option><option value="ws">Samoa</option><option value="sm">San Marino</option><option value="st">Sao Tome and Principe</option><option value="sa">Saudi Arabia</option><option value="sn">Senegal</option><option value="rs">Serbia</option><option value="sc">Seychelles</option><option value="sl">Sierra Leone</option><option value="sg">Singapore</option><option value="sk">Slovakia</option><option value="si">Slovenia</option><option value="sb">Solomon Islands</option><option value="so">Somalia</option><option value="za">South Africa</option><option value="gs">South Georgia and The South Sandwich Islands</option><option value="es">Spain</option><option value="lk">Sri Lanka</option><option value="sd">Sudan</option><option value="sr">Suriname</option><option value="sj">Svalbard and Jan Mayen</option><option value="sz">Swaziland</option><option value="se">Sweden</option><option value="ch">Switzerland</option><option value="sy">Syrian Arab Republic</option><option value="tw">Taiwan, Province of China</option><option value="tj">Tajikistan</option><option value="tz">Tanzania, United Republic of</option><option value="th">Thailand</option><option value="tl">Timor-leste</option><option value="tg">Togo</option><option value="tk">Tokelau</option><option value="to">Tonga</option><option value="tt">Trinidad and Tobago</option><option value="tn">Tunisia</option><option value="tr">Turkey</option><option value="tm">Turkmenistan</option><option value="tc">Turks and Caicos Islands</option><option value="tv">Tuvalu</option><option value="ug">Uganda</option><option value="ua">Ukraine</option><option value="ae">United Arab Emirates</option><option value="gb">United Kingdom</option><option value="us">United States</option><option value="um">United States Minor Outlying Islands</option><option value="uy">Uruguay</option><option value="uz">Uzbekistan</option><option value="vu">Vanuatu</option><option value="ve">Venezuela</option><option value="vn">Viet Nam</option><option value="vg">Virgin Islands, British</option><option value="vi">Virgin Islands, U.S.</option><option value="wf">Wallis and Futuna</option><option value="eh">Western Sahara</option><option value="ye">Yemen</option><option value="zm">Zambia</option><option value="zw">Zimbabwe</option>';
            $("#ccsid").append(countries);

            $('#sdate').val(decodeURIComponent(getUrlParameter('sdate').replace(/\+/g, '%20')));
            $('#edate').val(decodeURIComponent(getUrlParameter('edate').replace(/\+/g, '%20')));

            if (getUrlParameter('selectcountry') == 'on')
                $("#ccheckbox").prop("checked", true);
			if (getUrlParameter('selectsource') == 'on')
                $("#sidscheckbox").prop("checked", true);
            if (getUrlParameter('selectusercountry') == 'on')
                $("#cucheckbox").prop("checked", true);
            if (getUrlParameter('selectcampaign') == 'on')
                $("#cacheckbox").prop("checked", true);
            if (getUrlParameter('selectoperator') == 'on')
                $("#ocheckbox").prop("checked", true);
            if (getUrlParameter('selectaggregator') == 'on')
                $("#acheckbox").prop("checked", true);

            $("#echeckbox").prop("checked", false);


            function selectCampaigns(element, index, array) {
                if (element != null)
                    $('#aggCampaignid option[value="' + element + '"]').attr("selected", "selected");
                else
                    return;
            }
            function selectCountries(element, index, array) {
                if (element != null)
                    $('#ccsid option[value="' + element + '"]').attr("selected", "selected");
                else
                    return;
            }
            function selectAggregators(element, index, array) {
                if (element != null)
                    $('#aggAggsID option[value="' + element + '"]').attr("selected", "selected");
                else
                    return;
            }
            function selectOperators(element, index, array) {
                if (element != null)
                    $('#operatorsid option[value="' + element + '"]').attr("selected", "selected");
                else
                    return;
            }

            function getValsFromParam(param) {
                // This function is anonymous, is executed immediately and 
                // the return value is assigned to QueryString!
                var query_string = {};
                var query = window.location.search.substring(1);
                var vars = query.split("&");
                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split("=");
                    // If first entry with this name
                    if (typeof query_string[pair[0]] === "undefined") {
                        query_string[pair[0]] = pair[1];
                        // If second entry with this name
                    } else if (typeof query_string[pair[0]] === "string") {
                        var arr = [query_string[pair[0]], pair[1]];
                        query_string[pair[0]] = arr;
                        // If third or later entry with this name
                    } else {
                        query_string[pair[0]].push(pair[1]);
                    }
                }
                return query_string[param];
            }

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

            var camps = getValsFromParam('campaignsid%5B%5D');

            if (camps != null) {
                if (Object.prototype.toString.call(camps) === '[object Array]') {
                    camps.forEach(selectCampaigns);
                }
                else {
                    $('#aggCampaignid option[value="' + camps + '"]').attr("selected", "selected");
                }
            }
            
            var ccs = getValsFromParam('ccsid%5B%5D');
            if (ccs != null) {
                if (Object.prototype.toString.call(ccs) === '[object Array]') {
                    ccs.forEach(selectCountries);
                }
                else {
                    $('#ccsid option[value="' + ccs + '"]').attr("selected", "selected");
                }
            }

            var ops = getValsFromParam('operatorsid%5B%5D');

            if (ops != null) {
                if (Object.prototype.toString.call(ops) === '[object Array]') {
                    ops.forEach(selectOperators);
                }
                else {
                    $('#operatorsid option[value="' + ops + '"]').attr("selected", "selected");
                }
            }

            var aggs = getValsFromParam('aggsid%5B%5D');
            if (aggs != null) {
                if (Object.prototype.toString.call(aggs) === '[object Array]') {
                    aggs.forEach(selectAggregators);
                }
                else {
                    $('#aggAggsID option[value="' + aggs + '"]').attr("selected", "selected");
                }
            }



            var campaignSelects = [];
            var aggsSelects = [];
            var opsSelects = [];
            var countriesSelects = [];
            
            $('#aggCampaignid option:selected').each(function () {
                if (campaignSelects.indexOf($(this).val()) < 0)
                    campaignSelects.push($(this).val());
            });
            
            $('#ccsid option:selected').each(function () {
                if (countriesSelects.indexOf($(this).val()) < 0)
                    countriesSelects.push($(this).val());
            });

            $('#aggAggsID option:selected').each(function () {
                if (aggsSelects.indexOf($(this).val()) < 0)
                    aggsSelects.push($(this).val());
            });

            $('#operatorsid option:selected').each(function () {
                if (opsSelects.indexOf($(this).val()) < 0)
                    opsSelects.push($(this).val());
            });

            function rebindFuncs() {
                $("#aggCampaignid option").on('mousedown', function (e) {
                    e.preventDefault();
                    $(this).prop('selected', !$(this).prop('selected'));
                    return false;
                });
                $("#ccsid option").on('mousedown', function (e) {
                    e.preventDefault();
                    $(this).prop('selected', !$(this).prop('selected'));
                    return false;
                });
                $("#aggAggsID option").on('mousedown', function (e) {
                    e.preventDefault();
                    $(this).prop('selected', !$(this).prop('selected'));
                    return false;
                });
                $("#operatorsid option").on('mousedown', function (e) {
                    e.preventDefault();
                    $(this).prop('selected', !$(this).prop('selected'));
                    return false;
                });

                $('#aggCampaignid option').on('click', function (e) {
                    var pos = campaignSelects.indexOf(e.target.value);
                    if ((e.target).selected && pos < 0) {
                        campaignSelects.push(e.target.value);
                    }
                    else if (pos > -1 && !(e.target).selected) {
                        campaignSelects.splice(pos, 1);
                    }
                    //console.log(selects);
                });
                $('#ccsid option').on('click', function (e) {
                    var pos = countriesSelects.indexOf(e.target.value);
                    if ((e.target).selected && pos < 0) {
                        countriesSelects.push(e.target.value);
                    }
                    else if (pos > -1 && !(e.target).selected) {
                        countriesSelects.splice(pos, 1);
                    }
                    //console.log(selects);
                });
                $('#aggAggsID option').on('click', function (e) {
                    var pos = aggsSelects.indexOf(e.target.value);
                    if ((e.target).selected && pos < 0) {
                        aggsSelects.push(e.target.value);
                    }
                    else if (pos > -1 && !(e.target).selected) {
                        aggsSelects.splice(pos, 1);
                    }
                    //console.log(selects);
                });
                $('#operatorsid option').on('click', function (e) {
                    var pos = opsSelects.indexOf(e.target.value);
                    if ((e.target).selected && pos < 0) {
                        opsSelects.push(e.target.value);
                    }
                    else if (pos > -1 && !(e.target).selected) {
                        opsSelects.splice(pos, 1);
                    }
                    //console.log(selects);
                });
            }
            rebindFuncs();

            $(function () {
                $('#aggCampaignid').filterByText($('#campaignsfilter'), campaignSelects);
            });
            $(function () {
                $('#ccsid').filterByText($('#countriesfilter'), campaignSelects);
            });
            $(function () {
                $('#aggAggsID').filterByText($('#aggregatorsfilter'), aggsSelects);
            });
            $(function () {
                $('#operatorsid').filterByText($('#operatorsfilter'), opsSelects);
            });
            jQuery.fn.filterByText = function (textbox, selects) {
                return this.each(function () {
                    var select = this;
                    var options = [];
                    $(select).find('option').each(function () {
                        options.push({value: $(this).val(), text: $(this).text()});
                    });
                    $(select).data('options', options);
                    $(textbox).bind('change keyup', function () {

                        var options = $(select).empty().data('options');
                        var search = $.trim($(this).val());
                        var regex = new RegExp(search, "gi");
                        $.each(options, function (i) {
                            var option = options[i];
                            if (option.text.match(regex) !== null) {
                                if (selects.indexOf(option.value) != -1) {
                                    $(select).append($('<option>').text(option.text).val(option.value).attr('selected', 'selected'));
                                }
                                else {
                                    $(select).append($('<option>').text(option.text).val(option.value));
                                }
                            }
                        });
                        rebindFuncs();
                    });
                });
            };
            /*
             $('#aggCampaignid option[value="' + getUrlParameter('aggFunction') + '"]').attr("selected", "selected");
             $('#aggAggsID option[value="' + getUrlParameter('campaignsid') + '"]').attr("selected", "selected");
             $('#operatorsid option[value="' + getUrlParameter('selectedCampaign') + '"]').attr("selected", "selected");
             */
        });



    </script>


{% endblock %}    

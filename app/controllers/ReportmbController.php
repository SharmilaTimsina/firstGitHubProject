<?php

/**
 * ReportController
 *
 * Report Insertion and presentation
 */
class ReportMBController extends ControllerBase {

    private $reportObject;
    private $country_array = array(
        "AF" => "AF", "AL" => "AL", "DZ" => "DZ", "AS" => "AS", "AD" => "AD", "AO" => "AO", "AI" => "AI", "AQ" => "AQ",
        "AG" => "AG", "AR" => "AR", "AM" => "AM", "AW" => "AW", "AU" => "AU", "AT" => "AT", "AZ" => "AZ", "BS" => "BS",
        "BH" => "BH", "BD" => "BD", "BB" => "BB", "BY" => "BY", "BE" => "BE", "BZ" => "BZ", "BJ" => "BJ", "BM" => "BM",
        "BT" => "BT", "BO" => "BO", "BA" => "BA", "BW" => "BW", "BV" => "BV", "BR" => "BR", "BQ" => "BQ", "IO" => "IO",
        "VG" => "VG", "BN" => "BN", "BG" => "BG", "BF" => "BF", "BI" => "BI", "KH" => "KH", "CM" => "CM", "CA" => "CA",
        "CT" => "CT", "CV" => "CV", "KY" => "KY", "CF" => "CF", "TD" => "TD", "CL" => "CL", "CN" => "CN", "CX" => "CX",
        "CC" => "CC", "CO" => "CO", "KM" => "KM", "CG" => "CG", "CD" => "CD", "CK" => "CK", "CR" => "CR", "HR" => "HR",
        "CU" => "CU", "CY" => "CY", "CZ" => "CZ", "CI" => "CI", "DK" => "DK", "DJ" => "DJ", "DM" => "DM", "DO" => "DO",
        "NQ" => "NQ", "DD" => "DD", "EC" => "EC", "EG" => "EG", "SV" => "SV", "GQ" => "GQ", "ER" => "ER", "EE" => "EE",
        "ET" => "ET", "FK" => "FK", "FO" => "FO", "FJ" => "FJ", "FI" => "FI", "FR" => "FR", "GF" => "GF", "PF" => "PF",
        "TF" => "TF", "FQ" => "FQ", "GA" => "GA", "GM" => "GM", "GE" => "GE", "DE" => "DE", "GH" => "GH", "GI" => "GI",
        "GR" => "GR", "GL" => "GL", "GD" => "GD", "GP" => "GP", "GU" => "GU", "GT" => "GT", "GG" => "GG", "GN" => "GN",
        "GW" => "GW", "GY" => "GY", "HT" => "HT", "HM" => "HM", "HN" => "HN", "HK" => "HK", "HU" => "HU", "IS" => "IS",
        "IN" => "IN", "ID" => "ID", "IR" => "IR", "IQ" => "IQ", "IE" => "IE", "IM" => "IM", "IL" => "IL", "IT" => "IT",
        "JM" => "JM", "JP" => "JP", "JE" => "JE", "JT" => "JT", "JO" => "JO", "KZ" => "KZ", "KE" => "KE", "KI" => "KI",
        "KW" => "KW", "KG" => "KG", "LA" => "LA", "LV" => "LV", "LB" => "LB", "LS" => "LS", "LR" => "LR", "LY" => "LY",
        "LI" => "LI", "LT" => "LT", "LU" => "LU", "MO" => "MO", "MK" => "MK", "MG" => "MG", "MW" => "MW", "MY" => "MY",
        "MV" => "MV", "ML" => "ML", "MT" => "MT", "MH" => "MH", "MQ" => "MQ", "MR" => "MR", "MU" => "MU", "YT" => "YT",
        "FX" => "FX", "MX" => "MX", "FM" => "FM", "MI" => "MI", "MD" => "MD", "MC" => "MC", "MN" => "MN", "ME" => "ME",
        "MS" => "MS", "MA" => "MA", "MZ" => "MZ", "MM" => "MM", "NA" => "NA", "NR" => "NR", "NP" => "NP", "NL" => "NL",
        "AN" => "AN", "NT" => "NT", "NC" => "NC", "NZ" => "NZ", "NI" => "NI", "NE" => "NE", "NG" => "NG", "NU" => "NU",
        "NF" => "NF", "KP" => "KP", "VD" => "VD", "MP" => "MP", "NO" => "NO", "OM" => "OM", "PC" => "PC", "PK" => "PK",
        "PW" => "PW", "PS" => "PS", "PA" => "PA", "PZ" => "PZ", "PG" => "PG", "PY" => "PY", "YD" => "YD", "PE" => "PE",
        "PH" => "PH", "PN" => "PN", "PL" => "PL", "PT" => "PT", "PR" => "PR", "QA" => "QA", "RO" => "RO", "RU" => "RU",
        "RW" => "RW", "RE" => "RE", "BL" => "BL", "SH" => "SH", "KN" => "KN", "LC" => "LC", "MF" => "MF", "PM" => "PM",
        "VC" => "VC", "WS" => "WS", "SM" => "SM", "SA" => "SA", "SN" => "SN", "RS" => "RS", "CS" => "CS", "SC" => "SC",
        "SL" => "SL", "SG" => "SG", "SK" => "SK", "SI" => "SI", "SB" => "SB", "SO" => "SO", "ZA" => "ZA", "GS" => "GS",
        "KR" => "KR", "ES" => "ES", "LK" => "LK", "SD" => "SD", "SR" => "SR", "SJ" => "SJ", "SZ" => "SZ", "SE" => "SE",
        "CH" => "CH", "SY" => "SY", "ST" => "ST", "TW" => "TW", "TJ" => "TJ", "TZ" => "TZ", "TH" => "TH", "TL" => "TL",
        "TG" => "TG", "TK" => "TK", "TO" => "TO", "TT" => "TT", "TN" => "TN", "TR" => "TR", "TM" => "TM", "TC" => "TC",
        "TV" => "TV", "UM" => "UM", "PU" => "PU", "VI" => "VI", "UG" => "UG", "UA" => "UA", "SU" => "SU", "AE" => "AE",
        "GB" => "GB", "US" => "US", "ZZ" => "ZZ", "UY" => "UY", "UZ" => "UZ", "VU" => "VU", "VA" => "VA", "VE" => "VE",
        "VN" => "VN", "WK" => "WK", "WF" => "WF", "EH" => "EH", "YE" => "YE", "ZM" => "ZM", "ZW" => "ZW", "AX" => "AX",
    );

    public function initialize() {
        $this->tag->setTitle('Report');
        parent::initialize();
        $this->reportObject = new ReportMB();
        $this->reportObject2 = new Report2MB();
        $this->reportObject3 = new Report2MB();
    }

    public function indexAction() {

        $auth = $this->session->get('auth');
        $this->view->setVar("userLevel", $auth['userlevel']);
        $this->view->setVar("current_date", date('Y-m-d'));
        $this->view->setVar("current_datetime", date('Y-m-d H:00:00'));
        echo 'here';
        $this->setStatisticsViewVars();
        echo 'here2';
    }

    public function index2Action() {

        $auth = $this->session->get('auth');
        $this->view->setVar("userLevel", $auth['userlevel']);
        $this->view->setVar("current_date", date('Y-m-d'));
        $this->view->setVar("current_datetime", date('Y-m-d H:00:00'));
        $this->setStatisticsViewVars();
    }

    public function mainAction() {

        ini_set("memory_limit", "3000M");
        set_time_limit(0);
        $onlyCurrentDay = false;
        if ($this->request->get('s') != null and $this->request->get('e') != null) {
            $sdate = $this->request->get('s');
            $edate = $this->request->get('e');
            if ($sdate == date('Y-m-d') && $edate == date('Y-m-d')) {
                $onlyCurrentDay = true;
            }
        } else {
            $sdate = date('Y-m-d');
            $edate = date('Y-m-d');
        }

        $param = 'INNER';
        if ($this->request->get('c') != null) {
            $param = 'LEFT';
        }
        if ($this->request->get('cc') && !is_array($this->request->get('cc')))
            $country = $this->request->get('cc') == 'ALL' ? null : $this->request->get('cc');
        else if ($this->request->get('cc') && is_array($this->request->get('cc'))) {
            $country = '';
            $this->reportObject2 = $this->reportObject3;
            foreach ($this->request->get('cc') as $val) {
                if (isset($this->country_array[strtoupper($val)])) {
                    $country .= '"' . $this->country_array[strtoupper($val)] . '",';
                } else {
                    $country = null;
                    break;
                }
            }
            $country = isset($country) ? rtrim($country, ',') : null;
        } else
            $country = null;

        if ($this->request->get('action') == 'excel') {
            $this->view->disable();
            if ($this->request->get('testing') != null) {

                $result = $this->mainreportexcel($sdate, $edate, $country);
            } else
                $result = $this->main_report_table_rbb($sdate, $edate, $param, $country);
            $a = array(0 => array('Date', 's', 0), 1 => array('Campaign', 's', 0), 2 => array('Campaign Hash', 's', 0), 3 => array('Country', 's', 0), 4 => array('Source', 's', 0),
                5 => array('Agregator', 's', 0), 6 => array('Agregator Name', 's', 0), 7 => array('Sub id', 's', 0), 8 => array('Clicks', 's', 0), 9 => array('Conversions', 's', 0), 10 => array('Revenue', 'd', 2),
                11 => array('EPC', 'd', 2), 12 => array('CR', 'd', 2), 13 => array('Ad', 's', 0));
            $sqlColumns = 'insert_date,campaign,c_hash,campaign_country,source,agregator,agregator_name,sub_id,clicks,conversions,revenue,EPC,CR,ad';
            $this->excelRes($a, $sqlColumns, $result, 'Main Report ');
            //$this->main_excel($result);
        } else {
            $result = $this->main_report_table($sdate, $edate, $param, $country);
            //$cRes = $this->report_country_table($sdate, $edate, $param, $country);
            $countryRestable = $this->generate_countrydata_table($result[1]);

            $table = $this->generate_main_table($result[0], $sdate, $edate, $onlyCurrentDay, $country);
            $this->view->setVar("totalTable", $table[0]);
            $this->view->setVar("table", $table[1]);
            $this->view->setVar("countryTable", $countryRestable);
            $this->view->setVar("name", 'Main Report');
        }
    }

    public function operatorAction() {

        ini_set("memory_limit", "2048M");
        set_time_limit(0);
        if ($this->request->get('s') != null and $this->request->get('e') != null) {
            $param = 'INNER';
            if ($this->request->get('c') != null) {
                $param = 'LEFT';
            }
            if ($this->request->get('cc') && !is_array($this->request->get('cc')))
                $country = $this->getParams($this->request->get('cc'), 'country');

            else if ($this->request->get('cc') && is_array($this->request->get('cc'))) {
                $country = '';
                $this->reportObject2 = $this->reportObject3;
                foreach ($this->request->get('cc') as $val) {
                    if (isset($this->country_array[strtoupper($val)])) {
                        $country .= '"' . $this->country_array[strtoupper($val)] . '",';
                    } else {
                        $country = null;
                        break;
                    }
                }
                $country = isset($country) ? rtrim($country, ',') : null;
            } else
                $country = null;

            $logArray = $this->login_access('m.campaign_country', 'm.agregator', 'm.source', 0);
            if ($this->request->get('testing') != null) {
                $result = $this->reportObject3->getOperatorReport2($this->request->get('s'), $this->request->get('e'), $country, $logArray[0], $logArray[2]);
            } else {
                $result = $this->op_report_table($this->request->get('s'), $this->request->get('e'), $param, $country);
            }
            $this->op_excel2($result);
        }
    }

    public function mjumpAction() {
        ini_set("memory_limit", "2048M");
        set_time_limit(0);
        $auth = $this->session->get('auth');
        $countriesAndSources = $this->login_access();
        if ($this->request->get('s') != null and $this->request->get('e') != null && $this->request->get('testing') == null) {
            $country = $this->getParams($this->request->get('cc'), 'country');

            $result = $this->mj_report_table($this->request->get('s'), $this->request->get('e'), $countriesAndSources[1], $country);
            $this->mj_excel($result);
        } else {
            $report = new Report2MB();
            $countryarr = $this->request->get('cc');
            $country = null;
            if ($countryarr != null && is_array($countryarr)) {
                $country = '';
                foreach ($countryarr as $val) {
                    if (isset($this->country_array[strtoupper($val)])) {
                        $country .= '"' . $this->country_array[strtoupper($val)] . '",';
                    } else {
                        $country = null;
                        break;
                    }
                }
                $country = !empty($country) ? rtrim($country, ',') : null;
            }



            $res = $report->getMjumpResults($auth, $this->request->get('s'), $this->request->get('e'), $countriesAndSources[5], $country);
            $a = array(0 => array('Date', 's', 0), 1 => array('lpName', 's', 0), 2 => array('page', 's', 0),
                3 => array('ad', 's', 0), 4 => array('userCountry', 's', 0), 5 => array('carrier', 's', 0),
                6 => array('browser', 's', 0), 7 => array('OS', 's', 0),
                8 => array('subid', 's', 0), 9 => array('source', 's', 0),
                10 => array('lpClicks', 'i', 0),
                11 => array('clicks', 's', 0), 12 => array('conversions', 'i', 0), 13 => array('revenue', 'd', 2));
            $sqlColumns = 'insertDate,lpName,page,ad,userCountry,carrier,browser,OS,subid,source,lpClicks,clicks,conversions,revenue';
            $this->excelRes($a, $sqlColumns, $res, 'Mjump Report ');
            $this->mj_csv($result);

            //insertDate,lpName,page,ad,subid,userCountry,mobileBrand as carrier,browserName as browser,mobileTest as OS,
            //SUM(lpClick) as lpClicks, SUM(click) AS clicks, SUM(conversions) AS conversions,SUM(revenue) AS revenue
        }
    }

    public function payoutreportAction() {
        ini_set("memory_limit", "1024M");
        set_time_limit(0);
        if ($this->request->get('s') != null and $this->request->get('e') != null and $this->request->get('src') != null) {
            $countriesAndSources = $this->login_access('m.country', 'm.agregator', '', 0);
            $report = new Report2MB();
            $source = $this->request->get('src');
            $source = $this->getParams($source);
            $res = $report->getPayoutResult($this->request->get('s'), $this->request->get('e'), $source, $countriesAndSources[0], $countriesAndSources[1]);
            $a = array(0 => array('Hash', 's', 0), 1 => array('Campaign', 's', 0), 2 => array('Source', 's', 0), 3 => array('Payout', 'd', 2), 4 => array('Time', 's', 0));
            $sqlColumns = 'hash,campaign,source,value,insertTimestamp';
            $this->excelRes($a, $sqlColumns, $res, 'Payout Report ');
        }
    }

    public function reportbyhourAction() {
        ini_set("memory_limit", "1024M");
        set_time_limit(120);
        if ($this->request->get('s') != null and $this->request->get('e') != null) {
            $countriesAndSources = $this->login_access('m.country', 'm.agregator', 'r.sourceid', 0);
            $report = new Report2MB();
            $country = $this->getParams($this->request->get('country'), 'country');

            $res = $report->reportbyhour($this->request->get('s'), $this->request->get('e'), $country, $countriesAndSources[0], $countriesAndSources[2]);
            $a = array(0 => array('Date', 's', 0), 1 => array('Hour', 's', 0), 2 => array('Campaign', 's', 0), 3 => array('Campaign Hash', 's', 0), 4 => array('Country', 's', 0),
                5 => array('Source', 's', 0), 6 => array('Agregator', 's', 0), 7 => array('Sub id', 's', 0), 8 => array('Clicks', 'd', 0),
                9 => array('Conversions', 's', 0), 10 => array('Revenue', 'd', 2), 11 => array('EPC', 'd', 2), 12 => array('CR', 'd', 2), 13 => array('Ad', 's', 0));
            $sqlColumns = 'Date,Hour,Campaign,Campaign_Hash,Country,Source,Agregator,Subid,Clicks,Conversions,Revenue,EPC,CR,Ad';
            $this->excelRes($a, $sqlColumns, $res, 'Report by Hour');
        }
    }

    public function statisticsAction() {

        if ($this->request->get('fperiod') != null and $this->request->get('speriod') != null) {
            $country = null;
            $source = null;
            $agg = null;
            $operator = null;
            $campaign = null;


            $country = $this->getParams($this->request->get('cc'), 'country');

            if ($this->request->get('src') != null) {
                $source = '';
                foreach ($this->request->get('src') as $param) {
                    if (is_numeric($param)) {

                        $source.="$param,";
                    } else {
                        $source = null;
                        break;
                    }
                }
                if (isset($source))
                    $source = rtrim($source, ',');
            }
            if ($this->request->get('agg') != null) {
                $agg = '';
                foreach ($this->request->get('agg') as $param) {
                    if (is_numeric($param)) {
                        $agg.="$param,";
                    } else {
                        $agg = null;
                        break;
                    }
                }
                if (isset($agg))
                    $agg = rtrim($agg, ',');
            }
            if ($this->request->get('selectedCampaign') != null) {
                $campaign = '';
                foreach ($this->request->get('selectedCampaign') as $param) {
                    if (strlen($param) == '13') {
                        $campaign.='"' . $param . '",';
                    } else {
                        $campaign = null;
                        break;
                    }
                }
                if (isset($campaign))
                    $campaign = rtrim($campaign, ',');
            }
            if ($this->request->get('selectedOperator') != null && $this->request->get('selectedOperator') != 'allops') {
                $operator = $this->request->get('selectedOperator');
            }


            $auth = $this->session->get('auth');
            $res = '';

            if ($this->request->get('action') != 'statRequest') {
                $this->setStatisticsViewVars(null, null, null, null, null, null);
            }

            $sourcetype = null;
            if ($this->request->get('selectedSourceType') != null && $this->request->get('selectedSourceType') != 'undefined' && $this->request->get('selectedSourceType') != 'allsourcestypes' && (0 <= $this->request->get('selectedSourceType')) && ($this->request->get('selectedSourceType') <= 5))
                $sourcetype = $this->request->get('selectedSourceType');

            $this->view->setVar("userLevel", $auth['userlevel']);

            $fperiod = explode(' to ', $this->request->get('fperiod'));
            $firstp1 = $fperiod[0];
            if (!isset($fperiod[1])) {
                $firstp2 = $fperiod[0];
            } else {
                $firstp2 = $fperiod[1];
            }
            $speriod = explode(' to ', $this->request->get('speriod'));
            $sfirstp1 = $speriod[0];
            if (!isset($speriod[1])) {
                $sfirstp2 = $speriod[0];
            } else {
                $sfirstp2 = $speriod[1];
            }
            $hourStart = $this->request->get('hoursStart') . ':00:00';
            $hourEnd = $this->request->get('hoursEnd') . ':00:00';



            $loginAccess = $this->login_access('m.country', 'm.agregator', 'r.sourceid', 0);
            if ($auth['userlevel'] < 3) {
                if ($auth['userlevel'] == 1) {
                    $sourcescatRes = $this->reportObject3->getGeneralStatisticsBySourcesCategory($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
                    $sourcescatRes2 = $this->reportObject3->getGeneralStatisticsBySourcesCategory($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
                    if (isset($sourcescatRes)) {
                        $res .= $this->genGeneralTable('sourcetable2', $sourcescatRes, $sourcescatRes2, 'Source Types');
                    }
                }
                $loginAccess = $this->login_access('m.country', 'm.agregator', 'r.source', $loginAccess[3]);
                $countryRes = $this->reportObject3->getGeneralStatisticsCountryAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
                $countryRes2 = $this->reportObject3->getGeneralStatisticsCountryAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
                $res .= $this->genGeneralTable('countrytable', $countryRes, $countryRes2, 'Country');
            }

            if ($auth['userlevel'] < 3) {
                $loginAccess = $this->login_access('m.country', 'm.agregator', 'r.source', $loginAccess[3]);
                $sourcesRes = $this->reportObject3->getGeneralStatisticsSourcesAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
                $sourcesRes2 = $this->reportObject3->getGeneralStatisticsSourcesAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
            } else {
                $loginAccess = $this->login_access('m.country', 'r.agregator', 'r.sourceid', $loginAccess[3]);
                $sourcesRes = $this->reportObject3->getGeneralStatisticsBySourcesCategory($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
                $sourcesRes2 = $this->reportObject3->getGeneralStatisticsBySourcesCategory($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
            }

            if ($agg != null && $agg != 'allaggs') {
                $loginAccess = $this->login_access('m.country', 'm.agregator', 'r.sourceid', $loginAccess[3]);
                $aggCampsRes = $this->reportObject3->getGeneralStatisticsAggCampaignsAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
                $aggCampsRes2 = $this->reportObject3->getGeneralStatisticsAggCampaignsAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
            }
            $loginAccess = $this->login_access('m.country', 'r.agregator', 'r.sourceid', $loginAccess[3]);
            $aggRes = $this->reportObject3->getGeneralStatisticsAggregatorsAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
            $aggRes2 = $this->reportObject3->getGeneralStatisticsAggregatorsAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);

            $loginAccess = $this->login_access('m.country', 'r.agregator', 'r.source', $loginAccess[3]);
            $opRes = $this->reportObject3->getGeneralStatisticsOperatorsAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $operator, $sourcetype);
            $opRes2 = $this->reportObject3->getGeneralStatisticsOperatorsAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $operator, $sourcetype);

            if ($auth['userlevel'] > 2) {
                $res .= $this->genGeneralTable('sourcetable2', $sourcesRes, $sourcesRes2, 'Source Types');
            } else
                $res .= $this->genGeneralTable('sourcetable', $sourcesRes, $sourcesRes2, 'Sources');
            //}
            $res .= $this->genGeneralTable('aggregatortable', $aggRes, $aggRes2, 'Aggregator', $auth['userlevel'] > 2);

            if ($agg != null && $agg != 'allaggs') {
                $loginAccess = $this->login_access('m.country', 'm.agregator', 'r.sourceid', $loginAccess[3]);
                $aggCampsRes = $this->reportObject3->getGeneralStatisticsAggCampaignsAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
                $aggCampsRes2 = $this->reportObject3->getGeneralStatisticsAggCampaignsAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[2], $sourcetype, $operator);
                $res .= $this->genGeneralTable('campaigntable', $aggCampsRes, $aggCampsRes2, 'Campaign', $auth['userlevel'] > 2);
            }
            //}

            $res .= $this->genGeneralTable('operatortable', $opRes, $opRes2, 'Operator', $auth['userlevel'] > 2);


            if ($this->request->get('action') != 'statRequest') {
                $this->view->setVar("resTable", $res);
            } else {
                echo $res;
                $this->view->disable();
            }
        }
    }

    public function aggSummaryAction() {
        ini_set("memory_limit", "1024M");
        if ($this->request->get('sdate') != null and $this->request->get('edate') != null) {

            $report = new Report2MB();
            $ops = $cps = $ags = $cs = null;
            if ($this->request->get('inside') == null)
                $this->setaggSummaryViewVars();
            if ($this->request->get('operatorsid') != null && $this->request->get('operatorsid') != 'alloperators') {
                $ops = '';
                foreach ($this->request->get('operatorsid') as $op) {
                    if (strpos($op, 'alloperators') !== false) {
                        $ops = null;
                        break;
                    } else
                        $ops .= '"' . $op . '",';
                }
                $ops = isset($ops) ? rtrim($ops, ',') : null;
            }
            if ($this->request->get('campaignsid') != null && $this->request->get('campaignsid') != 'allcampaigns') {
                $cps = '';
                foreach ($this->request->get('campaignsid') as $cp) {
                    if (strpos($cp, 'allcampaigns') !== false) {
                        $cps = null;
                        break;
                    } else
                        $cps .= '"' . $cp . '",';
                }
                $cps = isset($cps) ? rtrim($cps, ',') : null;
            }
            if ($this->request->get('aggsid') != null && $this->request->get('aggsid') != 'allaggs') {
                $ags = '';
                foreach ($this->request->get('aggsid') as $aggs) {
                    if (strpos($aggs, 'allaggs') !== false) {
                        $ags = null;
                        break;
                    } else
                        $ags .= $aggs . ',';
                }
                $ags = isset($ags) ? rtrim($ags, ',') : null;
            }
            if ($this->request->get('ccsid') != null && $this->request->get('ccsid') != 'ALL') {
                $cs = '';
                foreach ($this->request->get('ccsid') as $ccs) {
                    if (strpos($ccs, 'ALL') !== false) {
                        $cs = null;
                        break;
                    } else
                        $cs .= '"' . $ccs . '",';
                }
                $cs = isset($cs) ? rtrim($cs, ',') : null;
            }
            $orderby = 'clicks';
            if ($this->request->get('orderaggreport') != null) {
                if ($this->request->get('orderaggreport') == 'conversions')
                    $orderby = 'conversions';
                else if ($this->request->get('orderaggreport') == 'clicks')
                    $orderby = 'clicks';
                else
                    $orderby = 'revenues';
            }

            $access = $this->login_access('campaign_country', 't1.agregator', 't1.source', 0);

            $res = $report->getAggData($this->request->get('sdate'), $this->request->get('edate'), $this->request->get('selectcountry'), $this->request->get('selectaggregator'), $this->request->get('selectcampaign'), $this->request->get('selecturl'), $this->request->get('selectoperator'), $orderby, $access[0], $ags, $cps, $ops, $access[2], $cs);
            $aggFunction = '';
            $resultColumns = array(array('Date', 's', 0));
            if ($this->request->get('selectcountry') != null) {
                $resultColumns[] = array('Country', 's', 0);
                $aggFunction .=',Country';
            }
            if ($this->request->get('selectoperator') != null) {
                $resultColumns[] = array('Operator', 's', 0);
                $aggFunction .=',Operator';
            }
            if ($this->request->get('selecturl') != null) {
                $resultColumns[] = array('CampaignUrl', 's', 0);
                $aggFunction .=',CampaignUrl';
            }
            if ($this->request->get('selectaggregator') != null) {
                $resultColumns[] = array('Aggregator', 's', 0);
                $aggFunction .=',Aggregator';
                if ($this->request->get('excel') == null)
                    $resultColumns[] = array('AggregatorName', 's', 0);
            }
            if ($this->request->get('selectcampaign') != null) {
                $resultColumns[] = array('Campaign', 's', 0);
                $aggFunction .=',Campaign';
            }

            if ($this->request->get('excel') != null) {
                array_push($resultColumns, array('Clicks', 'd', 0), array('Conversions', 'd', 0), array('Revenues', 'd', 2));
                $sqlColumns = 'Date' . (isset($aggFunction) ? $aggFunction : '') . ',clicks,conversions,revenues';
                if ($this->request->get('selectaggregator') != null) {
                    $resultColumns[] = array('AggregatorName', 's', 0);
                    $sqlColumns .= ',AggregatorName';
                }
                $this->excelRes($resultColumns, $sqlColumns, $res, 'Aggregator Report ');
            } else {
                $columnSize = floor(12 / (sizeof($resultColumns) + 3));
                $table = $this->genAggSummaryTable($resultColumns, $columnSize, $res);
                if ($this->request->get('inside') == null)
                    $this->view->setVar("resTable", $table);
                else {
                    echo $table;
                    $this->view->disable();
                }
            }
        }
    }

    private function genAggSummaryTable($columns, $columnsSize, $sqlRes) {
        $table = '<div id="restablediv"><div class="container well">
                    <div class="table-responsive" ><table class="table table-bordered centertext" >';
        $theads = '<thead><tr>';

        foreach ($columns as $column) {
            $theads .= '<td class="col-xs-' . ($columnsSize) . '">
                                <strong>' . $column[0] . '</strong>
                            </td>';
        }
        $table .=$theads . '<td class="col-xs-' . ($columnsSize) . '">
                                <strong>Clicks</strong>
                            </td><td class="col-xs-' . ($columnsSize) . '">
                                <strong>Conversions</strong>
                            </td><td class="col-xs-' . ($columnsSize) . '">
                                <strong>Revenue</strong>
                            </td></thead><tbody>';
        $totalClicks = 0;
        $totalConversions = 0;
        $totalRevenues = 0;
        foreach ($sqlRes as $row) {
            $tr = '<tr>';
            foreach ($columns as $column) {
                $tr .= '<td>' . $row[$column[0]] . '</td>';
            }
            $totalClicks += $row['clicks'];
            $totalConversions += $row['conversions'];
            $totalRevenues += $row['revenues'];
            $tr.= '<td>' . $row['clicks'] . '</td>';
            $tr.= '<td>' . $row['conversions'] . '</td>';
            $tr.= '<td>' . $row['revenues'] . '</td>';
            $table .= $tr . '</tr>';
        }
        $table .= '<tr><td class="bg-primary"><strong>Totals</strong></td>';

        for ($i = 0; $i < (sizeof($columns) - 1); $i++)
            $table .= '<td>-</td>';
        $table .= '<td><strong>' . $totalClicks . '</strong></td><td><strong>' . $totalConversions . '</strong></td><td><strong>' . number_format($totalRevenues, 2) . '</strong></td></tr>';
        $table .= '</tbody></table>
                </div>
            </div>
        </div>';
        return $table;
    }

    private function genGeneralTable($id, $arr, $arr2, $name, $OPERATORUSER = false) {
        $tablexs = $OPERATORUSER ? 2 : 3;
        $table = '
            <div class="container well" id="' . $id . '">
                <div class="table-responsive" ><table class="table table-bordered centertext" ><thead>
                    <tr>
                      <th style="vertical-align: middle;" rowspan="2" class="col-xs-1 centertext">' . $name . '</th>
                      <th class="col-xs-' . $tablexs . ' centertext" rowspan="1" colspan="3">Clicks</th>'
                . ($OPERATORUSER ? '<th class="col-xs-' . $tablexs . ' centertext" rowspan="1" colspan="3">Conversions</th>' : '') .
                '<th class="col-xs-' . $tablexs . ' centertext" rowspan="1" colspan="3">Revenue</th>
                      <th class="col-xs-' . $tablexs . ' centertext" rowspan="1" colspan="3">EPC</th>
                    </tr>
                    <tr>
                      <td class="col-xs-1"><strong>Previous Period</strong></td><td class="col-xs-1"><strong>Current Period</strong></td><td class="col-xs-1"><strong>Variation</strong></td>'
                . ($OPERATORUSER ? '<td class="col-xs-1"><strong>Previous Period</strong></td><td class="col-xs-1"><strong>Current Period</strong></td><td class="col-xs-1"><strong>Variation</strong></td>' : '') .
                '
                      <td class="col-xs-1"><strong>Previous Period</strong></td><td class="col-xs-1"><strong>Current Period</strong></td><td class="col-xs-1"><strong>Variation</strong></td>
                      <td class="col-xs-1"><strong>Previous Period</strong></td><td class="col-xs-1"><strong>Current Period</strong></td><td class="col-xs-1"><strong>Variation</strong></td>
                    </tr>
                        </thead><tbody>';
        $onlyid = ( $id == 'countrytable' || $id == 'operatortable' || $id == 'campaigntable');
        $totals = ( $id == 'countrytable' || $id == 'aggregatortable' || $id == 'sourcetable2');
        if ($totals) {
            $totalclicks1 = 0;
            $totalrevenue1 = 0;
            $totalclicks2 = 0;
            $totalrevenue2 = 0;
            if ($OPERATORUSER) {
                $totalconversions1 = 0;
                $totalconversions2 = 0;
            }
        }
        $j = 0;
        foreach ($arr2 as $row2) {
            $j++;
            if ($j > 12 && $name === 'Source')
                break;
            foreach ($arr as $key2 => $row) {
                if ($row['name'] == $row2['name']) {
                    $clicksVariation = (($row['clicks'] == '' || $row['clicks'] == 0) ? '0' : (($row2['clicks'] - $row['clicks']) / $row['clicks']) * 100);
                    $class1 = $clicksVariation > 0 ? 'text-success' : ($clicksVariation < 0 ? 'text-danger' : '');
                    if ($OPERATORUSER) {
                        $conversionsVariation = (($row['conversions'] == '' || $row['conversions'] == 0) ? '0' : (($row2['conversions'] - $row['conversions']) / $row['conversions']) * 100);
                        $class4 = $conversionsVariation > 0 ? 'text-success' : ($conversionsVariation < 0 ? 'text-danger' : '');
                    }
                    $revenuesVariation = (($row['revenues'] == '' || $row['revenues'] == 0) ? '0' : (($row2['revenues'] - $row['revenues']) / $row['revenues']) * 100);
                    $class2 = $revenuesVariation > 0 ? 'text-success' : ($revenuesVariation < 0 ? 'text-danger' : '');
                    $epcVariation = (($row['epc'] == '' || $row['epc'] == 0) ? 0 : (($row2['epc'] - $row['epc']) / $row['epc']) * 100);
                    $class3 = $epcVariation === 0 ? '' : ($epcVariation > 0.00 ? 'text-success' : 'text-danger' );
                    $table .= '<tr ' . ($id != 'operatortable' && $id != 'sourcetable2' ? 'class="goto" attrID="' . $row['id'] . '"' : '') . '><td>' . '<strong>' . $row['name'] . '</strong>' . ( $onlyid ? '' : '-' . $row['id'] ) . '</td>'
                            . '<td>' . $row['clicks'] . '</td><td>' . $row2['clicks'] . '</td><td class="' . $class1 . '">' . ($class1 != '' ? '<strong>' : '') . number_format($clicksVariation, 0, ',', '') . '&#37;' . ($class1 != '' ? '</strong>' : '') . '</td>'
                            . ($OPERATORUSER ? ('<td>' . $row['conversions'] . '</td><td>' . $row2['conversions'] . '</td><td class="' . $class4 . '">' . ($class4 != '' ? '<strong>' : '') . number_format($conversionsVariation, 0, ',', '') . '&#37;' . ($class4 != '' ? '</strong>' : '') . '</td>') : '')
                            . '<td>' . $row['revenues'] . '</td><td>' . $row2['revenues'] . '</td><td class="' . $class2 . '">' . ($class2 != '' ? '<strong>' : '') . number_format($revenuesVariation, 0, ',', '') . '&#37;' . ($class2 != '' ? '</strong>' : '') . '</td>'
                            . '<td>' . $row['epc'] . '</td><td>' . $row2['epc'] . '</td><td class="' . $class3 . '">' . ($class3 != '' ? '<strong>' : '') . number_format($epcVariation, 0, ',', '') . '&#37;' . ($class3 != '' ? '</strong>' : '') . '</td>';
                    if ($totals) {
                        $totalclicks1 += $row['clicks'];
                        $totalrevenue1 += $row['revenues'];
                        $totalclicks2 += $row2['clicks'];
                        $totalrevenue2 += $row2['revenues'];
                        if (isset($totalconversions1)) {
                            $totalconversions1 += $row['conversions'];
                            $totalconversions2 += $row2['conversions'];
                        }
                    }
                    unset($arr[$key2]);
                    continue 2;
                }
            }
            $table .= '<tr ' . ($id != 'operatortable' && $id != 'sourcetable2' ? 'class="goto" attrID="' . $row2['id'] . '"' : '') . '><td><strong>' . $row2['name'] . '</strong>' . ( $onlyid ? '' : '-' . $row2['id'] ) . '</td>'
                    . '<td>-</td><td>' . $row2['clicks'] . '</td><td>-</td>'
                    . ($OPERATORUSER ? ('<td>-</td><td>' . $row2['conversions'] . '</td><td>-</td>') : '')
                    . '<td>-</td><td>' . $row2['revenues'] . '</td><td>-</td>'
                    . '<td></td><td>' . $row2['epc'] . '</td><td>-</td></tr>';
            if ($totals) {
                $totalclicks2 += $row2['clicks'];
                $totalrevenue2 += $row2['revenues'];
                if (isset($totalconversions2))
                    $totalconversions2+= $row2['conversions'];
            }
        }

        if ($name != 'Source') {
            foreach ($arr as $row) {//missing rows
                if (!empty($row)) {
                    $table .= '<tr ' . ($id != 'operatortable' && $id != 'sourcetable2' ? 'class="goto" attrID="' . $row['id'] . '"' : '') . '><td><strong>' . $row['name'] . '</strong>' . ( $onlyid ? '' : '-' . $row['id'] ) . '</td><td>' . $row['clicks'] . '</td><td>-</td><td>-</td>'
                            . ($OPERATORUSER ? ('<td>' . $row['conversions'] . '</td><td>-</td><td>-</td>') : '')
                            . '<td>' . $row['revenues'] . '</td><td>-</td><td>-</td>'
                            . '<td>' . $row['epc'] . '</td><td>-</td><td>-</td></tr>';
                    if ($totals) {
                        $totalclicks1 += $row['clicks'];
                        $totalrevenue1 += $row['revenues'];
                    }
                }
            }
        }
        if ($totals) {
            $totalepc1 = $totalclicks1 === 0 ? 0 : $totalrevenue1 / $totalclicks1;
            $totalepc2 = $totalclicks2 === 0 ? 0 : $totalrevenue2 / $totalclicks2;
            $clicksVariation = (($totalclicks1 == '' || $totalclicks1 == 0) ? '0' : number_format((($totalclicks2 - $totalclicks1) / $totalclicks1) * 100, 0, ',', ''));
            $class1 = $clicksVariation > 0 ? 'text-success' : ($clicksVariation < 0 ? 'text-danger' : '');

            if ($OPERATORUSER) {
                $conversionsVariation = (($totalconversions1 == '' || $totalconversions1 == 0) ? '0' : number_format((($totalconversions2 - $totalconversions1) / $totalconversions1) * 100, 0, ',', ''));
                $class4 = $conversionsVariation > 0 ? 'text-success' : ($conversionsVariation < 0 ? 'text-danger' : '');
            }

            $revenuesVariation = (($totalrevenue1 == '' || $totalrevenue1 == 0) ? '0' : number_format((($totalrevenue2 - $totalrevenue1) / $totalrevenue1) * 100, 0, ',', ''));
            $class2 = $revenuesVariation > 0 ? 'text-success' : ($revenuesVariation < 0 ? 'text-danger' : '');
            $epcVariation = ($totalepc1 === 0 ? 0 : number_format((($totalepc2 - $totalepc1) / $totalepc1) * 100, 2, ',', ''));
            $class3 = ($totalepc1) == 0 ? '' : ((($totalepc2 - $totalepc1) / $totalepc1) > 0.0001 ? 'text-success' : 'text-danger' );

            $totalepc1 = number_format($totalepc1, 4, ',', '');
            $totalepc2 = number_format($totalepc2, 4, ',', '');

            $table .= '<tr><td class="bg-primary"><strong>Totals</strong></td><td><strong>' . $totalclicks1 . '</strong></td><td><strong>' . $totalclicks2 . '</strong></td><td class="' . $class1 . '"><strong>' . $clicksVariation . '&#37;</strong></td>'
                    . ($OPERATORUSER ? ('<td><strong>' . $totalconversions1 . '</strong></td><td><strong>' . $totalconversions2 . '</strong></td><td class="' . $class4 . '"><strong>' . $conversionsVariation . '&#37;</strong></td>') : '')
                    . '<td><strong>' . $totalrevenue1 . '</strong></td><td><strong>' . $totalrevenue2 . '</strong></td><td class="' . $class2 . '"><strong>' . $revenuesVariation . '&#37;</strong></td>'
                    . '<td><strong>' . $totalepc1 . '</strong></td><td><strong>' . $totalepc2 . '</strong></td ><td class="' . $class3 . '"><strong>' . $epcVariation . '&#37;</strong></td></tr>';
        }
        $table .= '</tbody></table>
                </div>
            </div>';
        return $table;
    }

    private function main_report_table($sdate, $edate, $param, $country) {



        if ($this->request->get('testing') != null) {
            $logArray = $this->login_access('m.campaign_country', 'm.source', 'm.agregator', 0);
            $rep2 = $this->reportObject2;
            $final = $rep2->getNewMainReportResult($sdate, $edate, $country, $logArray[0], $logArray[2]);
            $countriestotals = $rep2->getcountriestotals($sdate, $edate, $country, $logArray[0], $logArray[2]);
            return array($final, $countriestotals);
        }
        $dayArray = array();
        $report = new ReportMB();

        if ($sdate == date('Y-m-d') or $edate == date('Y-m-d')) {
            $logArray = $this->login_access('countryCode', 'fkAgregator', 'fkSource', 0);
            $dayArray = $report->getDailyResult($param, $country, $logArray[0], $logArray[2]);
        }

        $farray = array();
        if ($sdate != date('Y-m-d')) {
            //Agr Tables
            $filter = ' AND conversions!=0 ';
            if ($param == 'LEFT') {
                $filter = '';
            }
            $logArray = $this->login_access('campaign_country', 'source', 'agregator', 0);
            $farray = $report->getMainResult($sdate, $edate, $filter, $country, $logArray[0], $logArray[2]);
        }
        $specificData = array_merge($farray, $dayArray);
        $final = array();
        foreach ($specificData as $row) {
            $bol = 0;
            foreach ($final as $key => $value) {//verificar que o array final ja tem este pais.
                //ja contem, soma-se
                if ($final[$key]['c_hash'] == $row['c_hash'] and $final[$key]['campaign_country'] == $row['campaign_country'] and
                        $final[$key]['agregator'] == $row['agregator'] and $final[$key]['source'] == $row['source'] and $final[$key]['sub_id'] == $row['sub_id']) {
                    $final[$key]['clicks'] = $value['clicks'] + $row['clicks'];
                    $final[$key]['conversions'] = $value['conversions'] + $row['conversions'];
                    $final[$key]['revenue'] = $value['revenue'] + $row['revenue'];
                    //actualizado!
                    $bol = 1;
                    break;
                }
            }
            //foi adicionado
            if ($bol == 1) {
                continue;
            }
            //nao continha, adiciona-se row
            $final[] = $row;
        }
        $this->sortBySubkey($final, 'campaign');
        //print_r($final);
        $countryFinal = array();
        foreach ($specificData as $row) {
            $bol = 0;
            foreach ($countryFinal as $key => $value) {//verificar que o array final ja tem este pais.
                //ja contem, soma-se
                if ($countryFinal[$key]['campaign_country'] == $row['campaign_country']) {
                    $countryFinal[$key]['clicks'] = $value['clicks'] + $row['clicks'];
                    $countryFinal[$key]['conversions'] = $value['conversions'] + $row['conversions'];
                    $countryFinal[$key]['revenue'] = $value['revenue'] + $row['revenue'];
                    //actualizado!
                    $bol = 1;
                    break;
                }
            }
            //foi adicionado
            if ($bol == 1) {
                continue;
            }
            //nao continha, adiciona-se row
            $countryFinal[] = $row;
        }
        $this->sortBySubkey($countryFinal, 'campaign_country');
        //return $final;
        return array($final, $countryFinal);
    }

    private function main_report_table_rbb($sdate, $edate, $param, $country) {



        $dayArray = array();
        $report = new ReportMB();

        if ($sdate == date('Y-m-d') or $edate == date('Y-m-d')) {
            $countryAndSources = $this->login_access('countryCode', 'fkAgregator', 'fkSource', 0);
            $dayArray = $report->getDailyResult_rbb($param, $country, $countryAndSources[0], $countryAndSources[2]);
        }

        $farray = array();
        if ($sdate != date('Y-m-d')) {
            //Agr Tables
            $filter = ' AND conversions!=0 ';
            if ($param == 'LEFT') {
                $filter = '';
            }
            $countryAndSources = $this->login_access('campaign_country', 'agregator', 'source', 0);
            $farray = $report->getMainResult($sdate, $edate, $filter, $country, $countryAndSources[0], $countryAndSources[2]);
        }
        $specificData = array_merge($farray, $dayArray);
        $result = $specificData;
        return $result;
    }

    private function mainreportexcel($sdate, $edate, $country = null) {

        $countryAndSources = $this->login_access('m.campaign_country', 'm.agregator', 'm.source', 0);

        $result = $this->reportObject2->getMainReportResult($sdate, $edate, $country, $countryAndSources[0], $countryAndSources[2]);

        return $result;
    }

    private function op_report_table($sdate, $edate, $param, $country) {
        $logArray = $this->login_access('countryCode', 'fkAgregator', 'fkSource', 0);

        $dayArray = array();
        $report = new ReportMB();
        if ($sdate == date('Y-m-d') or $edate == date('Y-m-d')) {

            $dayArray = $report->getOpDailyResult($param, $country, $logArray[0], $logArray[2]);
        }

        $farray = array();
        if ($sdate != date('Y-m-d')) {
            //Agr Tables
            $filter = ' AND conversions!=0 ';
            if ($param == 'LEFT') {
                $filter = '';
            }
            $logArray = $this->login_access('campaign_country', 'agregator', 'source', $logArray[3]);
            $farray = $report->getOpMainResult($sdate, $edate, $filter, $country, $logArray[0], $logArray[1], $logArray[4], $logArray[2]);
        }

        $result = array_merge($farray, $dayArray);
        return $result;
    }

    private function report_country_table($sdate, $edate, $param, $country) {

        $logArray = $this->login_access('countryCode', 'fkAgregator', 'fkSource', 0);

        $dayPerCountryArray = array();
        $report = new ReportMB();
        if ($sdate == date('Y-m-d') or $edate == date('Y-m-d')) {

            $dayPerCountryArray = $report->getDailyResAggPerCountry($param, $country, $logArray[0], $logArray[2]);
        }

        $historicalDayPerCountryArray = array();
        if ($sdate != date('Y-m-d')) {
            //Agr Tables
            $filter = ' AND conversions!=0 ';
            if ($param == 'LEFT') {
                $filter = '';
            }
            $logArray = $this->login_access('countryCode', 'fkAgregator', 'fkSource', $logArray[3]);
            $historicalDayPerCountryArray = $report->getHistoricalPerCountryResult($sdate, $edate, $filter, $country, $logArray[0], $logArray[2]);
        }
        $result = array_merge($dayPerCountryArray, $historicalDayPerCountryArray);

        $final = array();
        foreach ($result as $row) {
            $bol = 0;
            foreach ($final as $key => $value) {//verificar que o array final ja tem este pais.
                //ja contem, soma-se
                if ($final[$key]['Country'] == $row['Country']) {
                    $final[$key]['Clicks'] = $value['Clicks'] + $row['Clicks'];
                    $final[$key]['Conversions'] = $value['Conversions'] + $row['Conversions'];
                    $final[$key]['Revenues'] = $value['Revenues'] + $row['Revenues'];
                    //actualizado!
                    $bol = 1;
                    break;
                }
            }
            //foi adicionado
            if ($bol == 1) {
                continue;
            }
            //nao continha, adiciona-se row
            $final[] = $row;
        }
        $this->sortBySubkey($final, 'Country');
        return $final;
    }

    private function sortBySubkey(&$array, $subkey, $sortType = SORT_ASC) {
        if (sizeof($array) < 1)
            return;
        foreach ($array as $subarray) {
            $keys[] = $subarray[$subkey];
        }
        array_multisort($keys, $sortType, $array);
    }

    private function mj_report_table($sdate, $edate, $sources, $country) {
        $auth = $this->session->get('auth');

        $report = new ReportMB();
        return $report->getMjResult($auth, $sdate, $edate, $sources, $country);
    }

    private function op_excel2($result) {

        ini_set("memory_limit", "4096M");
        set_time_limit(0);
        $temp = tmpfile();
        fwrite($temp, "sep=;\nDate;Sub id;Campaign Hash;Campaign;Campaign Country;User Country;Agregator;Source;Operator;Isp;Os;Browser;Mobile Type;Clicks;Conversions;Revenue;EPC;CR;Ad\n");
        foreach ($result as &$row) {
            fwrite($temp, $row['insert_date'] . ';' . $row['sub_id'] . ';' . $row['c_hash'] . ';' . $row['campaign'] . ';' . $row['campaign_country'] . ';'
                    . $row['user_country'] . ';' . $row['agregator'] . ';' . $row['agregatorName'] . ';' . $row['source'] . ';' . $row['operator'] . ';'
                    . $row['isp'] . ';' . $row['os'] . ';' . $row['browser'] . ';' . $row['mobilet'] . ';' . number_format(floatval($row['clicks']), 0, '', '') . ';' . number_format(floatval($row['conversions']), 0, '', '') . ';' . number_format(floatval($row['revenue']), 2, '.', '') . ';'
                    . ($row['clicks'] != 0 ? number_format(floatval($row['revenue'] / $row['clicks']), 2, '.', '') . ';' . number_format(floatval($row['conversions'] / $row['clicks']), 2, '.', '') : '0;0') . ';' . $row['ad'] . "\n");
        }
        fseek($temp, 0);
        while (($buffer = fgets($temp, 4096)) !== false) {
            echo $buffer;
        }
        fclose($temp);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="MobisteinFastReport' . time() . '.csv"';
        header($myContentDispositionHeader);
        header('Expires: 0');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        exit;
    }

    private function mj_excel($result) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("mobistein");
        $objPHPExcel->getProperties()->setLastModifiedBy('mobistein');
        $objPHPExcel->getProperties()->setTitle('MjumpFastReport');
        $objPHPExcel->getProperties()->setSubject('MjumpFastReport');
        $objPHPExcel->getProperties()->setDescription('Auto-generated Report');
        $alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $startRow = 1;

        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[0] . $startRow, 'Date');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, 'LP name');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, 'Page');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, 'Ad');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[4] . $startRow, 'LP clicks');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[5] . $startRow, 'Clicks');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[6] . $startRow, 'Conversions');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[7] . $startRow, 'Revenue');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[8] . $startRow, 'Subid');


        foreach ($result as &$row) {
            $startRow++;
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[0] . $startRow, $row['insertDate']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, $row['lpName']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, $row['page']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, $row['ad']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[4] . $startRow, $row['lpClicks']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[5] . $startRow, $row['clicks']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[6] . $startRow, $row['conversions']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[7] . $startRow, $row['revenue']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[8] . $startRow, $row['subid']);
        }
        header('Content-Type: application/vnd.ms-excel');
        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="MobisteinMJumpFastReport' . time() . '.xls"';
        header($myContentDispositionHeader);
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit();
    }

    private function login_access($countryfield, $agregatorfield, $sourcefield, $permissions = 0) {

        $auth = $this->session->get('auth');

        $permission = new Permission();
        if ($permissions == 0)
            $permissions = $permission->get_permissions($auth);
        $pstring = $permission->permission_string($permissions, $auth, $countryfield, $sourcefield, $agregatorfield);

        /* echo '<pre>';
          print_r($pstring);
          echo '<pre>'; */
        $aff = null; // Ric and Commercials
        if ($auth['userlevel'] == 2 && $auth['utype'] == 0) {//CM's Ivo Pedro
            $aff = 0;
        } else if ($auth['utype'] == 1) { // Affiliate manager
            $sources = $auth['affiliates'];
            $aff = 1;
        } else if ($auth['userlevel'] == 2 && $auth['utype'] == 2) { // mainstream CM's  like Arrojado
            $aff = 2;
        } else if ($auth['userlevel'] == 1 && ($auth['id'] == 3)) { // hello I'm Guille and Facha and I'm different
            $aff = 3;
        } else if ($auth['userlevel'] == 1 && ($auth['id'] == 2)) { // hello I'm Guille and Facha and I'm different,
            $aff = 4;
        }
        $larray = array($pstring[0], $auth['id'], $aff, $permissions, $pstring[1], $pstring[2], $pstring[3]);
        /* echo '<pre>';
          print_r($larray);
          echo '<pre>'; */
        //exit();
        return $larray;
    }

    private function generate_main_table($result, $sdate, $edate, $currentDay, $country) {
        $countryAndSources = $this->login_access('a', 'b', 'c', 0);
        $table_html = '<table class="table table-striped table-condensed"><thead>
        <tr>
          <th style="color:#909090;">Campaign</th>
          <th style="color:#909090;">Country</th>
          <th style="color:#909090;">Agregator</th>
          <th style="color:#909090;">Sub id</th>
          <th style="color:#909090;">Clicks</th>
          <th style="color:#909090;">Conv</th>
          <th style="color:#909090;">Revenue</th>
          <th style="color:#909090;">EPC</th>
          <th style="color:#909090;">CR</th>
        </tr>
      </thead><tbody>';
        $revtotal = 0;
        $clicktotal = 0;
        $convtotal = 0;
        if (!empty($result)) {
            foreach ($result as &$row) {

                $table_html.='<tr><th style="color: black;">' . $row['campaign'] . '</th><th>' . $row['campaign_country'] . '</th>'
                        . '<th>' . $row['agregator'] . '</th>'
                        . '<th>' . $row['sub_id'] . '</th>'
                        . '<th>' . $row['clicks'] . '</th>'
                        . '<th >' . $row['conversions'] . '</th>'
                        . '<th style="color: black;">' . (float) number_format(floatval($row['revenue']), 2) . '</th>'
                        . '<th>' . ($row['clicks'] == 0 ? '0.000' : number_format($row['revenue'] / $row['clicks'], 3)) . '</th>'
                        . '<th>' . ($row['clicks'] == 0 ? '0.00' : number_format($row['conversions'] / $row['clicks'] * 100, 2) ) . '%</th></tr>';
                $revtotal+=$row['revenue'];
                $clicktotal+= $row['clicks'];
                $convtotal+= $row['conversions'];
            }
        }

        $totals = $this->generate_day_total_table($sdate, $edate, $country);
        $revenueColumn = number_format($totals[2], 2);

        if ($currentDay) {
            $avgRes = $this->generate_avg_table(3, $country);
            $revenueColumn = number_format($totals[2], 2) . '&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;' . ($totals[2] && $totals[2] > 0 ? number_format((($totals[2] - $avgRes[0]['avgRev']) / $totals[2]) * 100, 3) : '0.000') . '&#37;';
            $avgTable = '<tr id="pastAvg"><th id="firstColumnAvg" style="color: black; font-weight:normal; font-size:90%"> Last 3 days from 00:00 to ' . date("H") . ':' . ((isset($country) || isset($countryAndSources[4])) ? '00' : ((floor((date("i") * 4) / 60) * 15) == 0 ? '00' : (floor((date("i") * 4) / 60) * 15) )) . ':00' .
                    '</th><th style="color: black; font-weight:normal; font-size:90%">' . number_format($avgRes[0]['avgClicks'], 0, '.', '') .
                    '</th><th style="color: black; font-weight:normal; font-size:90%">' . number_format($avgRes[0]['avgCon'], 0, '.', '') .
                    '</th><th style="color: black; font-weight:normal; font-size:90%">' . number_format($avgRes[0]['avgRev'], 2) .
                    '</th><th style="color: black; font-weight:normal; font-size:90%">' . number_format($avgRes[0]['avgEPC'], 3) . '</th>
                        <th style="color: black; font-weight:normal; font-size:90%">' . number_format($avgRes[0]['avgCR'] * 100, 2) . '</th></tr>';
        }


        $total_html = '<table class="table table-striped"><thead>
        <tr>
          <th style="color:#909090;">Period</th>
          <th style="color:#909090;">Clicks</th>
          <th style="color:#909090;">Conversions</th>
          <th style="color:#909090;">Revenue</th>
          <th style="color:#909090;">EPC</th>
          <th style="color:#909090;">CR</th>
        </tr></thead>
        <tbody>
        <tr><th>Selected Period </th><th>' . $totals[0] . '</th><th>' . $totals[1] . '</th><th id="revVar" >' . $revenueColumn . '</th><th>' . ($totals[0] == 0 ? '0' : number_format($totals[2] / $totals[0], 3)) . '</th><th>' . ($totals[0] == 0 ? '0' : number_format($totals[1] / $totals[0] * 100, 2)) . '</th></tr>';
        if ($currentDay) {
            $total_html .= $avgTable . '</tbody></table>' . '<label>Select days</label><select id="lastXdays"><option value="1">last day</option>'
                    . '<option value="2">Last 2 days</option><option value="3" selected="true">Last 3 days</option>'
                    . '<option value="4">Last 4 days</option><option value="5">Last 5 days</option>'
                    . '<option value="6">Last 6 days</option><option value="7">Last 7 days</option><option value="10">Last 10 days</option><option value="15">Last 15 days</option></select>';
        } else {
            $total_html .= '</tbody></table>';
        }
        $table_html.='</tbody></table>';
        $arr = array();
        $arr[0] = $total_html;
        $arr[1] = $table_html;
        return $arr;
    }

    private function generate_countrydata_table($result) {
        $table_html = '<table class="table table-striped table-condensed"><thead>
            <tr>
             <th style="color:#909090;">Country</th>
              <th style="color:#909090;">Clicks</th>
              <th style="color:#909090;">Conversions</th>
              <th style="color:#909090;">Revenue</th>
              <th style="color:#909090;">EPC</th>
              <th style="color:#909090;">CR</th>
            </tr>
          </thead><tbody>';

        foreach ($result as &$row) {

            $table_html.='<tr><th style="color: black;">' . $row['campaign_country'] . '</th><th>' . $row['clicks'] . '</th>'
                    . '<th >' . $row['conversions'] . '</th>'
                    . '<th id="curRevID" style="color: black;">' . number_format($row['revenue'], 2) . '</th>'
                    . '<th>' . ($row['clicks'] == 0 ? '0.000' : number_format($row['revenue'] / $row['clicks'], 3) ) . '</th>'
                    . '<th>' . ($row['clicks'] == 0 ? '0.00' : number_format($row['conversions'] / $row['clicks'] * 100, 2) ) . '%</th></tr>';
        }
        $table_html.='</tbody></table>';
        return $table_html;
    }

    public function getCarrierAction() {
        if ($this->request->get('country') != null && $this->request->get('country') != 'allcampaigns') {
            $operation = new Operation();
            $res = $operation->getOperators2($this->request->get('country'));
            $finalres = '<option value="allops">All operators</option>';
            foreach ($res as $row) {
                $finalres .= ('<option value="' . $row['operator'] . '">' . $row['operator'] . '</option>');
            }
            echo $finalres;
        }
    }

    public function getCarrier2Action() {
        if ($this->request->get('countriescarriers') != null) {
            echo 'ok';

            $operation = new Operation();
            $arr = explode(',', $this->request->get('countriescarriers'));
            $countries = '';
            foreach ($arr as $val) {
                if (isset($this->country_array[strtoupper($val)]))
                    $countries.='"' . $this->country_array[strtoupper($val)] . '",';
                else
                    return '';
            }
            $countries = rtrim($countries, ',');
            $res = $operation->getOperators2($countries);
            $finalres = '<option value="allops">All operators</option>';
            foreach ($res as $row) {
                $finalres .= ('<option value="' . $row['operator'] . '">' . $row['operator'] . '</option>');
            }
            echo $finalres;
        }
    }

    public function getavgdataAction() {
        if ($this->request->get('day') != null) {
            $country = $this->getParams($this->request->get('country'), 'country');
            $res = $this->generate_avg_table($this->request->get('day'), $country);
            $final = '';
            foreach ($res as $row) {
                $final .= ('Last ' . $this->request->get('day') . ' days from 00:00 to ' . date("H") . ':00:00') . '~' . number_format($row[0], 0) . '~' . number_format($row[1], 0) . '~' . number_format($row[2], 2) . '~' . number_format($row[3], 3) . '~' . number_format($row[4] * 100, 2);
            }
            echo $final;
        }
    }

    private function generate_avg_table($day, $country) {
        $logArray = $this->login_access('m.country', 'm.agregtor', 'sourceid', 0);

        date_default_timezone_set('Europe/Lisbon');
        $report = new ReportMB();
        $sdate = date("Y-m-d", strtotime(date("Y-m-d") . '-' . $day . ' days'));
        //if($this->reportObject2 == $this->reportObject3){
        if ($this->request->get('testing') != null) {
            return $this->reportObject3->getLastDaysAvg2($sdate, $country, $logArray[0], $logArray[2]);
        }
        //return $arr;
        //}
        $arr = $report->getLastDaysAvg($sdate, $country, $logArray[0], $logArray[2]);
        return $arr;
    }

    private function generate_day_total_table($sdate, $edate, $country) {
        date_default_timezone_set('Europe/Lisbon');

        $report = new ReportMB();
        if ($this->request->get('testing') != null) {
            $rep2 = $this->reportObject3;
            $logArray = $this->login_access('m.country', 'm.agregator', 'sourceid', 0);
            if ($sdate == date('Y-m-d') && $edate == date('Y-m-d')) {
                return $rep2->getDayTotal2($country, $logArray[0], $logArray[2]);
            } else {

                return $rep2->getDaysTotal2($sdate, $edate, $country, $logArray[0], $logArray[2]);
            }
        }
        $logArray = $this->login_access('m.country', 'm.agregator', 'sourceid', 0);
        if ($sdate == date('Y-m-d') && $edate == date('Y-m-d')) {
            $logArray = $this->login_access('country', 'fkAgregator', 'fkSource', 0);
            return $report->getDayTotal($country, $logArray[0], $logArray[2]);
        }
        $logArray = $this->login_access('campaign_country', 'agregator', 'source', 0);
        return $report->getDaysTotal($sdate, $edate, $country, $logArray[0], $logArray[2]);
    }

    public function networkAction() {

        ini_set("memory_limit", "2048M");
        set_time_limit(0);
        $countriesAndSources = $this->login_access('a', 'b', 'c');


        $netArray = array('35' => array('cmp,tracking', 'PubIds', 0), '37' => array('plugId,plugsource,plugbrowser,tracking', 'PlugPubIds', 0),
            '59' => array('category,tracking', 'TFactoryPubIds', 0), '70' => array('site,channel,keyword,vos,tracking', 'TForcePubIds', 0),
            '81' => array('zone,tracking', 'TAdcashPubIds', 0), '61' => array('site,category,keyword,tracking', 'TPopadsPubIds', 0),
            '38' => array('zone,tracking', 'TAdexpPubIds', 0), '5' => array('zone,ad,tracking', 'TAdultPubIds', 0),
            '23' => array('siteid,host,tracking', 'TExoPubIds', 0), '91' => array('siteid,host,tracking', 'TExoPubIds', 0),
            '96' => array('subid,tracking', 'TGunggoPubIds', 0), '40' => array('subid,tracking', 'TWebTrafficPubIds', 0),
            '95' => array('subid,tracking', 'TAdamoadsPubIds', 0), '100' => array('subid,tracking', 'TterraPubIds', 0),
            '103' => array('subid,tracking', 'TmglobePubIds', 0), '98' => array('subid,tracking', 'TthuntPubIds', 0),
            '1' => array('subid,cid,tracking', 'TBuzzPubIds', 0), '111' => array('subid,tracking', 'TMobadgePubIds', 0),
            '114' => array('source,tracking', 'TPolluxPubIds', 0), '116' => array('zoneid,tracking', 'TFogzyPubIds', 0),
            '51' => array('site,cat,os,tracking', 'TPopCashPubIds', 0), '127' => array('zone,tracking', 'TMediaHubPubIds', 0),
            '56' => array('src,oid,tracking', 'TTrafficShopPubIds', 0), '94' => array('account,site,tracking', 'TPopundernetPubIds', 0),
            '47' => array('site,spot,location,tracking', 'TTrafficJunkiePubIds', 1), '134' => array('pubid,subid,tracking', 'TAvazuPubIds', 0),
            '134' => array('pubid,subid,tracking', 'TAvazuPubIds', 0), '107' => array('pub_id,tracking', 'TMobusiPubIds', 0),
            '148' => array('zoneid,tracking', 'TClickaduPubIds', 0), '153' => array('zid,tracking', 'TTrafficpenPubIds', 0),
            '154' => array('subid,pubid,tracking', 'TMobusiAdnetworkPubIds', 0), '60' => array('src,campaign,tracking', 'THolderPubIds', 0), '165' => array('site,tracking', 'TAdsflyPubIds', 0),
            '166' => array('siteid,host,tracking', 'TExoPubIds2', 0), '167' => array('site,channel,keyword,vos,tracking', 'TForcePubIds2', 0),
            '168' => array('category,tracking', 'TFactoryPubIds2', 0), '169' => array('pub_id,tracking', 'TMobusiPubIds2', 0), '175' => array('p,tracking', 'THeadwayPubIds', 0),
            '187' => array('tid,tracking', 'TAdeaglemediaPubIds', 0), '211' => array('siteid,tracking', 'TDntxPubIds', 0), '150' => array('pubid,zoneid,bannerid,tracking', 'TClickPapaPubIds', 0), '212' => array('siteid,tracking', 'TPopMyAdsPubIds', 0), '209' => array('pubid,domain,tracking', 'TSelfServePlatformPubIds', 0),
            '174' => array('campaignid,creativeid,siteid,format,category,tracking', 'TTrafficstarsPubIds2', 0),
            '83' => array('zoneid,tracking', 'TPropelleradsPubIds', 0), '322' => array('vserv,tracking', 'TVservPubIds', 0),
            '222' => array('siteid,ban_id,domain_id,tracking', 'TBravoMediaPubIds', 0), '251' => array('subid,tracking', 'TMobusiMainstream', 0),
            '173' => array('source,tracking', 'TOperaMediaWorksPubIds', 0), '246' => array('zoneid,tracking', 'THilltopadsPubIds', 0),
            '229' => array('pub_id,tracking', 'TMobusiAffPubIds', 0), '354' => array('site,tracking', 'TUngadsPubIds', 0),
            '362' => array('siteid,host,tracking', 'Pubids__ExoDating', 0), '364' => array('campaignid,creativeid,siteid,format,tracking', 'Pubids__TrafficStarsDating', 0),
            '365' => array('site,spot,location,tracking', 'Pubids__TrafficJunkieDating', 1), '367' => array('pubid,subid,tracking', 'Pubids__MobusiDating', 0),
            '368' => array('plugId,plugsource,plugbrowser,tracking', 'Pubids__PlugDating', 0), '369' => array('zoneid,tracking', 'Pubids__PropellerDating', 0),
            '370' => array('site,category,keyword,tracking', 'Pubids__PopAdsDating', 0), '382' => array('site,category,keyword,tracking', 'TPopads2PubIds', 0),
            '385' => array('zoneid,tracking', 'TPropellerads2PubIds', 0), '386' => array('siteid,host,tracking', 'Pubids__Exoclick2', 0),
            '289' => array('pubid,tracking', 'TReachPubIds', 0), '346' => array('subid,tracking', 'TwwwpromoterPubIds', 0));
        //filtrar sources do user
        if (isset($countriesAndSources[6])) {
            $arr = $countriesAndSources[6];
            foreach ($netArray as $k => $v) {
                if (!in_array($k, $arr)) {
                    unset($netArray[$k]);
                }
            }
        }
        $source = $this->request->get('so');
        if (!array_key_exists($source, $netArray))
            return;
        $startDate = $this->request->get('s');

        $endDate = $this->request->get('e');
        $convTableMonth = '';
        if (date('Y-m-d') == date('Y-m-d', strtotime($endDate))) {
            $convTableMonth = date('mY', strtotime($endDate));
        }



        $countryarr = $this->request->get('cc');
        $country = null;
        if ($countryarr != null && is_array($countryarr)) {
            $country = '';
            foreach ($countryarr as $val) {
                if (isset($this->country_array[strtoupper($val)]) && $val != 'ALL') {
                    $country .= '"' . $this->country_array[strtoupper($val)] . '",';
                } else {
                    $country = null;
                    break;
                }
            }
            $country = !empty($country) ? rtrim($country, ',') : null;
        } else if ($countryarr != null && $countryarr != 'ALL') {
            if (isset($this->country_array[strtoupper($countryarr)])) {
                $country .= '"' . $this->country_array[strtoupper($countryarr)] . '"';
            } else {
                $country = null;
            }
        } else {
            $country = null;
        }

        $report = new Report2MB();
        $res = $report->getNetworkResult($startDate, $endDate, $convTableMonth, $source, $country, $countriesAndSources[5], $netArray);
        $a = array(0 => array('Date', 's', 0), 1 => array('TotalClick', 'i', 0), 2 => array('TotalConv', 'i', 0), 3 => array('cpa', 'd', 2), 4 => array('Rev', 'd', 2), 5 => array('countryCode', 's', 0));
        $remaining = explode(',', $netArray[$source][0]);
        $i = 6;
        foreach ($remaining as $field) {
            $a[$i] = array($field, 's', 0);
            $i++;
        }
        $sqlColumns = 'insertDate,TotalClicks,TotalConv,cpa,Rev,countryCode,' . $netArray[$source][0];
        $this->excelRes($a, $sqlColumns, $res, 'Network Report ');
    }

    private function getCampaigns() {
        $cache = $this->di->get("viewCache");
        $res = $cache->get('campaigns');
        //$res = Null;
        if ($res == null) {
            $mask = new Mask();
            $dbres = $mask->getAllCampaigns();
            $res = '';
            foreach ($dbres as $hashAndCampaign) {
                $res .= '<option value ="' . $hashAndCampaign['hash'] . '" >' . $hashAndCampaign['campaign'] . '</option>';
            }
            $cache->save('campaigns', $res, 900);
        }
        return $res;
    }

    private function getOperators() {
        $cache = $this->di->get("viewCache");
        $arrLogin = $this->login_access('a', 'b', 'c', 0);
        $comboString = $cache->get('operators' . $arrLogin[1]);
        //$comboString = nUll;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getOperators();
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString.='<option value="' . $row['operator_name'] . '">' . $row['operator_name'] . '</option>';
            }
            $cache->save('operators' . $arrLogin[1], $comboString, 900);
        }
        return $comboString;
    }

    private function getParams($request, $type = null) {
        $result = null;
        if ($type == 'country') {
            return $this->getParamsCountry($request);
        }

        return $this->getParamsInteger($request);
    }

    private function getParamsCountry($request) {
        if ($request != null && is_array($request)) {
            $result = '';
            foreach ($request as $val) {
                if (isset($this->country_array[strtoupper($val)]) && $val != 'ALL' && $val != 'undefined') {
                    $result .= '"' . $this->country_array[strtoupper($val)] . '",';
                } else {
                    $result = null;
                    break;
                }
            }
            $result = !empty($result) ? rtrim($result, ',') : null;
        } else if ($request != null && $request != 'ALL') {
            $result = '';
            if (isset($this->country_array[strtoupper($request)])) {
                $result .= '"' . $this->country_array[strtoupper($request)] . '"';
            } else {
                $result = null;
            }
        } else {
            $result = null;
        }
        return $result;
    }

    private function getParamsInteger($request) {
        if ($request != null && is_array($request)) {
            $result = '';
            foreach ($request as $val) {
                if (is_numeric($val) && $val != 'ALL' && $val != 'undefined') {
                    $result .= $val . ',';
                } else {
                    $result = null;
                    break;
                }
            }
            $result = !empty($result) ? rtrim($result, ',') : null;
        } else if ($request != null && $request != 'ALL' && $request != 'undefined') {
            $result = '';
            if (is_numeric($request)) {
                $result .= '' . $request . '';
            } else {
                $result = null;
            }
        } else {
            $result = null;
        }
        return $result;
    }

    private function getAggregators() {
        $cache = $this->di->get("viewCache");
        $arrLogin = $this->login_access('a', 'b', 'c', 0);
        $agrsting = (in_array('ALL', $arrLogin[5])) ? NULL : implode(',', $arrLogin[5]);
        $comboString = $cache->get('aggregators' . $agrsting);
        $comboString = nuLL;
        if ($comboString == null) {
            $operation = new Operation();
            /* echo 'here6';
              echo '<pre>';
              print_r($arrLogin);
              echo '<pre>'; */
            //exit();
            $dbres = $operation->getAggregators($agrsting);
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString.='<option value="' . $row['id'] . '">' . $row['agregator'] . '-' . $row['id'] . '</option>';
            }
            $cache->save('aggregators' . $arrLogin[1], $comboString, 10);
        }

        return $comboString;
    }

    private function setStatisticsViewVars($s = null, $e = null, $s2 = null, $e2 = null, $country = null, $agg = null, $campaign = null, $src = null) {
        $res = $this->getCampaigns();
        $aggs = $this->getAggregators();
        $srcs = $this->getSources();
        $ops = $this->getOperators();
        $operators = isset($country) ? $this->getOperators2($country) : '';
        $this->view->setVar("campaignSelectList", $res);
        $this->view->setVar("aggregatorsList", $aggs);
        $this->view->setVar("operatorslist", $ops);
        $this->view->setVar("srclist", $srcs);
        $this->view->setVar("operatorsList2", $operators);
    }

    private function setaggSummaryViewVars() {
        $res = $this->getCampaigns();
        $aggs = $this->getAggregators();
        $operators = $this->getOperators();
        $this->view->setVar("campaignSelectList", $res);
        $this->view->setVar("aggregatorsList", $aggs);
        $this->view->setVar("operatorslist", $operators);
    }

    private function getSources() {
        $cache = $this->di->get("viewCache");
        $comboString = $cache->get('sources');
        $comboString = nUlL;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getSources();
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString.='<option value="' . $row['id'] . '">' . $row['sourceName'] . '-' . $row['id'] . '</option>';
            }
            $cache->save('sources', $comboString, 900);
        }
        return $comboString;
    }

    private function getOperators2($country) {
        $cache = $this->di->get("viewCache");
        $comboString = $cache->get('operators2');
        $comboString = nUlL;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getOperators2($country);
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString.='<option value="' . $row['operator'] . '">' . $row['operator'] . '</option>';
            }
            $cache->save('operators2', $comboString, 900);
        }
        return $comboString;
    }

    private function excelRes($columns, $sqlColumns, $res, $title) {
        //columns contain, colum name, array column name, and type int/double(decimal case)/string
        $temp = tmpfile();
        $exColumns = "sep=;\n";
        for ($i = 0; $i < sizeof($columns); $i++) {
            $exColumns .= $columns[$i][0] . ';';
        }
        fwrite($temp, $exColumns . "\n");
        $sqlColumns = explode(',', $sqlColumns);
        foreach ($res as &$row) {
            $resultRow = '';
            $j = 0;
            foreach ($sqlColumns as $c) {
                $resultRow .= ($columns[$j][1] == 's' ? $row[$c] : number_format((float) $row[$c], $columns[$j][2], '.', '')) . ';';
                $j++;
            }
            fwrite($temp, $resultRow . "\n");
        }
        fseek($temp, 0);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="' . $title . time() . '.csv"';
        header($myContentDispositionHeader);
        header('Expires: 0');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        while (($buffer = fgets($temp, 4096)) !== false) {
            echo $buffer;
        }
        fclose($temp);

        exit();
    }

    private function main_excel($result) {

        ini_set("memory_limit", "512M");
        set_time_limit(0);

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("mobistein");
        $objPHPExcel->getProperties()->setLastModifiedBy('mobistein');
        $objPHPExcel->getProperties()->setTitle('FastReport');
        $objPHPExcel->getProperties()->setSubject('FastReport');
        $objPHPExcel->getProperties()->setDescription('Auto-generated Report');
        $alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $startRow = 1;

        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[0] . $startRow, 'Date');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, 'Campaign');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, 'Campaign Hash');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, 'Country');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[4] . $startRow, 'Source');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[5] . $startRow, 'Agregator');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[6] . $startRow, 'Sub id');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[7] . $startRow, 'Clicks');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[8] . $startRow, 'Conversions');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[9] . $startRow, 'Revenue');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[10] . $startRow, 'EPC');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[11] . $startRow, 'CR');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[12] . $startRow, 'Ad');

        foreach ($result as &$row) {
            $startRow++;
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[0] . $startRow, $row['insert_date']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, $row['campaign']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, $row['c_hash']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, $row['campaign_country']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[4] . $startRow, $row['source']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[5] . $startRow, $row['agregator']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[6] . $startRow, $row['sub_id']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[7] . $startRow, $row['clicks']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[8] . $startRow, $row['conversions']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[9] . $startRow, $row['revenue']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[10] . $startRow, number_format($row['revenue'] / $row['clicks'], 2));
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[11] . $startRow, number_format($row['conversions'] / $row['clicks'], 2));
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[12] . $startRow, $row['ad']);
        }

        header('Content-Type: application/vnd.ms-excel');
        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="MobisteinFastReport' . time() . '.xls"';
        header($myContentDispositionHeader);
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function statisticsNewAction() {
        try {
            $auth = $this->session->get('auth');
            $res = '';
            $chosenSrc = (null == $this->request->get('src')) ? 'allsrc' : $this->request->get('src');
            //$generateSrcTable = (null != $this->request->get('src') && $auth['user_level'] < 3 );
            $loginAccess = $this->login_access('m.country', 'm.agregator', 'r.sourceid', 0);


            $this->view->setVar("userLevel", $auth['userlevel']);

            $fperiod = explode(' to ', $this->request->get('fperiod'));
            $firstp1 = $fperiod[0];
            if (!isset($fperiod[1])) {
                $firstp2 = $fperiod[0];
            } else {
                $firstp2 = $fperiod[1];
            }

            $hourStart = $this->request->get('hoursStart') . ':00:00';
            $hourEnd = $this->request->get('hoursEnd') . ':00:00';
            $report = new ReportMB();
            $res = $report->getGeneralAggCampaignsAvgNewDB($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $this->request->get('aggFunction'), $this->request->get('cc'), $this->request->get('agg'), $this->request->get('selectedCampaign'), $chosenSrc, $loginAccess[2], $this->request->get('selectedOperator'));
            print_r($res);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}

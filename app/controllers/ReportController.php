<?php

/**
 * ReportController
 *
 * Report Insertion and presentation
 */
class ReportController extends ControllerBase {

    private $reportObject;
    private $country_array = array('AD' => 'AD', 'AE' => 'AE', 'AF' => 'AF', 'AG' => 'AG', 'AI' => 'AI', 'AL' => 'AL', 'AM' => 'AM',
        'AN' => 'AN', 'AO' => 'AO', 'AQ' => 'AQ', 'AR' => 'AR', 'AT' => 'AT', 'AU' => 'AU', 'AW' => 'AW', 'AZ' => 'AZ',
        'BA' => 'BA', 'BB' => 'BB', 'BD' => 'BD', 'BE' => 'BE', 'BF' => 'BF', 'BG' => 'BG', 'BH' => 'BH', 'BI' => 'BI',
        'BJ' => 'BJ', 'BM' => 'BM', 'BN' => 'BN', 'BO' => 'BO', 'BR' => 'BR', 'BS' => 'BS', 'BT' => 'BT', 'BV' => 'BV',
        'BW' => 'BW', 'BY' => 'BY', 'BZ' => 'BZ', 'CA' => 'CA', 'CC' => 'CC', 'CF' => 'CF', 'CG' => 'CG', 'CH' => 'CH',
        'CI' => 'CI', 'CK' => 'CK', 'CL' => 'CL', 'CM' => 'CM', 'CN' => 'CN', 'CO' => 'CO', 'CR' => 'CR', 'CU' => 'CU',
        'CV' => 'CV', 'CX' => 'CX', 'CY' => 'CY', 'CZ' => 'CZ', 'DE' => 'DE', 'DJ' => 'DJ', 'DK' => 'DK', 'DM' => 'DM',
        'DO' => 'DO', 'DS' => 'DS', 'DZ' => 'DZ', 'EC' => 'EC', 'EE' => 'EE', 'EG' => 'EG', 'EH' => 'EH', 'ER' => 'ER',
        'ES' => 'ES', 'ET' => 'ET', 'FI' => 'FI', 'FJ' => 'FJ', 'FK' => 'FK', 'FM' => 'FM', 'FO' => 'FO', 'FR' => 'FR',
        'FX' => 'FX', 'GA' => 'GA', 'GB' => 'GB', 'GD' => 'GD', 'GE' => 'GE', 'GF' => 'GF', 'GH' => 'GH', 'GI' => 'GI',
        'GL' => 'GL', 'GM' => 'GM', 'GN' => 'GN', 'GP' => 'GP', 'GQ' => 'GQ', 'GR' => 'GR', 'GS' => 'GS', 'GT' => 'GT',
        'GU' => 'GU', 'GW' => 'GW', 'GY' => 'GY', 'HK' => 'HK', 'HM' => 'HM', 'HN' => 'HN', 'HR' => 'HR', 'HT' => 'HT',
        'HU' => 'HU', 'ID' => 'ID', 'IE' => 'IE', 'IL' => 'IL', 'IN' => 'IN', 'IO' => 'IO', 'IQ' => 'IQ', 'IR' => 'IR',
        'IS' => 'IS', 'IT' => 'IT', 'JM' => 'JM', 'JO' => 'JO', 'JP' => 'JP', 'KE' => 'KE', 'KG' => 'KG', 'KH' => 'KH',
        'KI' => 'KI', 'KM' => 'KM', 'KN' => 'KN', 'KP' => 'KP', 'KR' => 'KR', 'KW' => 'KW', 'KY' => 'KY', 'KZ' => 'KZ',
        'LA' => 'LA', 'LB' => 'LB', 'LC' => 'LC', 'LI' => 'LI', 'LK' => 'LK', 'LR' => 'LR', 'LS' => 'LS', 'LT' => 'LT',
        'LU' => 'LU', 'LV' => 'LV', 'LY' => 'LY', 'MA' => 'MA', 'MC' => 'MC', 'MD' => 'MD', 'ME' => 'ME', 'MG' => 'MG',
        'MH' => 'MH', 'MK' => 'MK', 'ML' => 'ML', 'MM' => 'MM', 'MN' => 'MN', 'MO' => 'MO', 'MP' => 'MP', 'MQ' => 'MQ',
        'MR' => 'MR', 'MS' => 'MS', 'MT' => 'MT', 'MU' => 'MU', 'MV' => 'MV', 'MW' => 'MW', 'MX' => 'MX', 'MY' => 'MY',
        'MZ' => 'MZ', 'NA' => 'NA', 'NC' => 'NC', 'NE' => 'NE', 'NF' => 'NF', 'NG' => 'NG', 'NI' => 'NI', 'NL' => 'NL',
        'NO' => 'NO', 'NP' => 'NP', 'NR' => 'NR', 'NU' => 'NU', 'NZ' => 'NZ', 'OM' => 'OM', 'PA' => 'PA', 'PE' => 'PE',
        'PF' => 'PF', 'PG' => 'PG', 'PH' => 'PH', 'PK' => 'PK', 'PL' => 'PL', 'PM' => 'PM', 'PN' => 'PN', 'PR' => 'PR',
        'PS' => 'PS', 'PT' => 'PT', 'PW' => 'PW', 'PY' => 'PY', 'QA' => 'QA', 'RE' => 'RE', 'RO' => 'RO', 'RS' => 'RS',
        'RU' => 'RU', 'RW' => 'RW', 'SA' => 'SA', 'SB' => 'SB', 'SC' => 'SC', 'SD' => 'SD', 'SE' => 'SE', 'SG' => 'SG',
        'SH' => 'SH', 'SI' => 'SI', 'SJ' => 'SJ', 'SK' => 'SK', 'SL' => 'SL', 'SM' => 'SM', 'SN' => 'SN', 'SO' => 'SO',
        'SR' => 'SR', 'ST' => 'ST', 'SV' => 'SV', 'SY' => 'SY', 'SZ' => 'SZ', 'TC' => 'TC', 'TD' => 'TD', 'TF' => 'TF',
        'TG' => 'TG', 'TH' => 'TH', 'TJ' => 'TJ', 'TK' => 'TK', 'TM' => 'TM', 'TN' => 'TN', 'TO' => 'TO', 'TP' => 'TP',
        'TR' => 'TR', 'TT' => 'TT', 'TV' => 'TV', 'TW' => 'TW', 'TY' => 'TY', 'TZ' => 'TZ', 'UA' => 'UA', 'UG' => 'UG',
        'UM' => 'UM', 'US' => 'US', 'UY' => 'UY', 'UZ' => 'UZ', 'VA' => 'VA', 'VC' => 'VC', 'VE' => 'VE', 'VG' => 'VG',
        'VI' => 'VI', 'VN' => 'VN', 'VU' => 'VU', 'WF' => 'WF', 'WS' => 'WS', 'WW' => 'WW', 'XK' => 'XK', 'YE' => 'YE',
        'YU' => 'YU', 'ZA' => 'ZA', 'ZM' => 'ZM', 'ZR' => 'ZR', 'ZW' => 'ZW');

    public function initialize() {
        $this->tag->setTitle('Report');
        parent::initialize();
        $this->reportObject2 = new Report2();
    }

    public function indexAction() {

        $auth = $this->session->get('auth');
        $this->view->setVar("userLevel", $auth['userlevel']);
        $this->view->setVar("current_date", date('Y-m-d'));
        $this->view->setVar("current_datetime", date('Y-m-d H:00:00'));
        $result = $this->getDi()->getDb4()->query('SELECT id, sourceName FROM Sources s INNER JOIN sourcesMetadata ss ON ss.fkID = s.id ORDER BY sourceName')->fetchAll();
        if (!empty($result)) {
            $res = '';
            foreach ($result as $row) {
                $res .= "<option value=" . $row['id'] . ">" . $row['sourceName'] . '-' . $row['id'] . "</option>";
            }
            $this->view->setVar("sourcesvar", $res);
        }

        $this->setStatisticsViewVars();
        if ($auth['userlevel'] == 2 && $auth['countries'] != null && $auth['countries'] != '') {
            $country = str_replace(',', '","', $auth['countries']);
            $country = '"' . $country . '"';
            $c = Countries::find(array("id IN ($country)"));
            $finalres = '<option value=\'ALL\' >All countries</option>';
            foreach ($c as $country) {
                $finalres .= "<option value='$country->id'>$country->name</option>";
            }
            $this->view->setVar("countries", $finalres);
        } else {
            $finalres = '<option value=\'ALL\' >All countries</option>';
            $c = $this->getDi()->getDb4()->query('SELECT c.id as id, c.name as name FROM Countries c INNER JOIN (SELECT country FROM Mask GROUP BY country) Z ON Z.country LIKE c.id ORDER BY c.name ASC ')->fetchAll();
            foreach ($c as $country) {
                $finalres .= "<option value='" . $country['id'] . "'>" . $country['name'] . "</option>";
            }
            $this->view->setVar("countries", $finalres);
            ;
        }
    }

    public function mainAction() {

        ini_set("memory_limit", "5000M");
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

            $result = $this->mainreportexcel($sdate, $edate, $country);

            $a = array(0 => array('Date', 's', 0), 1 => array('Offer', 's', 0), 2 => array('Offer Hash', 's', 0), 3 => array('Country', 's', 0), 4 => array('Source', 's', 0),
                5 => array('ClientID', 's', 0), 6 => array('Client Name', 's', 0), 7 => array('Sub id', 's', 0), 8 => array('Clicks', 's', 0), 9 => array('Conversions', 's', 0), 10 => array('Revenue', 'd', 2),
                11 => array('EPC', 'd', 2), 12 => array('CR', 'd', 2), 13 => array('Ad', 's', 0), 14 => array('Entity', 's', 0));
            $sqlColumns = 'insert_date,campaign,c_hash,campaign_country,source,agregator,agregator_name,sub_id,clicks,conversions,revenue,EPC,CR,ad,Entity';
            $sqlColumns .= ',aff_flag,account';
            $a[15] = array('Area', 's', 0);
            $a[16] = array('Account', 's', 0);

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
        set_time_limit(120);
        if ($this->request->get('s') != null and $this->request->get('e') != null) {
            if ($this->request->get('cc') && !is_array($this->request->get('cc')))
                $country = $this->getParams($this->request->get('cc'), 'country');
            else if ($this->request->get('cc') && is_array($this->request->get('cc'))) {
                $country = '';
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
            $simple = $this->request->get('simple') != null ? 1 : 0;

            $logArray = $this->login_access();

            $result = $this->reportObject2->getOperatorReport2($this->request->get('s'), $this->request->get('e'), $country, $logArray[0], $logArray[1], $logArray[2], $logArray[4], $simple);
            if ($simple)
                $this->op_excel($result);
            else
                $this->op_excel2($result);
        }
    }

    public function mjumpAction() {
        ini_set("memory_limit", "2048M");
        set_time_limit(0);
        $auth = $this->session->get('auth');
        $countriesAndSources = $this->login_access();

        $report = new Report2();
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



        $res = $report->getMjumpResults($auth, $this->request->get('s'), $this->request->get('e'), $countriesAndSources[1], $country);
        $a = array(0 => array('Date', 's', 0), 1 => array('lpName', 's', 0), 2 => array('page', 's', 0),
            3 => array('ad', 's', 0), 4 => array('userCountry', 's', 0), 5 => array('Carrier', 's', 0),
            6 => array('browser', 's', 0), 7 => array('OS', 's', 0),
            8 => array('subid', 's', 0), 9 => array('source', 's', 0),
            10 => array('lpClicks', 'i', 0),
            11 => array('clicks', 's', 0), 12 => array('conversions', 'i', 0), 13 => array('revenue', 'd', 2), 14 => array('eplpc', 'd', 2), 15 => array('epc', 'd', 2));
        $sqlColumns = 'insertDate,lpName,page,ad,userCountry,carrier,browser,OS,subid,source,lpClicks,clicks,conversions,revenue,eplpc,epc';
        $this->excelRes($a, $sqlColumns, $res, 'Mjump Report ');
        $this->mj_csv($result);

        //insertDate,lpName,page,ad,subid,userCountry,mobileBrand as carrier,browserName as browser,mobileTest as OS,
        //SUM(lpClick) as lpClicks, SUM(click) AS clicks, SUM(conversions) AS conversions,SUM(revenue) AS revenue
        //}
    }

    public function reportbyhourAction() {
        ini_set("memory_limit", "2048M");
        set_time_limit(120);
        if ($this->request->get('s') != null and $this->request->get('e') != null) {
            $countriesAndSources = $this->login_access();
            $report = new Report2();
            $country = $this->getParams($this->request->get('country'), 'country');
            $source = $this->getParams($this->request->get('source'), 'source');

            $res = $report->reportbyhour($this->request->get('s'), $this->request->get('e'), $country, $source, $countriesAndSources[0], $countriesAndSources[1], $countriesAndSources[2], $countriesAndSources[4]);
            $a = array(0 => array('Date', 's', 0), 1 => array('Hour', 's', 0), 2 => array('Offer', 's', 0), 3 => array('Offer Hash', 's', 0), 4 => array('Country', 's', 0),
                5 => array('Source', 's', 0), 6 => array('Client', 's', 0), 7 => array('Sub id', 's', 0), 8 => array('Clicks', 'd', 0),
                9 => array('Conversions', 's', 0), 10 => array('Revenue', 'd', 2), 11 => array('EPC', 'd', 2), 12 => array('CR', 'd', 2), 13 => array('Ad', 's', 0));
            $sqlColumns = 'Date,Hour,Campaign,Campaign_Hash,Country,Source,Agregator,Subid,Clicks,Conversions,Revenue,EPC,CR,Ad';
            $this->excelRes($a, $sqlColumns, $res, 'Report by Hour');
        }
    }

    public function lpreportAction() {
        ini_set("memory_limit", "2048M");
        set_time_limit(120);
        if ($this->request->get('s') != null and $this->request->get('e') != null) {
            $countriesAndSources = $this->login_access();
            $report = new Report2();
            $country = $this->getParams($this->request->get('country'), 'country');
            $source = $this->getParams($this->request->get('source'), 'source');
            $simple = ($this->request->get('c') != null ? true : false);
            if (!$simple)
                $res = $report->lpreport($this->request->get('s'), $this->request->get('e'), $country, $source, $countriesAndSources[0], $countriesAndSources[1], $countriesAndSources[4]);
            else
                $res = $report->lpreportsimple($this->request->get('s'), $this->request->get('e'), $country, $source, $countriesAndSources[0], $countriesAndSources[1], $countriesAndSources[4]);
            if (!$simple) {
                $a = array(array('Date', 's', 0),
                    array('OfferCountry', 's', 0),
                    array('Subid', 's', 0),
                    array('Source', 's', 0),
                    array('ClientID', 'i', 0),
                    //array('campaignhash', 's', 0),
                    //array('lpid', 's', 0),
                    array('UserCountry', 's', 0),
                    array('Offer', 's', 0),
                    array('Carrier', 's', 0),
                    array('ISP', 's', 0),
                    array('Os', 's', 0),
                    array('Browser', 's', 0),
                    array('MobileType', 's', 0),
                    //array('njumpcode', 's', 0),
                    array('Njumpname', 's', 0),
                    array('Jumpline', 's', 0),
                    array('LP', 's', 0),
                    array('lpclicks', 'i', 0),
                    array('clicks', 'i', 0),
                    array('conversions', 'i', 0),
                    array('revenue', 'd', 2),
                    array('ad', 's', 0));
                $sqlColumns = 'Date,OfferCountry,Subid,Source,ClientID,UserCountry,Offer,Carrier,ISP,Os,Browser,MobileType,Njumpname,Jumpline,LP,lpclicks,clicks,conversions,revenue,ad';
            } else {
                $a = array(array('Date', 's', 0),
                    array('OfferCountry', 's', 0),
                    array('Subid', 's', 0),
                    array('Source', 's', 0),
                    array('ClientID', 'i', 0),
                    //array('campaignhash', 's', 0),
                    //array('lpid', 's', 0),
                    array('Offer', 's', 0),
                    array('Carrier', 's', 0),
                    array('ISP', 's', 0),
                    array('Njumpname', 's', 0),
                    array('LP', 's', 0),
                    array('lpclicks', 'i', 0),
                    array('clicks', 'i', 0),
                    array('conversions', 'i', 0),
                    array('revenue', 'd', 2),
                    array('ad', 's', 0));
                $sqlColumns = 'Date,OfferCountry,Subid,Source,ClientID,Offer,Carrier,ISP,Njumpname,LP,lpclicks,clicks,conversions,revenue,ad';
            }
            $str = ($this->request->get('s') == $this->request->get('e')) ? $this->request->get('s') : ($this->request->get('s') . ' to ' . $this->request->get('e'));
            $this->excelRes($a, $sqlColumns, $res, 'LP report ' . $str);
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

                        $source .= "$param,";
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
                        $agg .= "$param,";
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
                        $campaign .= '"' . $param . '",';
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
            $loginAccess = $this->login_access();
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


            if ($auth['userlevel'] == 1 || $auth['userlevel'] == 3 || $auth['userlevel'] == 4) {
                $sourcescatRes = $this->reportObject2->getGeneralStatisticsBySourcesCategory($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
                $sourcescatRes2 = $this->reportObject2->getGeneralStatisticsBySourcesCategory($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
                if (isset($sourcescatRes)) {
                    $res .= $this->genGeneralTable('sourcetable2', $sourcescatRes, $sourcescatRes2, 'Source Types');
                }
            }

            $hourtableRes = $this->reportObject2->getGeneralStatisticsHoursAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
            $hourtableRes2 = $this->reportObject2->getGeneralStatisticsHoursAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);

            $res .= $this->genGeneralTable('hourtabletable', $hourtableRes, $hourtableRes2, 'Hour', $auth['userlevel'] > 2);

            $countryRes = $this->reportObject2->getGeneralStatisticsCountryAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
            $countryRes2 = $this->reportObject2->getGeneralStatisticsCountryAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
            $res .= $this->genGeneralTable('countrytable', $countryRes, $countryRes2, 'Country');
            //}

            if ($auth['userlevel'] < 3) {
                $sourcesRes = $this->reportObject2->getGeneralStatisticsSourcesAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
                $sourcesRes2 = $this->reportObject2->getGeneralStatisticsSourcesAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
            }


            if ($agg != null && $agg != 'allaggs') {

                $aggCampsRes = $this->reportObject2->getGeneralStatisticsAggCampaignsAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
                $aggCampsRes2 = $this->reportObject2->getGeneralStatisticsAggCampaignsAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
            }

            $aggRes = $this->reportObject2->getGeneralStatisticsAggregatorsAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
            $aggRes2 = $this->reportObject2->getGeneralStatisticsAggregatorsAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);

            $opRes = $this->reportObject2->getGeneralStatisticsOperatorsAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $operator, $sourcetype);
            $opRes2 = $this->reportObject2->getGeneralStatisticsOperatorsAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $operator, $sourcetype);

            if ($auth['userlevel'] < 3)
                $res .= $this->genGeneralTable('sourcetable', $sourcesRes, $sourcesRes2, 'Sources');
            //}


            $res .= $this->genGeneralTable('aggregatortable', $aggRes, $aggRes2, 'Client', $auth['userlevel'] > 2);

            if ($agg != null && $agg != 'allaggs') {

                $aggCampsRes = $this->reportObject2->getGeneralStatisticsAggCampaignsAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
                $aggCampsRes2 = $this->reportObject2->getGeneralStatisticsAggCampaignsAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $country, $agg, $campaign, $source, $loginAccess[4], $sourcetype, $operator);
                $res .= $this->genGeneralTable('campaigntable', $aggCampsRes, $aggCampsRes2, 'Offer', $auth['userlevel'] > 2);
            }
            //}

            $res .= $this->genGeneralTable('operatortable', $opRes, $opRes2, 'Carrier', $auth['userlevel'] > 2);



            if ($this->request->get('action') != 'statRequest') {
                $this->view->setVar("resTable", $res);
            } else {
                echo $res;
                $this->view->disable();
            }
        }
    }
    
    public function notworkingAction(){
        try{
            $auth = $this->session->get('auth');
            ini_set("memory_limit", "5000M");
            if($auth['id'] != 1 && $auth['id'] != 4 && $auth['id'] != 5 && $auth['id'] != 23 && $auth['id'] != 6){
                echo '1';
                return;
            }
            $nex = $this->getDi()->getDb4();
            $whatsapp = $this->getDi()->getDb();
            $sdate = $this->request->get('sdate');
            $edate = $this->request->get('edate');
            $sql = 'SELECT UPPER( country ) AS country, sourceId AS source, dateInsert AS insert_date, sum( revenue ) AS rev, sum( investment ) AS investment, "Adult" AS area ' .
                    ' FROM `SourcesInvestment` '.
                    ' WHERE dateInsert BETWEEN :sdate AND :edate ' .
                    ' GROUP BY UPPER( country ) , sourceId, dateInsert ' .
                    ' UNION ALL '.
                    ' SELECT UPPER( country ) AS country, source, insert_date, sum( revenue ) AS rev, sum( investment ) AS investment, "Mainstream" AS area '.
                    ' FROM `I__investReport` '.
                    ' WHERE insert_date BETWEEN :sdate AND :edate and subid NOT LIKE ""  ' .
                    ' GROUP BY UPPER( country ) , source, insert_date; ';
            $res = $whatsapp->query($sql,array('sdate'=>$sdate,'edate'=>$edate))->fetchAll();
            $sql = 'DROP TEMPORARY TABLE IF EXISTS invst;
CREATE TEMPORARY TABLE invst(
`country` CHAR(2) NULL,
`source` INT NULL,
`insert_date` DATE NULL,
`rev` DECIMAL(10,3) DEFAULT "0.000" ,
`investment` DECIMAL(10,3) DEFAULT "0.000",
area VARCHAR(64) NULL
) ENGINE=InnoDB;';
            
            $nex->query($sql);
            if(!empty($res)){
                $valsarr = array();
                $val = 'INSERT INTO invst ( country,source,insert_date,rev,investment,area) VALUES ';
                foreach($res as $row){
                    $val .= ' (?,?,?,?,?,?), ';
                    $valsarr[] = $row['country'];
                    $valsarr[] = $row['source'];
                    $valsarr[] = $row['insert_date'];
                    $valsarr[] = $row['rev'];
                    $valsarr[] = $row['investment'];
                    $valsarr[] = $row['area'];
                }
                $val = rtrim($val,', ').';';
                
                $nex->query($val,$valsarr,array());
                $val = '';
                
            }
            $nex->query('INSERT INTO invst ( country,source,insert_date,rev,investment,area)
SELECT UPPER(campaign_country) as country, affiliate_id as source, insertDate as insert_date, sum(steincpa) as rev, sum(cpa) as investment, "Affiliates" as Area 
FROM affiliatesreporting.ClicksAgg
WHERE insertDate BETWEEN :sdate AND :edate and (cpa > 0 or steincpa > 0)
GROUP BY UPPER(campaign_country),affiliate_id,insertDate;',array('sdate'=>$sdate,'edate'=>$edate),array());
            
            $res = $nex->query('SELECT insert_date,country,source,s.sourceName as sourceName,rev,investment,area
FROM invst left join Sources s ON s.id = invst.source ORDER BY insert_date,country;')->fetchAll();
            if(!empty($res)){
                $a = array(0 => array('Date', 's', 0), 1 => array('Country', 's', 0), 2 => array('Source', 's', 0),
                    3 => array('SourceName', 's', 0), 4 => array('Revenue', 'd', 2), 5 => array('Investment', 'd', 2), 6 => array('Area', 's', 0));
                $sqlColumns = 'insert_date,country,source,sourceName,rev,investment,area';
                $this->excelRes($a, $sqlColumns, $res, 'Random generated data');
            }
            
            
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function aggSummaryAction() {
        ini_set("memory_limit", "4024M");
        if ($this->request->get('sdate') != null and $this->request->get('edate') != null) {
            $access = $this->login_access();
            $auth = $this->session->get('auth');
            $report = new Report2();
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

            $res = $report->getAggData2($this->request->get('sdate'), $this->request->get('edate'), $this->request->get('selectcountry'),$this->request->get('selectsource'), $this->request->get('selectusercountry'), $this->request->get('selectaggregator'), $this->request->get('selectcampaign'), $this->request->get('selecturl'), $this->request->get('selectoperator'), $orderby, $access[0], $access[1], $access[2], $ags, $cps, $ops, $access[4], $cs, $auth);

            $aggFunction = '';
            $resultColumns = array(array('Date', 's', 0));

            if ($this->request->get('selectcountry') != null) {
                $resultColumns[] = array('Country', 's', 0);
                $aggFunction .= ',Country';
            }

            if ($this->request->get('selectusercountry') != null) {
                $resultColumns[] = array('UserCountry', 's', 0);
                $aggFunction .= ',UserCountry';
            }
            if ($this->request->get('selectoperator') != null) {
                $resultColumns[] = array('Carrier', 's', 0);
                $aggFunction .= ',Carrier';
            }
            if ($this->request->get('selecturl') != null) {
                $resultColumns[] = array('OfferUrl', 's', 0);
                $aggFunction .= ',OfferUrl';
            }
            if ($this->request->get('selectaggregator') != null) {
                $resultColumns[] = array('ClientID', 's', 0);
                $aggFunction .= ',ClientID';
                if ($this->request->get('excel') == null)
                    $resultColumns[] = array('Client', 's', 0);
            }
            if ($this->request->get('selectcampaign') != null) {
                $resultColumns[] = array('Offer', 's', 0);
                $aggFunction .= ',Offer';
            }

            if ($this->request->get('excel') != null) {
                array_push($resultColumns, array('Clicks', 'd', 0), array('Conversions', 'd', 0), array('Revenues', 'd', 2));

                $sqlColumns = 'Date' . (isset($aggFunction) ? $aggFunction : '') . ',clicks,conversions,revenues';
                if ($this->request->get('selectaggregator') != null) {
                    $resultColumns[] = array('Client', 's', 0);
                    $resultColumns[] = array('Account', 's', 0);
                    $resultColumns[] = array('Entity', 's', 0);
                    $sqlColumns .= ',Client,account,Entity';
                }
                $resultColumns[] = array('Area', 's', 0);
                $sqlColumns .= ',Area';
                if ($this->request->get('selectsource') != null) {
                    $sqlColumns .= ',SourceID';
                }
                if ($this->request->get('selectsource') != null) {
                    //$aggFunction .= ',SourceID';
                    $resultColumns[] = array('SourceID', 's', 0);
                }
                $this->excelRes($resultColumns, $sqlColumns, $res, 'Client Report ' . date('Ymd'));
            } else {
                $resultColumns[] = array('Area', 's', 0);
                //$sqlColumns .= ',Area';
                if ($this->request->get('selectsource') != null) {
                    //$aggFunction .= ',SourceID';
                    $resultColumns[] = array('SourceID', 's', 0);
                }
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
        $table .= $theads . '<td class="col-xs-' . ($columnsSize) . '">
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
            $tr .= '<td>' . $row['clicks'] . '</td>';
            $tr .= '<td>' . $row['conversions'] . '</td>';
            $tr .= '<td>' . $row['revenues'] . '</td>';
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
                    $table .= '<tr ' . ( 'class="goto" attrID="' . ($id == 'hourtabletable' ? substr($row['id'], 0, 2) : ($id == 'operatortable' ? $row['name'] : $row['id'])) . '"' ) . '><td>' . '<strong>' . $row['name'] . '</strong>' . ( $onlyid ? '' : '-' . $row['id'] ) . '</td>'
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
            $table .= '<tr ' . ($id != 'sourcetable2' ? 'class="goto" attrID="' . ($id == 'hourtabletable' ? substr($row2['id'], 0, 2) : ($id == 'operatortable' ? $row2['name'] : $row2['id'])) . '"' : '') . '><td><strong>' . $row2['name'] . '</strong>' . ( $onlyid ? '' : '-' . $row2['id'] ) . '</td>'
                    . '<td>-</td><td>' . $row2['clicks'] . '</td><td>-</td>'
                    . ($OPERATORUSER ? ('<td>-</td><td>' . $row2['conversions'] . '</td><td>-</td>') : '')
                    . '<td>-</td><td>' . $row2['revenues'] . '</td><td>-</td>'
                    . '<td></td><td>' . $row2['epc'] . '</td><td>-</td></tr>';
            if ($totals) {
                $totalclicks2 += $row2['clicks'];
                $totalrevenue2 += $row2['revenues'];
                if (isset($totalconversions2))
                    $totalconversions2 += $row2['conversions'];
            }
        }

        if ($name != 'Source') {
            foreach ($arr as $row) {//missing rows
                if (!empty($row)) {
                    $table .= '<tr ' . ($id != 'sourcetable2' ? 'class="goto" attrID="' . ($id == 'hourtabletable' ? substr($row['id'], 0, 2) : ($id == 'operatortable' ? $row['name'] : $row['id'])) . '"' : '') . '><td><strong>' . $row['name'] . '</strong>' . ( $onlyid ? '' : '-' . $row['id'] ) . '</td><td>' . $row['clicks'] . '</td><td>-</td><td>-</td>'
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

        $logArray = $this->login_access();
        $rep2 = $this->reportObject2;
        $final = $rep2->getNewMainReportResult($sdate, $edate, $country, $logArray[0], $logArray[1], $logArray[4], $logArray[2]);
        $countriestotals = $rep2->getcountriestotals($sdate, $edate, $country, $logArray[0], $logArray[1], $logArray[4], $logArray[2]);
        return array($final, $countriestotals);
    }

    private function mainreportexcel($sdate, $edate, $country) {

        $countryAndSources = $this->login_access();

        $result = $this->reportObject2->getMainReportResult($sdate, $edate, $country, $countryAndSources[0], $countryAndSources[1], $countryAndSources[4], $countryAndSources[2]);
        return $result;
    }

    private function sortBySubkey(&$array, $subkey, $sortType = SORT_ASC) {
        if (sizeof($array) < 1)
            return;
        foreach ($array as $subarray) {
            $keys[] = $subarray[$subkey];
        }
        array_multisort($keys, $sortType, $array);
    }

    private function op_excel($result) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        ini_set("memory_limit", "4096M");
        set_time_limit(0);
        $temp = tmpfile();
        fwrite($temp, "sep=;\nDate;Sub id;Offer Hash;Offer;Offer Country;User Country;ClientID;Client;Source;Carrier;Clicks;Conversions;Revenue;Ad;Area;Account\n");
        foreach ($result as &$row) {
            fwrite($temp, $row['insert_date'] . ';' . $row['sub_id'] . ';' . $row['c_hash'] . ';' . $row['campaign'] . ';' . $row['campaign_country'] . ';'
                    . $row['user_country'] . ';' . $row['agregator'] . ';' . $row['agregatorName'] . ';' . $row['source'] . ';' . $row['operator'] . ';'
                    . number_format(floatval($row['clicks']), 0, '', '') . ';' . number_format(floatval($row['conversions']), 0, '', '') . ';' . number_format(floatval($row['revenue']), 2, '.', '')
                    . ';' . $row['ad'] . ';' . $row['area'] . ';' . $row['account'] . "\n");
        }
        fseek($temp, 0);
        while (($buffer = fgets($temp, 4096)) !== false) {
            echo $buffer;
        }
        fclose($temp);

        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="MobisteinFastReport' . time() . '.csv"';
        header($myContentDispositionHeader);
        header('Expires: 0');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        exit;
    }

    private function op_excel2($result) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        ini_set("memory_limit", "4096M");
        set_time_limit(0);
        $temp = tmpfile();
        fwrite($temp, "sep=;\nDate;Sub id;Offer Hash;Offer;Offer Country;User Country;ClientID;Client;Source;Carrier;Isp;Os;Browser;Mobile Type;Clicks;Conversions;Revenue;Ad;Area;Account\n");
        foreach ($result as &$row) {
            fwrite($temp, $row['insert_date'] . ';' . $row['sub_id'] . ';' . $row['c_hash'] . ';' . $row['campaign'] . ';' . $row['campaign_country'] . ';'
                    . $row['user_country'] . ';' . $row['agregator'] . ';' . $row['agregatorName'] . ';' . $row['source'] . ';' . $row['operator'] . ';'
                    . $row['isp'] . ';' . $row['os'] . ';' . $row['browser'] . ';' . $row['mobilet'] . ';' . number_format(floatval($row['clicks']), 0, '', '') . ';' . number_format(floatval($row['conversions']), 0, '', '') . ';' . number_format(floatval($row['revenue']), 2, '.', '')
                    . ';' . $row['ad'] . ';' . $row['area'] . ';' . $row['account'] . "\n");
        }
        fseek($temp, 0);
        while (($buffer = fgets($temp, 4096)) !== false) {
            echo $buffer;
        }
        fclose($temp);

        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="MobisteinFastReport' . time() . '.csv"';
        header($myContentDispositionHeader);
        header('Expires: 0');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        exit;
    }

    private function login_access() {

        $auth = $this->session->get('auth');

        if ($auth['countries'] != '') {
            $country = str_replace(',', '","', $auth['countries']);
            $country = '"' . $country . '"';
        } else
            $country = null;

        $sources = $auth['sources'] == '' ? null : $auth['sources'];
        $aggs = $auth['aggregators'] == '' ? null : $auth['aggregators'];
        $aff = null; // Ric and Commercials
        if ($auth['userlevel'] == 2 && $auth['utype'] == 0) {//CM's Ivo Pedro
            $aff = 0;
        } else if ($auth['utype'] == 1) { // Affiliate manager
            $sources = $auth['affiliates'];
            $aff = 1;
        } else if ($auth['userlevel'] == 2 && $auth['utype'] == 2) { // mainstream CM's  like Arrojado
            $aff = 2;
            //} else if ($auth['userlevel'] == 1 && ($auth['id'] == 3)) { // hello I'm Guille and Facha and I'm different
            //$aff = 3;
        } else if ($auth['userlevel'] == 1 && ($auth['id'] == 2)) { // hello I'm Guille and Facha and I'm different,
            $aff = 4;
        }
        return array($country, $sources, $aggs, $auth['id'], $aff);
    }

    private function generate_main_table($result, $sdate, $edate, $currentDay, $country) {
        $countryAndSources = $this->login_access();
        $table_html = '<table class="table table-striped table-condensed"><thead>
        <tr>
          <th style="color:#909090;">Offer</th>
          <th style="color:#909090;">Country</th>
          <th style="color:#909090;">Client</th>
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

                $table_html .= '<tr><th style="color: black;">' . $row['campaign'] . '</th><th>' . $row['campaign_country'] . '</th>'
                        . '<th>' . $row['agregator'] . '</th>'
                        . '<th>' . $row['sub_id'] . '</th>'
                        . '<th>' . $row['clicks'] . '</th>'
                        . '<th >' . $row['conversions'] . '</th>'
                        . '<th style="color: black;">' . (float) number_format(floatval($row['revenue']), 2) . '</th>'
                        . '<th>' . ($row['clicks'] == 0 ? '0.000' : number_format($row['revenue'] / $row['clicks'], 3)) . '</th>'
                        . '<th>' . ($row['clicks'] == 0 ? '0.00' : number_format($row['conversions'] / $row['clicks'] * 100, 2) ) . '%</th></tr>';
                $revtotal += $row['revenue'];
                $clicktotal += $row['clicks'];
                $convtotal += $row['conversions'];
            }
        }

        $totals = $this->generate_day_total_table($sdate, $edate, $country);
        $revenueColumn = number_format($totals[2], 2);

        if ($currentDay) {
            $avgRes = $this->generate_avg_table(3, $country);
            $revenueColumn = number_format($totals[2], 2) . '&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;' . ($totals[2] && $totals[2] > 0 ? number_format((($totals[2] - $avgRes[0]['avgRev']) / $totals[2]) * 100, 3) : '0.000') . '&#37;';
            $avgTable = '<tr id="pastAvg"><th id="firstColumnAvg" style="color: black; font-weight:normal; font-size:90%"> Last 3 days from 00:00 to ' . date("H") . ':' . ((isset($country) || isset($countryAndSources[0])) ? '00' : ((floor((date("i") * 4) / 60) * 15) == 0 ? '00' : (floor((date("i") * 4) / 60) * 15) )) . ':00' .
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
        $table_html .= '</tbody></table>';
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

            $table_html .= '<tr><th style="color: black;">' . $row['campaign_country'] . '</th><th>' . $row['clicks'] . '</th>'
                    . '<th >' . $row['conversions'] . '</th>'
                    . '<th id="curRevID" style="color: black;">' . number_format($row['revenue'], 2) . '</th>'
                    . '<th>' . ($row['clicks'] == 0 ? '0.000' : number_format($row['revenue'] / $row['clicks'], 3) ) . '</th>'
                    . '<th>' . ($row['clicks'] == 0 ? '0.00' : number_format($row['conversions'] / $row['clicks'] * 100, 2) ) . '%</th></tr>';
        }
        $table_html .= '</tbody></table>';
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
                    $countries .= '"' . $this->country_array[strtoupper($val)] . '",';
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
        $logArray = $this->login_access();

        date_default_timezone_set('Europe/Lisbon');
        $report = new Report();
        $sdate = date("Y-m-d", strtotime(date("Y-m-d") . '-' . $day . ' days'));
        return $this->reportObject2->getLastDaysAvg2($sdate, $country, $logArray[0], $logArray[1], $logArray[4], $logArray[2]);
    }

    private function generate_day_total_table($sdate, $edate, $country) {
        date_default_timezone_set('Europe/Lisbon');
        $logArray = $this->login_access();
        $rep2 = $this->reportObject2;
        if ($sdate == date('Y-m-d') && $edate == date('Y-m-d'))
            return $rep2->getDayTotal2($country, $logArray[0], $logArray[1], $logArray[2], $logArray[4]);
        else {
            return $rep2->getDaysTotal2($sdate, $edate, $country, $logArray[0], $logArray[1], $logArray[2], $logArray[4]);
        }
    }

    public function networkAction() {

        ini_set("memory_limit", "2048M");
        set_time_limit(0);
        $countriesAndSources = $this->login_access();


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
            '289' => array('pubid,tracking', 'TReachPubIds', 0), '346' => array('subid,tracking', 'TwwwpromoterPubIds', 0),
            '441' => array('source,os,browser,campaign_name,tracking', 'TZeroParkPubIds', 0), '446' => array('zoneid,tracking', 'THilltopads2PubIds', 0),
            '476' => array('pubid,tracking', 'TMobivisitsPubIds', 0), '615' => array('pubid,tracking', 'TActiveRevenuePubIds', 0), '614' => array('pubid,tracking', 'TadxitePubIds', 0),
            '646' => array('pubid,tracking', 'TpachadsPubIds', 0));
        //filtrar sources do user
        if (isset($countriesAndSources[1])) {
            $arr = explode(',', $countriesAndSources[1]);
            foreach ($netArray as $k => $v) {
                if (!in_array($k, $arr)) {
                    unset($netArray[$k]);
                }
            }
        }
        $source = $this->request->get('so');

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

        $report = new Report2();
        if ($startDate <= '2017-02-14') {
            $res = $report->getNetworkResult($startDate, $endDate, $convTableMonth, $source, $country, $countriesAndSources[0], $netArray);
            $a = array(0 => array('Date', 's', 0), 1 => array('TotalClick', 'i', 0), 2 => array('TotalConv', 'i', 0), 3 => array('cpa', 'd', 2), 4 => array('Rev', 'd', 2), 5 => array('countryCode', 's', 0));
            $remaining = explode(',', $netArray[$source][0]);
            $i = 6;
            foreach ($remaining as $field) {
                $a[$i] = array($field, 's', 0);
                $i++;
            }
            $sqlColumns = 'insertDate,TotalClicks,TotalConv,cpa,Rev,countryCode,' . $netArray[$source][0];
            $this->excelRes($a, $sqlColumns, $res, 'Network Report ');
        } else {
            $res = $report->getNetworkReport($startDate, $endDate, $source, $country, $countriesAndSources[0], $countriesAndSources[1]);
            //fkAgregator as clientid, hashMask as offerid,campaignName as offer, countryCode as offerCountry, fkSource as sourceid, "
            //. "format as format, adNumber as adNumber,subid, SUM(clicks) as clicks,$column, SUM(conversions) as conversions, SUM(ccpa) as revenue
            //
            //
                //$this->excelRes($a, $sqlColumns, $res, 'Network Report ');
            $a = array(
                array('Date', 's', 0),
                array('clientid', 's', 0),
                array('offerid', 's', 0),
                array('offer', 's', 0),
                array('offerCountry', 's', 0),
                array('sourceid', 'i', 0),
                array('format', 's', 0),
                array('adNumber', 's', 0),
                array('subid', 's', 0),
                array('carrier', 's', 0),
                array('clicks', 'i', 0),
                array('conversions', 'i', 0),
                array('revenue', 'd', 2),
            );
            $remaining = '';
            if (!empty($res) && !empty($res[1])) {
                $arr = explode(',', $res[1]);
                foreach (array_splice($arr, 1) as $val) {
                    $a[] = array($val, 's', 0);
                }
                $remaining = $res[1];
            }

            if (empty($res) && empty($res[0]))
                $res[0] = array();
            $sqlColumns = 'Date,clientid,offerid,offer,offerCountry,sourceid,format,adNumber,subid,carrier,clicks,conversions,revenue' . $remaining;
            $this->excelRes($a, $sqlColumns, $res[0], 'Network Report ');
        }
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
        $arrLogin = $this->login_access();
        $comboString = $cache->get('operators' . $arrLogin[3]);
        //$comboString = nUll;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getOperators();
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString .= '<option value="' . $row['operator_name'] . '">' . $row['operator_name'] . '</option>';
            }
            $cache->save('operators' . $arrLogin[3], $comboString, 900);
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
        $arrLogin = $this->login_access();
        $comboString = $cache->get('aggregators' . $arrLogin[3]);
        $comboString = nuLL;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getAggregators($arrLogin[2]);
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString .= '<option value="' . $row['id'] . '">' . $row['agregator'] . '-' . $row['id'] . '</option>';
            }
            $cache->save('aggregators' . $arrLogin[3], $comboString, 900);
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
            $dbres = $operation->getSources($this->session->get('auth'));
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString .= '<option value="' . $row['id'] . '">' . $row['sourceName'] . '-' . $row['id'] . '</option>';
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
                $comboString .= '<option value="' . $row['operator'] . '">' . $row['operator'] . '</option>';
            }
            $cache->save('operators2', $comboString, 900);
        }
        return $comboString;
    }

    private function excelRes($columns, $sqlColumns, $res, $title) {
        //columns contain, colum name, array column name, and type int/double(decimal case)/string
        $auth = $this->session->get('auth');
        $decimalchar = (($auth['decimalchar'] != null) ? $auth['decimalchar'] : '.');
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
                $resultRow .= ($columns[$j][1] == 's' ? $row[$c] : number_format((float) $row[$c], $columns[$j][2], $decimalchar, '')) . ';';
                $j++;
            }
            fwrite($temp, $resultRow . "\n");
        }
        fseek($temp, 0);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="' . $title . '.csv"';
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

    public function statisticsNewAction() {
        try {
            $auth = $this->session->get('auth');
            $res = '';
            $chosenSrc = (null == $this->request->get('src')) ? 'allsrc' : $this->request->get('src');
            //$generateSrcTable = (null != $this->request->get('src') && $auth['user_level'] < 3 );
            $loginAccess = $this->login_access();


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
            $report = new Report();
            $res = $report->getGeneralAggCampaignsAvgNewDB($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2], $this->request->get('aggFunction'), $this->request->get('cc'), $this->request->get('agg'), $this->request->get('selectedCampaign'), $chosenSrc, $loginAccess[4], $this->request->get('selectedOperator'));
            print_r($res);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}

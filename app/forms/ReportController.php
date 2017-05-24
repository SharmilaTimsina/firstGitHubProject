<?php

/**
 * ReportController
 *
 * Report Insertion and presentation
 */
class ReportController extends ControllerBase {

    private $reportObject;

    public function initialize() {
        $this->tag->setTitle('Report');
        parent::initialize();
        $this->reportObject = new Report();
    }

    public function indexAction() {

        $auth = $this->session->get('auth');
        $this->view->setVar("userLevel", $auth['userlevel']);
        $this->view->setVar("current_date", date('Y-m-d'));
        $this->view->setVar("current_datetime", date('Y-m-d H:00:00'));
        $this->setStatisticsViewVars();
    }

    public function mainAction() {

        ini_set("memory_limit", "1024M");
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

        $country = $this->request->get('cc') == 'ALL' ? null : $this->request->get('cc');
        

        if ($this->request->get('action') == 'excel') {
            $this->view->disable();
            $result = $this->main_report_table_rbb($sdate, $edate, $param, $country);
            $a = array(0 => array('Date', 's', 0), 1 => array('Campaign', 's', 0), 2 => array('Campaign Hash', 's', 0), 3 => array('Country', 's', 0), 4 => array('Source', 's', 0),
                5 => array('Agregator', 's', 0), 6 => array('Sub id', 's', 0), 7 => array('Clicks', 's', 0), 8 => array('Conversions', 's', 0), 9 => array('Revenue', 'd', 2),
                10 => array('EPC', 'd', 2), 11 => array('CR', 'd', 2), 12 => array('Ad', 's', 0));
            $sqlColumns = 'insert_date,campaign,c_hash,campaign_country,source,agregator,sub_id,clicks,conversions,revenue,EPC,CR,ad';
            $this->excelRes($a, $sqlColumns, $result, 'Main Report ');
            //$this->main_excel($result);
        } else {
            $result = $this->main_report_table($sdate, $edate, $param, $country);
            $cRes = $this->report_country_table($sdate, $edate, $param, $country);
            $countryRestable = $this->generate_countrydata_table($cRes);

            $table = $this->generate_main_table($result, $sdate, $edate,$onlyCurrentDay, $country);
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
            $country = $this->request->get('cc') == 'ALL' ? null : $this->request->get('cc');
        
            $result = $this->op_report_table($this->request->get('s'), $this->request->get('e'), $param, $country);

            $this->op_excel2($result);
        }
    }

    public function mjumpAction() {
        ini_set("memory_limit", "1024M");
        set_time_limit(0);
        if ($this->request->get('s') != null and $this->request->get('e') != null) {
            $result = $this->mj_report_table($this->request->get('s'), $this->request->get('e'));
            $this->mj_excel($result);
        }
    }

    public function payoutreportAction() {
        ini_set("memory_limit", "1024M");
        set_time_limit(0);
        if ($this->request->get('s') != null and $this->request->get('e') != null and $this->request->get('src') != null) {
            $countriesAndSources = $this->login_access();
            $report = new Report();
            $source = $this->request->get('src');
            if ($this->request->get('src') == 'allsrc') {
                $source = null;
            }
            $res = $report->getPayoutResult($this->request->get('s'), $this->request->get('e'), $source, $countriesAndSources[0], $countriesAndSources[1]);
            $a = array(0 => array('Hash', 's', 0), 1 => array('Campaign', 's', 0), 2 => array('Source', 's', 0), 3 => array('Payout', 'd', 2), 4 => array('Time', 's', 0));
            $sqlColumns = 'hash,campaign,source,value,insertTimestamp';
            $this->excelRes($a, $sqlColumns, $res, 'Payout Report ');
        }
    }
    public function reportbyhourAction() {
        ini_set("memory_limit", "1024M");
        set_time_limit(0);
        if ($this->request->get('s') != null and $this->request->get('e') != null and $this->request->get('country') != null) {
            $countriesAndSources = $this->login_access();
            $report = new Report();
            $country = $this->request->get('country');
            if ($this->request->get('country') == 'ALL') {
                $country = null;
            }
            $res = $report->reportbyhour($this->request->get('s'), $this->request->get('e'), $country, $countriesAndSources[0], $countriesAndSources[1]);
            $a = array(0 => array('Date', 's', 0),1=>array('Hour', 's', 0), 2 => array('Campaign', 's', 0), 3 => array('Campaign Hash', 's', 0), 4 => array('Country', 's', 0),
                5 => array('Source', 's', 0), 6 => array('Agregator', 's', 0),7 => array('Sub id', 's', 0),8 => array('Clicks', 'd', 0),
                9 => array('Conversions', 's', 0),10 => array('Revenue', 'd', 2),11 => array('EPC', 'd', 2),12 => array('CR', 'd', 2),13 => array('Ad', 's', 0));
            $sqlColumns = 'Date,Hour,Campaign,Campaign_Hash,Country,Source,Agregator,Subid,Clicks,Conversions,Revenue,EPC,CR,Ad';
            $this->excelRes($a, $sqlColumns, $res, 'Report by Hour');
        }
    }

    public function statisticsAction() {

        if ($this->request->get('fperiod') != null and $this->request->get('speriod') != null) {
            $res = '';
            $chosenSrc = (null == $this->request->get('src')) ? 'allsrc' : $this->request->get('src');
            $generateSrcTable = (null != $this->request->get('src'));
            if ($this->request->get('action') != 'statRequest') {
                $this->setStatisticsViewVars(null, null, $this->request->get('cc'), $this->request->get('agg'), $this->request->get('selectedCampaign'), $chosenSrc);
            }
            $loginAccess = $this->login_access();
            $auth = $this->session->get('auth');
            
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

            if($auth['userlevel']<3){
                $countryRes = $this->reportObject->getGeneralCountryAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2],$this->request->get('aggFunction'),$this->request->get('cc'), $this->request->get('agg'), $this->request->get('selectedCampaign'), $chosenSrc);
                $countryRes2 = $this->reportObject->getGeneralCountryAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd,$loginAccess[0], $loginAccess[1], $loginAccess[2],$this->request->get('aggFunction'), $this->request->get('cc'), $this->request->get('agg'), $this->request->get('selectedCampaign'), $chosenSrc);
                $res .= $this->genGeneralTable('countrytable', $countryRes, $countryRes2, 'Country');
            }
            if ($generateSrcTable) {
                $sourcesRes = $this->reportObject->getGeneralSourcesAvg($firstp1, $firstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2],$this->request->get('aggFunction'),$this->request->get('cc'), $this->request->get('agg'), $this->request->get('selectedCampaign'), $chosenSrc);
                $sourcesRes2 = $this->reportObject->getGeneralSourcesAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd, $loginAccess[0], $loginAccess[1], $loginAccess[2],$this->request->get('aggFunction'),$this->request->get('cc'), $this->request->get('agg'), $this->request->get('selectedCampaign'), $chosenSrc);
            }
            $aggRes = $this->reportObject->getGeneralAggregatorsAvg($firstp1, $firstp2, $hourStart, $hourEnd,$loginAccess[0], $loginAccess[1], $loginAccess[2],$this->request->get('aggFunction'), $this->request->get('cc'), $this->request->get('agg'), $this->request->get('selectedCampaign'), $chosenSrc);
            $aggRes2 = $this->reportObject->getGeneralAggregatorsAvg($sfirstp1, $sfirstp2, $hourStart, $hourEnd,$loginAccess[0], $loginAccess[1], $loginAccess[2],$this->request->get('aggFunction'), $this->request->get('cc'), $this->request->get('agg'), $this->request->get('selectedCampaign'), $chosenSrc);
            
            if ($generateSrcTable)
                $res .= $this->genGeneralTable('sourcetable', $sourcesRes, $sourcesRes2, 'Source');
            $res .= $this->genGeneralTable('aggregatortable', $aggRes, $aggRes2, 'Aggregator');

            if ($this->request->get('action') != 'statRequest') {
                $this->view->setVar("resTable", $res);
            } else {
                echo $res;
                $this->view->disable();
            }
        }
    }

    public function aggSummaryAction(){
        ini_set("memory_limit", "1024M");
        if ($this->request->get('sdate') != null and $this->request->get('edate') != null ) {
            $access = $this->login_access();
            $report = new Report();
            $ops = null;$cps=null;$ags=null;
            if($this->request->get('inside') == null)
                $this->setaggSummaryViewVars();
            if($this->request->get('operatorsid')!=null && $this->request->get('operatorsid')!='alloperators'){
                $ops = '';
                foreach($this->request->get('operatorsid') as $op){
                    if(strpos($op,'alloperators')!==false){
                            $ops = null;
                            break;
                    }
                    else
                        $ops .= '"'. $op . '",';
                }
                $ops = isset($ops) ? rtrim($ops,',') : null;
            }
            if($this->request->get('campaignsid')!=null && $this->request->get('campaignsid')!='allcampaigns'){
                $cps = '';
                foreach($this->request->get('campaignsid') as $cp){
                    if(strpos($cp,'allcampaigns')!==false){
                            $cps = null;
                            break;
                    }
                    else
                        $cps .= '"'. $cp . '",';
                }
                $cps = isset($cps) ? rtrim($cps,',') : null;
            }
            if($this->request->get('aggsid')!=null && $this->request->get('aggsid')!='allaggs'){
                $ags='';
                foreach($this->request->get('aggsid') as $aggs){
                    if(strpos($aggs,'allaggs')!==false){
                            $ags = null;
                            break;
                    }
                    else
                        $ags .= $aggs.',';
                }
                $ags = isset($ags) ? rtrim($ags, ',') : null;
            }
            
            $res = $report->getAggData($this->request->get('sdate'),$this->request->get('edate'),$this->request->get('selectcountry'),$this->request->get('selectaggregator'),$this->request->get('selectcampaign'),$this->request->get('selectoperator'),$access[0],$access[1],$access[2],$ags,$cps,$ops); 
            $aggFunction = '';
            $resultColumns = array(array('Date', 's', 0));
            if($this->request->get('selectcountry')!=null){
                $resultColumns[] = array('Country', 's', 0);
                $aggFunction .=',Country';
            }
            if($this->request->get('selectoperator')!=null){
                $resultColumns[] = array('Operator', 's', 0);
                $aggFunction .=',Operator';
            }
            if($this->request->get('selectaggregator')!=null){
                $resultColumns[] = array('Aggregator', 's', 0);
                $aggFunction .=',Aggregator';
            }
            if($this->request->get('selectcampaign')!=null){
                $resultColumns[] = array('Campaign', 's', 0);
                $aggFunction .=',Campaign';
            }
            
            if($this->request->get('excel')!= null){
                array_push($resultColumns, array('clicks', 'd', 0), array('conversions', 'd', 0),array('revenues', 'd', 2));
                $sqlColumns = 'date'.(isset($aggFunction) ? $aggFunction : '').',clicks,conversions,revenues';
                if($this->request->get('selectaggregator') != null)
                    $resultColumns[] = array('aggregatorName', 's', 0);
                $this->excelRes($resultColumns, $sqlColumns, $res, 'Aggregator Report ');
            }
            else{
                if($this->request->get('selectaggregator') != null)
                    $resultColumns[] = array('aggregatorName', 's', 0);
                $columnSize = floor(12 / (sizeof($resultColumns)+3));
                $table = $this->genAggSummaryTable($resultColumns, $columnSize, $res);
                if($this->request->get('inside')==null)
                    $this->view->setVar("resTable", $table);
                else{
                    echo $table;
                    $this->view->disable();
                }
            }
        }
    }
    
    private function genAggSummaryTable($columns,$columnsSize, $sqlRes){
        $table = '<div id="restablediv"><div class="container well">
                    <div class="table-responsive" ><table class="table table-bordered centertext" >';
        $theads ='<thead><tr>';

        foreach($columns as $column){
            $theads .= '<td class="col-xs-'.($columnsSize).'">
                                <strong>'.$column[0].'</strong>
                            </td>';
        }
        $table .=$theads.'<td class="col-xs-'.($columnsSize).'">
                                <strong>Clicks</strong>
                            </td><td class="col-xs-'.($columnsSize).'">
                                <strong>Conversions</strong>
                            </td><td class="col-xs-'.($columnsSize).'">
                                <strong>Revenue</strong>
                            </td></thead><tbody>';
        $totalClicks = 0;
        $totalConversions = 0;
        $totalRevenues = 0;
        foreach($sqlRes as $row){
            $tr = '<tr>';
            foreach($columns as $column){
                $tr .= '<td>'.$row[$column[0]].'</td>';
            }
            $totalClicks += $row['clicks'];
            $totalConversions += $row['conversions'];
            $totalRevenues += $row['revenues'];
            $tr.= '<td>'.$row['clicks'].'</td>';
            $tr.= '<td>'.$row['conversions'].'</td>';
            $tr.= '<td>'.$row['revenues'].'</td>';
            $table .= $tr.'</tr>';
            
        }
        $table .= '<tr><td class="bg-primary"><strong>Totals</strong></td>';
        
        for($i=0;$i<(sizeof($columns)-3);$i++)
            $table .= '<td>-</td>';
        $table .= '<td><strong>'.$totalClicks.'</strong></td><td><strong>'.$totalConversions.'</strong></td><td><strong>'.number_format($totalRevenues,2).'</strong></td></tr>';
        $table .= '</tbody></table>
                </div>
            </div>
        </div>';
        return $table;
        
    }
    
    private function genGeneralTable($id, $arr, $arr2, $name) {
        $table = '
            <div class="container well" id="' . $id . '">
                <div class="table-responsive" ><table class="table table-bordered centertext" ><thead>
                    <tr>
                      <th style="vertical-align: middle;" rowspan="2" class="col-xs-1 centertext">' . $name . '</th>
                      <th class="col-xs-3 centertext" rowspan="1" colspan="3">Clicks</th>
                      <th class="col-xs-3 centertext" rowspan="1" colspan="3">Revenue</th>
                      <th class="col-xs-3 centertext" rowspan="1" colspan="3">EPC</th>
                    </tr>
                    <tr>
                      <td class="col-xs-1"><strong>Previous Period</strong></td><td class="col-xs-1"><strong>Current Period</strong></td><td class="col-xs-1"><strong>Variation</strong></td>
                      <td class="col-xs-1"><strong>Previous Period</strong></td><td class="col-xs-1"><strong>Current Period</strong></td><td class="col-xs-1"><strong>Variation</strong></td>
                      <td class="col-xs-1"><strong>Previous Period</strong></td><td class="col-xs-1"><strong>Current Period</strong></td><td class="col-xs-1"><strong>Variation</strong></td>
                    </tr>
                        </thead><tbody>';
        $onlyid = ( $id == 'countrytable');
        $totals = ( $id == 'countrytable' || $id == 'aggregatortable');
        if ($totals) {
            $totalclicks1 = 0;
            $totalrevenue1 = 0;
            $totalclicks2 = 0;
            $totalrevenue2 = 0;
        }
        $j = 0;
        foreach ($arr as $row) {
            $j++;
            if ($j > 12 && $name === 'Source')
                break;
            foreach ($arr2 as $key2 => $row2) {
                if ($row['name'] == $row2['name']) {
                    $clicksVariation = (($row['clicks'] == '' || $row['clicks'] == 0) ? '0' : (($row2['clicks'] - $row['clicks']) / $row['clicks']) * 100);
                    $class1 = $clicksVariation > 0 ? 'text-success' : ($clicksVariation < 0 ? 'text-danger' : '');
                    $revenuesVariation = (($row['revenues'] == '' || $row['revenues'] == 0) ? '0' : (($row2['revenues'] - $row['revenues']) / $row['revenues']) * 100);
                    $class2 = $revenuesVariation > 0 ? 'text-success' : ($revenuesVariation < 0 ? 'text-danger' : '');
                    $epcVariation = (($row['epc'] == '' || $row['epc'] == 0) ? 0 : (($row2['epc'] - $row['epc']) / $row['epc']) * 100);
                    $class3 = $epcVariation === 0 ? '' : ($epcVariation > 0.00 ? 'text-success' : 'text-danger' );
                    $table .= '<tr><td>' . '<strong>' . $row['name'] . '</strong>' . ( $onlyid ? '' : '-' . $row['id'] ) . '</td>'
                            . '<td>' . $row['clicks'] . '</td><td>' . $row2['clicks'] . '</td><td class="' . $class1 . '">' . ($class1 != '' ? '<strong>' : '') . number_format($clicksVariation, 0, ',', '') . '&#37;' . ($class1 != '' ? '</strong>' : '') . '</td>'
                            . '<td>' . $row['revenues'] . '</td><td>' . $row2['revenues'] . '</td><td class="' . $class2 . '">' . ($class2 != '' ? '<strong>' : '') . number_format($revenuesVariation, 0, ',', '') . '&#37;' . ($class2 != '' ? '</strong>' : '') . '</td>'
                            . '<td>' . $row['epc'] . '</td><td>' . $row2['epc'] . '</td><td class="' . $class3 . '">' . ($class3 != '' ? '<strong>' : '') . number_format($epcVariation, 0, ',', '') . '&#37;' . ($class3 != '' ? '</strong>' : '') . '</td>';
                    if ($totals) {
                        $totalclicks1 += $row['clicks'];
                        $totalrevenue1 += $row['revenues'];
                        $totalclicks2 += $row2['clicks'];
                        $totalrevenue2 += $row2['revenues'];
                    }
                    unset($arr2[$key2]);
                    continue 2;
                }
            }
            $table .= '<tr><td><strong>' . $row['name'] . '</strong>' . ( $onlyid ? '' : '-' . $row['id'] ) . '</td>'
                    . '<td>' . $row['clicks'] . '</td><td>-</td><td>-</td>'
                    . '<td>' . $row['revenues'] . '</td><td>-</td><td>-</td>'
                    . '<td>' . $row['epc'] . '</td><td>-</td><td>-</td></tr>';
            if ($totals) {
                $totalclicks1 += $row['clicks'];
                $totalrevenue1 += $row['revenues'];
            }
        }

        if ($name != 'Source') {
            foreach ($arr2 as $row) {//missing rows
                if (!empty($row)) {
                    $table .= '<tr><td><strong>' . $row['name'] . '</strong>' . ( $onlyid ? '' : '-' . $row['id'] ) . '</td><td>-</td><td>' . $row['clicks'] . '</td><td>-</td>'
                            . '<td>-</td><td>' . $row['revenues'] . '</td><td>-</td>'
                            . '<td>-</td><td>' . $row['epc'] . '</td><td>-</td></tr>';
                    if ($totals) {
                        $totalclicks2 += $row['clicks'];
                        $totalrevenue2 += $row['revenues'];
                    }
                }
            }
        }
        if ($totals) {
            $totalepc1 = $totalclicks1 === 0 ? 0 : $totalrevenue1 / $totalclicks1;
            $totalepc2 = $totalclicks2 === 0 ? 0 : $totalrevenue2 / $totalclicks2;
            $clicksVariation = (($totalclicks1 == '' || $totalclicks1 == 0) ? '0' : number_format((($totalclicks2 - $totalclicks1) / $totalclicks1) * 100, 0, ',', ''));
            $class1 = $clicksVariation > 0 ? 'text-success' : ($clicksVariation < 0 ? 'text-danger' : '');
            
            $revenuesVariation = (($totalrevenue1 == '' || $totalrevenue1 == 0) ? '0' : number_format((($totalrevenue2 - $totalrevenue1) / $totalrevenue1) * 100, 0, ',', ''));
            $class2 = $revenuesVariation > 0 ? 'text-success' : ($revenuesVariation < 0 ? 'text-danger' : '');
            $epcVariation = ($totalepc1 === 0 ? 0 : number_format((($totalepc2 - $totalepc1) / $totalepc1) * 100, 2, ',', ''));
            $class3 = ($totalepc1) == 0 ? '' : ((($totalepc2 - $totalepc1) / $totalepc1) > 0.0001 ? 'text-success' : 'text-danger' );

            $totalepc1 = number_format($totalepc1, 4, ',', '');
            $totalepc2 = number_format($totalepc2, 4, ',', '');

            $table .= '<tr><td class="bg-primary"><strong>Totals</strong></td><td><strong>' . $totalclicks1 . '</strong></td><td><strong>' . $totalclicks2 . '</strong></td><td class="' . $class1 . '"><strong>' . $clicksVariation . '&#37;</strong></td>'
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

        $dayArray = array();
        $report = new Report();

        if ($sdate == date('Y-m-d') or $edate == date('Y-m-d')) {

            $dayArray = $report->getDailyResult($param, $country, $logArray[0],$logArray[1]);
        }

        $farray = array();
        if ($sdate != date('Y-m-d')) {
            //Agr Tables
            $filter = ' AND conversions!=0 ';
            if ($param == 'LEFT') {
                $filter = '';
            }

            $farray = $report->getMainResult($sdate, $edate, $filter, $country, $logArray[0], $logArray[1]);
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
        return $final;
    }

    private function main_report_table_rbb($sdate, $edate, $param, $country) {

        $countryAndSources = $this->login_access();

        $dayArray = array();
        $report = new Report();

        if ($sdate == date('Y-m-d') or $edate == date('Y-m-d')) {

            $dayArray = $report->getDailyResult_rbb($param, $country, $countryAndSources[0], $countryAndSources[1]);
        }

        $farray = array();
        if ($sdate != date('Y-m-d')) {
            //Agr Tables
            $filter = ' AND conversions!=0 ';
            if ($param == 'LEFT') {
                $filter = '';
            }

            $farray = $report->getMainResult($sdate, $edate, $filter, $country, $countryAndSources[0], $countryAndSources[1]);
        }
        $specificData = array_merge($farray, $dayArray);
        $result = $specificData;
        return $result;
    }

    private function op_report_table($sdate, $edate, $param, $country) {
        $logArray = $this->login_access();

        $dayArray = array();
        $report = new Report();
        if ($sdate == date('Y-m-d') or $edate == date('Y-m-d')) {

            $dayArray = $report->getOpDailyResult($param, $country,$logArray[0],$logArray[1]);
        }

        $farray = array();
        if ($sdate != date('Y-m-d')) {
            //Agr Tables
            $filter = ' AND conversions!=0 ';
            if ($param == 'LEFT') {
                $filter = '';
            }
            $farray = $report->getOpMainResult($sdate, $edate, $filter, $country, $logArray[0], $logArray[1]);
        }

        $result = array_merge($farray, $dayArray);
        return $result;
    }

    private function report_country_table($sdate, $edate, $param, $country) {

        $logArray = $this->login_access();

        $dayPerCountryArray = array();
        $report = new Report();
        if ($sdate == date('Y-m-d') or $edate == date('Y-m-d')) {

            $dayPerCountryArray = $report->getDailyResAggPerCountry($param, $country, $logArray[0], $logArray[1]);
        }

        $historicalDayPerCountryArray = array();
        if ($sdate != date('Y-m-d')) {
            //Agr Tables
            $filter = ' AND conversions!=0 ';
            if ($param == 'LEFT') {
                $filter = '';
            }
            $historicalDayPerCountryArray = $report->getHistoricalPerCountryResult($sdate, $edate, $filter,$country, $logArray[0], $logArray[1]);
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
        if(sizeof($array) < 1)
                return;
        foreach ($array as $subarray) {
            $keys[] = $subarray[$subkey];
        }
        array_multisort($keys, $sortType, $array);
    }

    private function mj_report_table($sdate, $edate) {

        $report = new Report();
        return $report->getMjResult($sdate, $edate);
    }

    private function op_excel2($result) {

        ini_set("memory_limit", "4096M");
        set_time_limit(0);
        $temp = tmpfile();
        fwrite($temp, "sep=;\nDate;Sub id;Campaign Hash;Campaign;Campaign Country;User Country;Agregator;Source;Operator;Isp;Os;Browser;Mobile Type;Clicks;Conversions;Revenue;EPC;CR;Ad\n");
        foreach ($result as &$row) {
            fwrite($temp, $row['insert_date'] . ';' . $row['sub_id'] . ';' . $row['c_hash'] . ';' . $row['campaign'] . ';' . $row['campaign_country'] . ';'
                    . $row['user_country'] . ';' . $row['agregator'] . ';' . $row['source'] . ';' . $row['operator'] . ';'
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
        }
        header('Content-Type: application/vnd.ms-excel');
        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="MobisteinMJumpFastReport' . time() . '.xls"';
        header($myContentDispositionHeader);
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
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
        
//        if($auth['userlevel'] > 1 ){
//            if(!isset($country))
//                $country='"zz"';
//        }
//        if($auth['userlevel'] > 2 ){
//            if(!isset($sources))
//                $sources = '0';
//            if(!isset($country))
//                $country='"zz"';
//            if(!isset($aggs))
//                $aggs = '0';
//        }
        return array($country, $sources,$aggs, $auth['id']);
    }

    private function generate_main_table($result, $sdate, $edate, $currentDay, $country) {
        $countryAndSources = $this->login_access();
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
        foreach ($result as &$row) {

            $table_html.='<tr><th style="color: black;">' . $row['campaign'] . '</th><th>' . $row['campaign_country'] . '</th>'
                    . '<th>' . $row['agregator'] . '</th>'
                    . '<th>' . $row['sub_id'] . '</th>'
                    . '<th>' . $row['clicks'] . '</th>'
                    . '<th >' . $row['conversions'] . '</th>'
                    . '<th style="color: black;">' . (float) number_format(floatval($row['revenue']), 2) . '</th>'
                    . '<th>' . number_format($row['revenue'] / $row['clicks'], 3) . '</th>'
                    . '<th>' . number_format($row['conversions'] / $row['clicks'] * 100, 2) . '%</th></tr>';
            $revtotal+=$row['revenue'];
            $clicktotal+= $row['clicks'];
            $convtotal+= $row['conversions'];
        }
        $totals = $this->generate_day_total_table($sdate, $edate,$country);
        $revenueColumn = number_format($totals[2], 2);
        if ($currentDay) {
            $avgRes = $this->generate_avg_table(3, $country);
            $revenueColumn = number_format($totals[2], 2) . '&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;' . ($totals[2] ? number_format((($totals[2] - $avgRes[0]['avgRev']) / $totals[2]) * 100, 3) : '0.000') . '&#37;';
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

            $table_html.='<tr><th style="color: black;">' . $row['Country'] . '</th><th>' . $row['Clicks'] . '</th>'
                    . '<th >' . $row['Conversions'] . '</th>'
                    . '<th id="curRevID" style="color: black;">' . number_format($row['Revenues'], 2) . '</th>'
                    . '<th>' . number_format($row['Revenues'] / $row['Clicks'], 3) . '</th>'
                    . '<th>' . number_format($row['Conversions'] / $row['Clicks'] * 100, 2) . '%</th></tr>';
        }
        $table_html.='</tbody></table>';
        return $table_html;
    }

    public function getavgdataAction() {
        if ($this->request->get('day') != null && $this->request->get('country') != null){
            $country = $this->request->get('country') == 'ALL' ? null : $this->request->get('country');
            $res = $this->generate_avg_table($this->request->get('day'), $country);
            $final = '';
            foreach ($res as $row) {
                $final .= ('Last ' . $this->request->get('day') . ' days from 00:00 to ' . date("H") . ':' . ((floor((date("i") * 4) / 60) * 15) == 0 ? '00' : (floor((date("i") * 4) / 60) * 15) ) . ':00') . '~' . number_format($row[0], 0) . '~' . number_format($row[1], 0) . '~' . number_format($row[2], 2) . '~' . number_format($row[3], 3) . '~' . number_format($row[4] * 100, 2);
            }
            echo $final;
        }
    }

    private function generate_avg_table($day, $country) {
        $logArray = $this->login_access();
        
        date_default_timezone_set('Europe/Lisbon');
        $report = new Report();
        $sdate = date("Y-m-d", strtotime(date("Y-m-d") . '-' . $day . ' days'));
        $arr = $report->getLastDaysAvg($sdate, $country, $logArray[0], $logArray[1]);
        return $arr;
    }

    private function generate_day_total_table($sdate, $edate,$country) {
        $logArray = $this->login_access();
        $report = new Report();
        if($sdate == date('Y-m-d') && $edate == date('Y-m-d'))
            return $report->getDayTotal($country,$logArray[0],$logArray[1]);
        
        return $report->getDaysTotal($sdate, $edate,$country,$logArray[0],$logArray[1]);
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
            '47' => array('site,spot,tracking', 'TTrafficJunkiePubIds', 1), '134' => array('pubid,subid,tracking', 'TAvazuPubIds', 0),
            '107' => array('pub_id,tracking', 'TMobusiPubIds', 0),
            '148' => array('zoneid,tracking', 'TClickaduPubIds', 0), '153' => array('zid,tracking', 'TTrafficpenPubIds', 0),
            '154' => array('subid,tracking', 'TMobusiAdnetworkPubIds', 0),'60' => array('src,campaign,tracking', 'THolderPubIds', 0),'165' => array('site,tracking', 'TAdsflyPubIds', 0),
            '166' => array('siteid,host,tracking', 'TExoPubIds2', 0), '167' => array('site,channel,keyword,vos,tracking', 'TForcePubIds2', 0),
            '168' => array('category,tracking', 'TFactoryPubIds2', 0), '169' => array('pub_id,tracking', 'TMobusiPubIds2', 0),'175' => array('p,tracking', 'THeadwayPubIds', 0));	
        //filtrar sources do user
        if(isset($countriesAndSources[1])){
            $arr = explode(',', $countriesAndSources[1]);
            foreach($netArray as $k=>$v){
                 if(!in_array($k, $arr)){
                    unset($netArray[$k]);
                 }
            }
        }
        $source = $this->request->get('so');
        if(!array_key_exists($source, $netArray))
            return;
        $startDate = $this->request->get('s');

        $endDate = $this->request->get('e');
        $convTableMonth = '';
        if (date('Y-m-d') == date('Y-m-d', strtotime($endDate))) {
            $convTableMonth = date('mY', strtotime($endDate));
        }



        $country = $this->request->get('cc');
        $report = new Report();
        $res = $report->getNetworkResult($startDate, $endDate, $convTableMonth, $source, $country,$countriesAndSources[0], $netArray);
        $a = array(0=> array('Date', 's', 0), 1 => array('TotalClick', 'i', 0), 2 => array('TotalConv', 'i', 0), 3 => array('cpa', 'd', 2), 4 => array('Rev', 'd', 2), 5 => array('countryCode', 's', 0));
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
        $res = Null;
        if ($res == null) {
            $mask = new Mask();
            $dbres = $mask->getAllCampaigns();
            $res = '';
            foreach ($dbres as $hashAndCampaign) {
                $res .= '<option value ="' . $hashAndCampaign['hash'] . '" >' . $hashAndCampaign['campaign'] . '</option>';
            }
            $cache->save('campaigns', $res);
        }
        return $res;
    }

    private function getOperators() {
        $cache = $this->di->get("viewCache");
        $arrLogin = $this->login_access();
        $comboString = $cache->get('operators'.$arrLogin[3]);
        $comboString = nUll;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getOperators();
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString.='<option value="' . $row['operator_name'] . '">' . $row['operator_name'] .'</option>';
            }
            $cache->save('operators'.$arrLogin[3], $comboString);
        }
        return $comboString;
    }
    
    private function getAggregators() {
        $cache = $this->di->get("viewCache");
        $arrLogin = $this->login_access();
        $comboString = $cache->get('aggregators'.$arrLogin[3]);
        $comboString = nuLL;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getAggregators($arrLogin[2]);
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString.='<option value="' . $row['id'] . '">' . $row['agregator'] . '-' . $row['id'] . '</option>';
            }
            $cache->save('aggregators'.$arrLogin[3], $comboString);
        }

        return $comboString;
    }

    private function setStatisticsViewVars($s = null, $e = null, $s2 = null, $e2 = null, $country = 0, $agg = null, $campaign = null, $src = null) {
        $res = $this->getCampaigns();
        $aggs = $this->getAggregators();
        $srcs = $this->getSources();
        $operators = $this->getOperators();
        $this->view->setVar("campaignSelectList", $res);
        $this->view->setVar("aggregatorsList", $aggs);
        $this->view->setVar("srclist", $srcs);
        $this->view->setVar("operatorslist", $operators);
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
            $cache->save('sources', $comboString);
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
        while (($buffer = fgets($temp, 4096)) !== false) {
            echo $buffer;
        }
        fclose($temp);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="' . $title . time() . '.csv"';
        header($myContentDispositionHeader);
        header('Expires: 0');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
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

    private function op_excel($result) {

//        echo '<pre>';
//        print_r($result);
//        echo '<pre>';
//        exit();
        ini_set("memory_limit", "4096M");
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
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, 'Sub id');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, 'Campaign Hash');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, 'Campaign');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[4] . $startRow, 'Campaign Country');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[5] . $startRow, 'User Country');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[6] . $startRow, 'Agregator');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[7] . $startRow, 'Source');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[8] . $startRow, 'Operator');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[9] . $startRow, 'Isp');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[10] . $startRow, 'Os');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[11] . $startRow, 'Browser');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[12] . $startRow, 'Mobile Type');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[13] . $startRow, 'Clicks');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[14] . $startRow, 'Conversions');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[15] . $startRow, 'Revenue');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[16] . $startRow, 'EPC');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[17] . $startRow, 'CR');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[18] . $startRow, 'Ad');

//insert_date, sub_id,c_hash,campaign, campaign_country, user_country,agregator, source, operator, isp, os, browser, mobilet, clicks, conversions, revenue        
        foreach ($result as &$row) {
            $startRow++;
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[0] . $startRow, $row['insert_date']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, $row['sub_id']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, $row['c_hash']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, $row['campaign']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[4] . $startRow, $row['campaign_country']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[5] . $startRow, $row['user_country']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[6] . $startRow, $row['agregator']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[7] . $startRow, $row['source']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[8] . $startRow, $row['operator']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[9] . $startRow, $row['isp']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[10] . $startRow, $row['os']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[11] . $startRow, $row['browser']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[12] . $startRow, $row['mobilet']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[13] . $startRow, $row['clicks']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[14] . $startRow, $row['conversions']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[15] . $startRow, $row['revenue']);
            if ($row['clicks'] != 0) {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[16] . $startRow, number_format($row['revenue'] / $row['clicks'], 2));
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[17] . $startRow, number_format($row['conversions'] / $row['clicks'], 2));
            } else {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[16] . $startRow, 0);
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[17] . $startRow, 0);
            }
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[18] . $startRow, $row['ad']);
        }

        header('Content-Type: application/vnd.ms-excel');
        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="MobisteinFastReport' . time() . '.xls"';
        header($myContentDispositionHeader);
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    
    /* public function networkAction() {

      ini_set("memory_limit", "512M");
      set_time_limit(0);
      $auth = $this->session->get('auth');

      $countries = ($auth['countries'] != '') ? explode(',', $auth['countries']) : FALSE;


      $netArray = array('35' => array('cmp,tracking', 'PubIds', 0), '37' => array('plugId,plugsource,plugbrowser,tracking', 'PlugPubIds', 0),
      '59' => array('category,tracking', 'TFactoryPubIds', 0), '70' => array('site,channel,keyword,vos,tracking', 'TForcePubIds', 0),
      '81' => array('zone,tracking', 'TAdcashPubIds', 0), '61' => array('site,category,keyword,tracking', 'TPopadsPubIds', 0),
      '38' => array('zone,tracking', 'TAdexpPubIds', 0), '5' => array('zone,ad,tracking', 'TAdultPubIds', 0),
      '23' => array('siteid,host,tracking', 'TExoPubIds', 0), '91' => array('siteid,host,tracking', 'TExoPubIds', 0),
      '96' => array('subid,tracking', 'TGunggoPubIds', 0), '40' => array('subid,tracking', 'TWebTrafficPubIds', 0),
      '95' => array('subid,tracking', 'TAdamoadsPubIds', 0), '100' => array('subid,tracking', 'TterraPubIds', 0),
      '103' => array('subid,tracking', 'TmglobePubIds', 0), '98' => array('subid,tracking', 'TthuntPubIds', 0),
      '1' => array('subid,cid,tracking', 'TBuzzPubIds', 0), '111' => array('subid,tracking', 'TMobadgePubIds', 0),
      '114' => array('source,tracking', 'TPolluxPubIds', 0), '116' => array('subid', 'TFogzyPubIds', 0),
      '51' => array('site,cat,os,tracking', 'TPopCashPubIds', 0), '127' => array('zone,tracking', 'TMediaHubPubIds', 0),
      '56' => array('src,oid,tracking', 'TTrafficShopPubIds', 0), '94' => array('account,site,tracking', 'TPopundernetPubIds', 0),
      '47' => array('site,spot,tracking', 'TTrafficJunkiePubIds', 1), '134' => array('pubid,subid,tracking', 'TAvazuPubIds', 0),
      '134' => array('pubid,subid,tracking', 'TAvazuPubIds', 0), '107' => array('pub_id,tracking', 'TMobusiPubIds', 0), '148' => array('zoneid,tracking', 'TClickaduPubIds', 0));

      $startDate = $this->request->get('s');

      $endDate = $this->request->get('e');

      $table = date('mY', strtotime($startDate));
      $endTable = date('mY', strtotime($endDate));
      $country = $this->request->get('cc');
      if ($auth['userlevel'] > 2) {
      if (!in_array($_REQUEST['country'], $countries)) {
      $country = 'zz';
      }
      }
      $source = $this->request->get('so');
      $join = 'LEFT';
      if ($source == 91) {
      $join = 'INNER';
      }
      $report = new Report();
      $res = $report->getNetworkResult($startDate, $endDate, $table, $endTable, $join, $source, $country, $netArray);


      $objPHPExcel = new PHPExcel();
      $objPHPExcel->getProperties()->setCreator("mobipium");
      $objPHPExcel->getProperties()->setLastModifiedBy('mobisteinNetworkReport');
      $objPHPExcel->getProperties()->setTitle('MobisteinNetworkReport');
      $objPHPExcel->getProperties()->setSubject('Report');
      $objPHPExcel->getProperties()->setDescription('Auto-generated Report');
      $alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

      $startRow = '4';
      $startData = $startRow + 1;



      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[0] . $startRow, 'TotalClick');
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, 'TotalConv');
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, 'cpa');
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, 'Rev');
      $fields = explode(',', $netArray[$source][0]);




      $b = 4;
      foreach ($fields as &$field) {

      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b] . $startRow, $field);
      $b++;
      }
      if ($netArray[$source][2] == 1) {

      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 1] . $startRow, 'Agregator');
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 2] . $startRow, 'Country');
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 3] . $startRow, 'Client');
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 4] . $startRow, 'Campaign');
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 5] . $startRow, 'Source');
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 6] . $startRow, 'Format');
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 7] . $startRow, 'Ad');
      }

      foreach ($res as &$row) {
      $startRow++;

      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[0] . $startRow, $row['TotalClicks']);
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, $row['TotalConv']);
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, $row['cpa']);
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, $row['Rev']);
      $i = 4;
      foreach ($fields as &$field) {

      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i] . $startRow, $row[$field]);

      $i++;
      }
      if ($netArray[$source][2] == 1) {

      $ftrack = explode('_', $row['tracking']);
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 1] . $startRow, $ftrack[0]);
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 2] . $startRow, $ftrack[1]);
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 3] . $startRow, $ftrack[2]);
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 4] . $startRow, $ftrack[3]);
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 5] . $startRow, $ftrack[4]);
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 6] . $startRow, $ftrack[5]);
      $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 7] . $startRow, $ftrack[6]);
      }
      }
      header('Content-Type: application/vnd.ms-excel');
      $myContentDispositionHeader = 'Content-Disposition: attachment;filename="MobisteinNetworkExcelReport' . time() . '.xls"';
      header($myContentDispositionHeader);
      header('Cache-Control: max-age=0');

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
      exit;
      } */
    
    
    //        $objPHPExcel = new PHPExcel();
//        $objPHPExcel->getProperties()->setCreator("mobipium");
//        $objPHPExcel->getProperties()->setLastModifiedBy('mobisteinNetworkReport');
//        $objPHPExcel->getProperties()->setTitle('MobisteinNetworkReport');
//        $objPHPExcel->getProperties()->setSubject('Report');
//        $objPHPExcel->getProperties()->setDescription('Auto-generated Report');
//        $alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
//
//        $startRow = '4';
//        $startData = $startRow + 1;
//
//
//
//        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[0] . $startRow, 'TotalClick');
//        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, 'TotalConv');
//        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, 'cpa');
//        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, 'Rev');
//        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[4] . $startRow, 'countryCode');
//        $fields = explode(',', $netArray[$source][0]);
//
//
//
//
//        $b = 5;
//        foreach ($fields as &$field) {
//
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b] . $startRow, $field);
//            $b++;
//        }
//        
//        if ($netArray[$source][2] == 1) {
//
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 1] . $startRow, 'Agregator');
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 2] . $startRow, 'Country');
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 3] . $startRow, 'Client');
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 4] . $startRow, 'Campaign');
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 5] . $startRow, 'Source');
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 6] . $startRow, 'Format');
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$b + 7] . $startRow, 'Ad');
//        }
//
//        foreach ($res as &$row) {
//            $startRow++;
//
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[0] . $startRow, $row['TotalClicks']);
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, $row['TotalConv']);
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, $row['cpa']);
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, $row['Rev']);
//            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[4] . $startRow, $row['countryCode']);
//            $i = 5;
//            foreach ($fields as &$field) {
//
//                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i] . $startRow, $row[$field]);
//
//                $i++;
//            }
//            if ($netArray[$source][2] == 1) {
//
//                $ftrack = explode('_', $row['tracking']);
//                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 1] . $startRow, $ftrack[0]);
//                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 2] . $startRow, $ftrack[1]);
//                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 3] . $startRow, $ftrack[2]);
//                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 4] . $startRow, $ftrack[3]);
//                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 5] . $startRow, $ftrack[4]);
//                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 6] . $startRow, $ftrack[5]);
//                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[$i + 7] . $startRow, $ftrack[6]);
//            }
//        }
//        header('Content-Type: application/vnd.ms-excel');
//        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="MobisteinNetworkExcelReport' . time() . '.xls"';
//        header($myContentDispositionHeader);
//        header('Cache-Control: max-age=0');
//
//        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->save('php://output');
//        exit;
}

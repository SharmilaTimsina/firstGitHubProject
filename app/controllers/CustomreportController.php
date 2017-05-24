<?php

class CustomreportController extends ControllerBase {

    private $a = 5;
    private $datearray = null;

    public function initialize() {
        $this->tag->setTitle('Custom Report');
        ini_set("memory_limit", "3000M");
        parent::initialize();
        $this->datearray = array(
            '0' => array(date('Y-m-d'), date('Y-m-d')),
            '1' => array(date('Y-m-d', strtotime("-1 days")), date('Y-m-d', strtotime("-1 days"))),
            '2' => array(date('Y-m-d', strtotime("-3 days")), date('Y-m-d')),
            '3' => array(date('Y-m-d', strtotime("-7 days")), date('Y-m-d')),
            '4' => array(date('Y-m-d', strtotime("-30 days")), date('Y-m-d')),
            '5' => array(date('Y-m-01'), date('Y-m-d')),
            '6' => array(date('Y-m-01', strtotime("first day of previous month")), date('Y-m-d', strtotime("last day of previous month"))));
        ;
    }

    public function indexAction() {
        try {
            $auth = $this->session->get('auth');
            $id = $auth['id'];
            $report = CustomReport::find(array("userid = $id"));
            $table = $this->generatetable($report);
            $this->view->setVar('table', $table);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E'.__CLASS__,$ex->getMessage());
        }
    }

    public function deleteSavedReportAction(){
        try{
            $auth = $this->session->get('auth');
            $userid = $auth['id'];
            $userlevel = $auth['userlevel'];
            $reportid = $this->request->get('reportid');
            $rep = CustomReport::findFirst(array(" id = $reportid AND (userid=$userid OR $userlevel < 2) "));
            $rep->delete();
            $id = $auth['id'];
            $report = CustomReport::find(array("userid = $id"));
            echo $this->generatetable($report);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E'.__CLASS__,$ex->getMessage());
        }
    }
    
    public function createcustomAction() {
        $a = new CustomReport();
        $this->view->setVar('jsonContentSelectBox', json_encode($a->getDims()));
    }

    public function downloadReportAction() {
        
        $userid = $this->session->get('auth')['id'];
        $userlevel = $this->session->get('auth')['userlevel'];
        //countries sources aggs authid sourcetype(0,1,2,3,4)adulto afiliados mainstream 3 guille ve tudo menos afiliados 4 facha tudo menos mainstream
        $oldpermissions = $this->login_access();
        
        if($this->request->getPost('reportid') == null)
            exit();
        $reportid = $this->request->getPost('reportid');
        $rep = CustomReport::findFirst(array('id = '.$reportid.' AND (userid = '.$userid.' OR '.$userlevel.' <2)'));
        if(empty($rep))
            return null;
        
        $selectedids = explode(',', $rep->selects);
        $filterids = unserialize($rep->filters);
        
//        $var = unserialize('a:4:{i:10;a:3:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";}i:15;a:1:{i:0;s:7:"127_3_5";}i:17;a:2:{i:0;s:2:"MX";i:1;s:2:"AR";}i:45;a:3:{i:0;s:1:"2";i:1;s:1:"3";i:2;s:1:"4";}}');
//        print_r($var);
//        exit();
        //$filterids = array(2 => "vuClip");
        $filterids = CustomQuery::swapfilterkeys($filterids);
        $hourly = isset($rep->hourb) ? true : false;
        if($hourly){
            if($this->request->getPost('hourb') != null)
                $filterids['101'] = array(
                    $this->request->getPost('hourb'),
                    $this->request->getPost('houre'));
            else    
                $filterids['101'] =  array($rep->hourb.':00:00',$rep->houre.':00:00');
        }
        $date = $this->request->getPost('dateb') != null ? array(date('Y-m-d',strtotime($this->request->getPost('dateb'))), 
            date('Y-m-d',strtotime($this->request->getPost('datee')))) : null;
        $filterids['100'] = isset($date) ? $date : $this->datearray[$rep->date];
        if(!isset($filterids['100']))
            return;
        $aggregation = 'SUM';
        $orderby = array(401 => 0, 2 => 1);
        
        
        //
        if($this->request->getPost('page') != null){
            $page = $this->request->getPost('page');
        } else
            $page = null;
        
        $result = CustomQuery::generateCustomQuery($selectedids, $filterids, $hourly, $aggregation, $selectedids, 1, $oldpermissions,$page);
        //print_r($result[0]);
        //echo $result[1];
        //print_r($result[2]);
        $columns='';
        $i=0;
        
        foreach($result[2] as $selecttypes){
            $key = $selecttypes['id'];
            $columns .= $selecttypes['attname'].',';
            $selecttype = $selecttypes['columntype']=='number'?'d':'s';
            //echo $key.'<br>';
            $decimaltype = ($key == 406 ? 4 : (($key==404 || $key == 403) ? 2 :0));
            
            $a[$i] = array($selecttypes['attname'], $selecttype, $decimaltype);
            $i++;
            
            
        }
        //print_r($a);
        if($this->request->getPost('html') != null){
            echo HelpingFunctions::tablehtmlRes($a, rtrim($columns,','), $result[0], 'Custom Report ',$result[3]);
            exit();
        }
        HelpingFunctions::excelRes($a, rtrim($columns,','), $result[0], 'Custom Report ');
    }

    public function echoReportAction() {
        
        $userid = $this->session->get('auth')['id'];
        $userlevel = $this->session->get('auth')['userlevel'];
        $oldpermissions = $this->login_access();
        if($this->request->get('reportid') == null)
            exit();
        $reportid = $this->request->get('reportid');
        $rep = CustomReport::findFirst(array('id = '.$reportid.' AND (userid = '.$userid.' OR '.$userlevel.' <2)'));
        if(empty($rep))
            return null;
        
        $selectedids = explode(',', $rep->selects);
        $filterids = unserialize($rep->filters);
        
//        $var = unserialize('a:4:{i:10;a:3:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";}i:15;a:1:{i:0;s:7:"127_3_5";}i:17;a:2:{i:0;s:2:"MX";i:1;s:2:"AR";}i:45;a:3:{i:0;s:1:"2";i:1;s:1:"3";i:2;s:1:"4";}}');
//        print_r($var);
//        exit();
        //$filterids = array(2 => "vuClip");
        $filterids = CustomQuery::swapfilterkeys($filterids);
        $hourly = isset($rep->hourb) ? true : false;
        if($hourly){
            if($this->request->getPost('hourb') != null)
                $filterids['101'] = array(
                    $this->request->getPost('hourb').':00:00',
                    $this->request->getPost('houre').':00:00',);
            else    
                $filterids['101'] =  array($rep->hourb.':00:00',$rep->houre.':00:00');
        }
        $date = $this->request->getPost('dateb') != null ? array(date('Y-m-d',strtotime($this->request->getPost('dateb'))), 
            date('Y-m-d',strtotime($this->request->getPost('datee')))) : null;
        $filterids['100'] = isset($date) ? $date : $this->datearray[$rep->date];
        if(!isset($filterids['100']))
            return;
        $aggregation = 'SUM';
        $orderby = array(401 => 0, 2 => 1);
        
        
        //
        if($this->request->getPost('page') != null){
            $page = $this->request->getPost('page');
        }
        if(!isset($page))
            $page = null;
        $result = CustomQuery::generateCustomQuery($selectedids, $filterids, $hourly, $aggregation,$selectedids, 1, $oldpermissions,$page);
        //print_r($result);
        echo $result[1];
        exit();
//print_r($result[2]);
        $columns='';
        $i=0;
        
        foreach($result[2] as $selecttypes){
            $columns .= $selecttypes['attname'].',';
            $selecttype = $selecttypes['columntype']=='number'?'d':'s';
            $decimaltype = $selecttypes['id'] == 406 ? 4 : $selecttypes['id']==404 && $selecttypes['id'] == 403 ? 2 :0;
            
            $a[$i] = array($selecttypes['attname'], $selecttype, $decimaltype);
            $i++;

        }
        
        //print_r($a);
        if($this->request->get('html') != null){
            echo HelpingFunctions::tablehtmlRes($a, rtrim($columns,','), $result[0], 'Custom Report ',$result[3]);
            exit();
        }
        HelpingFunctions::excelRes($a, rtrim($columns,','), $result[0], 'Custom Report ');
    }
    
    
    
    
    private function generatetable($report) {
        $table = '';
        if (!empty($report)) {
            foreach ($report as $r) {
                
                $begindate = is_array($this->datearray[$r->date]) ? $this->datearray[$r->date][0] : $this->datearray[$r->date];
                $beginhour = is_numeric($r->hourb) ? $r->hourb . ':00' : '';
                $enddate = is_array($this->datearray[$r->date]) ? $this->datearray[$r->date][1] : '';
                $endhour = !empty($r->houre) ? $r->houre . ':59' : '';
                $timevar = ' time="' . ($beginhour != '' ? 'true' : 'false') . '" ';
                $startdatevar = 'startDate="' . $begindate . ($beginhour != '' ? ' ' . $beginhour : '') . '"';
                $datebeginclean = 'datebeginclean="' . $begindate . '"';
                $dateendclean = 'dateendclean="' . $enddate . '"';
                $endatevar = 'endDate="' . $enddate . ($endhour != '' ? ' ' . $endhour : '') . '"';
                $starttime = 'startTime="' . $beginhour . '"';
                $endtime = 'endTime="' . $endhour . '"';
                
                
                
                $table .= "<tr id=" . $r->id . ">
   <td class=\"checkclass\"><input name=\"checkimgdate\" type=\"checkbox\" class=\"checkBoxDate\"></td>
   <td>$r->name</td>
   <td>$r->description</td>
   <td  class=\"dateforpost\" $timevar $startdatevar $datebeginclean $dateendclean $endatevar $starttime $endtime id=\"datePicker$r->id\";>" . $begindate . ($enddate != '' ? " to $enddate" : '') . ($beginhour != '' ? " ($beginhour to $endhour)" : '' ) . '</td>
   <td class="checkclass"><a href="http://mobisteinreport.com/customreport/createcustom?rid='.$r->id.'"><img class="modalIcon" type="edit" src="/img/editCR.svg" idReport="'.$r->id.'"></a></td>
   <td class="checkclass"><img class="modalIcon" type="download" src="/img/dloadCR.svg" idReport="'.$r->id.'"></td>
   <td class="checkclass"><img class="modalIcon" type="preview" src="/img/previewCR.svg" idReport="'.$r->id.'"></td>
   <td class="checkclass"><img class="modalIcon" type="delete" src="/img/trashCR.svg" idReport="'.$r->id.'"></td>
  </tr>';

            }
        }
        return $table;
    }

    public function savecustomreportAction() {
        try {
            $select = array();
            if ($this->request->getPost('columnsOrder') != null) {
                $select = $this->request->getPost('columnsOrder');
            }
            $filter = array();
            if ($this->request->getPost('filters') != null) {
                $a = json_decode(json_decode(json_encode($this->request->getPost('filters')), true), true);
                foreach ($a as $key => $values) {
                    foreach ($values as $val)
                        $filter[$key] = explode(',', $val);
                }
            }
            $filter = serialize($filter);

            $date = $this->request->getPost('date') != null ? $this->request->getPost('date') : null;
            $hourb = $this->request->getPost('hourB') != null ? $this->request->getPost('hourB') : null;
            $houre = $this->request->getPost('hourE') != null ? $this->request->getPost('hourE') : null;
            $hourb = isset($hourb) && $hourb < 10 ? '0' . $hourb : $hourb;
            $houre = isset($houre) && $houre < 10 ? '0' . $houre : $houre;
            $agregation = $this->request->getPost('agregation') != null ? $this->request->getPost('agregation') : 'SUM';
            $orderby = $this->request->getPost('orderby') != null ? $this->request->getPost('orderby') : 'DESC';

            $userid = $this->session->get('auth')['id'];
            $name = $this->request->getPost('name') != null ? $this->request->getPost('name') : null;
            $description = $this->request->getPost('description') != null ? $this->request->getPost('description') : null;
            $reportid = $this->request->getPost('reportid') != null ? $this->request->getPost('reportid') : null;
            $userlevel = $this->session->get('auth')['userlevel'];
            if (isset($reportid))
                $report = CustomReport::findFirst(array(
                            " id = $reportid AND (userid=$userid OR $userlevel < 2) "));
            if (!empty($report)) {
                $a = $report;
            } else {
                $a = new CustomReport();
            }
            $a->userid = $userid;
            $a->name = $name;
            $a->selects = $select;
            $a->filters = $filter;
            $a->orderby = $orderby;
            $a->date = $date;
            $a->hourb = $hourb;
            $a->houre = $houre;
            $a->aggregation = $agregation;
            $a->description = $description;
            $a->insertTimestamp = date('Y-m-d H:i:s');
            if ($a->save() == false) {
                $messages = $a->getMessages();
                foreach ($messages as $message) {
                    echo $message, "\n";
                }
                return;
            }
            echo '0';
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E'.__CLASS__,$ex->getMessage());
            echo 'Something went wrong, contact system administrators like Martim';
        }
    }

    public function getAttributesAction() {
        try {
            $this->view->disable();
            $result = array();
            $cache = $this->di->get("viewCache");
            $resAttributes = $cache->get('reportingattributes');
            $resAttributes = null;
            if ($resAttributes == null) {
                $repAtt = new CustomReport();
                $resAttributes = $repAtt->getattributes();
                $cache->save('reportingattributes', $resAttributes, 900);
            }
            $resmeasures = $cache->get('reportingmeasures');
            $resmeasures = null;
            if ($resmeasures == null) {
                $repAtt = new CustomReport();
                $resmeasures = $repAtt->getmeasures();
                $cache->save('reportingmeasures', $resmeasures, 900);
            }
            $result['attributes'] = $resAttributes;
            $result['measures'] = $resmeasures;
            if ($this->request->get('reportid') != null)
                $reportid = $this->request->get('reportid');
            if (isset($reportid)) {
                $auth = $this->session->get('auth');
                $userid = $auth['id'];
                $userlevel = $auth['userlevel'];
                $report = CustomReport::findFirst(array(
                            " id = $reportid AND (userid=$userid OR $userlevel < 2) "));
                
                if (!empty($report)) {
                    $resultw = array();
                    $resultw['report']['id'] = $report->id;
                    $resultw['report']['name'] = $report->name;
                    $resultw['report']['selects'] = $report->selects;
                    $arr = array_merge(explode(',',$report->selects),array_keys(unserialize($report->filters)));
                    
                    $sql ='SELECT GROUP_CONCAT(disabledids SEPARATOR "," ) as disabledids '
                            . "FROM reporting__rattributes "
                            . "WHERE attid IN (".implode(',',$arr).")";
                    $statement = $this->getDi()->getDb4()->prepare($sql);
                    $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
                    $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);     
                    if(!empty($array_ret)){
                        $resultw['report']['disabledids'] = array_unique(explode(',',$array_ret[0]['disabledids']));
                    }
                    $resultw['report']['filters'] = unserialize($report->filters);
                    $resultw['report']['orderby'] = $report->orderby;
                    $resultw['report']['date'] = $report->date;
                    $resultw['report']['hourB'] = $report->hourb;
                    $resultw['report']['hourE'] = $report->houre;
                    $resultw['report']['description'] = $report->description;
                    echo json_encode($resultw);
                    return;
                }
                else{
                    echo 0;
                    return;
                }
            }
            
            echo json_encode($result);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E'.__CLASS__,$ex->getMessage());
        }
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
        } else if ($auth['userlevel'] == 1 && ($auth['id'] == 3)) { // hello I'm Guille and Facha and I'm different
            $aff = 3;
        } else if ($auth['userlevel'] == 1 && ($auth['id'] == 2)) { // hello I'm Guille and Facha and I'm different, 
            $aff = 4;
        }
        return array($country, $sources, $aggs, $auth['id'], $aff);
    }

}

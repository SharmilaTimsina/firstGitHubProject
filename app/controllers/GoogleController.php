<?php

class GoogleController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Dashboard');
        parent::initialize();
    }

    public function indexAction() {
        try {
            $this->view->setVar('userid',$this->session->get('auth')['id']);
            $this->view->setVar("userLevel", $this->session->get('auth')['userlevel']);
            $sql = 'SELECT s.id as id , s.sourceName as name FROM Sources s INNER JOIN C__sourcesMetadata ss on s.id = ss.source WHERE ss.httpsConn = 2 OR ss.httpsConn = 3 ';
            $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
            $finalres = '';
            if (!empty($res)) {
                foreach ($res as $row) {
                    $finalres .= '<option value="' . $row['id'] . '">' . $row['id'] . '-' .$row['name']  . '</option>';
                }
            }
            $this->view->setVar('googlesources', $finalres);
            
             $sql = 'SELECT s.id as id , s.sourceName as name FROM Sources s LEFT JOIN C__sourcesMetadata ss on s.id = ss.source WHERE s.affiliate = 2 and ss.source IS NULL and s.sourceName NOT LIKE "%facebook%" ';
             $res2 = $this->getDi()->getDb4()->query($sql)->fetchAll();
            $finalres2 = '';
            if (!empty($res2)) {
                foreach ($res2 as $row) {
                    $finalres2 .= '<option value="' . $row['id'] . '">' . $row['id'] . '-' .$row['name'] . '</option>';
                }
            }
            $this->view->setVar('googlesources2', $finalres2);
        } catch (Exception $e) {
            echo 'problem on this function. contact it team or re-login' . $e->getMessage();
        }
    }

    public function addgooglesourceAction() {
        try {
            $db = $this->getDi()->getDb4();
            $db->begin();
            $newgoogle = $this->request->getPost('newgoogle');
            $sql = 'INSERT INTO `Overhead_meta`(`source`, `param`, `encode`, `custom`) SELECT :sourceid,param,encode,custom FROM Overhead_meta WHERE source = 1219 ;';
            $db->query($sql, array('sourceid'=>$newgoogle));
            $sql = 'INSERT INTO `C__sourcesMetadata` (`source`, `urlcb`, `paramsid`, `payoutRev`, `httpsConn`, `country`) SELECT :sourceid,urlcb,paramsid,payoutRev,httpsConn,country FROM C__sourcesMetadata WHERE source = 1219 ;';
            $db->query($sql, array('sourceid'=>$newgoogle));
            $db->commit();
            echo 0;
        } catch (Exception $ex) {
            if(isset($db))
                $db->rollback();
             echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            return null;
            
        }
    }

    
    public function downloadreportAction() {
        try {
            $stime = $this->request->get('sdate');
            $etime = $this->request->get('edate');
            $sourceid = $this->request->get('source');
            $temp = tmpfile();
            $sql = 'SELECT gclid, "firstofflineconv" as convname, convtimestamp , payout, "EUR" as currency FROM GoogleConversions Where source =  ' . $sourceid . ' AND status = 5 AND CAST(convtimestamp AS DATE) BETWEEN "' . $stime . '" AND "' . $etime . '"';
            //echo $sql;
            //$res = $this->getDi()->getDb2()->query($sql)->fetchAll();
            $res = array();
            $sql2 = 'SELECT gclid, "firstofflineconv" as convname, convtimestamp , payout, "EUR" as currency FROM GoogleConversions Where source =  ' . $sourceid . ' AND status = 5 AND CAST(convtimestamp AS DATE) BETWEEN "' . $stime . '" AND "' . $etime . '"';
            //echo $sql;
            $res2 = $this->getDi()->getDb7()->query($sql2)->fetchAll();
            $res = array_merge($res, $res2);
            fwrite($temp, 'Parameters:TimeZone=Europe/London,,,,' . "\n" . 'Google Click ID,Conversion Name,Conversion Time,Conversion Value,Conversion Currency' . "\n");
            if (!empty($res)) {
                foreach ($res as $row) {
                    fwrite($temp, $row['gclid'] . ',' . ($sourceid == 455 ? 'firstofflineconv1' : $row['convname']) . ',' . $row['convtimestamp'] . ',' . $row['payout'] . ',' . $row['currency'] . "\n");
                }
            }
            fseek($temp, 0);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            $myContentDispositionHeader = 'Content-Disposition: attachment;filename=GoogleConvs' . $sourceid . '.csv';
            header($myContentDispositionHeader);
            header('Expires: 0');
            header('Cache-Control: max-age=0');
            header('Pragma: public');

            while (($buffer = fgets($temp, 4096)) !== false) {
                echo $buffer;
            }
            fclose($temp);
        } catch (Exception $e) {
            echo 'problem on this function. contact it team or re-login' . $e->getMessage();
        }
    }

}

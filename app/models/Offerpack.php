<?php

use Phalcon\Mvc\Model;

class Offerpack extends Model {

    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('offerpack__offerpack');
    }

    public function getCampaigns($auth) {
        $forbiddensql = '';
        if ($auth['userlevel'] == 2 && $auth['utype'] == 0 && $auth['userarea'] != 1) {//CM's Ivo Pedro and so ON
            $forbiddensql = ' WHERE a.cname NOT IN (2) ';
        } else if ($auth['userlevel'] == 3 && $auth['utype'] == 0 && $auth['userarea'] == 1 && $auth['id'] != 25) {//SALES
            $userarea = 1; //Antonio SALAS
            $forbiddensql = ' WHERE a.cname NOT IN (2) ';
        } else if ($auth['userlevel'] == 2 && $auth['utype'] == 2) { // mainstream CM's  like Arrojado
            $forbiddensql = ' WHERE a.cname NOT IN (2) ';
        }
        $statement = $this->getDi()->getDb4()->prepare('SELECT o.id as id, o.hashMask as hash, o.offername as name FROM  offerpack__offerpack o inner join Mask m ON m.hash = o.hashMask inner join Agregators a ON a.id = m.agregator ' . $forbiddensql);
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getJumps() {
        $statement = $this->getDi()->getDb4()->prepare('SELECT Mask.id, hash , campaign as name FROM  Mask INNER JOIN offerpack__offerpack op ON op.hashMask = hash');
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    private function filterToString($value) {
        $values = explode(',', $value);
        $valuesString = '';
        foreach ($values as $value) {
            $valuesString .= "'" . $value . "',";
        }
        $valuesString = rtrim($valuesString, ",");

        return $valuesString;
    }

    public function getFilteredContent($array_filter, $auth) {

        $domain = ($auth['userlevel'] == 2 ||
                $auth['userlevel'] == 1 ||
                $auth['userlevel'] == 0) ? 'http://jump.youmobistein.com/?jp=' : 'http://jump.mobipiumlink.com/?jp=';
        $array_sql = array();
        if ($auth['userlevel'] == 2 && $auth['utype'] == 0 && $auth['userarea'] != 1) {//CM's Ivo Pedro and so ON
            $countrycms = str_replace(',', '","', $auth['countries']);
            $forbiddensql = ' AND ma.country IN ("' . $countrycms . '") AND a.cname NOT IN (2) ';
        } else if ($auth['userlevel'] == 3 && $auth['utype'] == 0 && $auth['userarea'] == 1 && $auth['id'] != 25) {//SALES
            $userarea = 1; //Antonio SALA
            //$forbiddensql = ' AND ma.agregator IN (' . $auth['aggregators'] . ') AND a.cname NOT IN (2) ';
            $forbiddensql = ' AND a.cname NOT IN (2) ';
        } else if ($auth['userlevel'] == 2 && $auth['utype'] == 2) { // mainstream CM's  like Arrojado
            $countrycms = (empty($auth['countries']) ? null : str_replace(',', '","', $auth['countries']));
            $forbiddensql = ' AND ma.aff_flag IN (2,4) ' . (isset($countrycms) ? ' AND ma.country IN ("' . $countrycms . '") AND a.cname NOT IN (2) ' : '' );
        }
        foreach ($array_filter as $key => $value) {
            if ($value != '' && $value != 'ALL' && $value != 'All' && $value != 'null') {
                switch ($key) {
                    case 'countries':
                        array_push($array_sql, " ma.country IN (" . $this->filterToString($value) . ")");
                        break;
                    case 'carriers':
                        array_push($array_sql, " op.carrier IN (" . $this->filterToString($value) . ")");
                        break;
                    case 'aggs':
                        array_push($array_sql, " ma.agregator IN (" . $this->filterToString($value) . ")");
                        break;
                    case 'area':
                        array_push($array_sql, " op.area IN (" . $this->filterToString($value) . ")");
                        break;
                    case 'vertical':
                        array_push($array_sql, " op.verticalid IN (" . $this->filterToString($value) . ")");
                        break;
                    case 'model':
                        array_push($array_sql, " op.modelid IN (" . $this->filterToString($value) . ")");
                        break;
                    case 'status':
                        array_push($array_sql, " op.status IN (" . $this->filterToString($value) . ")");
                        break;
                    case 'account':
                        array_push($array_sql, " op.accountmanager IN (" . $this->filterToString($value) . ")");
                        break;
                    case 'campaign_name':
                        array_push($array_sql, " op.offername IN (" . $this->filterToString($value) . ")");
                        break;
                    case 'jump_name':
                        array_push($array_sql, " ma.campaign IN (" . $this->filterToString($value) . ")");
                        break;
                    case 'searchInput':
                        array_push($array_sql, " ( ma.campaign LIKE '%$value%' OR op.offername LIKE '%$value%' OR ma.rurl LIKE '%$value%' OR op.ownership LIKE '%$value%' OR op.description LIKE '%$value%' OR op.vertical LIKE '%$value%' OR op.flow LIKE '%$value%' OR op.carrier LIKE '%$value%' OR op.hashMask LIKE '$value' ) ");
                        break;
                }
            }
        }

        $imploded = '';
        if (sizeof($array_sql) > 0)
            $imploded = ' AND' . implode(' AND ', $array_sql);
        $sql = "SELECT op.id, op.hashMask as mask, CAST(op.insertTimestamp as DATE) as insertTimestamp, ma.country, op.carrier, ma.agregator as advertiser, "
                . "op.offername as campaign_name, op.area, op.vertical, ma.campaign as jumpname , op.modelid, (CASE WHEN ma.cpaOriginalValue = '' OR ma.cpaOriginalValue IS NULL THEN ma.cpa ELSE ma.cpaOriginalValue END ) as cpa, ma.curtype, "
                . "CAST(op.dailycap as DECIMAL(10,2)) as dailycap, op.status, op.accountmanager, op.campaignmanager, "
                . "op.status,CONCAT('" . $domain . "',op.hashMask,'&id=', ma.agregator,'_',ma.country,'_1_',ma.campaign,'_99_99_99') as jumpurl, ma.rurl as clienturl,  (
        CASE
            WHEN op.screenshot IS NULL
            THEN ''
            ELSE CONCAT('http://mobisteinreport.com/offerpack/getScreenshot?offerhash=',op.hashMask)
        END
      ) AS imageurl, (CASE WHEN B.bannerhash IS NULL THEN '' ELSE 1 END ) as banner   "
                . "FROM offerpack__offerpack op INNER JOIN Mask ma ON op.hashMask = ma.hash INNER JOIN Agregators a ON ma.agregator = a.id "
                . " LEFT JOIN (SELECT offerhash as bannerhash FROM offerpack__offerbanners GROUP BY offerhash ) B ON B.bannerhash = op.hashMask WHERE op.status != '4' " . $imploded . ' '
                . (isset($forbiddensql) ? $forbiddensql : '' )
                . 'ORDER BY ma.insertTimestamp DESC LIMIT 100';
        $statement = $this->getDi()->getDb4()->prepare($sql);
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        //mail('pedrorleonardo@gmail.com', 'sql', $sql);
        $result = $res->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getFilteredContent2($array_filter, $auth, $excel = 0) {
        try {
            ini_set("memory_limit", "2048M");


            $domain = ($auth['userlevel'] == 2 ||
                    $auth['userlevel'] == 1 ||
                    $auth['userlevel'] == 0) ? 'http://jump.youmobistein.com/?jp=' : 'http://jump.mobipiumlink.com/?jp=';
            $array_sql = array();
            if ($auth['userlevel'] == 2 && $auth['utype'] == 0 && $auth['userarea'] != 1) {//CM's Ivo Pedro and so ON
                $countrycms = str_replace(',', '","', $auth['countries']);
                $forbiddensql = ' AND ma.country IN ("' . $countrycms . '") AND a.cname NOT IN (2) ';
            } else if ($auth['userlevel'] == 3 && $auth['utype'] == 0 && $auth['userarea'] == 1 && $auth['id'] != 25) {//SALES
                $userarea = 1; //Antonio SALA
                //$forbiddensql = ' AND ma.agregator IN (' . $auth['aggregators'] . ') AND a.cname NOT IN (2) ';
                $forbiddensql = ' AND a.cname NOT IN (2) ';
            } else if ($auth['userlevel'] == 2 && $auth['utype'] == 2) { // mainstream CM's  like Arrojado
                $countrycms = (empty($auth['countries']) ? null : str_replace(',', '","', $auth['countries']));
                $forbiddensql = ' AND ma.aff_flag IN (2,4) ' . (isset($countrycms) ? ' AND ma.country IN ("' . $countrycms . '") AND a.cname NOT IN (2) ' : '' );
            }
            foreach ($array_filter as $key => $value) {
                if ($value != '' && $value != 'ALL' && $value != 'All' && $value != 'null') {
                    $value = trim($value);
                    switch ($key) {
                        case 'countries':
                            array_push($array_sql, " ma.country IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'carriers':
                            array_push($array_sql, " op.carrier IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'aggs':
                            array_push($array_sql, " ma.agregator IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'area':
                            array_push($array_sql, " op.area IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'vertical':
                            array_push($array_sql, " op.verticalid IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'model':
                            array_push($array_sql, " op.modelid IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'status':
                            array_push($array_sql, " op.status IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'account':
                            array_push($array_sql, " op.accountmanager IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'campaign_name':
                            array_push($array_sql, " op.offername IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'jump_name':
                            array_push($array_sql, " ma.campaign IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'exclusive':
                            array_push($array_sql, " op.exclusive IN (" . $this->filterToString($value) . ")");
                            break;
                        case 'searchInput':
                            array_push($array_sql, " ( ma.campaign LIKE '%$value%' OR op.offername LIKE '%$value%' OR ma.rurl LIKE '%$value%' OR op.ownership LIKE '%$value%' OR op.description LIKE '%$value%' OR op.vertical LIKE '%$value%' OR op.flow LIKE '%$value%' OR op.carrier LIKE '%$value%' OR op.hashMask LIKE '$value' ) ");
                            break;
                    }
                }
            }
            http://mobisteinreport.com/offerpack/getzip?offerhash=' . $offerhash . '&type=screenshot
            $imploded = '';
            $stat = ' AND op.status != 4 ';
            if (sizeof($array_sql) > 0)
                $imploded = ' AND' . implode(' AND ', $array_sql);
            if (strpos($imploded, ' op.status IN') !== false) {
                $stat = '';
                //mail('pedrorleonardo@gmail.com', 'entrei', 'fuck');
            }
            $sql = "SELECT op.id, op.hashMask as mask,op.description as comments, CAST(op.insertTimestamp as DATE) as insertTimestamp, ma.country, op.carrier, ma.agregator as advertiser, "
                    . "op.offername as campaign_name, op.area, op.vertical, ma.campaign as jumpname , op.modelid, (CASE WHEN ma.cpaOriginalValue = '' OR ma.cpaOriginalValue IS NULL THEN ma.cpa ELSE ma.cpaOriginalValue END ) as cpa, ma.curtype, "
                    . "CAST(op.dailycap as DECIMAL(10,2)) as dailycap, op.status, op.accountmanager, op.campaignmanager, "
                    . "op.status,CONCAT('" . $domain . "',op.hashMask,'&id=', ma.agregator,'_',ma.country,'_1_',ma.campaign,'_99_99_99') as jumpurl, ma.rurl as clienturl,  (
        CASE
            WHEN Sc.location IS NOT NULL
            THEN CONCAT('http://mobisteinreport.com/',REPLACE(location, '/home/whatsapp/public_html/mobisteinreport.com/public/', ''))
            ELSE ''
        END
      ) AS imageurl, (CASE WHEN B.bannerhash IS NULL THEN '' ELSE CONCAT('http://mobisteinreport.com/offerpack/getzip?offerhash=',B.bannerhash,'&type=banner') END ) as banner, IFNULL(cpahistory,'') cpahistory, IFNULL(J.statushistory,'') statushistory,exclusive,
      (CASE WHEN Sc.schash IS NULL THEN '' ELSE CONCAT('http://mobisteinreport.com/offerpack/getzip?offerhash=',Sc.schash,'&type=screenshot') END ) as screenshotzip "
                    . ($excel ? " ,a.agregator AS advertiserName, offmodel.name as offermodel, offstatus.name as offerstatus, offareatype.name as offerarea,u.name as AccountManager, u2.name as CampaignManager,CASE WHEN exclusive = 2 THEN 'Exclusive Google' WHEN exclusive = 1 THEN 'Exclusive' ELSE 'Not Exclusive' END as offerexclusive " : '')
                    . " FROM offerpack__offerpack op INNER JOIN Mask ma ON op.hashMask = ma.hash INNER JOIN Agregators a ON ma.agregator = a.id "
                    . " LEFT JOIN (SELECT MAX(location) as location, offerhash as schash FROM offerpack__offerscreenshotfiles GROUP BY offerhash ) Sc ON Sc.schash = op.hashMask "
                    . " LEFT JOIN (SELECT offerhash as bannerhash FROM offerpack__offerbannerfiles GROUP BY offerhash ) B ON B.bannerhash = op.hashMask "
                    . " LEFT JOIN (SELECT SUBSTRING_INDEX(GROUP_CONCAT(CASE WHEN currency = 'USD' THEN cpa ELSE cpaOriginalValue END,'|',insertDate,'|',currency ORDER BY insertDate DESC), ',', 3) as cpahistory,hashmask FROM `offerpack__cpahistory` GROUP BY hashmask ) U ON U.hashmask = op.hashmask "
                    . " LEFT JOIN (SELECT SUBSTRING_INDEX(GROUP_CONCAT(offstatuss.name,'|',insertDate,'|',IFNULL(statusreason,'') ORDER BY insertDate DESC), ',', 3) as statushistory,hashmask FROM `offerpack__statushistory` opp LEFT JOIN offerpack__status offstatuss ON offstatuss.id = opp.status GROUP BY hashmask ) J ON J.hashmask = op.hashmask "
                    . ($excel ? " LEFT JOIN users u ON u.id = op.accountmanager LEFT JOIN users u2 ON u2.id = op.campaignmanager LEFT JOIN offerpack__model offmodel ON offmodel.id = op.modelid LEFT JOIN offerpack__status offstatus ON offstatus.id = op.status LEFT JOIN offerpack__areatype offareatype ON offareatype.id = op.area " : '')
                    . " WHERE 1 = 1 " . $stat . $imploded . ' '
                    . (isset($forbiddensql) ? $forbiddensql : '' )
                    . 'ORDER BY ma.insertTimestamp DESC '
                    . ($excel ? "" : ' LIMIT 60');
            $statement = $this->getDi()->getDb4()->prepare($sql);
            $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            //mail('pedrorleonardo@gmail.com', 'sql', $sql);
            $result = $res->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            return null;
        }
    }

    public function updateCpa($array_filter) {
        $statement = $this->getDi()->getDb4()->prepare("UPDATE Mask SET cpa='$array_filter[cpa]' WHERE hash IN (" . $this->filterToString($array_filter['ids']) . ")");
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
    }

    public function disableOffer($hash) {
        $statement = $this->getDi()->getDb4()->prepare("UPDATE offerpack__offerpack SET status='4' WHERE hashMask = '$hash'");
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
    }

}

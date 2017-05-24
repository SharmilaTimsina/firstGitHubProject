<?php

use Phalcon\Mvc\Model;

class Integration extends Model {

    public function get_conversionReport($data_array) {

        try {
            $db2 = $this->getDi()->getDb2();
            $db7 = $this->getDi()->getDb7();
            $db4 = $this->getDi()->getDb4();
            $monthYear = explode("/", $data_array['yearAndMonth']);
            $table_name = "Conversions" . $monthYear[0] . $monthYear[1];
            if ($monthYear[0] >= 08 && $monthYear[1] >= 2016) {
                $sqloriginaljp = ',originalJP as requestmade';
            }
            $start_date = $monthYear[1] . '-' . $monthYear[0] . '-' . $data_array['startDay'];
            $end_date = $monthYear[1] . '-' . $monthYear[0] . '-' . $data_array['endDay'];
            $duplicate = $data_array['duplicate'];
            $agregator_id = $data_array['agregator'];

            $rest = "";
            if ($duplicate)
                $rest = " GROUP BY clickId ";


            $ojp = isset($sqloriginaljp) ? $sqloriginaljp : '';
            $str = "SELECT countryCode as country , ip AS IP, cv.insertTimestamp AS Date, clickId, ccpa AS CPA, campaignName, rurl " . $ojp . " FROM " . $table_name . " as cv INNER JOIN Mask ON hash=hashMask WHERE fkAgregator=" . $agregator_id . " AND insertDate BETWEEN '" . $start_date . "' AND '" . $end_date . "'" . $rest;
            //mail('pedrorleonardo@gmail.com', 'type', $str);
            //exit();
            $statement = $db2->prepare($str);
            $statement2 = $db7->prepare($str);
            $exe = $db2->executePrepared($statement, array(), array());
            $exe2 = $db7->executePrepared($statement2, array(), array());
            $str22 = 'DROP TEMPORARY TABLE IF EXISTS bunga;
CREATE TEMPORARY TABLE bunga(
`country` CHAR(2) NULL,
`IP` INT NULL,
`Date` datetime NULL,
`clickId` VARCHAR(50) NULL ,
`CPA` DECIMAL(10,3) DEFAULT "0.000",
' . (isset($sqloriginaljp) ? 'requestmade VARCHAR(512) NULL, ' : "" ) . '
`campaignName` VARCHAR(50) NULL ,
`rurl` VARCHAR(512) NULL
) ENGINE=InnoDB;
';
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
            //$array_ret = array();
            $arr2 = $exe2->fetchAll(PDO::FETCH_ASSOC);
            $valuesarr = array();
            $arr = array_merge($array_ret, $arr2);
            $i = 0;
            $str22 .= ' INSERT INTO bunga (country,IP,Date,clickId,CPA' . (isset($sqloriginaljp) ? ',requestmade' : '') . ',campaignName,rurl) VALUES ';
            if (!empty($array_ret) || !empty($arr2)) {
                foreach ($arr as $values) {
                    if ($i == 10000) {
                        $str22 = rtrim($str22, ',') . ';';
                        $db4->query($str22,$valuesarr,array());
                        $str22 = ' INSERT INTO bunga (country,IP,Date,clickId,CPA' . (isset($sqloriginaljp) ? ',requestmade' : '') . ',campaignName,rurl) VALUES ';
                        $valuesarr = array();
                        $i = 0;
                    }
                    $str22 .= '(?,?,?,?,?,' . (isset($sqloriginaljp) ? '?,' : '' ) . '?,' . '?),';
                    $values['country']. '","' . $values['IP'] . '","' . $values['Date'] . '","' . $values['clickId'] . '","' . $values['CPA'] . '",' . (isset($sqloriginaljp) ? '"' . $values['requestmade'] . '",' : '' ) . '"' . $values['campaignName'] . '",' . '"' . $values['rurl'] . '"),';
                    $valuesarr[] =  $values['country'];
                    $valuesarr[] =  $values['IP'];
                    $valuesarr[] =  $values['Date'];
                    $valuesarr[] =  $values['clickId'];
                    $valuesarr[] =  $values['CPA'];
                    if(isset($sqloriginaljp))
                        $valuesarr[] =  $values['requestmade'];
                    $valuesarr[] =  $values['campaignName'];
                    $valuesarr[] =  $values['rurl'];
                    /* if ($i == 0) {
                      mail('pedrorleonardo@gmail.com', 'test', $str22);
                      } */
                    $i++;
                }
                $str22 = rtrim($str22, ',') . ';';
                $db4->query($str22,$valuesarr,array());
                //mail('pedrorleonardo@gmail.com', 'test', $str22);
            } else
                return null;


            $st = 'SELECT country , IP, Date, clickId, CPA' . (!empty($sqloriginaljp) ? ',requestmade ' : '') . ',campaignName, rurl FROM bunga ' . $rest;
            
            //$st = 'SELECT COUNT(*) FROM bunga ';
            //echo $st;
            $st2 = $db4->prepare($st);
            $aa = $db4->executePrepared($st2, array(), array());
            $aaa = $aa->fetchAll(PDO::FETCH_ASSOC);
            //print_r($aaa);
            //exit();
            return $aaa;
        } catch (Exception $e) {
            echo $e->getMessage() . $e->getLine();
            exit();
        }
    }

    public function getMainstreamUsers() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT name, investaccess FROM `users` WHERE navtype = '2'");

        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function save_test($data_array) {
        try {

            $urlRisk = $data_array['urlRisk'];
            $nameRisk = $data_array['nameRisk'];
            $hashRisk = uniqid();
            $timestamp = date("Y-m-d H:i:s");

            //insert table mask
            $statement = $this->getDi()->getDb4()->execute("INSERT INTO Mask (hash, agregator, client, country, campaign, format, rurl, cpa, curtype, aff_flag, geo_flag, insertTimestamp) VALUES ('" . $hashRisk . "', 64 , 1 , 'ru' , '" . $nameRisk . "' , 0 , '" . $urlRisk . "' , 1 , 'USD' , 0 , NULL , '" . $timestamp . "')");

            //table multiclick
            $hashGroup = '5710c26de023a';
            $lpName = 'martim_test_91_15';
            $lpUrl = "jump.youmobistein.com/?jp=" . $hashRisk . "&id=64_ru_109_" . $nameRisk . "_";

            $statement = $this->getDi()->getDb4()->execute("INSERT INTO MultiClick(hashMask, lpName, lpUrl, beginhour, endhour, linkName, percent, action, insertTimestamp, c_country, sback, stype, linkedjump, autoop, climiar, climiar_s) VALUES ('" . $hashGroup . "','" . $lpName . "','" . $lpUrl . "',NULL,NULL,'TATA2',100,0,'" . $timestamp . "','ru',0,0,'" . $hashRisk . "' ,0,0,0)");
        } catch (PDOException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getInfoLines() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT mc.id, mc.hashMask, mc.lpUrl, mc.percent, mk.campaign, mk.rurl, mc.linkedjump FROM MultiClick mc INNER JOIN Mask mk ON mc.linkedjump = mk.hash WHERE mc.hashMask =  '5710c26de023a'");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function deleteLine($hash) {
        $statement = $this->getDi()->getDb4()->prepare('DELETE FROM MultiClick WHERE linkedjump="' . $hash . '"');
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());

        $statement2 = $this->getDi()->getDb4()->prepare('DELETE FROM Mask WHERE hash="' . $hash . '"');
        $exe2 = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
    }

}

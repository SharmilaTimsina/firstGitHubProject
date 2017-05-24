<?php

use Phalcon\Mvc\Model;

class Mask extends Model {

    public $id;
    public $hash;
    public $agregator;
    public $client;
    public $country;
    public $campaign;
    public $source;
    public $format;
    public $adnumber;
    public $rurl;
    public $cpa;
    public $cpaOriginalValue;
    public $curtype;
    public $afcarrier;
    public $insertTimestamp;

    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource("Mask");
    }

    public function getAllCampaigns() {
        $sql = 'SELECT hash, campaign FROM Mask ORDER BY campaign';
        return $this->getDi()->getDb4()->query($sql)->fetchAll();
    }

    //returns false if already exists campaignName
    public function checkCampaign($campaignName) {

        $campQuery = 'SELECT campaign FROM Mask WHERE campaign="' . $campaignName . '"';
        $campRes = $this->getDi()->getDb4()->query($campQuery)->fetchAll();
        return empty($campRes);
    }

    public function getCampaignLink($campID) {

        $sql = 'SELECT hash,rurl,cpa,afcarrier,curtype,cpaOriginalValue, agregator, country, campaign,client,aff_flag  FROM Mask WHERE hash="' . $campID . '"';
        $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
        if (empty($res))
            return null;
        $category = '';
        if ($res[0]['aff_flag'] == 2) {
            $sql2 = 'SELECT subjectid  FROM CategoriesXCampaigns WHERE campaignhash="' . $campID . '"';
            $res2 = $this->getDi()->getDb4()->query($sql2)->fetchAll();
            $category = !empty($res2) ? $res2[0]['subjectid'] : '';
        }
        return $res[0]['rurl'] . '$-$' . $res[0]['cpa'] . '$-$' . $res[0]['afcarrier'] . '$-$' . $res[0]['curtype'] . '$-$' . $res[0]['cpaOriginalValue']
                . '$-$' . $res[0]['agregator'] . '$-$' . $res[0]['country'] . '$-$' . $res[0]['campaign'] . '$-$' . $res[0]['client'] . '$-$' . $res[0]['aff_flag'] . '$-$' . $category;
    }

    public function selectCampaign($campaign, $sdate, $edate) {
        $queryCpa = 'SELECT * FROM campaignCpa INNER JOIN(SELECT hash FROM Mask WHERE id=' . $campaign . ') AS m1 ON (hash=hashMask) WHERE dstart<="' . $sdate . '" AND dend>="' . $edate . '"';
        $result = $this->getDi()->getDb4()->query($queryCpa)->fetchAll();
        return $result;
    }

    public function createMask($cpaField, $hash, $agg, $countryCode, $campaign, $url, $campaigncpa, $affiliate, $currencyType, $mainstreamtype) {

        try {
            $insert = 'INSERT INTO Mask (hash,agregator,client,country,campaign,rurl,' . $cpaField . ',afcarrier, curtype, aff_flag) VALUES("' . $hash . '","' . $agg . '",1,"' . $countryCode . '","' . $campaign . '","' . $url . '","' . $campaigncpa . '","' . $affiliate . '","' . $currencyType . '",' . $mainstreamtype . ')';
            $this->getDi()->getDb4()->execute($insert);
            if ($currencyType != 'USD') {
                $calcCurrency = 'CALL calcCurrency("' . $currencyType . '","' . $hash . '")';
                $this->getDi()->getDb4()->execute($calcCurrency);
            }
            $statement2 = $this->getDi()->getDb4()->prepare('SELECT id,hash,agregator,client,country,campaign,source,format,adnumber,rurl,cpa,cpaOriginalValue,curtype,afcarrier,aff_flag,insertTimestamp FROM Mask WHERE hash = :hash');
            $result = $this->getDi()->getDb4()->executePrepared($statement2, array('hash' => $hash), array(\Phalcon\Db\Column::TYPE_VARCHAR))->fetchAll();

            if (!empty($result)) {
                $statement = $this->getDi()->getDb()->prepare('INSERT INTO tinas__Mask (id,hash,agregator,client,country,campaign,source,format,adnumber,rurl,cpa,cpaOriginalValue,curtype,afcarrier,aff_flag,insertTimestamp) VALUES(:id,:hash,:agregator,:client,:country,:campaign,:source,:format,:adnumber,:rurl,:cpa,:cpaOriginalValue,:curtype,:afcarrier,:aff_flag,:insertTimestamp)');
                $this->getDi()->getDb()->executePrepared($statement, array('id' => $result[0]['id']
                    , 'hash' => $result[0]['hash']
                    , 'agregator' => $result[0]['agregator']
                    , 'client' => $result[0]['client']
                    , 'country' => $result[0]['country']
                    , 'campaign' => $result[0]['campaign']
                    , 'source' => $result[0]['source']
                    , 'format' => $result[0]['format']
                    , 'adnumber' => $result[0]['adnumber']
                    , 'rurl' => $result[0]['rurl']
                    , 'cpa' => $result[0]['cpa']
                    , 'cpaOriginalValue' => $result[0]['cpaOriginalValue']
                    , 'curtype' => $result[0]['curtype']
                    , 'afcarrier' => $result[0]['afcarrier']
                    , 'aff_flag' => $result[0]['aff_flag']
                    , 'insertTimestamp' => $result[0]['insertTimestamp']), array());
            }
        } catch (Exception $e) {
            
        }

        //$insertCpa = 'INSERT INTO campaignCpa (hashMask,cpa,ctype,dstart,dend) VALUES("' . $hash . '","' . $campaigncpa . '","0","' . date('Y-m-d') . '","2100-01-01")';

        //$this->getDi()->getDb4()->execute($insertCpa);

        $this->getDi()->get("viewCache")->delete('campaigns');
    }

    public function updateValues($hash, $agregator, $client, $country, $campaign, $rurl, $cpa, $cpaOriginalValue, $curtype, $afcarrier, $hashClause, $mainstreamtype) {
        $sql = 'UPDATE `Mask` SET ';
        if (isset($hash)) {
            $sql .= ' hash = "' . $hash . '",';
        }
        if (isset($agregator)) {
            $sql .= ' agregator = ' . $agregator . ',';
        }
        if (isset($client)) {
            $sql .= ' client = "' . $client . '",';
        }
        if (isset($country)) {
            $sql .= ' country = "' . $country . '",';
        }
        if (isset($campaign)) {
            $sql .= ' campaign = "' . $campaign . '",';
        }
        if (isset($rurl)) {
            $sql .= ' rurl = "' . $rurl . '",';
        }
        if (isset($cpa)) {
            $sql .= ' cpa = "' . $cpa . '",';
        }
        if (isset($cpaOriginalValue)) {
            $sql .= ' cpaOriginalValue = "' . $cpaOriginalValue . '",';
        }
        if (isset($curtype)) {
            $sql .= ' curtype = "' . $curtype . '",';
        }
        if (isset($afcarrier)) {
            $sql .= ' afcarrier = "' . $afcarrier . '",';
        }
        if (isset($mainstreamtype)) {
            $sql .= ' aff_flag = ' . $mainstreamtype . ',';
        }
        $sql = rtrim($sql, ",");
        $sql .= ' WHERE `hash` = "' . $hashClause . '";';
        $this->getDi()->getDb4()->execute($sql);
        //return $this->getDi()->getDb4()->lastInsertId();
    }

    public function getMaskHash($campaignName) {
        try {
            $sql = 'SELECT hash FROM Mask WHERE campaign LIKE "' . $campaignName . '"';
            $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
            if (empty($res))
                return null;
            else if (empty($res[0]['hash'])) {
                return null;
            } else
                return $res[0]['hash'];
        } catch (Exception $e) {
            return null;
        }
    }

}

<?php

use Phalcon\Mvc\Model;

class Slink extends Model {

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $hashMask;

    /**
     * @var string
     */
    public $lpName;

    /**
     * @var string
     */
    public $lpUrl;

    /**
     * @var string
     */
    public $lpOp;

    /**
     * @var string
     */
    public $isp;

    /**
     * @var string
     */
    public $device;

    /**
     * @var string
     */
    public $linkName;

    /**
     * @var string
     */
    public $linkref;

    /**
     * @var integer
     */
    public $percent;

    /**
     * @var integer
     */
    public $action;

    /**
     * @var timestamp
     */
    public $insertTimestamp;

    /**
     * @var c_country
     */
    public $c_country;

    /**
     * @var beginhour
     */
    public $beginhour;

    /**
     * @var endhour
     */
    public $endhour;

    /**
     * @var sback
     */
    public $sback;

    /**
     * @var stype
     */
    public $stype;

    /**
     * @var linkedjump
     */
    public $linkedjump;

    /**
     * @var autoop
     */
    public $autoop;

    /**
     * @var climiar
     */
    public $climiar;

    /**
     * @var clmiar_s
     */
    public $climiar_s;

    /**
     * Initializes correct njump table
     */
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('MultiClick');
//        $this->skipAttributes(
//              [
//                  'lpOp',
//                  'isp',
//                  'device',
//                  'action',
//                  'linkref'
//              ]
//          );
    }

    public function update_name($hash, $newV) {

        $query = 'UPDATE MultiClick SET lpName=:lpnamenew WHERE hashMask=:hash';
        $query2 = str_replace('MultiClick', "tinas__MultiClick", $query);

        $statement = $this->getDi()->getDb4()->prepare($query);
        $statement2 = $this->getDi()->getDb()->prepare($query2);
        $this->getDi()->getDb4()->executePrepared($statement, array('lpnamenew' => $newV, 'hash' => $hash), array('lpname' => \Phalcon\Db\Column::TYPE_VARCHAR, 'hash' => \Phalcon\Db\Column::TYPE_VARCHAR));
        $this->getDi()->getDb()->executePrepared($statement2, array('lpnamenew' => $newV, 'hash' => $hash), array('lpname' => \Phalcon\Db\Column::TYPE_VARCHAR, 'hash' => \Phalcon\Db\Column::TYPE_VARCHAR));

        return 1; //$this->getDi()->getDb4()->affectedRows();
    }

    public function clone_insert($res, $newname, $auth) {

        $newHash = uniqid();

        $sqlbuild = 'INSERT INTO MultiClick (hashMask,lpName,lpUrl,lpOp,isp,beginhour,endhour,device,linkName,percent,c_country,sback,insertTimestamp,stype,linkedjump) VALUES ';
        foreach ($res as $row) {
            //$row = array_map('trim', $row);
            $sqlbuild .= '("' . $newHash . '","' . $newname . '","' . $row->lpUrl . '","' . $row->lpOp . '","' . $row->isp . '",' . ((isset($row->beginhour) && $row->beginhour != '') ? '"' . $row->beginhour . '",' : 'NULL,') . ((isset($row->endhour) && $row->endhour != '') ? '"' . $row->endhour . '",' : 'NULL,') . '"' . $row->device . '","' . $row->linkName . '","' . $row->percent . '","' . $row->c_country . '","' . $row->sback . '","' . date('Y-m-d H:i:s') . '",' . (isset($auth['utype']) ? $auth['utype'] : '0') . ',' . (isset($row->linkedjump) ? ('"' . $row->linkedjump . '"') : 'NULL') . '),';
        }
        $sql = rtrim($sqlbuild, ',');

        $this->getDi()->getDb4()->query($sql);
        $this->getDi()->getDb()->query(str_replace('MultiClick', "tinas__MultiClick", $sql));

        return $newHash;
    }

    public function delete_njump($hash) {
//        $hash = real_escape_string($hash)
        $sql = 'DELETE FROM MultiClick WHERE hashMask = "' . $hash . '"';
        $this->getDi()->getDb4()->query($sql);
        $this->getDi()->getDb()->query(str_replace('MultiClick', "tinas__MultiClick", $sql));
        return;
    }

    public function update_field($colname, $id, $news) {
        if ($colname == "lpUrl") {
            $linkedhash = $this->getCampaignHash($news);
            $countryvar = $this->getCampaignCountry($linkedhash);
        }
        $sql = 'UPDATE MultiClick SET ' . $colname . '=' . (!isset($news) ? 'NULL' : '"' . $news . '"') . (isset($countryvar) ? ',c_country="' . $countryvar . '"' : ' ' ) . (isset($linkedhash) ? (', linkedjump="' . $linkedhash . '" ') : ' ') . ' WHERE id = ' . $id . '';
        //mail('pedrorleonardo@gmail.com','message',$sql);
        $this->getDi()->getDb4()->query($sql);
        $this->getDi()->getDb()->query(str_replace('MultiClick', "tinas__MultiClick", $sql));

        return;
    }

    public function delete_row($id) {

        $sql = 'DELETE FROM MultiClick WHERE id = ' . $id;
        $this->getDi()->getDb4()->query($sql);
        $this->getDi()->getDb()->query(str_replace('MultiClick', "tinas__MultiClick", $sql));

        return;
    }

    public function clone_line_insert($res, $auth) {

        $row = $res[0];
        $sqlbuild = 'INSERT INTO MultiClick (hashMask,lpName,lpUrl,lpOp,isp,beginhour,endhour,device,linkName,percent,c_country, sback,insertTimestamp,stype,linkedjump) VALUES ("' . $row->hashMask . '","' . $row->lpName . '","' . $row->lpUrl . '","' . $row->lpOp . '","' . $row->isp . '",' . (isset($row->beginhour) ? '"' . $row->beginhour . '"' : 'NULL') . ',' . (isset($row->endhour) ? '"' . $row->endhour . '"' : 'NULL') . ',"' . $row->device . '","' . $row->linkName . '","' . $row->percent . '","' . $row->c_country . '","' . $row->sback . '","' . date('Y-m-d H:i:s') . '",' . (isset($auth['utype']) ? $auth['utype'] : '0') . ',' . (isset($row->linkedjump) ? ('"' . $row->linkedjump . '"') : 'NULL') . ')';
        $this->getDi()->getDb4()->query($sqlbuild);
        $this->getDi()->getDb()->query(str_replace('MultiClick', "tinas__MultiClick", $sqlbuild));
    }

    public function insert_multiple_line($data, $hash, $cname, $auth) {

        $sqlbuild = 'INSERT INTO MultiClick (hashMask,lpName,lpUrl,beginhour,endhour,lpOp,device,isp,linkName,percent,c_country,sback,insertTimestamp,stype,linkedjump) VALUES ';

        foreach ($data as $line) {
            $line = array_map('trim', $line);
            //print_r($line);
            $avar = 0;
            $url = trim($line[1]);
            $country = '';
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
                $avar = 1;
            }
            if (strpos($line[1], '://mjump.') !== false) {
                $country = $this->findMjpCountry($line[1]);
            } else if (strpos($line[1], '://jump.') !== false) {
                $country = $this->getJUMPcountry($line[1]);
            }
            if ($line[0] == '' or $line[7] == '') {
                $avar = 1;
            }
            if ($line[4] == 'ISP' and $line[6] == '') {
                $avar = 1;
            }
            if ($country != '' && ($auth['userlevel'] > 1 && $auth['countries'] != '')) {
                $countries = explode(',', $auth['countries']);
                if (array_search(strtolower($country), array_map('strtolower', $countries)) === false) {
                    echo 'The jump country does not belong to this user.';
                    return false;
                }
            }
            //line6 bhou line7 ehour, check times and both must be null or setted
            if ($line[2] != '' && $line[3] != '') {
                if ((!$this->isTimeWellFormed($line[2])) || (!$this->isTimeWellFormed($line[3]))) {
                    echo 'Time format is incorrect1';
                    return false;
                }
            } else if ($line[2] == '' && $line[3] == '') {

            } else {
                echo 'Both time periods must have a value associated.';
                return false;
            }


            if ($avar == 0) {
                $jumphash = $this->getCampaignHash($line[1]);
                $line = '("' . $hash . '","' . $cname . '","' . $line[1] . '",' . ((isset($line[2]) && $line[2] != '') ? '"' . $line[2] . '",' : 'NULL,' ) . ((isset($line[3]) && $line[3] != '') ? '"' . $line[3] . '",' : 'NULL,' ) . '"' . $line[4] . '","' . $line[5] . '","' . $line[6] . '","' . $line[0] . '","' . $line[7] . '","' . $country . '","' . $line[8] . '","' . date('Y-m-d H:i:s') . '",' . (isset($auth['utype']) ? $auth['utype'] : '0') . ',' . (isset($jumphash) ? ('"' . $jumphash . '"') : 'NULL') . '),';
                $sqlbuild .= $line;
            }
        }
        $sql = rtrim($sqlbuild, ',');
        //echo $sql;
        $this->getDi()->getDb4()->query($sql);
        $this->getDi()->getDb()->query(str_replace('MultiClick', "tinas__MultiClick", $sql));
        return true;
    }

    private function findMjpCountry($mjpurl) {
        $parts = parse_url($mjpurl);
        parse_str($parts['query'], $query);
        $jp = isset($query['jp']) ? $query['jp'] : '';
        if ($jp === '')
            return '';
        $mjump = Smlink::findFirst(array('hashMask = "' . $jp . '"'));
        if (empty($mjump) || !isset($mjump->linkref) || empty($mjump->linkref))
            return '';
        return $this->getJUMPcountry($mjump->linkref);
    }

    private function getJUMPcountry($str) {
        $parts = parse_url($str);
        parse_str($parts['query'], $query);
        if (isset($query['id'])) {
            $exploded = explode('_', $query['id']);
            if (isset($exploded[1]))
                return $exploded[1];
        }
        return '';
    }

    private function isTimeWellFormed($time) {
        if (!isset($time) || $time == '')
            return false;
        $a = preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $time);
        if ($a === false || $a == false) {
            //echo $time;
            return false;
        }
        return true;
    }

    public function get_epc($jumplist, $njump, $date) {
        $sql = 'SELECT id,hash,SUM(revenue) AS revenue,SUM(clicks) AS clicks FROM EpcAgr WHERE hash IN (' . $jumplist . ') AND njump="' . $njump . '" AND insert_date>="' . $date . '" GROUP BY hash';
        $a = $this->getDi()->getDb()->query($sql)->fetchAll();

        $farray = array();

        $today = $this->today_epc($njump);
        //print_r($today);
        foreach ($a as $b) {
            $farray[$b['hash']] = $b;
        }
        foreach ($today as $c) {

            if (isset($farray[$c['hashMask']])) {

                $farray[$c['hashMask']]['clicks'] += $c['clicks'];
                $farray[$c['hashMask']]['revenue'] += $c['rev'];
            } else {
                $farray[$c['hashMask']]['clicks'] = $c['clicks'];
                $farray[$c['hashMask']]['revenue'] = $c['rev'];
            }
        }


        return $farray;
    }

    private function today_epc($njump) {

        $queryclicks = 'SELECT hashMask, COUNT(DISTINCT uniqid) AS clicks  FROM ClicksDaily WHERE fkClient="' . $njump . '" GROUP BY hashMask';
        $conversionclicks = 'SELECT hashMask, COUNT( DISTINCT clickId ) AS conversions, SUM( ccpa ) AS rev FROM (SELECT hashMask, fkClient, clickId, ccpa FROM ConversionsDaily WHERE fkClient="' . $njump . '" GROUP BY clickId) AS cv GROUP BY hashMask';
        $finalquery = 'SELECT cls.hashMask As hashMask, clicks, conversions,rev FROM (' . $queryclicks . ') as cls INNER JOIN (' . $conversionclicks . ') AS cvs ON cls.hashMask=cvs.hashMask';
        //mail('mfcbarone@gmail.com','TestEpc',$finalquery);
        return $this->getDi()->getDb4()->query($finalquery)->fetchAll();
    }

    public function getNjumps($countries, $stype) {
        $njumps = $this->getDi()->getDb4()->query('SELECT lpName as njump, hashMask as hashnjump FROM MultiClick WHERE 0=0' . (isset($countries) ? ' AND c_country IN (' . $countries . ')' : '') . (isset($stype) ? ' AND stype = ' . $stype : '') . ' GROUP BY njump, hashnjump')->fetchAll();
        return $njumps;
    }

    public function getCampaignHash($linkref) {
        if (strpos($linkref, 'mjump') === false)
            $jump = true;
        else
            $jump = false;
        $jp = null;
        parse_str($linkref, $output);
        foreach ($output as $row) {
            $jp = $row;
            break;
        }
        if (!isset($jp))
            return null;
        if ($jump) {
            return $jp;
        } else {
            return $this->getJumpFromMJump($jp);
        }
    }

    public function getNjumpsWithCountryCategory($country = null, $category = null) {
        try {
            $sql = 'SELECT lpName as njump, hashMask as hashnjump FROM MultiClick nj left join CategoriesXCampaigns c ON nj.linkedjump =c.campaignhash WHERE nj.stype = 2 ' . (isset($category) ? (' AND c.subjectid = ' . $category) : '' ) . (isset($country) ? (' AND nj.c_country = "' . $country . '"') : '' ) . '  GROUP BY hashMask';
            //echo $sql;
            //echo $sql;
            return $this->getDi()->getDb4()->query($sql)->fetchAll();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getCampaignCategory($hash) {
        try {
            $res = $this->getDi()->getDb4()->query('SELECT subjectid FROM CategoriesXCampaigns WHERE campaignhash = "' . $hash . '"')->fetchAll();
            return isset($res[0]) ? (isset($res[0]['subjectid']) ? $res[0]['subjectid'] : null ) : null;
        } catch (Exception $ex) {
            return null;
        }
    }

    private function getJumpFromMJump($mjumphash) {
        try {
            $campaign = $this->getDi()->getDb4()->query('SELECT linkref FROM LandingPage WHERE hashmask = "' . $mjumphash . '" LIMIT 1')->fetchAll();
            //mail('pedrorleonardo@gmail.com','ma','SELECT linkref FROM LandingPage WHERE hashmask = "' . $mjumphash . '" LIMIT 1');
            if (empty($campaign))
                return null;
            $jp = null;
            parse_str($campaign[0]['linkref'], $output);
            foreach ($output as $row) {
                $jp = $row;
                break;
            }
            return $jp;
        } catch (Exception $ex) {
            return null;
        }
    }

    private function getCampaignCountry($linkedjump) {
        try {
            $res = $this->getDi()->getDb4()->query('SELECT country FROM Mask where hash = "' . $linkedjump . '";')->fetchAll();
            //mail('pedrorleonardo@gmail.com','mes','SELECT country FROM Mask where hash = "'.$linkedjump.'";');
            if (!empty($res) && !empty($res[0]) && isset($res[0]['country']))
                return $res[0]['country'];
            return null;
        } catch (Exception $ex) {
            return null;
        }
    }

    private function getCampaignCategoryFromMJump($mjumphash) {
        try {
            $campaign = $this->getDi()->getDb4()->query('SELECT linkref FROM LandingPage WHERE hashmask = "' . $mjumphash . '" LIMIT 1')->fetchAll();
            if (empty($campaign))
                return null;
            return $this->getCampaignCategory($campaign[0]['linkref']);
        } catch (Exception $ex) {
            return null;
        }
    }

    public function get_InfoClicksNumber($data_array) {
        $statement = $this->getDi()->getDb4()->prepare("SELECT count(*) as numberLines FROM `MultiClick` where lpUrl LIKE '%" . $data_array['searchclick'] . "%'");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function editclicksTable($data_array) {
        //rows to copy and edit
        $statement = $this->getDi()->getDb4()->prepare("SELECT id as idMultiClickRow, lpName as lpname, percent as oldPercent, lpUrl FROM MultiClick where lpUrl LIKE '%" . $data_array['searchclick'] . "%'");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        //masx number on idGroup
        $maxStatement = $this->getDi()->getDb4()->prepare("SELECT MAX(idGroup) AS HighestId FROM MultiClicksEditBackup");
        $exe3 = $this->getDi()->getDb4()->executePrepared($maxStatement, array(), array());
        $array_ret3 = $exe3->fetchAll(PDO::FETCH_ASSOC);

        //copy
        $new_id = $array_ret3[0]['HighestId'] + 1;
        $values = "";
        $values2 = "";
        foreach ($array_ret as $row) {

            date_default_timezone_set("Europe/Lisbon");

            $values .= " ('" . $row['idMultiClickRow'] . "','" . $new_id . "','" . $data_array['description'] . "','" . $data_array['searchclick'] . "','" . $row['oldPercent'] . "','" . date("Y-m-d H:i:s") . "'),";
        }
        $values = substr($values, 0, -1);

        //edit
        $changePercent = $this->getDi()->getDb4()->prepare("UPDATE MultiClick SET percent=0 where lpUrl LIKE '%" . $data_array['searchclick'] . "%'");
        $this->getDi()->getDb4()->executePrepared($changePercent, array(), array());

        //insert on backup multiclick
        $statement2 = $this->getDi()->getDb4()->prepare("INSERT INTO MultiClicksEditBackup (idMultiClicksRow, idGroup, description, search, oldPercent, insertTimestamp) VALUES " . $values);
        $this->getDi()->getDb4()->executePrepared($statement2, array(), array());

        return $this->get_newTable();
    }

    public function get_newTable() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT description , idGroup, search, oldPercent , insertTimestamp FROM MultiClicksEditBackup GROUP BY idGroup");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function set_changeMultiClick($idGroup) {
        $statement = $this->getDi()->getDb4()->prepare("SELECT idMultiClicksRow, idGroup, oldPercent FROM MultiClicksEditBackup WHERE idGroup=" . $idGroup);
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array_ret as $row) {
            $changePercent = $this->getDi()->getDb4()->prepare("UPDATE MultiClick SET percent=" . $row['oldPercent'] . " where id=" . $row['idMultiClicksRow']);
            $this->getDi()->getDb4()->executePrepared($changePercent, array(), array());
        }

        $changePercent = $this->getDi()->getDb4()->prepare("DELETE FROM MultiClicksEditBackup where idGroup=" . $idGroup);
        $this->getDi()->getDb4()->executePrepared($changePercent, array(), array());

        return $this->get_newTable();
    }

    public function updateMultiClick($data_array, $type) {

        if ($type == 1) {
            $statement = $this->getDi()->getDb4()->prepare("UPDATE MultiClick SET c_country='" . $data_array['c_country'] . "' , linkref='1' WHERE hashMask='" . $data_array['hashMask'] . "'");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        } else if ($type == 2) {
            $statement = $this->getDi()->getDb4()->prepare("UPDATE MultiClick SET linkref='' WHERE hashMask='" . $data_array['hashMask'] . "'");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        }
    }

}

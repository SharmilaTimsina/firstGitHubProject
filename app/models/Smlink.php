<?php

use Phalcon\Mvc\Model;

class Smlink extends Model {

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
    public $autoop;

    /**
     * @var integer
     */
    public $climiar;

    /**
     * @var timestamp
     */
    public $insertTimestamp;

    /**
     * @var lacucaracha
     */
    public $stype;

    /**
     * Initializes correct njump table
     */
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('LandingPage');
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

        $statement = $this->getDi()->getDb4()->prepare('UPDATE LandingPage SET lpName=:lpnamenew WHERE hashMask=:hash');
        $this->getDi()->getDb4()->executePrepared($statement, array('lpnamenew' => $newV, 'hash' => $hash), array('lpname' => \Phalcon\Db\Column::TYPE_VARCHAR, 'hash' => \Phalcon\Db\Column::TYPE_VARCHAR));

        return 1; //$this->getDi()->getDb4()->affectedRows();
    }

    public function clone_insert($res, $newname, $stype) {
        $newHash = uniqid();

        $sqlbuild = 'INSERT INTO LandingPage (hashMask,lpName,lpUrl,linkref,lpOp,linkName,percent,stype,insertTimestamp) VALUES ';
        foreach ($res as $row) {
            $sqlbuild .= '("' . $newHash . '","' . $newname . '","' . $row->lpUrl . '","' . $row->linkref . '","' . $row->lpOp . '","' . $row->linkName . '","' . $row->percent . '",' . (isset($stype) ? $stype : '0') . ',"' . date('Y-m-d H:i:s') . '"),';
        }
        $sql = rtrim($sqlbuild, ',');

        $this->getDi()->getDb4()->query($sql);

        return $newHash;
    }

    public function delete_njump($hash) {
//        $hash = real_escape_string($hash)
        $sql = 'DELETE FROM LandingPage WHERE hashMask = "' . $hash . '"';
        $this->getDi()->getDb4()->query($sql);

        return;
    }

    public function update_field($colname, $id, $news) {

        $sql = 'UPDATE LandingPage SET ' . $colname . '="' . $news . '" WHERE id = ' . $id . '';
        $this->getDi()->getDb4()->query($sql);

        return;
    }

    public function delete_row($id) {

        $sql = 'DELETE FROM LandingPage WHERE id = ' . $id;
        $this->getDi()->getDb4()->query($sql);

        return;
    }

    public function clone_line_insert($res, $stype) {
        $row = $res[0];
        $sqlbuild = 'INSERT INTO LandingPage (hashMask,lpName,lpUrl,lpOp,linkName,percent,stype,insertTimestamp) VALUES ("' . $row->hashMask . '","' . $row->lpName . '","' . $row->lpUrl . '","' . $row->lpOp . '","' . $row->linkName . '","' . $row->percent . '",' . (isset($stype) ? $stype : '0') . ',"' . date('Y-m-d H:i:s') . '")';
        $this->getDi()->getDb4()->query($sqlbuild);
    }

    public function insert_multiple_line($data, $hash, $cname, $stype) {

        $xparam = '';
        if ($stype == 2) {
            $xparam = ',lpOp';
        }
        $sqlbuild = 'INSERT INTO LandingPage (hashMask,lpName,lpUrl,linkref,linkName,percent,stype,insertTimestamp' . $xparam . ') VALUES ';
        foreach ($data as $line) {
            $line = array_map('trim', $line);
            $avar = 0;
            $url = trim($line[1]);
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
                $avar = 1;
            }
            if ($stype != 2) {
                //updated line2 = linkref, now it can be empty since the new malgo trick
                if ($line[0] == '' /* or $line[2] == '' */ or $line[3] == '' or $line[1] == '') {
                    $avar = 1;
                }
                if ($avar == 0) {
                    $line = '("' . $hash . '","' . $cname . '","' . $line[1] . '","' . $line[2] . '","' . $line[0] . '","' . $line[3] . '",' . (isset($stype) ? $stype : '0') . ',"' . date('Y-m-d H:i:s') . '"),';
                    $sqlbuild .= $line;
                }
            } else {
                //updated line2 = linkref, now it can be empty since the new malgo trick
                if ($line[0] == '' /* or $line[2] == '' */ or $line[4] == '' or $line[1] == '') {
                    $avar = 1;
                }
                if ($avar == 0) {
                    $line = '("' . $hash . '","' . $cname . '","' . $line[1] . '","' . $line[2] . '","' . $line[0] . '","' . $line[4] . '",' . (isset($stype) ? $stype : '0') . ',"' . date('Y-m-d H:i:s') . '","' . $line[3] . '"),';
                    $sqlbuild .= $line;
                }
            }
        }
        //mail('pedrorleonardo@gmail.com','test',$sqlbuild);
        $sql = rtrim($sqlbuild, ',');
        $this->getDi()->getDb4()->query($sql);
    }

    public function get_epc($hash, $epcdate) {
        $idate = ($epcdate != 0) ? $epcdate : date('Y-m-d');
        $sql = 'SELECT page,mjumpid, SUM(revenue) AS rev,SUM(lpClicks) AS clicks FROM `MjumpReport` WHERE mjumpkey=:hash AND insertDate>="' . $idate . '" GROUP BY page';
        //echo $sql;
        $statement = $this->getDi()->getDb4()->prepare($sql);
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array('hash' => $hash), array(\Phalcon\Db\Column::TYPE_VARCHAR));
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

}

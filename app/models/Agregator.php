<?php

use Phalcon\Mvc\Model;

class Agregator extends Model {

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $agregator;

    /**
     * @var string
     */
    public $trackingParam;

    /**
     * @var double
     */
    public $insertTimestamp;
    public $custom_url;
    public $sinfo;

    /**
     * Initializes correct njump table
     */
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('Agregators');
    }

    public function get_agregators($auth) {

        $array_ret = array();

        if ($auth['userlevel'] != 3) {
            $statement = $this->getDi()->getDb4()->prepare("SELECT a.id , agregator, trackingParam, sinfo, custom_url, c.currency as currency, currencyRequestKey, payoutRequestKey FROM Agregators a LEFT JOIN C__aggmetadata c ON a.id = c.agg");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $agregatores = $auth['aggregators'];

            $statement2 = $this->getDi()->getDb4()->prepare("SELECT a.id , agregator, trackingParam, sinfo, custom_url, c.currency as currency, currencyRequestKey, payoutRequestKey FROM Agregators a LEFT JOIN C__aggmetadata c ON a.id = c.agg WHERE a.id " . (!empty($agregatores) ? " IN (" . $agregatores . ")" : ""));
            $exe2 = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
            $array_ret = $exe2->fetchAll(PDO::FETCH_ASSOC);
        }

        return $array_ret;
    }

    public function meta_dataHandle($id, $array_agg) {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id from C__aggmetadata where agg='" . $id . "'");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        $up_string = "";
        $up_array = array();
        foreach ($array_agg as $key => $value) {
            if ($value != null && $value != "" && $key != "id" && $key != "agregator" && $key != "trackingParam" && $key != "sinfo") {
                $up_string .= ' ' . $key . '=:' . $key . ' ,';
                $up_array[$key] = $value;
            } else if ($key == "sinfo") {

            } else if ($value == "") {
                $up_string .= ' ' . $key . '= NULL,';
            }
        }

        $up_string = substr($up_string, 0, -1);

        if (count($array_ret) > 0) {
            //row exists
            //echo "UPDATE C__aggmetadata SET " . $up_string . " WHERE agg =" . $array_agg['id'];

            $statement2 = $this->getDi()->getDb4()->prepare("UPDATE C__aggmetadata SET " . $up_string . " WHERE agg =" . $array_agg['id']);
            $exe2 = $this->getDi()->getDb4()->executePrepared($statement2, $up_array, array());
        } else {
            //row not exists
            $statement2 = $this->getDi()->getDb4()->prepare("INSERT INTO C__aggmetadata (agg, currency, currencyRequestKey, payoutRequestKey) VALUES ('" . $array_agg['id'] . "','" . $array_agg['currency'] . "','" . $array_agg['currencyRequestKey'] . "','" . $array_agg['payoutRequestKey'] . "')");
            $exe2 = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
        }
    }

    public function getUsrAgr($id) {
        try {
            $sql = 'SELECT agregator ,a.id as id FROM users u INNER JOIN Agregators a ON u.aggregators LIKE CONCAT("%,",a.id,",%") WHERE u.id=:id  AND userlevel=:userlevel order by agregator asc';
            $statement = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array('id' => $id, 'userlevel' => 3), array());
            return $exe->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getAggregatorUser() {
        try {
            /* $sql = 'SELECT username, agregator ,a.id as id FROM users1 u INNER JOIN Agregators a ON u.aggregators LIKE CONCAT("%",a.id,"%") WHERE userlevel=3 AND (agregator != "" AND agregator!="NONE") AND username!="Ana2"'; */
            $sql = 'SELECT  a.id,a.agregator,u.username,u.id as uid
            FROM   (SELECT * FROM Agregators WHERE agregator!="None" AND agregator!="" )as a
            LEFT JOIN (SELECT id,username,aggregators FROM users WHERE userlevel=3 and username!="Ana2") as u
            ON FIND_IN_SET(a.id, u.aggregators) >0
            GROUP   BY a.id
			ORDER BY agregator ASC';
            /* $sql='SELECT a.id,a.agregator,u.username FROM Agregators a  LEFT JOIN users1 u  ON find_in_set(a.id,u.aggregators) WHERE u.userlevel=3 AND (a.agregator != "" AND a.agregator!="NONE") AND u.username!="Ana2"'; */
            $statement = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            return $exe->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

}

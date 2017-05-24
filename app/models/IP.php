<?php

use Phalcon\Mvc\Model;

class IP extends Model {

    public function initialize() {

    }

    public function getIps($country) {
        $statement = $this->getDi()->getDb()->prepare("SELECT INET_NTOA(lip) as lowerip, INET_NTOA(uip) as upperip, lip, uip, countrycode, carrier FROM ipsmobiledatabase052017 WHERE countrycode LIKE '" . strtoupper($country) . "' AND carrier IS NOT NULL and carrier != '-';");

        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

}

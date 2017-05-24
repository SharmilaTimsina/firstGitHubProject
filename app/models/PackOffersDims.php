<?php

use Phalcon\Mvc\Model;

class PackOffersDims extends Model {

    public function initialize() {
        $this->setConnectionService('db4');
    }

    public function getverticalvalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, name from offerpack__vertical ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getstatusvalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, name from offerpack__status ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getmodelvalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, name from offerpack__model ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getflowvalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, name from offerpack__flow ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getareatypevalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, name from offerpack__areatype ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getcarriervalues($country = null) {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, country, countryname, name, carrierTag, status from offerpack__carrier WHERE id IN (1,656) " . (isset($country) ? " OR country = :country " : "") . ' ORDER BY name ');
        $countryarr = isset($country) ? array('country' => $country) : array();
        $countryarrtype = isset($country) ? array(\Phalcon\Db\Column::TYPE_CHAR) : array();
        $exe = $this->getDi()->getDb4()->executePrepared($statement, $countryarr, $countryarrtype);
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getcarriervaluesMultiple($country = null) {
        if ($country == 'ALL') {
            $statement = $this->getDi()->getDb4()->prepare("SELECT id, country, countryname, name, carrierTag, status from offerpack__carrier ORDER BY country ");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            return $exe->fetchAll(PDO::FETCH_ASSOC);
        }

        $country = explode(",", $country);
        $countries = '';
        foreach ($country as $oneCountry) {

            if ($oneCountry === end($country))
                $countries .= "'" . $oneCountry . "'";
            else
                $countries .= "'" . $oneCountry . "',";
        }

        $statement = $this->getDi()->getDb4()->prepare("SELECT id, country, countryname, name, carrierTag, status from offerpack__carrier where country IN ($countries) OR id = 1 ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getcountriesvalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, name from Countries ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getcurrenciesvalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT currency from CurrencyHistory GROUP by currency ORDER BY currency ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getaggsvalues($auth) {
        $forbiddensql = '';
        if ($auth['userlevel'] == 2 && $auth['utype'] == 0 && $auth['userarea'] != 1) {//CM's Ivo Pedro and so ON
            $forbiddensql = ' WHERE cname NOT IN (2) ';
        } else if ($auth['userlevel'] == 3 && $auth['utype'] == 0 && $auth['userarea'] == 1 && $auth['id'] != 25) {//SALES
            $forbiddensql = ' WHERE cname NOT IN (2) ';
        } else if ($auth['userlevel'] == 2 && $auth['utype'] == 2) { // mainstream CM's  like Arrojado
            $forbiddensql = ' WHERE cname NOT IN (2) ';
        }

        $statement = $this->getDi()->getDb4()->prepare("SELECT id, agregator as name from Agregators $forbiddensql ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getcmsvalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT MAX(id) as id, name as name,userarea from users WHERE userarea IN (0) GROUP BY name ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getaccountvalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT MAX(id) as id, name as name,userarea from users WHERE userarea IN (1) GROUP BY name ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getregulationvalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, name from offerpack__regulations ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getownershipvalues() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, name from offerpack__ownership ORDER BY name ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $exe->fetchAll(PDO::FETCH_ASSOC);
    }

}

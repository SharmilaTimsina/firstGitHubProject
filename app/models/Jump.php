<?php

use Phalcon\Mvc\Model;

class Jump extends Model {

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
	public $aff_flag;
	public $geo_flag;
	public $insertTimestamp;
	public $type_flag;

    public function initialize()
    {
        $this->setConnectionService('db4');
        $this->setSource('Mask');
    }
	
	public function getSource() {
		return "Mask";
	}
	
	public function getCurrencyHistory($currency) {
		
		$statement = $this->getDi()->getDb4()->prepare("SELECT id, currency, rate, insertDate FROM CurrencyHistory where currency='" . $currency . "'  ORDER BY id DESC LIMIT 1");
		$exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
		$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
		
		return $array_ret;
	}

	public function getJumpNames($campaignName) {
		$statement = $this->getDi()->getDb4()->prepare("SELECT * FROM Mask WHERE campaign REGEXP '^" . $campaignName . "[0-9]*$'");
		$exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
		$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
		
		return $array_ret;
	}
	
}

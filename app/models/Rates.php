<?php

use Phalcon\Mvc\Model;

class Rates extends Model
{
    
    public function initialize() {
       
    }
	
	public function getRates() {
		$array_rates = array(
				'todayEUR' => '',
				'monthEUR' => '',
				'weekEUR' => '',
				'todayBR' => '',
				'monthBR' => '',
				'weekBR' => '',
				'todayGBT' => '',
				'monthGBT' => '',
				'weekGBT' => '',
				'todayMXN' => '',
				'monthMXN' => '',
				'weekMXN' => ''
			);

		//today
		$date = date("Y-m-d");
		$statement = $this->getDi()->getDb4()->prepare("SELECT id, currency, FORMAT((AVG(rate)), 3) as rate FROM CurrencyHistory WHERE insertDate = '$date' group by currency");
		$exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
		$array_retToday =  $exe->fetchAll(PDO::FETCH_ASSOC);

		//month
		$month = date("m");
		$statement = $this->getDi()->getDb4()->prepare("SELECT id, currency, FORMAT((AVG(rate)), 3) as rate,  DATE_FORMAT(insertDate,'%m') as month FROM CurrencyHistory WHERE DATE_FORMAT(insertDate,'%m') = '$month' group by currency, month");
		$exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
		$array_retMonth =  $exe->fetchAll(PDO::FETCH_ASSOC);

		//week
		$statement = $this->getDi()->getDb4()->prepare('SELECT id, currency, FORMAT((AVG(rate)), 3) as rate FROM `CurrencyHistory` WHERE insertDate >= DATE(NOW()) - INTERVAL 7 DAY group by currency');
		$exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
		$array_retWeek =  $exe->fetchAll(PDO::FETCH_ASSOC);
	
		return array('today' => $array_retToday, 'month' => $array_retMonth, 'week' => $array_retWeek);
	}
	
	public function getThreeMonthsInfo($data_array) {

		$where = '';
		($data_array['eur'] != 'false') ? $where .= "'EUR'," : '';
		($data_array['mxn'] != 'false') ? $where .= "'MXN'," : '';
		($data_array['brl'] != 'false') ? $where .= "'BRL'," : '';
		($data_array['gbp'] != 'false') ? $where .= "'GBP'," : '';
		
		if($where != '')
			$where = substr($where, 0, -1);
			$where = ' AND currency IN (' . $where . ')';

		$statement = $this->getDi()->getDb4()->prepare("SELECT DATE_FORMAT(insertDate,'%Y-%m-%d') as dateIn, currency, FORMAT((AVG(rate)), 3) as rate FROM `CurrencyHistory` WHERE insertDate >= now()-interval 3 month " . $where . " group by currency, dateIn");
		$exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
		$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);

		return $array_ret;
	}	
}   
    
  
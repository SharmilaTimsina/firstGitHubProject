<?php

use Phalcon\Mvc\Model;

class CRM extends Model {

	public function get_agregators($auth) {
		$agregators = $auth['aggregators'];

		if(isset($agregators) && $agregators != '') {
			$statement = $this->getDi()->getDb2()->prepare("SELECT id , agregator FROM Agregators WHERE id IN (" . $agregators . ")");
			
			$exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
			$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
			
			return $array_ret;
		} else {
			$statement = $this->getDi()->getDb2()->prepare("SELECT id , agregator FROM Agregators");
			
			$exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
			$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
			
			return $array_ret;
		}
	}
	
	public function get_clients($auth) {
		$agregators = $auth['aggregators'];

		if(isset($agregators) && $agregators != '') {
			$statement = $this->getDi()->getDb()->prepare("SELECT id , aggregatorName, idAgregator FROM ClientsCrm WHERE idAgregator IN (" . $agregators . ")");
			
			$exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
			$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
			
			return $array_ret;
		} else {
			$statement = $this->getDi()->getDb()->prepare("SELECT id , aggregatorName, idAgregator FROM ClientsCrm");
			
			$exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
			$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
			
			return $array_ret;
		}
	}
	
	public function get_client($id) {
		$statement = $this->getDi()->getDb()->prepare("SELECT * FROM ClientsCrm WHERE idAgregator='" . $id . "'");
			
		$exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
		$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
		
		return $array_ret;
	}
	
	public function create_client($array_data) {
		$statement = $this->getDi()->getDb()->prepare("INSERT INTO ClientsCrm(amanagerName, aggregatorName, idAgregator, skype, workEmail, askForNumbers, stateAggr, notes, status, language) VALUES ('$array_data[accountmanager]','$array_data[aggname]','$array_data[aggrid]','$array_data[aggrskype]','$array_data[workemail]','$array_data[askfornumbers]','$array_data[clientstate]','$array_data[aggnotes]','$array_data[aggstatus]','$array_data[agglang]')");
	
		$exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
		
		$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function edit_client($array_data) {
		$statement = $this->getDi()->getDb()->prepare("UPDATE ClientsCrm SET amanagerName='$array_data[accountmanager]',aggregatorName='$array_data[aggname]',idAgregator='$array_data[aggrid]',skype='$array_data[aggrskype]',workEmail='$array_data[workemail]',askForNumbers='$array_data[askfornumbers]',stateAggr='$array_data[clientstate]',notes='$array_data[aggnotes]',status='$array_data[aggstatus]',language='$array_data[agglang]' WHERE id='$array_data[idforedit]'");
			
		$exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
		
		$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
	}
	
	
	public function get_last7Days($type, $array_data, $auth, $idAgg) {
		$date = date('Y-m-d', strtotime('-7 days'));
		
		if($type == 1) {
			
			$exception = '';
			$exception2 = '';
			if($auth['id'] == '2' || $auth['id'] == '3') {
				$exception = ' AND affiliate="0"';
				$exception2 = ' AND affiliate="0"';
			} 
						
			//last 7 days
			$statement = $this->getDi()->getDb()->prepare('SELECT insert_date as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM Agr__MainReport INNER JOIN tinas__Sources ON source=tinas__Sources.id WHERE insert_date >=  "' . $date . '"' . $exception . ' AND Agr__MainReport.agregator= "' . $idAgg . '" GROUP BY insert_date');
						
			$exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
			$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);

			//today
				//clicks
			$statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS clicks FROM ClicksDaily INNER JOIN Sources ON fkSource=Sources.id WHERE ClicksDaily.fkAgregator= "' . $idAgg . '" ' . $exception2);
			$exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
			$array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);
			
		
				//conversions
			$statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS conversions, SUM( ccpa ) AS revenue FROM ConversionsDaily INNER JOIN Sources ON fkSource=Sources.id WHERE ConversionsDaily.fkAgregator= "' . $idAgg . '" ' . $exception2);
			$exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
			$array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);	
			
			
			foreach($array_ret as $key => $value) {
				$cr = $value['conversions'] / $value['clicks'];
				
				$array_ret[$key]['cr'] = $cr;
				
				$array_ret[$key]['revenue'] = number_format($array_ret[$key]['revenue'], 2, '.', '');
				$array_ret[$key]['cr'] = number_format($array_ret[$key]['cr'] * 100 , 3, '.', '');
			}
			
			($array_ret_clicks[0]['clicks'] != 0) ? ($cr = $array_ret_conversions[0]['conversions'] / $array_ret_clicks[0]['clicks']) : ($cr = 0);
			
			array_push($array_ret, array('date' => $array_ret_clicks[0]['date'] , 'clicks' => $array_ret_clicks[0]['clicks'], 'conversions' => $array_ret_conversions[0]['conversions'], 'revenue' => number_format($array_ret_conversions[0]['revenue'], 2, '.', ''), 'cr' => $cr * 100));
			
			return $array_ret;
			
		} else if ($type == 5) {
			
			$sqlDb = '';
			$sqlDb2 = '';
			if($array_data[0]['aggregators'] != '') {
				$aggregators = str_replace(',', '","', $auth['aggregators']);
				$sqlDb .= ' AND agregator IN ("' . $aggregators . '") ';
				$sqlDb2 .= ' AND fkAgregator IN ("' . $aggregators . '") ';
				
				
			}

			//last 7 days
			$statement = $this->getDi()->getDb()->prepare('SELECT insert_date as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM Agr__MainReport WHERE insert_date >=  "' . $date . '" ' . $sqlDb . ' AND Agr__MainReport.agregator= "' . $idAgg . '" GROUP BY insert_date');
			$exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
			$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
				
			//today
				//clicks
			$statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS clicks FROM ClicksDaily INNER JOIN Sources ON fkSource=Sources.id WHERE ClicksDaily.fkAgregator= "' . $idAgg . '" ' . $sqlDb2);
			$exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
			$array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);
		


				//conversions
			$statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS conversions, SUM( ccpa ) AS revenue FROM ConversionsDaily INNER JOIN Sources ON fkSource=Sources.id WHERE ConversionsDaily.fkAgregator= "' . $idAgg . '" ' . $sqlDb2);
			$exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
			$array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);	
	
			foreach($array_ret as $key => $value) {
				$cr = $value['conversions'] / (($value['clicks'] == 0) ? 1 : $value['clicks']);
				
				$array_ret[$key]['cr'] = $cr;
				
				$array_ret[$key]['revenue'] = number_format($array_ret[$key]['revenue'], 2, '.', '');
				$array_ret[$key]['cr'] = number_format($array_ret[$key]['cr'] * 100 , 3, '.', '');
			}
			
			($array_ret_clicks[0]['clicks'] != 0) ? ($cr = $array_ret_conversions[0]['conversions'] / $array_ret_clicks[0]['clicks']) : ($cr = 0);
			
			array_push($array_ret, array('date' => $array_ret_clicks[0]['date'] , 'clicks' => $array_ret_clicks[0]['clicks'], 'conversions' => $array_ret_conversions[0]['conversions'], 'revenue' => number_format($array_ret_conversions[0]['revenue'], 2, '.', ''), 'cr' => $cr * 100));
			
			return $array_ret;
		}
	}
	
	
	public function percentShift($type, $array_data, $auth, $idAgg) {
		$hour = date('H');
		
		if($type == 1) {
			
			$exception = '';
			if($auth['id'] == '2' || $auth['id'] == '3') {
				$exception = ' AND affiliate="0"';
			} 
			
			
			//last 3 days
			$dayYesterday = date('Y-m-d',strtotime("-1 days"));
			$day3days = date('Y-m-d',strtotime("-3 days"));

			$statement = $this->getDi()->getDb()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN tinas__Sources ON sourceid=tinas__Sources.id INNER JOIN tinas__Mask ON tinas__Mask.hash=SourceAndCampaigns.hashmask WHERE tinas__Mask.agregator='" . $idAgg . "' AND datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $exception . " group by datePeriod");
			$exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
			$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);

			//today
			$today = date('Y-m-d');
			$statement2 = $this->getDi()->getDb()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN tinas__Sources ON sourceid=tinas__Sources.id  INNER JOIN tinas__Mask ON tinas__Mask.hash=SourceAndCampaigns.hashmask WHERE tinas__Mask.agregator='" . $idAgg . "' AND datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $exception . " group by datePeriod");
			$exe2 = $this->getDi()->getDb()->executePrepared($statement2, array(), array());
			$array_ret2 =  $exe2->fetchAll(PDO::FETCH_ASSOC);
			
			
			$days3clicks = 0;
			$days3conversions = 0;
			$days3revenue = 0;
			foreach($array_ret as $element) {
				$days3clicks += $element['clicks'];
				$days3conversions += $element['conversions'];
				$days3revenue += $element['revenue'];
			}
			
			$days3clicks = $days3clicks / 3;
			$days3conversions = $days3conversions / 3;
			$days3revenue = $days3revenue / 3;
			($days3clicks != 0) ? ($epc3days = number_format($days3revenue / $days3clicks, 3)) : ($epc3days = 0) ;
			
			$array_result = array();
			if(isset($array_ret2[0])) {
				$percentageRevenue = ($array_ret2[0]['revenue'] != 0) ? number_format( 100 - (($days3revenue * 100) / $array_ret2[0]['revenue']), 2) : 0;
				$percentageClicks = ($array_ret2[0]['clicks'] != 0) ? number_format(100 - (($days3clicks * 100) / $array_ret2[0]['clicks']), 2) : 0;
				$percentageConversions = ($array_ret2[0]['conversions'] != 0) ? number_format(100 - (($days3conversions * 100) / $array_ret2[0]['conversions']), 2) : 0;
				$a = ($array_ret2[0]['clicks'] != 0) ? ($array_ret2[0]['revenue'] / $array_ret2[0]['clicks']) : 0;
				$percentageepc = ($a != 0) ? number_format(100 - (($epc3days * 100) / $a), 2) : 0;
				
				array_push($array_result, array( 
					'pshift' => array('clicks' => $percentageClicks, 'conversions' => $percentageConversions, 'revenue' => $percentageRevenue, 'epc' => $percentageepc),
					'last3days' => array('clicks' => number_format($days3clicks), 'conversions' => number_format($days3conversions , 2), 'revenue' => number_format($days3revenue, 2), 'epc' => $epc3days),
					'today' => array('clicks' => number_format($array_ret2[0]['clicks']), 'conversions' => number_format($array_ret2[0]['conversions'] , 2), 'revenue' => number_format($array_ret2[0]['revenue'], 2), 'epc' => number_format($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'], 3))
				));
			} else {
				array_push($array_result, array( 
					'pshift' => array('clicks' => 0, 'conversions' => 0, 'revenue' => 0, 'epc' => 0),
					'last3days' => array('clicks' => 0, 'conversions' => 0, 'revenue' => 0, 'epc' => 0),
					'today' => array('clicks' => 0, 'conversions' => 0, 'revenue' => 0, 'epc' => 0)
				));
			}
			return $array_result;
		
		} else if ($type == 5) { 
			
			$sql = '';
			if($array_data[0]['aggregators'] != '') {
				$aggregators = str_replace(',', '","', $auth['aggregators']);
				$aggregators = ' AND agregator IN ("' . $aggregators . '") ';
				$sql .= $aggregators;
			}
			
		
			//last 3 days
			$dayYesterday = date('Y-m-d',strtotime("-1 days"));
			$day3days = date('Y-m-d',strtotime("-3 days"));

			$statement = $this->getDi()->getDb()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN tinas__Mask ON SourceAndCampaigns.hashmask=tinas__Mask.hash WHERE tinas__Mask.agregator='" . $idAgg . "' AND datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $sql . " group by datePeriod");
			$exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
			$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
	
			//today
			$today = date('Y-m-d');
			$statement2 = $this->getDi()->getDb()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns  INNER JOIN tinas__Mask ON SourceAndCampaigns.hashmask=tinas__Mask.hash  WHERE tinas__Mask.agregator='" . $idAgg . "' AND datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $sql . " group by datePeriod");
			$exe2 = $this->getDi()->getDb()->executePrepared($statement2, array(), array());
			$array_ret2 =  $exe2->fetchAll(PDO::FETCH_ASSOC);
			
			
			$days3clicks = 0;
			$days3conversions = 0;
			$days3revenue = 0;
			foreach($array_ret as $element) {
				$days3clicks += $element['clicks'];
				$days3conversions += $element['conversions'];
				$days3revenue += $element['revenue'];
			}
			
			$days3clicks = $days3clicks / 3;
			$days3conversions = $days3conversions / 3;
			$days3revenue = $days3revenue / 3;
			$epc3days = ($days3clicks != 0) ? number_format($days3revenue / $days3clicks, 3) : 0;
			
			$array_result = array();
			if(isset($array_ret2[0])) {
				$percentageRevenue = number_format( 100 - (($days3revenue * 100) / $array_ret2[0]['revenue']), 2);
				$percentageClicks = number_format(100 - (($days3clicks * 100) / $array_ret2[0]['clicks']), 2);
				$percentageConversions = number_format(100 - (($days3conversions * 100) / $array_ret2[0]['conversions']), 2);
				$percentageepc = number_format(100 - (($epc3days * 100) / ($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'])), 2);
				
				array_push($array_result, array( 
					'pshift' => array('clicks' => $percentageClicks, 'conversions' => $percentageConversions, 'revenue' => $percentageRevenue, 'epc' => $percentageepc),
					'last3days' => array('clicks' => number_format($days3clicks), 'conversions' => number_format($days3conversions , 2), 'revenue' => number_format($days3revenue, 2), 'epc' => $epc3days),
					'today' => array('clicks' => number_format($array_ret2[0]['clicks']), 'conversions' => number_format($array_ret2[0]['conversions'] , 2), 'revenue' => number_format($array_ret2[0]['revenue'], 2), 'epc' => number_format($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'], 3))
				));
			} else {
				array_push($array_result, array( 
					'pshift' => array('clicks' => 0, 'conversions' => 0, 'revenue' => 0, 'epc' => 0),
					'last3days' => array('clicks' => 0, 'conversions' => 0, 'revenue' => 0, 'epc' => 0),
					'today' => array('clicks' => 0, 'conversions' => 0, 'revenue' => 0, 'epc' => 0)
				));
			}
			return $array_result;
			
			/*
			$percentageRevenue = number_format( 100 - (($days3revenue * 100) / $array_ret2[0]['revenue']), 2);
			$percentageClicks = number_format(100 - (($days3clicks * 100) / $array_ret2[0]['clicks']), 2);
			$percentageConversions = number_format(100 - (($days3conversions * 100) / $array_ret2[0]['conversions']), 2);
			$percentageepc = number_format(100 - (($epc3days * 100) / ($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'])), 2);
			
			$array_result = array();
			array_push($array_result, array( 
				'pshift' => array('clicks' => $percentageClicks, 'conversions' => $percentageConversions, 'revenue' => $percentageRevenue, 'epc' => $percentageepc),
				'last3days' => array('clicks' => number_format($days3clicks), 'conversions' => number_format($days3conversions , 2), 'revenue' => number_format($days3revenue, 2), 'epc' => $epc3days),
				'today' => array('clicks' => number_format($array_ret2[0]['clicks']), 'conversions' => number_format($array_ret2[0]['conversions'] , 2), 'revenue' => number_format($array_ret2[0]['revenue'], 2), 'epc' => number_format($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'], 3))
			));
			
			return $array_result;
			*/
		}
	
	}
}

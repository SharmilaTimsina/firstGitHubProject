<?php

ini_set('max_execution_time','0'); 
ini_set('memory_limit','800M');

class FreportingController extends ControllerBase
{
	
    public function initialize()
    {
        $this->tag->setTitle('Financial Reporting');
        parent::initialize();
    }

    public function indexAction() {
		$auth = $this->session->get('auth');
		
		$agre = new FReporting();
		$result = $agre->get_agregators(1, $auth);
		$combo = '';
		foreach($result as $agregator) {
			$combo .=  "<option agname='$agregator[agregator]' value='$agregator[id]'>$agregator[id] - $agregator[agregator]</option>";
        }
		
		$this->view->setVar("combo", $combo);
		
		
		
		$acc = new FReporting();
		$result = $acc->get_accountsWithPermissions($auth);
		
        $accountsCombo = '';
        foreach ($result as $account) {
            $accountsCombo .=  "<option agname='$account[username]' value='$account[id]'>$account[username]</option>"; 
        }
        $this->view->setVar("accountsCombo", $accountsCombo);
			
		$this->view->setVar("aggregatorsCombo", $this->getAggs());
				
	}

	public function getAggs() {
		$auth = $this->session->get('auth');

		$agre = new FReporting();
		$result = $agre->get_agregators(2, $auth);
		$aggregatorsCombo = '';
		foreach($result as $agregator) {
			$aggregatorsCombo .=  "<option agname='$agregator[agregator]' value='$agregator[id]'>$agregator[id] - $agregator[agregator]</option>";
        }

        return $aggregatorsCombo;
	}

	public function getAggsAction() {
		echo $this->getAggs();
	}
	
	public function getAffsAction() {

		$fre = new FReporting();
		$result = $fre->getAffiliates();

		$affCombo = '';
		foreach($result as $aff) {
			$affCombo .=  "<option value='$aff[id]'>$aff[id] - $aff[sourceName]</option>";
        }

        echo $affCombo;
	}

	public function uploadAction() {
		if ( isset($_FILES["file"]) ) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Invalid file!";
			}
			else {
				$tdate = $this->request->getPost('tdate');
				$agregator = $this->request->getPost('agregator');
				$agregatorName = $this->request->getPost('aggname');
				
				if(!isset($tdate) || $tdate == '' || !isset($agregator) || $agregator == '') {
					echo "Date or aggregator missed!";
					return null;
				} else {
				
					$tmpName = $_FILES['file']['tmp_name'];
					$csvAsArray = array_map('str_getcsv', file($tmpName));

					//aggregators 
					$agre = new FReporting();
					$result = $agre->get_accounts($agregator);
					
					//delete if exists previous information
					$countries = array();
					foreach(array_slice($csvAsArray,1) AS $line) {
						array_push($countries, $line[0]);
					}

					$fre = new FReporting();
					$fre->deleteBeforeInsert($agregator, $tdate);
					
					foreach(array_slice($csvAsArray,1) AS $line) {
						
						$agre = new FReporting();
						$result2 = $agre->get_mobisteininfo($line[0], $agregator, $tdate);
						
						//conversion
						if($line[3] != 'USD') {
							$agre = new FReporting();
							$convertedAmount = $agre->convertAmount($line[2], $line[3]);

							$convertedAmountInvoiced = $agre->convertAmount($line[4], $line[3]);
						} else {
							$convertedAmount = $line[2];
							$convertedAmountInvoiced = $line[4];
						}
				
						$info = array(
							'aggregatorId' => $agregator,
							'aggregatorName' => $agregatorName,
							'serviceDate' => $tdate,
							'country' => strtoupper($line[0]),
							'clientLeads' => $line[1],
							'clientAmount' => $line[2],
							'clientCurrency' => $line[3],
							'totalAmountInvoiced' => $convertedAmountInvoiced,
							'comments' => $line[5],
							'accountId' => $result['ids'],
							'accountName' => $result['usernames'],
							'mobisteinLeads' => $result2['leads'],
							'duplicateds' => $result2['duplicated'],
							'mobisteinAmount' => $result2['revenue'],
							'typeInfo' => $result2['typeInfo'],
							'clientAmountDollar' => $convertedAmount,
							'difRevenue' => ($convertedAmount - $result2['revenue']),
							'difLeads' => ($line[1] - $result2['leads']),
							'difPercentRevenue' => (($result2['revenue'] == 0) ? 0 : (($convertedAmount - $result2['revenue'])/$result2['revenue'])),
							'difPercentLeads' => (($result2['leads'] == 0) ? 0 : (($line[1] - $result2['leads'])/$result2['leads']))
						);
									
						print_r($info);
						
						$fre = new FReporting();
						if($fre->save($info) == false) {
							foreach ($fre->getMessages() as $message) {
								echo $message, "\n";
							}
							return;					
						} else {
							echo "Complete without errors!";
						}
					}
					
					$agre = new FReporting();
					$result3 = $agre->get_othermobisteininfo($countries, $agregator, $tdate);
					
					foreach($result3 AS $line) {
						$info = array(
							'aggregatorId' => $agregator,
							'aggregatorName' => $agregatorName,
							'serviceDate' => $tdate,
							'country' => strtoupper($line['country']),
							'clientLeads' => 0,
							'clientAmount' => 0,
							'clientCurrency' => 'USD',
							'totalAmountInvoiced' => 0,
							'comments' => 0,
							'accountId' => $result['ids'],
							'accountName' => $result['usernames'],
							'mobisteinLeads' => $line['leads'],
							'duplicateds' => $line['duplicated'],
							'mobisteinAmount' => $line['revenue'],
							'typeInfo' => $line['typeInfo'],
							'clientAmountDollar' => 0,
							'difRevenue' => (0 - $line['revenue']),
							'difLeads' => (0 - $line['leads']),
							'difPercentRevenue' => (($line['revenue'] == 0) ? 0 : ((0 - $line['revenue'])/$line['revenue'])),
							'difPercentLeads' => (($line['leads'] == 0) ? 0 : ((0 - $line['leads'])/$line['leads']))
						);
									
						print_r($info);
						
						$fre = new FReporting();
						if($fre->save($info) == false) {
							foreach ($fre->getMessages() as $message) {
								echo $message, "\n";
							}
							return;					
						} else {
							echo "Complete without errors!";
						}
					}
				}
			}
		}
	}

	public function uploadMultiAggAction() {
		if ( isset($_FILES["file"]) ) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Invalid file!";
				return null;
			}
		} else {
			echo "Invalid file!";
			return null;
		}
			
		$tdate = $this->request->getPost('tdate');
		if(!isset($tdate) || $tdate == '') {
			echo "Date missed!";
			return null;
		}	

		//date info
		$date = explode('-', $tdate);
		
		$typeInfo = '';
		if($date[2] == '15') 
			$typeInfo = '1';
		else 
			$typeInfo = '2';

		//info
		$agre = new FReporting();
		$agregatorsInfo = $agre->getInformationAgregators($tdate);

		//return [$data, $agregators_ids]
		$tmpName = $_FILES['file']['tmp_name'];
		$csv_info = $this->csv_to_array($tmpName, ';');

		//final array with all information
		$final_info = array();

		//accounts for all aggs
		$accounts = $agre->get_accountsMulti($csv_info[1]);

		//delete before insert for aggs in csv
		$agre->deleteBeforeInsertMulti($csv_info[1], $tdate);

		$array_info_agregators = $agre->getAggregators();
		
		foreach($csv_info[0] as $key => $agregatorCsv) {
			foreach($agregatorsInfo as $key2 => $agregatorMobistein) {
				
				if(($agregatorMobistein['agg_id'] == $agregatorCsv['agg_id']) && ($agregatorMobistein['countryCode'] == $agregatorCsv['country'])) {
					
					$convertedAmount = 0;
					$convertedAmountInvoiced = 0;
					if($agregatorCsv['amount_currency'] != 'USD') {
						$converted = $agre->convertAmountToDollar($agregatorCsv['client_amount'], $agregatorCsv['total_amount_invoiced'] , $agregatorCsv['amount_currency'], $tdate);

						$convertedAmount = $converted[0];
						$convertedAmountInvoiced = $converted[1];
					} else {
						$convertedAmount = $agregatorCsv['client_amount'];
						$convertedAmountInvoiced = $agregatorCsv['total_amount_invoiced'];
					}

					$agregator = array(
									'agg_id' => $agregatorMobistein['agg_id'],
									'agg_name' => $agregatorMobistein['agg_name'],
									'country' => $agregatorMobistein['countryCode'],
									'mobistein_amount' => $agregatorMobistein['revenue'],
									'mobistein_leads' => $agregatorMobistein['conversions'],
									'duplicated' => $agregatorMobistein['duplicated'],
									'accounts_ids' => isset($accounts[$agregatorMobistein['agg_id']]) ? $accounts[$agregatorMobistein['agg_id']]['ids'] : '',
									'accounts_names' => isset($accounts[$agregatorMobistein['agg_id']]) ? $accounts[$agregatorMobistein['agg_id']]['usernames'] : '',
									'service_date' => $tdate,
									'type_info' => $typeInfo,
									'client_leads' => $agregatorCsv['client_leads'],
									'client_amount' => $agregatorCsv['client_amount'],
									'client_amount_dollar' => $convertedAmount,
									'client_currency' => $agregatorCsv['amount_currency'],
									'total_amount_invoiced' => $convertedAmountInvoiced,
									'comments' => $agregatorCsv['comments'],
									'difRevenue' => ($convertedAmount - $agregatorMobistein['revenue']),
									'difLeads' => ($agregatorCsv['client_leads'] - $agregatorMobistein['conversions']),
									'difPercentRevenue' => (($agregatorMobistein['revenue'] == 0) ? 0 : (($convertedAmount - $agregatorMobistein['revenue'])/$agregatorMobistein['revenue'])),
									'difPercentLeads' => (($agregatorMobistein['conversions'] == 0) ? 0 : (($agregatorCsv['client_leads'] - $agregatorMobistein['conversions'])/$agregatorMobistein['conversions']))
								);
					
					array_push($final_info, $agregator);

					unset($csv_info[0][$key]);
					unset($agregatorsInfo[$key2]);
					break;
				}
			}
		}

		foreach($csv_info[0] as $key => $agregatorCsv) {
			
			$convertedAmount = 0;
			$convertedAmountInvoiced = 0;
			if($agregatorCsv['amount_currency'] != 'USD') {
				$converted = $agre->convertAmountToDollar($agregatorCsv['client_amount'], $agregatorCsv['total_amount_invoiced'] , $agregatorCsv['amount_currency'], $tdate);

				$convertedAmount = $converted[0];
				$convertedAmountInvoiced = $converted[1];
			} else {
				$convertedAmount = $agregatorCsv['client_amount'];
				$convertedAmountInvoiced = $agregatorCsv['total_amount_invoiced'];
			}

			$agregator = array(
							'agg_id' => $agregatorCsv['agg_id'],
							'agg_name' => isset($array_info_agregators[$agregatorCsv['agg_id']]) ? $array_info_agregators[$agregatorCsv['agg_id']] : '',
							'country' => $agregatorCsv['country'],
							'mobistein_amount' => 0,
							'mobistein_leads' => 0,
							'duplicated' => 0,
							'accounts_ids' => isset($accounts[$agregatorCsv['agg_id']]) ? $accounts[$agregatorCsv['agg_id']]['ids'] : '',
							'accounts_names' => isset($accounts[$agregatorCsv['agg_id']]) ? $accounts[$agregatorCsv['agg_id']]['usernames'] : '',
							'service_date' => $tdate,
							'type_info' => $typeInfo,
							'client_leads' => $agregatorCsv['client_leads'],
							'client_amount' => $agregatorCsv['client_amount'],
							'client_amount_dollar' => $convertedAmount,
							'client_currency' => $agregatorCsv['amount_currency'],
							'total_amount_invoiced' => $convertedAmountInvoiced,
							'comments' => $agregatorCsv['comments'],
							'difRevenue' => ($convertedAmount - 0),
							'difLeads' => ($agregatorCsv['client_leads'] - 0),
							'difPercentRevenue' => 0,
							'difPercentLeads' => 0
						);

			array_push($final_info, $agregator);
		}

		foreach($agregatorsInfo as $key2 => $agregatorMobistein) {
			$agregator = array(
							'agg_id' => $agregatorMobistein['agg_id'],
							'agg_name' => $agregatorMobistein['agg_name'],
							'country' => $agregatorMobistein['countryCode'],
							'mobistein_amount' => $agregatorMobistein['revenue'],
							'mobistein_leads' => $agregatorMobistein['conversions'],
							'duplicated' => $agregatorMobistein['duplicated'],
							'accounts_ids' => isset($accounts[$agregatorMobistein['agg_id']]) ? $accounts[$agregatorMobistein['agg_id']]['ids'] : '',
							'accounts_names' => isset($accounts[$agregatorMobistein['agg_id']]) ? $accounts[$agregatorMobistein['agg_id']]['usernames'] : '',
							'service_date' => $tdate,
							'type_info' => $typeInfo,
							'client_leads' => 0,
							'client_amount' => 0,
							'client_amount_dollar' => 0,
							'client_currency' => '',
							'total_amount_invoiced' => 0,
							'comments' => '',
							'difRevenue' => (0 - $agregatorMobistein['revenue']),
							'difLeads' => (0 - $agregatorMobistein['conversions']),
							'difPercentRevenue' => (($agregatorMobistein['revenue'] == 0) ? 0 : ((0 - $agregatorMobistein['revenue'])/$agregatorMobistein['revenue'])),
							'difPercentLeads' => (($agregatorMobistein['conversions'] == 0) ? 0 : ((0 - $agregatorMobistein['conversions'])/$agregatorMobistein['conversions']))
						);

			array_push($final_info, $agregator);
		}

		$agre->insertIntoWhat($final_info);
	}

	public function csv_to_array($filename='', $delimiter=';') {
	    if(!file_exists($filename) || !is_readable($filename))
	        return FALSE;

	    $data = array();
	    $agregators_ids = array();
	    if (($handle = fopen($filename, 'r')) !== FALSE)
	    {
	        while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE)
	        {	
	        	$header = ['agg_id', 'country', 'client_leads', 'client_amount', 'amount_currency', 'total_amount_invoiced', 'comments'];

	        	if(!in_array($row[0], $agregators_ids))
	        		array_push($agregators_ids, $row[0]);

	            $data[] = array_combine($header, $row);
	        }
	        fclose($handle);
	    }

	    array_shift($data);
	    array_shift($agregators_ids);

	    return [$data, $agregators_ids];
	}
	
	public function uploadMultiAgg2Action() {
		if ( isset($_FILES["file"]) ) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Invalid file!";
			}
			else {
				$tdate = $this->request->getPost('tdate');
				
				if(!isset($tdate) || $tdate == '') {
					echo "Date missed!";
					return null;
				} else {
				
					$tmpName = $_FILES['file']['tmp_name'];
					$csvAsArray = array_map('str_getcsv', file($tmpName));

					$aggregatorsInfo = array();
					$aggsIds = array();
					$countries = array();
					foreach(array_slice($csvAsArray,1) AS $line) {
						
						//check if exists in array this agregator
						$aggId = $line[0];
						$country = $line[1];

						if(array_key_exists($aggId, $aggregatorsInfo)) {
							
							array_push($aggregatorsInfo[$aggId], array(
									'country' => $line[1],
									'clientLeads' => $line[2],
									'clientAmount' => $line[3],
									'currency' => $line[4],
									'totalAmountInvoiced' => $line[5],
									'comments' => ((isset($line[6])) ? $line[6] : '')
								));

							array_push($countries[$aggId], $country);


						} else {
							$aggregatorsInfo[$aggId] = array();
							$countries[$aggId] = array();
							
							array_push($countries[$aggId], $country);

							array_push($aggsIds, $aggId);

							array_push($aggregatorsInfo[$aggId], array(
									'country' => $line[1],
									'clientLeads' => $line[2],
									'clientAmount' => $line[3],
									'currency' => $line[4],
									'totalAmountInvoiced' => $line[5],
									'comments' => ((isset($line[6])) ? $line[6] : '')
								));
						}
					}
					
					//accounts for each agg 
					$agre = new FReporting();
					$accounts = $agre->get_accountsMulti($aggsIds);
					
					//delete before insert
					$fre = new FReporting();
					$fre->deleteBeforeInsertMulti($aggsIds, $tdate);

					$date = explode('-', $tdate);
					$typeInfo = '';
					if($date[2] == '15') 
						$typeInfo = '1';
					else 
						$typeInfo = '2';

					//print_r($aggregatorsInfo);

					$fre = new FReporting();
					$aggsNames = $fre->getNameAgregators($aggsIds);

					$aggInfo = array();
					foreach($aggregatorsInfo AS $agg => $value) {
						//get mobistein info
						$agre = new FReporting();
						$result2 = $agre->get_mobisteininfoMulti($countries[$agg], $agg, $tdate);
						
						$processedCountries = array();
						foreach($result2 AS $mobinfo) {
							
							foreach ($value as $line) {

								if(strtoupper($mobinfo['country']) == strtoupper($line['country'])) {
									$line['typeInfo'] = $typeInfo;
									$line['revenue'] = $mobinfo['rev'];
									$line['leads'] = $mobinfo['conv'];
									$line['duplicated'] = $mobinfo['duplicated'];
									$line['aggID'] = $agg;

									unset($aggregatorsInfo[$agg]);
									array_push($processedCountries, $line['country']);

									foreach ($aggsNames as $agg2) {
										if($line['aggID'] == $agg2['id']) {
											$line['aggName'] = $agg2['agregator'];

											$line['accountId'] = $accounts[$agg]['ids'];
											$line['accountName'] = $accounts[$agg]['usernames'];
											array_push($aggInfo, $line);
										}
									}

									break;
								} 
							}
						}

						foreach ($value as $line) {
							if(!in_array($line['country'], $processedCountries)){
								$line['typeInfo'] = $typeInfo;
								$line['revenue'] = '0';
								$line['leads'] = '0';
								$line['duplicated'] = 0;
								$line['aggID'] = $agg;

								foreach ($aggsNames as $agg2) {
									if($line['aggID'] == $agg2['id']) {
										$line['aggName'] = $agg2['agregator'];

										$line['accountId'] = $accounts[$agg]['ids'];
										$line['accountName'] = $accounts[$agg]['usernames'];
										array_push($aggInfo, $line);
									}
								}
							}
						}
					}
					
					foreach($aggInfo AS $info) {

						//conversion
						if($info['currency'] != 'USD') {
							$agre = new FReporting();
							$convertedAmount = $agre->convertAmount($info['clientAmount'], $info['currency']);

							$convertedAmountInvoiced = $agre->convertAmount($info['totalAmountInvoiced'], $info['currency']);
						} else {
							$convertedAmount = $info['clientAmount'];
							$convertedAmountInvoiced = $info['totalAmountInvoiced'];
						}
						

						$info = array(
							'aggregatorId' => $info['aggID'],
							'aggregatorName' => $info['aggName'],
							'serviceDate' => $tdate,
							'country' => strtoupper($info['country']),
							'clientLeads' => $info['clientLeads'],
							'clientAmount' => $info['clientAmount'],
							'clientCurrency' => $info['currency'],
							'totalAmountInvoiced' => $convertedAmountInvoiced,
							'comments' => $info['comments'],
							'accountId' => $info['accountId'],
							'accountName' => $info['accountName'],
							'mobisteinLeads' => $info['leads'],
							'duplicateds' => $info['duplicated'],
							'mobisteinAmount' => $info['revenue'],
							'typeInfo' => $info['typeInfo'],
							'clientAmountDollar' => $convertedAmount,
							'difRevenue' => ($convertedAmount - $info['revenue']),
							'difLeads' => ($info['clientLeads'] - $info['leads']),
							'difPercentRevenue' => (($info['revenue'] == 0) ? 0 : (($convertedAmount - $info['revenue'])/$info['revenue'])),
							'difPercentLeads' => (($info['leads'] == 0) ? 0 : (($info['clientLeads'] - $info['leads'])/$info['leads']))
						);

						print_r($info);
						
						$fre = new FReporting();
						if($fre->save($info) == false) {
							foreach ($fre->getMessages() as $message) {
								echo $message, "\n";
							}
							//return;					
						} else {
							echo "Complete without errors!";
						}
					}

					foreach($aggsIds AS $agg) {

						$agre = new FReporting();
						$result3 = $agre->get_othermobisteininfo($countries[$agg], $agg, $tdate);
						
						$extraInfo = array();
						foreach ($aggsNames as $agg2) {
							if($agg == $agg2['id']) {
								$extraInfo['aggName'] = $agg2['agregator'];

								$extraInfo['accountId'] = $accounts[$agg]['ids'];
								$extraInfo['accountName'] = $accounts[$agg]['usernames'];

								break;
							}
						}

						foreach($result3 AS $line) {
							
							//print_r($result3);

							$info = array(
								'aggregatorId' => $agg,
								'aggregatorName' => $extraInfo['aggName'],
								'serviceDate' => $tdate,
								'country' => strtoupper($line['country']),
								'clientLeads' => 0,
								'clientAmount' => 0,
								'clientCurrency' => 'USD',
								'totalAmountInvoiced' => 0,
								'comments' => 0,
								'accountId' => $extraInfo['accountId'],
								'accountName' => $extraInfo['accountName'],
								'mobisteinLeads' => $line['leads'],
								'duplicateds' => $line['duplicated'],
								'mobisteinAmount' => $line['revenue'],
								'typeInfo' => $line['typeInfo'],
								'clientAmountDollar' => 0,
								'difRevenue' => (0 - $line['revenue']),
								'difLeads' => (0 - $line['leads']),
								'difPercentRevenue' => (($line['revenue'] == 0) ? 0 : ((0 - $line['revenue'])/$line['revenue'])),
								'difPercentLeads' => (($line['leads'] == 0) ? 0 : ((0 - $line['leads'])/$line['leads']))
							);
										
							print_r($info);
							
							$fre = new FReporting();
							if($fre->save($info) == false) {
								foreach ($fre->getMessages() as $message) {
									echo $message, "\n";
								}
								//return;					
							} else {
								echo "Complete without errors (other countries)!";
							}
						}
					}		

					$date = explode('-', $tdate);
					if($date[2] == '15') {
						//nothing to do
					}
					else {
						$typeInfo = 2;

						$fre = new FReporting();
						$resu = $fre->get_mobisteininfoAggsNotInserted($aggsIds, $tdate);

						$aggs = array();
						foreach($resu AS $line) {
							array_push($aggs, $line['agg']);
						}

						$agre = new FReporting();
						$accounts = $agre->get_accountsMulti($aggs);

						foreach($resu AS $line) {
							
							$extraInfo = array();
							foreach ($aggsNames as $agg2) {
								if($line['agg'] == $agg2['id']) {
									$extraInfo['aggName'] = $agg2['agregator'];

									$extraInfo['accountId'] = $accounts[$line['agg']]['ids'];
									$extraInfo['accountName'] = $accounts[$line['agg']]['usernames'];

									break;
								}
							}

							$info = array(
								'aggregatorId' => $line['agg'],
								'aggregatorName' => $extraInfo['aggName'],
								'serviceDate' => $tdate,
								'country' => strtoupper($line['country']),
								'clientLeads' => 0,
								'clientAmount' => 0,
								'clientCurrency' => 'USD',
								'totalAmountInvoiced' => 0,
								'comments' => '',
								'accountId' => $extraInfo['accountId'],
								'accountName' => $extraInfo['accountName'],
								'mobisteinLeads' => $line['conv'],
								'duplicateds' => $line['duplicated'],
								'mobisteinAmount' => $line['rev'],
								'typeInfo' => $typeInfo,
								'clientAmountDollar' => 0,
								'difRevenue' => (0 - $line['rev']),
								'difLeads' => (0 - $line['conv']),
								'difPercentRevenue' => (($line['rev'] == 0) ? 0 : ((0 - $line['rev'])/$line['rev'])),
								'difPercentLeads' => (($line['conv'] == 0) ? 0 : ((0 - $line['conv'])/$line['conv']))
							);
										
							print_r($info);
							
							$fre = new FReporting();
							if($fre->save($info) == false) {
								foreach ($fre->getMessages() as $message) {
									echo $message, "\n";
								}
								//return;					
							} else {
								echo "Complete without errors (other aggs)!";
							}
						}
					}
				}
			}
		}
	}

	public function setFilterAffs($data_array) {
		$auth = $this->session->get('auth');

		$countries = explode(',', $data_array['countries']);
		$aggregators = explode(',', $data_array['aggregators']);

		$sdate =  substr($data_array['date'], 0, 10);
		$edate = substr($data_array['date'], 13, 20);

		$countriesSql = '';
		if(in_array('ALL', $countries)) {
			$countriesSql = '';
		} else {
			$countriesSql = '';
			foreach($countries as $country) {
				$countriesSql .= "'" . strtoupper($country) . "',";
			}
			$countriesSql = substr ($countriesSql, 0, -1);
			
			$countriesSql = " AND campaign_country IN (" . $countriesSql . ")";
		}

		$aggregatorsSql = '';
		if(in_array('ALL', $aggregators)) {
			$aggregatorsSql = '';
			
			if($auth['userlevel'] == 3) {
				$aggregatorsPerUser = explode(',', $auth['aggregators']);
				foreach($aggregatorsPerUser as $aggregator) {
					$aggregatorsSql .= "'" . $aggregator . "',";
				}
				$aggregatorsSql = substr($aggregatorsSql, 0, -1);
				
				$aggregatorsSql = " AND advertiser_id IN (" . $aggregatorsSql . ")";
			}
			
		} else {
			$aggregatorsSql = '';
			foreach($aggregators as $aggregator) {
				$aggregatorsSql .= "'" . $aggregator . "',";
			}
			$aggregatorsSql = substr($aggregatorsSql, 0, -1);
			
			$aggregatorsSql = " AND advertiser_id IN (" . $aggregatorsSql . ")";
		}
		
		$month = '';
		$groupbySql = '';
		if($data_array['groupbymonthCheck'] == 'true') {
			$month = '<td>Month</td>';
			$groupbySql .= ($groupbySql != '') ? ' ,DATE_FORMAT(insertDate,"%M %Y") ' : ' DATE_FORMAT(insertDate,"%M %Y") ';
		}

		if($data_array['groupbyaffiliateCheck'] == 'true') {
			$groupbySql .= ($groupbySql != '') ? ' ,affiliate_id ' : ' affiliate_id ';
		}

		if($data_array['groupbyaffCheck'] == 'true') {
			$groupbySql .= ($groupbySql != '') ? ' ,advertiser_id ' : ' advertiser_id ';
		} 

		/*
		else {
			$groupbySql .= ($groupbySql != '') ? ' ,advertiser_id ' : ' advertiser_id ';
		}
		*/

		if($data_array['groupbycountryCheck'] == 'true') 
			$groupbySql .= ($groupbySql != '') ? ' ,campaign_country ' : ' campaign_country ';

		$tableHead = "<thead style='text-align: center;'>
						<tr>
							<td>Client</td>
							<td>Affiliate</td>
							<td>Country</td>
							<td>Investment</td>
							<td>Revenue</td>
							<td>Margin</td>
							<td>Margin %</td>
							$month
						</tr>
					</thead>";

		$repor = new FReporting();
		$result = $repor->get_infoAffs($data_array, $countriesSql, $aggregatorsSql, $groupbySql, $sdate, $edate);

		$tbody = '<tbody>';
		foreach($result as $row) {
			
			$revenue = ($row['revenue'] == null) ? 0 : number_format($row['revenue'], 2, '.', ',');
			$margin = ($row['margin'] == null) ? 0 : number_format($row['margin'], 2, '.', ',');
			$investment = ($row['investment'] == null) ? 0 : number_format($row['investment'], 2, '.', ',');

			$country = '-';
			$aggInfo = '-';
			$affinfo = '-';

			$agregation = false;
			if($data_array['groupbycountryCheck'] == 'true') {
				$country = $row['campaign_country'];
				$agregation = true;
			}

			if($data_array['groupbyaffCheck'] == 'true') {
				$aggInfo = $row['aggInfo'];
				$agregation = true;
			}

			if($data_array['groupbyaffiliateCheck'] == 'true') {
				$affinfo = $row['affInfo'];
				$agregation = true;
			}

			$month = '';
			if($data_array['groupbymonthCheck'] == 'true') {
				$month = "<td>" . $row['month'] . "</td>";
				$agregation = true;
			} 

			if(!$agregation) {
				$aggInfo = $row['aggInfo'];
			}

			$marginPercent = ($revenue != 0) ? number_format(((($row['revenue'] - $row['investment']) / $row['revenue']) * 100),2,',', '.') : number_format(0,2,',', '.') ;

			$tbody .= "<tr>
							<td>$aggInfo</td>
							<td>$affinfo</td>
							<td>$country</td>
							<td>$investment$</td>
							<td>$revenue$</td>
							<td>$margin$</td>
							<td>$marginPercent%</td>
							$month
						</tr>";

		}
		$tbody .= '</tbody>';
		
		$tfoot = '';
		
		echo json_encode(array('thead' => $tableHead, 'tbody' => $tbody, 'tfoot' => $tfoot));
	}

	public function setFilterAction() {
		$auth = $this->session->get('auth');
		
		$data_array = array(
			'date' => $this->request->getPost('date'),
			'countries' => $this->request->getPost('countries'),
			'accounts' => $this->request->getPost('accounts'),
			'aggregators' => $this->request->getPost('aggregators'),
			'typeInfo' => $this->request->getPost('typeInfo'),
			//'checkBox' => $this->request->getPost('checkBox'),
			'checkBoxIds' => $this->request->getPost('checkBoxIds'),
			'groupby' => $this->request->getPost('groupby'),
			'affiliates' => $this->request->getPost('affiliates'),
			'groupbyaffCheck' => $this->request->getPost('groupbyaffCheck'),
			'groupbymonthCheck' => $this->request->getPost('groupbymonthCheck'),
			'groupbyaffiliateCheck' => $this->request->getPost('groupbyaffiliateCheck'),
			'groupbycountryCheck' => $this->request->getPost('groupbycountryCheck')
		);

		if($data_array['affiliates'] == 'true') {
			$this->setFilterAffs($data_array);
			return;
		}
		
		$sdate =  substr($data_array['date'], 0, 10);
		$edate = substr($data_array['date'], 13, 20);
		
		$countries = explode(',', $data_array['countries']);
		$accounts = explode(',', $data_array['accounts']);
		$aggregators = explode(',', $data_array['aggregators']);
		$checkBoxIds = explode(',', $data_array['checkBoxIds']);
		
		$groupbySql = '';
		if($data_array['groupby'] == 'true') 
			$groupbySql = ' ,country ';
			
		
		$countriesSql = '';
		if(in_array('ALL', $countries)) {
			$countriesSql = '';
		} else {
			$countriesSql = '';
			foreach($countries as $country) {
				$countriesSql .= "'" . strtoupper($country) . "',";
			}
			$countriesSql = substr($countriesSql, 0, -1);
			
			$countriesSql = " AND country IN (" . $countriesSql . ")";
		}
		
		$accountsSql = '';
		if(in_array('ALL', $accounts)) {
			$accountsSql = '';
			
			if($auth['userlevel'] == 3) {
				$accountsSql = ' AND ( ';
				$accountsSql .= " accountName LIKE '%" . $auth['name'] . "%') ";
			}
			
		} else {
			$accountsSql = ' AND ( ';
			foreach($accounts as $account) {
				if ($account !== end($accounts)) {
					$accountsSql .= " accountName LIKE '%" . $account . "%' OR ";
				} else {
					$accountsSql .= " accountName LIKE '%" . $account . "%') ";
				}
			}
		}
		
		$aggregatorsSql = '';
		if(in_array('ALL', $aggregators)) {
			$aggregatorsSql = '';
			
			if($auth['userlevel'] == 3) {
				$aggregatorsPerUser = explode(',', $auth['aggregators']);
				foreach($aggregatorsPerUser as $aggregator) {
					$aggregatorsSql .= "'" . $aggregator . "',";
				}
				$aggregatorsSql = substr($aggregatorsSql, 0, -1);
				
				$aggregatorsSql = " AND aggregatorId IN (" . $aggregatorsSql . ")";
			}
			
		} else {
			$aggregatorsSql = '';
			foreach($aggregators as $aggregator) {
				$aggregatorsSql .= "'" . $aggregator . "',";
			}
			$aggregatorsSql = substr($aggregatorsSql, 0, -1);
			
			$aggregatorsSql = " AND aggregatorId IN (" . $aggregatorsSql . ")";
		}
		
		//$tableHeads = explode(',', $data_array['checkBox']);
		
		$tableHead = '<thead style="text-align: center;">
							<tr>
								<td title="Client Name">Agregator</td>
								<td title="Campaign Country">Country</td>
								<td>Account</td>
								<td title="Service Date">Data</td>';
	
		sort($checkBoxIds);
		foreach($checkBoxIds as $checkbox) {
				switch($checkbox){
					case 1:
						$tableHead .= '<td>Mobistein Conversions</td>';
						break;
					case 2:
						$tableHead .= '<td title="Client Conversions (inserted by accounting team)">Client Conversions</td>';
						break;
					case 3:
						$tableHead .= '<td title="Client Conversions - Mobistein Conversions">Dif. Conversions</td>';
						break;
					case 4:
						$tableHead .= '<td title="Δ Client Conversions vs Mobistein Conversions (%)">Dif. Conversions %</td>';
						break;
					case 5:
						$tableHead .= '<td title="Conversions Duplicated (detected by Mobistein)">Duplicated</td>';
						break;
					case 6:
						$tableHead .= '<td title="Δ Conversions Duplicated vs Mobistein Conversions(%)">Dif. duplicated %</td>';
						break;
					case 7:
						$tableHead .= '<td>Mobistein Revenue</td>';
						break;
					case 8:
						$tableHead .= '<td title="Client Revenue (inserted by accounting team)">Client Revenue</td>';
						break;
					case 9:
						$tableHead .= '<td title="Client Revenue - Mobistein Revenue">Dif. Revenue</td>';
						break;
					case 10:
						$tableHead .= '<td title="Client Revenue - Mobistein Revenue (%)">Dif. Revenue %</td>';
						break;
					case 12:
						$tableHead .= '<td>Invoiced</td>';
						break;
					case 11:
						$tableHead .= '<td title="Invoices issued (inserted by accounting team)">Total amount Invoiced</td>';
						break;
				}
				
			}	
		
		$tableHead .= '<td>Comments</td>
							</tr>
						</thead>';
						
		$repor = new FReporting();
		$result = $repor->get_info($data_array, $accountsSql, $countriesSql,$groupbySql, $aggregatorsSql, $sdate, $edate);
		
		$totals = array(
			'mobisteinConversions' => 0,
			'clientConversions' => 0,
			'difLeads' => 0,
			'difPercentLeads' => 0,
			'duplicateds' => 0,
			'difduplicateds' => 0,
			'mobisteinRevenue' => 0,
			'clientRevenue' => 0,
			'difRevenue' => 0,
			'difPercentRevenue' => 0,
			'totalAmountInvoiced' => 0
		);
			
		$tbody = '<tbody>';
		foreach($result as $row) {
			
			if($data_array['groupby'] === 'true') 
				$country = $row['country'];
			else 
				$country = '-';
			
			$tbody .= "<tr>
						<td>$row[aggregator]</td>
						<td>$country</td>
						<td>$row[accountName]</td>
						<td>$row[serviceDate]</td>";
			
			//colunas escolhidas
			//1
			$totals['mobisteinConversions'] += $row['mobisteinConversions'];
			//2
			$totals['clientConversions'] += $row['clientConversions'];
			//5
			$totals['duplicateds'] += $row['duplicateds'];
			//7
			$totals['mobisteinRevenue'] += $row['mobisteinRevenue'];
			//8
			$totals['clientRevenue'] += $row['clientRevenue'];
			//11
			$totals['totalAmountInvoiced'] += $row['totalAmountInvoiced'];
			
			foreach($checkBoxIds as $checkbox) {
				switch($checkbox){
					case 1:
						$mc = number_format($row['mobisteinConversions'], 0, '.', ',');
						$tbody .= "<td>$mc</td>";
						break;
					case 2:
						$cc = number_format($row['clientConversions'], 0, '.', ',');
						$tbody .= "<td>$cc</td>";
						break;
					case 3:
						$difc = number_format($row['difLeads'], 0, '.', ',');
						
						$color = '';
						if($difc < 0)
							$color = 'color:red;';
						else if($difc > 0)
							$color = 'color:green;';
						
						$tbody .= "<td style='$color'>$difc</td>";
						break;
					case 4:
						$difcp = number_format(($row['difPercentLeads'] * 100), 2, '.', ',');
						
						$color = '';
						if($difcp < 0)
							$color = 'color:red;';
						else if($difcp > 0)
							$color = 'color:green;';
						
						if($difcp < -5)
							$color = 'color:red; font-weight: 800;';	
						
						$tbody .= "<td style='$color'>$difcp%</td>";
						break;
					case 5:
						$dup = number_format($row['duplicateds'], 0, '.', ',');
						$tbody .= "<td>$dup</td>";
						break;
					case 6:
						$difdup = number_format(($row['difduplicateds'] * 100), 2, '.', ',');
						$tbody .= "<td>$difdup%</td>";
						break;
					case 7:
						$mobrev = number_format($row['mobisteinRevenue'], 2, '.', ',');
						$tbody .= "<td>$mobrev$</td>";
						break;
					case 8:
						$clirev = number_format($row['clientRevenue'], 2, '.', ',');
						$tbody .= "<td>$clirev$</td>";
						break;
					case 9:
						$difrev = number_format($row['difRevenue'], 2, '.', ',');
						
						$color = '';
						if($difrev < 0)
							$color = 'color:red;';
						else if($difrev > 0)
							$color = 'color:green;';
						
						$tbody .= "<td style='$color'>$difrev$</td>";
						break;
					case 10:
						$difprev = number_format($row['difPercentRevenue'], 2, '.', ',');
						
						$color = '';
						if($difprev < 0)
							$color = 'color:red;';
						else if($difprev > 0)
							$color = 'color:green;';
						
						if($difprev < -5)
							$color = 'color:red; font-weight: 800;';	
						
						$tbody .= "<td style='$color'>$difprev%</td>";
						
						break;
					case 12:
						if($row['invoicedState'] == 'TRUE')
							$tbody .= "<td><img class='stateImage' src='/img/ok.gif'></td>";
						else 
							$tbody .= "<td><img class='stateImage' src='/img/nok.png'></td>";
						break;
					case 11:
						$totalAmount = number_format($row['totalAmountInvoiced'], 2, '.', ',');
						$tbody .= "<td>$totalAmount$</td>";
						break;
				}
				
				
				
			}	

			$country = '';
			if($data_array['groupby'] == 'true') 
				$country = $row['country'];

			if($row['comments'] != '')
				$tbody .= "'<td><img class='detailsImg' c='$row[comments]' agg='$row[aggregatorId]' coun='$country' date='$row[serviceDate]' src='/img/details.png' data-toggle='modal' data-target='#modaldetails'></td>
							</tr>";
			else {
				$tbody .= "'<td><img class='detailsImg' c='$row[comments]' agg='$row[aggregatorId]' coun='$country' date='$row[serviceDate]' src='/img/detailsNot.png' data-toggle='modal' data-target='#modaldetails'></td>
							</tr>";
			}
		}
		
		$tbody .= '</tbody>';
		
		
		$tfoot = '<tfoot>';
	
		$tfoot .= "<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>";
			
		//colunas escolhidas
		foreach($checkBoxIds as $checkbox) {
			switch($checkbox){
				case 1:
					$mc = number_format($totals['mobisteinConversions'], 0, '.', ',');
					$tfoot .= "<td>$mc</td>";
					break;
				case 2:
					$cc = number_format($totals['clientConversions'], 0, '.', ',');
					$tfoot .= "<td>$cc</td>";
					break;
				case 3:
					$difLeads = number_format(($totals['clientConversions'] - $totals['mobisteinConversions']), 0, '.', ',');
					$tfoot .= "<td>$difLeads</td>";
					break;
				case 4:
					$difPercentLeads = number_format(($totals['mobisteinConversions'] == 0) ? 0 : ((($totals['clientConversions'] - $totals['mobisteinConversions']) / $totals['mobisteinConversions']) * 100), 2, '.', ',');
					
					$color = '';
					if($difPercentLeads < 0)
						$color = 'color:red;';
					else if($difPercentLeads > 0)
						$color = 'color:green;';
					
					if($difPercentLeads < -5)
						$color = 'color:red; font-weight: 800;';	
					
					$tfoot .= "<td style='$color'>$difPercentLeads%</td>";
					break;
				case 5:
					$dup = number_format($totals['duplicateds'], 0, '.', ',');
					$tfoot .= "<td>$dup</td>";
					break;
				case 6:
					$difduplicateds = number_format(($totals['mobisteinConversions'] == 0) ? 0 : ($totals['duplicateds'] / $totals['mobisteinConversions']) * 100, 2, '.', '');
					$tfoot .= "<td>$difduplicateds%</td>";
					break;
				case 7:
					$mobrev = number_format($totals['mobisteinRevenue'], 2, '.', ',');
					$tfoot .= "<td>$mobrev$</td>";
					break;
				case 8:
					$clirev = number_format($totals['clientRevenue'], 2, '.', ',');
					$tfoot .= "<td>$clirev$</td>";
					break;
				case 9:
					$difrev = number_format(($totals['clientRevenue'] - $totals['mobisteinRevenue']), 2, '.', ',');
					
					$color = '';
					if($difrev < 0)
						$color = 'color:red;';
					else if($difrev > 0)
						$color = 'color:green;';
						
					$tfoot .= "<td style='$color'>$difrev$</td>";
					break;
				case 10:
					$difPercentRevenue = number_format(($totals['mobisteinRevenue'] == 0) ? 0 : ((($totals['clientRevenue'] - $totals['mobisteinRevenue']) / $totals['mobisteinRevenue']) * 100), 2, '.', ',');
					
					$color = '';
					if($difPercentRevenue < 0)
						$color = 'color:red;';
					else if($difPercentRevenue > 0)
						$color = 'color:green;';
					
					if($difPercentRevenue < -5)
						$color = 'color:red; font-weight: 800;';	
					
					$tfoot .= "<td style='$color'>$difPercentRevenue%</td>";
					break;
				case 12:
					$tfoot .= "<td>-</td>";
					break;
				case 11:
					$totalAmount = number_format($totals['totalAmountInvoiced'], 2, '.', ',');
					$tfoot .= "<td>$totalAmount$</td>";
					break;
			}
		}
		
		$tfoot .= "<td>-</td></tr>";
		
		$tfoot .= '</tfoot>';
		
		echo json_encode(array('thead' => $tableHead, 'tbody' => $tbody, 'tfoot' => $tfoot));
	}
	
	public function updateCommentAction() {
		$data_array = array(
			'comment' => $this->request->getPost('comment'),
			'agg' => $this->request->getPost('agg'),
			'coun' => $this->request->getPost('coun'),
			'date' => $this->request->getPost('date')
		);
		
		$repor = new FReporting();
		$result = $repor->updateCom($data_array);
	}
	
	public function downloadCsvAffs($data_array) {
		$auth = $this->session->get('auth');

		$countries = explode(',', $data_array['countries']);
		$aggregators = explode(',', $data_array['aggregators']);

		$sdate =  substr($data_array['date'], 0, 10);
		$edate = substr($data_array['date'], 13, 20);

		$countriesSql = '';
		if(in_array('ALL', $countries)) {
			$countriesSql = '';
		} else {
			$countriesSql = '';
			foreach($countries as $country) {
				$countriesSql .= "'" . strtoupper($country) . "',";
			}
			$countriesSql = substr ($countriesSql, 0, -1);
			
			$countriesSql = " AND campaign_country IN (" . $countriesSql . ")";
		}

		$aggregatorsSql = '';
		if(in_array('ALL', $aggregators)) {
			$aggregatorsSql = '';
			
			if($auth['userlevel'] == 3) {
				$aggregatorsPerUser = explode(',', $auth['aggregators']);
				foreach($aggregatorsPerUser as $aggregator) {
					$aggregatorsSql .= "'" . $aggregator . "',";
				}
				$aggregatorsSql = substr($aggregatorsSql, 0, -1);
				
				$aggregatorsSql = " AND advertiser_id IN (" . $aggregatorsSql . ")";
			}
			
		} else {
			$aggregatorsSql = '';
			foreach($aggregators as $aggregator) {
				$aggregatorsSql .= "'" . $aggregator . "',";
			}
			$aggregatorsSql = substr($aggregatorsSql, 0, -1);
			
			$aggregatorsSql = " AND advertiser_id IN (" . $aggregatorsSql . ")";
		}


		$month = '';
		$groupbySql = '';
		if($data_array['groupbymonthCheck'] == 'true') {
			$month = '<td>Month</td>';
			$groupbySql .= ($groupbySql != '') ? ' ,DATE_FORMAT(insertDate,"%M %Y") ' : ' DATE_FORMAT(insertDate,"%M %Y") ';
		}

		if($data_array['groupbyaffiliateCheck'] == 'true') {
			$groupbySql .= ($groupbySql != '') ? ' ,affiliate_id ' : ' affiliate_id ';
		}

		if($data_array['groupbyaffCheck'] == 'true') {
			$groupbySql .= ($groupbySql != '') ? ' ,advertiser_id ' : ' advertiser_id ';
		} 

		if($data_array['groupbycountryCheck'] == 'true') 
			$groupbySql .= ($groupbySql != '') ? ' ,campaign_country ' : ' campaign_country ';


		/*
		$month = '';
		$groupbySql = '';
		if($data_array['groupbymonthCheck'] == 'true') {
			$month = '|Month';
			$groupbySql .= ($groupbySql != '') ? ' ,DATE_FORMAT(insertDate,"%M %Y") ' : ' DATE_FORMAT(insertDate,"%M %Y") ';
		} 

		if($data_array['groupbyaffiliateCheck'] == 'true') {
			$groupbySql .= ($groupbySql != '') ? ' ,affiliate_id ' : ' affiliate_id ';
		}

		if($data_array['groupbyaffCheck'] == 'true') {
			$groupbySql .= ($groupbySql != '') ? ' ,advertiser_id ' : ' advertiser_id ';
		} else {
			$groupbySql .= ($groupbySql != '') ? ' ,advertiser_id, campaign_country' : ' advertiser_id , campaign_country ';
		}
		*/

		$tableHead = "Agregator|Affiliate|Country|Investment|Revenue|Margin|Margin %" . $month;

		$repor = new FReporting();
		$result = $repor->get_infoAffs($data_array, $countriesSql, $aggregatorsSql, $groupbySql, $sdate, $edate);

		$tbody = '';
		foreach($result as $row) {
			
			$revenue = ($row['revenue'] == null) ? 0 : $row['revenue'] ;
			$margin = ($row['margin'] == null) ? 0 : $row['margin'] ;
			$investment = ($row['investment'] == null) ? 0 : $row['investment'] ;

			$country = '-';
			$aggInfo = '-';
			$affInfo = '-';

			$agregation = false;
			if($data_array['groupbycountryCheck'] == 'true') {
				$country = $row['campaign_country'];
				$agregation = true;
			}

			if($data_array['groupbyaffCheck'] == 'true') {
				$aggInfo = $row['aggInfo'];
				$agregation = true;
			}

			if($data_array['groupbyaffiliateCheck'] == 'true') {
				$affInfo = $row['affInfo'];
				$agregation = true;
			}

			$month = '';
			if($data_array['groupbymonthCheck'] == 'true') {
				$month = "|" . $row['month'];
				$agregation = true;
			} 

			if(!$agregation) {
				$aggInfo = $row['aggInfo'];
			}

			$marginPercent = ($revenue != 0) ? number_format(((($revenue - $investment) / $revenue) * 100),2,',', '.') . '%' : 0 ;

			$tbody .= "$aggInfo|$affInfo|$country|$investment|$revenue|$margin|$marginPercent $month\n";

		}
		
		$tableHead .= "\n " . $tbody;
		
		echo "sep=|\n" . $tableHead;
		
	}

	public function downloadCsvAction() {
		$auth = $this->session->get('auth');
		
		$data_array = array(
			'date' => $this->request->getPost('date'),
			'countries' => $this->request->getPost('countries'),
			'accounts' => $this->request->getPost('accounts'),
			'aggregators' => $this->request->getPost('aggregators'),
			'typeInfo' => $this->request->getPost('typeInfo'),
			//'checkBox' => $this->request->getPost('checkBox'),
			'checkBoxIds' => $this->request->getPost('checkBoxIds'),
			'groupby' => $this->request->getPost('groupby'),
			'affiliates' => $this->request->getPost('affiliates'),
			'groupbyaffCheck' => $this->request->getPost('groupbyaffCheck'),
			'groupbymonthCheck' => $this->request->getPost('groupbymonthCheck'),
			'groupbyaffiliateCheck' => $this->request->getPost('groupbyaffiliateCheck'),
			'groupbycountryCheck' => $this->request->getPost('groupbycountryCheck')
		);

		if($data_array['affiliates'] == 'true') {
			$this->downloadCsvAffs($data_array);
			return;
		}
		
		$sdate =  substr($data_array['date'], 0, 10);
		$edate = substr($data_array['date'], 13, 20);
		
		$countries = explode(',', $data_array['countries']);
		$accounts = explode(',', $data_array['accounts']);
		$aggregators = explode(',', $data_array['aggregators']);
		$checkBoxIds = explode(',', $data_array['checkBoxIds']);
		
		$groupbySql = '';
		if(isset($data_array['groupby']) && $data_array['groupby'] != '')
			if($data_array['groupby'] == 'true') 
				$groupbySql = ' country ';
		
		$countriesSql = '';
		if(in_array('ALL', $countries)) {
			$countriesSql = '';
		} else {
			$countriesSql = '';
			foreach($countries as $country) {
				$countriesSql .= "'" . strtoupper($country) . "',";
			}
			$countriesSql = substr($countriesSql, 0, -1);
			
			$countriesSql = " AND country IN (" . $countriesSql . ")";
		}
		
		$accountsSql = '';
		if(in_array('ALL', $accounts)) {
			$accountsSql = '';
			
			if($auth['userlevel'] == 3) {
				$accountsSql = ' AND ( ';
				$accountsSql .= " accountName LIKE '%" . $auth['name'] . "%') ";
			}
			
		} else {
			$accountsSql = ' AND ( ';
			foreach($accounts as $account) {
				if ($account !== end($accounts)) {
					$accountsSql .= " accountName LIKE '%" . $account . "%' OR ";
				} else {
					$accountsSql .= " accountName LIKE '%" . $account . "%') ";
				}
			}
		}
		
		$aggregatorsSql = '';
		if(in_array('ALL', $aggregators)) {
			$aggregatorsSql = '';
			
			if($auth['userlevel'] == 3) {
				$aggregatorsPerUser = explode(',', $auth['aggregators']);
				foreach($aggregatorsPerUser as $aggregator) {
					$aggregatorsSql .= "'" . $aggregator . "',";
				}
				$aggregatorsSql = substr($aggregatorsSql, 0, -1);
				
				$aggregatorsSql = " AND aggregatorId IN (" . $aggregatorsSql . ")";
			}
			
		} else {
			$aggregatorsSql = '';
			foreach($aggregators as $aggregator) {
				$aggregatorsSql .= "'" . $aggregator . "',";
			}
			$aggregatorsSql = substr($aggregatorsSql, 0, -1);
			
			$aggregatorsSql = " AND aggregatorId IN (" . $aggregatorsSql . ")";
		}
		
		//$tableHeads = explode(',', $data_array['checkBox']);
		
		$tableHead = 'Agregator|Country|Account|Data';
								
		sort($checkBoxIds);
		foreach($checkBoxIds as $checkbox) {
				switch($checkbox){
					case 1:
						$tableHead .= '|Mobistein Conversions';
						break;
					case 2:
						$tableHead .= '|Client Conversions';
						break;
					case 3:
						$tableHead .= '|Dif. Conversions';
						break;
					case 4:
						$tableHead .= '|Dif. Conversions %';
						break;
					case 5:
						$tableHead .= '|Duplicated';
						break;
					case 6:
						$tableHead .= '|Dif. duplicated %';
						break;
					case 7:
						$tableHead .= '|Mobistein Revenue';
						break;
					case 8:
						$tableHead .= '|Client Revenue';
						break;
					case 9:
						$tableHead .= '|Dif. Revenue';
						break;
					case 10:
						$tableHead .= '|Dif. Revenue %';
						break;
					case 12:
						$tableHead .= '|Invoiced';
						break;
					case 11:
						$tableHead .= '|Total amount Invoiced';
						break;
				}
				
			}	
		
		$tableHead .= '|Comments
';
						
		$repor = new FReporting();
		$result = $repor->get_info($data_array, $accountsSql, $countriesSql,$groupbySql, $aggregatorsSql, $sdate, $edate);
		
		$tbody = '';
		foreach($result as $row) {
			$tbody .= "$row[aggregator]|$row[country]|$row[accountName]|$row[serviceDate]";
			
			//colunas escolhidas
			foreach($checkBoxIds as $checkbox) {
				switch($checkbox){
					case 1:
						$mc = number_format($row['mobisteinConversions'], 0, '.', ' ');
						$tbody .= "|$mc";
						break;
					case 2:
						$cc = number_format($row['clientConversions'], 0, '.', ' ');
						$tbody .= "|$cc";
						break;
					case 3:
						$difc = number_format($row['difLeads'], 0, '.', ' ');
						$tbody .= "|$difc";
						break;
					case 4:
						$difcp = number_format(($row['difPercentLeads'] * 100), 2, '.', '');
						$tbody .= "|$difcp%";
						break;
					case 5:
						$dup = number_format($row['duplicateds'], 0, '.', ' ');
						$tbody .= "|$dup";
						break;
					case 6:
						$difdup = number_format(($row['difduplicateds'] * 100), 2, '.', '');
						$tbody .= "|$difdup%";
						break;
					case 7:
						$mobrev = number_format($row['mobisteinRevenue'], 2, '.', ' ');
						$tbody .= "|$mobrev$";
						break;
					case 8:
						$clirev = number_format($row['clientRevenue'], 2, '.', ' ');
						$tbody .= "|$clirev$";
						break;
					case 9:
						$difrev = number_format($row['difRevenue'], 2, '.', ' ');
						$tbody .= "|$difrev$";
						break;
					case 10:
						$difprev = number_format($row['difPercentRevenue'], 2, '.', '');
						$tbody .= "|$difprev%";
						break;
					case 11:
						$totalAmount = number_format($row['totalAmountInvoiced'], 2, '.', ' ');
						$tbody .= "|$totalAmount$";
						break;
					case 12:
						$tbody .= "|$row[invoicedState]";
						break;
				}
				
			}			
		
			$tbody .= "|$row[comments]\n";
		}
		
		$tableHead .= $tbody;
		
		echo "sep=|\n" . $tableHead;
	}
	
	public function getChartAction() {
		$data_array = array(
			'aggreg' => $this->request->getPost('aggreg'),
			'typeInfo' => $this->request->getPost('typeInfo')
		);
		
		$repor = new FReporting();
		$result = $repor->get_infoByType($data_array);
		
		$colors = array('#D70C00','#EE8B00','#FFD605','#466028','#00F391','#0080E2','#7E00F3','#FF0BFF','#990008','#A0A0A0','#458D00','#00FF00','#008288','#5B5B5B','#C600CC','#660002','#CABFAB','#FF0000','#000000','#33FF9D','#0000FF','#D89EFF','#FF6887','#007CB9','#13FF00','#FFC12D','#700961','#39627F','#D65F5F','#2EAC6D');
		
		$final_info = array();
		$service_date = array();
		foreach($result as $agregator) {		
			//dates
			array_push($service_date,$agregator['serviceDate']);
			$service_date = array_unique($service_date);
			sort($service_date);
		}
		
		if(count($service_date) != 0) {
			$final_info = array("serviceDate" => $service_date);
		}
		
	
		foreach($result as $agregator) {		
			
			$dpl = number_format($agregator['difPercentLeads'], 2, '.', '');
			$tai = number_format($agregator['totalAmountInvoiced'], 2, '.', '');
		
			$k = array_search($agregator['serviceDate'], $service_date); 
			
			
			if(array_key_exists($agregator['aggregatorId'], $final_info)) {
				$final_info[$agregator['aggregatorId']]['difLeads'][$k] = $dpl;
				
				$final_info[$agregator['aggregatorId']]['totalAmount'][$k] = $tai;
				
			} else {
				//difLeads
				$difLeadsArray = array_fill(0, count($service_date), 0);
				$difLeadsArray[$k] = $dpl;
				
				//totalAmount
				$totalAmountArray = array_fill(0, count($service_date), 0);
				$totalAmountArray[$k] = $tai;
				
				$final_info[$agregator['aggregatorId']] = 
					array(
						'agregatorid' => $agregator['aggregatorId'],
						'totalAmount' => $totalAmountArray,
						'difLeads' => $difLeadsArray,
						'color' => ''
					);
			}
			
		}
		

		shuffle($colors);
		foreach($final_info as $key => $value) {		
			
			if($key != 'serviceDate')
				$final_info[$key]['color'] = end($colors);
			
			array_pop($colors);
	
		}
	
		echo json_encode($final_info);
	
	}
}

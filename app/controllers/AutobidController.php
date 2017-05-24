<?php

class AutobidController extends ControllerBase {

	public function initialize()
    {
        $this->tag->setTitle('AutoBid');
        parent::initialize();
    }
	
    public function indexAction() {
        try {
			$tableCampaigns = $this->getTable();
			$this->view->setVar("tableCampaigns", $tableCampaigns);
			
			$selectAccounts = $this->getAccounts();
			$this->view->setVar("selectAccounts", $selectAccounts);
			
			$usersAutobid = $this->getUsers();
			$this->view->setVar("usersAutobid", $usersAutobid);
			
        } catch (Exception $e) {
            
        }
    }
	
	public function resetpassAction() {
		$data = array(
			'username' => trim($this->request->getPost('account')),
			'password' => trim($this->request->getPost('password'))
		);
		
		$autobid = new Autobid();
		$result = $autobid->resetPassAccount($data);
	}
	
	public function addAccountAction() {
		$data = array(
			'username' => trim($this->request->getPost('email')),
			'password' => trim($this->request->getPost('password')),
			'users' => trim($this->request->getPost('users'))
		);
		
		$autobid = new Autobid();
		$result = $autobid->insertAccount($data);
			
		$selectAccounts = $this->getAccounts();
		
		echo $selectAccounts;
	}
	
	public function downloadReportAction() {
		$data = array(
			'datepicker' => $this->request->get('datepicker'),
			'account' => $this->request->get('account')
		);
		
		$autobid = new Autobid();
		$result = $autobid->getReport($data);
		
		
		$columns = array("username", "accountId",  "campaignId",  "campaignName",  "adRate", "maxAdRate", "limitBid",  "oldBid","newBid", "proccess", "timeStamp");
		$content = array();
		foreach($result as $element) {
			$array_object = array();
			
			array_push( $array_object , $element['username'], $element['accountid'], $element['campaignId'], $element['campaignName'], $element['adRate'], $element['maxadrate'], $element['maxbid'], $element['oldbid'],$element['newbid'],  $element['proccess'], $element['timeStamp']);
			
			array_push($content, $array_object);
		}
	
		$this->sendExcel($content, $columns);
	}
	
	private function sendExcel($data, $columns) {
		$title = "AutoBid Report";
		ini_set("memory_limit", "2048M");
	
		
        try {
            $temp = tmpfile();
            $exColumns = "sep=;\n";
            for ($i = 0; $i < sizeof($columns); $i++) {
                $exColumns .= $columns[$i] . ';';
            }
            fwrite($temp, $exColumns . "\n");


            for ($j = 0; $j < sizeof($data); $j++) {
                $resultRow = '';
                foreach ($data[$j] as $k => $v) {
                    $resultRow .= $data[$j][$k] . ";";
                }

                fwrite($temp, $resultRow . "\n");
            }

            fseek($temp, 0);
            while (($buffer = fgets($temp, 4096)) !== false) {
                echo $buffer;
            }
            fclose($temp);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            $myContentDispositionHeader = 'Content-Disposition: attachment;filename="' . $title . '.csv"';
            header($myContentDispositionHeader);
            header('Expires: 0');
            header('Cache-Control: max-age=0');
            header('Pragma: public');
            exit();

        } catch (Exception $e) {
            Tracer::writeTo('E', $e->getMessage(), 'e', $e->getLine(), __METHOD__, __CLASS__);
            $this->generateErrorResponse();
        }
    }
	
	public function getUsers() {
		$autobid = new Autobid();
		$result = $autobid->getUsers();
		
		$usersSelect = "";
		foreach($result as $user) {
			$usersSelect .=  "<option value='$user[id]'>$user[username]</option>";
        }
		
		return $usersSelect;
	}
	
	public function getAccounts() {
		$auth = $this->session->get('auth');
		
		$autobid = new Autobid();
		$result = $autobid->getAccounts();
		
		$accountsSelect = "";
		foreach($result as $account) {
			
			$users = explode(",", $account['users']);
			
			if( in_array($auth['id'], $users) )
				$accountsSelect .=  "<option value='$account[id]'>$account[username]</option>";
        }
		
		return $accountsSelect;
	}
	
	public function addcampaignAction() {
		$data = array(
			'maxbid' => str_replace(',', '.', $this->request->getPost('maxbid')),
			'campaignId' => trim($this->request->getPost('campaignid')),
			'account' => $this->request->getPost('selectboxAccounts'),
			'upOnly' => $this->request->getPost('uponly')
		);

		$autobid = new Autobid();
		$result = $autobid->addCamp($data);
		
		if($data['campaignId'] != '') {
			$url = 'http://35.157.9.102:8000/trafficfactoryCampaignId.py?idCampaign=' . $data['campaignId'] . '&idAccount=' . $data['account'];
			
			file_get_contents($url);
		}
		
		echo $this->getTable();
		
	}
	
	public function editcampaignAction() {
		$data = array(
			'idtable' => $this->request->getPost('idcampaign'),
			'maxbid' => $this->request->getPost('maxbid'),
			'upOnly' => $this->request->getPost('uponly')
		);
		
		$autobid = new Autobid();
		$result = $autobid->editCamp($data);
		
		
		echo $this->getTable();
	}
	
	public function deletecampaignAction() {
		$data = array(
			'idtable' => $this->request->getPost('idcampaign')
			//'maxbid' => $this->request->getPost('maxbid'),
			//'campaignid' => $this->request->getPost('campaignid')
		);
		
		$autobid = new Autobid();
		$result = $autobid->deleteCamp($data);
		
		
		echo $this->getTable();
	}

	private function getTable() {
		

		$auth = $this->session->get('auth');
		
		$autobid = new Autobid();
		
		$result = $autobid->getCamp();
		
		$result2 = $autobid->getAccounts();	
		$accounts = array();
		foreach($result2 as $account) {
			$users = explode(",", $account['users']);
			
			if( in_array($auth['id'], $users) ) {
				array_push($accounts, $account['username']);
			}
		}
		
		$campTable = '';
		$trRowUsers = 0;
		foreach ($result as $campaign) {
			
			$lastedit = '';
			if($campaign['timeStamp'] != '') {
				$lastedit = $campaign['timeStamp'];
			}

			if($campaign['campaignName'] == NULL || $campaign['campaignName'] == '')
				$campaignName = 'Pending';
			else if($campaign['campaignName'] == 'NF')
				$campaignName = 'Campaign name not found';
			else 
				$campaignName = $campaign['campaignName'];
			
			$adRate = "";
			$maxRate = "";
			if($campaign['proccess'] == 1) {
				$adRate = "-";
				$maxRate = "-";
				$maxbid = "-";
				$diffbid = "-";
			} else if($campaign['proccess'] == 0){
				$adRate = $campaign['adRate'];
				$maxRate = $campaign['maxadrate'];
				
				$diffbid = ($adRate != 0) ? number_format(((($maxRate / $adRate) * $campaign['newbid']) - $campaign['newbid']), 4, '.', ',') : '-';
				
				$maxbid = ($adRate != 0) ? number_format((($campaign['newbid'] * $maxRate) / $adRate), 4, '.', ',') : '-';
			}
			
			$status = '';
			if($campaign['status'] == 'True') {
				//green 
				$status = '<img class="imagestatus" src="/img/statusOK.png"><span style="display:none">2</span>';
			} else if($campaign['status'] == 'False'){
				//orange
				$status = '<img class="imagestatus" src="/img/statusnok.png"><span style="display:none">1</span>';
			} else {
				$status = '-<span style="display:none">0</span>';
			}
			
			$newbid = '';
			$limitbid = '';
			if($campaign['status'] == 'False' && $campaign['maxbid'] == $campaign['newbid']) {
				$newbid = "<td style='color: red; font-weight: 900'>$campaign[newbid]</td>";
				$limitbid = "<td style='color: red; font-weight: 900' id='maxbid'>$campaign[maxbid]</td>";
			} else {
				$newbid = "<td>$campaign[newbid]</td>";
				
				if($campaign['maxbid'] !== '') {
					$limitbid = "<td id='maxbid'>$campaign[maxbid]</td>";
				} else {
					$limitbid = "<td id='maxbid'>-</td>";
				}
			}
			
			$running = '';
			if($campaign['status'] == 'Paused') {
				$running = '<img class="imagestatus2" src="/img/runningPause.png"><span style="display:none">0</span>';
			} else {
				$running = '<img class="imagestatus2" src="/img/runningPlay.png"><span style="display:none">1</span>';
			}
			
			$onlyUp = '';
			if($campaign['upOnly'] == 0) {
				$onlyUp = '<img class="imagestatus3" src="/img/upDown.png"><span style="display:none">0</span>';
			} else {
				$onlyUp = '<img class="imagestatus3" src="/img/upUp.png"><span style="display:none">1</span>';
			}
					
			if( in_array($campaign['username'], $accounts) ) {
				$campTable .= "<tr uponly=$campaign[upOnly] sourceid=$campaign[idSource] idcam=$campaign[id] trNumber = $trRowUsers>
						<td id='campaignid'>$campaign[campaignid]</td>
						<td id='campaignName'>$campaignName</td>
						$newbid
						<td>$maxbid</td>
						$limitbid
						<td>$diffbid</td>
						<td>$adRate</td>
						<td>$maxRate</td>
						<td>$onlyUp</td>
						<td>$status</td>
						<td>$running</td>
						<td>$lastedit</td>
						<td>$campaign[username]</td>
						<td class='iconEdit'>
							<img class='modalIcon' src='/img/details.png' data-toggle='modal' data-target='#modalCreateCampaign' />
						</td>
					</tr>";
							
				$trRowUsers++;
			}
		}
		
		
		return $campTable;
	}
	
	public function refreshTableAction() { 
		echo $this->getTable();
	}
}

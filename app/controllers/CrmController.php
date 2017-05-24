<?php

class CrmController extends ControllerBase {

	public function initialize()
    {
        $this->tag->setTitle('CRM Manager');
        parent::initialize();
    }
	
    public function indexAction() {
		
		$auth = $this->session->get('auth');
		
		$agre = new CRM();
		$result = $agre->get_agregators($auth);
		
		$agregatorsSelect = "";
		foreach($result as $agregator) {
			$name = ' ';
			if($agregator['agregator'] != '') {	
				$name = $agregator['agregator'];
			}
			
            $agregatorsSelect .=  "<option value='$agregator[id]'>$name</option>";
        }
		
		$this->view->setVar("agregatorsSelect", $agregatorsSelect);
		
		
		
		$this->view->setVar("clientsSelect", $this->getClients());
		
    }
	
	public function getClientAction() {
		$id = $this->request->getPost('idAgg');
		
		$agre = new CRM();
		$result = $agre->get_client($id);
		
		array_push($result, $id);
		echo json_encode($result);
    }
	
	public function createClientAction() {
		$array_data = array(
			'accountmanager' => $this->request->getPost('amanagername'),
			'aggrid' => $this->request->getPost('selectboxClientsIds'),
			'aggrskype' => $this->request->getPost('aggrskype'),
			'workemail' => $this->request->getPost('workemail'),
			'askfornumbers' => $this->request->getPost('selectboxClientsAskfornumbers'),
			'clientstate' => $this->request->getPost('selectboxClientsState'),
			'agglang' => $this->request->getPost('agglanguage'),
			'aggstatus' => $this->request->getPost('aggstatus'),
			'aggnotes' => $this->request->getPost('aggnotes'),
			'aggname' => $this->request->getPost('aggname')
		);
		
		$agre = new CRM();
		$agre->create_client($array_data);
    }
	
	public function editClientAction() {
		$array_data = array(
			'accountmanager' => $this->request->getPost('amanagername'),
			'aggrid' => $this->request->getPost('selectboxClientsIds'),
			'aggrskype' => $this->request->getPost('aggrskype'),
			'workemail' => $this->request->getPost('workemail'),
			'askfornumbers' => $this->request->getPost('selectboxClientsAskfornumbers'),
			'clientstate' => $this->request->getPost('selectboxClientsState'),
			'agglang' => $this->request->getPost('agglanguage'),
			'aggstatus' => $this->request->getPost('aggstatus'),
			'aggnotes' => $this->request->getPost('aggnotes'),
			'aggname' => $this->request->getPost('aggname'),
			'idforedit' => $this->request->getPost('idTable')
		);
		
		$agre = new CRM();
		$agre->edit_client($array_data);
    }
	
	public function getClientsAction() {
		echo $this->getClients();
    }
	
	public function getClients() {
		$auth = $this->session->get('auth');
				
		$agre = new CRM();
		$result = $agre->get_agregators($auth);
		
		$result = $agre->get_clients($auth);
		
		$clientsSelect = "";
		foreach($result as $client) {
			$name = ' ';
			if($client['aggregatorName'] != '') {	
				$name = $client['aggregatorName'];
			}
			
            $clientsSelect .=  "<option value='$client[idAgregator]'>$client[idAgregator] - $name</option>";
        }
		
		return $clientsSelect;
	}
	
	public function last7DaysAction() {
		
        $auth = $this->session->get('auth');
		$idAgg = $this->request->get('id');
		
        $dash = new CRM();
		
		$countries = '';
		$sources = '';
		$aggregators = '';
		$arrayData = array();
		$chartinfo = '';
		$tableinfo = '';
		$array_final = array();
		
		if($auth['userlevel']== 1 && $auth['utype'] == 0 || $auth['userlevel'] == 4){
    
			//pode ver tudo
		    $type = 1;
			
			$result = $dash->get_last7Days($type, $arrayData, $auth, $idAgg);
			$chartinfo = $result;
			
			$result2 = $dash->percentShift($type, $arrayData, $auth, $idAgg);
			$tableinfo = $result2;
			
			array_push($array_final, $chartinfo, $tableinfo);
		
        } else if($auth['userlevel']== 3 && $auth['utype'] == 0 ){ 
			$type = 5;
			//bloqueado por agregator (cms)
			
		    ($auth['aggregators'] != "") ? ($aggregators = $auth['aggregators']) : '';
			array_push($arrayData, array('aggregators' => $aggregators ));
			
			$result = $dash->get_last7Days($type, $arrayData, $auth, $idAgg);
			$chartinfo = $result;
			
			$result2 = $dash->percentShift($type, $arrayData, $auth, $idAgg);
			$tableinfo = $result2;
			
			array_push($array_final, $chartinfo, $tableinfo);
        } 
		
		echo json_encode($array_final);
		
		//echo $tableinfo;
		//echo $chartinfo;
		
		//affiliate = 0 -> adulto, 1 -> afiliado . 2 -> mainstream, 3 -> afiliados antigo que so correm no mobistein
	}
}

<?php

use Phalcon\Mvc\Url;


class ClientController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Customer Realtionship Management');
		date_default_timezone_set('Europe/Lisbon');
        parent::initialize();
    }

    public function indexAction() {
        $client = new Client();
        $client_name_id = $client->getClientNameId();
		//get all countries
		$geo=new Countries();
        $client_geo = $geo->getAllCountries();
        $user = new Users();
        $user_am = $user->getUserAm();
        $this->view->setVar('client', $client_name_id);
        $this->view->setVar('geo', $client_geo);
        $this->view->setVar('am', $user_am);
    	
    }


	public function hideClientAction(){
		$this->view->disable();
		if ($this->request->isPost() )
		{	
			$id=$this->request->getPost('id');
			$client=new Client();
			$updateValue=$client->hideClient($id);
			if($updateValue)
			{
				echo 'updated';
				
			}	
		}
	}
	
	
	public function searchDataAction() {
        $this->view->disable();
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            $status = $this->request->getPost('status');
            $geo = $this->request->getPost('geo');
            $io = $this->request->getPost('io');
            $am = $this->request->getPost('am');
            $id = $this->request->getPost('id');
            $content=$this->request->getPost('content');
			//for dataTables
			$searchvalue='';
			$order_column='';
			$order_dir='';
			$start='';
			$length='';
		
			if(isset($_POST['search']['value']))
			{
				$search_value=$_POST['search']['value'];
			}
			if(isset($_POST['order']))
			{
				$order_column=$_POST['order']['0']['column'];
				$order_dir=$_POST['order']['0']['dir'];
			}
			if($_POST['length']!=-1)
			{
				$length=$_POST['length'];
				$start=$_POST['start'];
			}

            try {
                $data = new Client();
                $get_data = $data->searchData($type, $status, $geo, $io, $am, $id,$content,$search_value,$order_column,$order_dir,$length,$start);
				
				$data1=array();
				if(isset($get_data) && isset($get_data[0])){
					print_r($get_data);
					foreach ($get_data as  $row) {
						$value1='';
						$sub_array=array();
						$sub_array[]=$row['client'];
						$sub_array[]=$row['id'];  
						$id=$row['id'];
						$sub_array[]=$row['type'];
						$sub_array[]=$row['status']; 
						$sub_array[]=$row['accountName'];
						if($row['private_profile']==1){
							$value1='Check AM';
						}else{
							$value1=$row['email']; 
						}
						$sub_array[]=$value1; 
				
						if($row['private_profile']==1){
							$value1='Check AM';
						}else{
							$value1=$row['skype']; 
						}	
						$sub_array[]=$value1;
						$sub_array[]=$row['geo'];  
						if($row['io']!=NULL || $row['io']!='' )
						{
							$value='Y';
						}
						else
						{
							$value='N';
						} 	
				
						$sub_array[]=$value; 
					
						$sub_array[]=$row['am']; 
						//$sub_array[]="<a href='http://mobisteinlp.com/adminproject/client/updateClient?id=$id '><button class='glyphicon glyphicon-pencil'></button></a><button class='glyphicon glyphicon-remove-circle hideRow' id=$id ></button>";
						
							
						$sub_array[]="<a href='/client/updateClient?id=$id '><button id='newC'><img id='add' src='/images/edit.svg'></button></a><button id='newC'><img id=$id class='hideRow' src='/images/close_red.svg'></button>";		
						
						$data1[]=$sub_array;
				
					}
				
				}
			
				$result=$data->get_searchall_data_row();
				$result=$result[0]['numrow'];
				/*
				foreach ($result as $value) {
					$result= $value['numrow'];
				}*/
				$result1=$data->get_searchallfiltered_data_row();
				$result1=$result1[0]['numrow'];
				/*
				foreach ($result1 as $value) {
					$result1= $value['numrow'];
				}*/
				$output=array(
					"draw" =>intval($_POST['draw']),
					"recordsTotal" =>$result,
					"recordsFiltered" =>$result1,
					"data" =>$data1
				);
				echo json_encode($output);
            } catch (Exception $e) {
                
            }
        }
    }
	
	public function getHistoryInfoAction(){
		
		$this->view->disable();
		$chat=new Chat();
		$getData=$chat->getHistoryInfo();
		echo json_encode($getData);	
	}
	
	public function getIssueInfoAction(){
		
		$this->view->disable();
		$chat=new Chat();
		$getData=$chat->getIssueInfo();
		echo json_encode($getData);		
	}
	
	
    public function createClientAction() {
        $data = new Countries();
        $get_data = $data->getAllCountries();
        $user = new Users();
        $user_am = $user->getUserAm();
        $this->view->setVar('am', $user_am);
        $this->view->setVar('geo', $get_data);
		$this->view->pick('client/createClient1');
    }
	
	
	public function chatSaveAction(){
		$this->view->disable();
		//echo 'I am from send';
		if($this->request->isPost())
		{
			$type_chat=$this->request->getPost('type_chat');
			$text=$this->request->getPost('text');
			$uid=$this->request->getPost('uid');
			$chat=new Chat();
			$getData=$chat->chatSave($text,$uid,$type_chat);
			echo json_encode($getData);
			
		}
	}
	
	//avoid this function later this is just to test
	
	
	public function getGraphDataAction(){
		$this->view->disable();
		if($this->request->isPost())
		{
			$id=$this->request->getPost('id');
		
		
		}
	}
	

    public function saveClientAction() {
        $this->view->disable();
        $io = '';
		$type1='';
		$content1='';
        if ($this->request->isPost()) {
            //"file"=>$this->request->getPost('io'),
			$type=$this->request->getPost('type');
			
			if(!empty($type))
			{
				$type1=implode(',',$type);
				//$type1 = implode(',',array_unique(explode(',', $type1))); //to remove the duplicate, if user press all and select other options
			}
			
			//remove duplicate
			$type1 = implode(',',array_unique(explode(',', $type1)));
			
			$content=$this->request->getPost('content');
			
			if(!empty($content)){
				$content1=implode(',',$content);
			}
			$content1 = implode(',',array_unique(explode(',', $content1)));
            $clientData = array(
                "username" => $this->request->getPost('username'),
                "private_profile" => $this->request->getPost('private_profile'),
                "am" => $this->request->getPost('am'),
                "status" => $this->request->getPost('status'),
                "type" => $type1,
                "other_companies_name" => $this->request->getPost('other_companies_name'),
                "domain_product" => $this->request->getPost('domain_product'),
                "accountName" => $this->request->getPost('accountName'),
                "email" => $this->request->getPost('email'),
                "skype" => $this->request->getPost('skype'),
                "office_location" => $this->request->getPost('office_location'),
                "content" => $content1,
                "confirm_nos" => $this->request->getPost('confirm_nos'),
                "platform" => $this->request->getPost('platform'),
                "password" => $this->request->getPost('password'),
                "financial_contacts" => $this->request->getPost('financial_contacts'),
                "other_emails" => $this->request->getPost('other_emails'),
                "other_skypes" => $this->request->getPost('other_skypes'),
                "company_name" => $this->request->getPost('company_name'));
            $geo = $this->request->getPost('geo');
			
            $company_name = $this->request->getPost('company_name');
			if(!empty($company_name)){
				if ($this->request->hasFiles() == true) {
					$uploads = $this->request->getUploadedFiles();
					$isUploaded = false;
					#do a loop to handle each file individually
					foreach ($uploads as $upload) {
                    //check security for files
						if ($upload->getSize() > 1048576) {
							return false;
						}
						else {
							$ext = $upload->getName(); 
							$ext=pathinfo($ext,PATHINFO_EXTENSION);
							//allow only the file of these type
							$white_list_ext = array("jpeg","docx", "pdf", "jpg");
							if (in_array($ext, $white_list_ext))
							{
								$baseLocation = '../files/io/' . $company_name;
								if (!is_dir($baseLocation)) {
									mkdir($baseLocation, 0777, true);
								}
								$dir = $baseLocation . "/" . date('d.m.y') .time(). '_' . str_replace(' ', '_', $upload->getName());
								$io = $company_name . "/" . date('d.m.y') .time(). '_' . str_replace(' ', '_', $upload->getName());
								$upload->moveTo($dir);
							}
							else{	
								
									// return error on file format message
							}
						}
					}
				}

				$clientData['io'] = $io;
				$data = new Client();
				$response = $data->saveClient($clientData, $geo);
				if ($response == true) {
					echo 'success';
					//echo 'CLIENT SAVE SUCCESSFULLY';
					//$this->flash->success("Client created successfully!");
				} else {
					echo 'error';
					//echo 'Error!';
					//$this->flash->error("Error in saving client!");
				}
			}	
			else{
				
				return false;
			}
		}
    }

    public function updateClientAction() {
		if($this->request->get())
		{
			
       // $id = 754; //use another method to get the id of the user to be edited
		$id=$this->request->get('id');
        $data = new Countries();
        $get_data = $data->getAllCountries();
        $client = new Client();
        $get_client_info = $client->getClientDetailById($id);
        $get_geo_id = $client->get_client_geo($id);
		$user = new Users();
        $user_am = $user->getUserAm();
        $this->view->setVar('am', $user_am);
        $this->view->setVar('clientgeo', $get_geo_id);
        $this->view->setVar('geo', $get_data);
        $this->view->setVar('client', $get_client_info);
		$this->view->pick('client/createClient1');
		}

        //get the geo of the agregators
    }

	public function getClientUpdatedAction() {
        $this->view->disable();
		$io = '';
		$type1='';
		$content1='';
        if ($this->request->isPost()) {
            //"file"=>$this->request->getPost('io'),
			$type=$this->request->getPost('type');
			if(!empty($type))
			{
				$type1=implode(',',$type);
				//$type1 = implode(',',array_unique(explode(',', $type1))); //to remove the duplicate, if user press all and select other options
			}
			
			//remove duplicate
			$type1 = implode(',',array_unique(explode(',', $type1)));
			
			$content=$this->request->getPost('content');
			if(!empty($content)){
				$content1=implode(',',$content);
			}
			$content1 = implode(',',array_unique(explode(',', $content1)));
            $clientData = array(
                "username" => $this->request->getPost('username'),
                "private_profile" => $this->request->getPost('private_profile'),
                "am" => $this->request->getPost('am'),
                "status" => $this->request->getPost('status'),
                "type" => $type1,
                "other_companies_name" => $this->request->getPost('other_companies_name'),
                "domain_product" => $this->request->getPost('domain_product'),
                "accountName" => $this->request->getPost('accountName'),
                "email" => $this->request->getPost('email'),
                "skype" => $this->request->getPost('skype'),
                "office_location" => $this->request->getPost('office_location'),
                "content" => $content1,
                "confirm_nos" => $this->request->getPost('confirm_nos'),
                "platform" => $this->request->getPost('platform'),
                "password" => $this->request->getPost('password'),
                "financial_contacts" => $this->request->getPost('financial_contacts'),
                "other_emails" => $this->request->getPost('other_emails'),
                "other_skypes" => $this->request->getPost('other_skypes'),
				"ag_id"=>$this->request->getPost('ag_id'),
                "company_name" => $this->request->getPost('company_name'));
            $geo = $this->request->getPost('geo');
            $company_name = $this->request->getPost('company_name');
			if(!empty($company_name)){
				if ($this->request->hasFiles() == true) {
					$uploads = $this->request->getUploadedFiles();
					$isUploaded = false;
					#do a loop to handle each file individually
					foreach ($uploads as $upload) {
                    //check security for files
						if ($upload->getSize() > 1048576) {
							return false;
						} else {
							$ext = $upload->getName(); 
							$ext=pathinfo($ext,PATHINFO_EXTENSION);
							//allow only the file of these type
							$white_list_ext = array("jpeg","docx", "pdf", "jpg");
							if (in_array($ext, $white_list_ext))
							{
								$baseLocation = '../files/io/' . $company_name;
								if (!is_dir($baseLocation)) {
									mkdir($baseLocation, 0777, true);
								}
								$dir = $baseLocation . "/" . date('d.m.y') .time(). '_' . str_replace(' ', '_', $upload->getName());
								$io = $company_name . "/" . date('d.m.y') .time(). '_' . str_replace(' ', '_', $upload->getName());
								$upload->moveTo($dir);
							}
							else{	
								
									// return error on file format message
							}
						}
					}
				}

				$clientData['io'] = $io;
				$data = new Client();
				$response = $data->getClientUpdated($clientData, $geo);
				if ($response == true) {
					echo 'success';
					//echo 'CLIENT UPDATED SUCCESSFULLY';
					
					//$this->flash->success("Client updated successfully!");
				} else {
					echo 'error';
					//$this->flash->error("Error updating client!");
				}
			}	
			else{
				
				return false;
			}
		}
		
    }	
	

	
	public function searchAllAction(){
		$this->view->disable();
		if ($this->request->isPost()){
			$searchvalue=$this->request->getPost('searchvalue');
			$search_value;
			$order_column='';
			$order_dir='';
			$start='';
			$length='';
		
			if(isset($_POST['search']['value']))
			{
				$search_value=$_POST['search']['value'];
			}
			if(isset($_POST['order']))
			{
				$order_column=$_POST['order']['0']['column'];
				$order_dir=$_POST['order']['0']['dir'];
			}
			if($_POST['length']!=-1)
			{
				$length=$_POST['length'];
				$start=$_POST['start'];
			}

            try {
                $data = new Client();
                $get_data = $data->searchAll($searchvalue,$search_value,$order_column,$order_dir,$length,$start);
				$data1=array();
				if(isset($get_data)){
					foreach ($get_data as  $row) {
						$value1='';
						$sub_array=array();
						$sub_array[]=$row['client'];
						$sub_array[]=$row['id'];  
						$id=$row['id'];
						$sub_array[]=$row['type'];
						$sub_array[]=$row['status']; 
						$sub_array[]=$row['accountName'];
						if($row['private_profile']==1){
							$value1='Check AM';
						}else{
							$value1=$row['email']; 
						}
						$sub_array[]=$value1; 
						//can be avoided this part
						if($row['private_profile']==1){
							$value1='Check AM';
						}else{
							$value1=$row['skype']; 
						}	
						$sub_array[]=$value1;
						$sub_array[]=$row['geo'];  
						if($row['io']!=NULL || $row['io']!='' )
						{
							$value='Y';
						}
						else
						{
							$value='N';
						} 	
				
						$sub_array[]=$value; 
					
						$sub_array[]=$row['am']; 
						//$sub_array[]="<a href='http://mobisteinlp.com/adminproject/client/updateClient?id=$id '><button class='glyphicon glyphicon-pencil'></button></a><button class='glyphicon glyphicon-remove-circle hideRow' id=$id ></button>";
						
							
						$sub_array[]="<a href='/client/updateClient?id=$id '><button id='newC'><img id='add' src='/images/edit.svg'></button></a><button id='newC'><img id=$id class='hideRow' src='/images/close_red.svg'></button>";		
						
						$data1[]=$sub_array;
				
					}
				
				}
			
				$result=$data->get_searchall_data_row();
				$result=$result[0]['numrow'];
				/*
				foreach ($result as $value) {
					$result= $value['numrow'];
				}*/
				$result1=$data->get_searchallfiltered_data_row();
				$result1=$result1[0]['numrow'];
				/*
				foreach ($result1 as $value) {
					$result1= $value['numrow'];
				}*/
				$output=array(
					"draw" =>intval($_POST['draw']),
					"recordsTotal" =>$result,
					"recordsFiltered" =>$result1,
					"data" =>$data1
				);
				echo json_encode($output);
            } catch (Exception $e) {
                
            }
			
			
		}
		
		
	}
}

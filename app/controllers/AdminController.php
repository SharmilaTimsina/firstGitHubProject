<?php
use Phalcon\Mvc\Url;

class AdminController extends ControllerBase
{ 
    public function initialize()
    {
        $this->tag->setTitle('Admin Task');
        parent::initialize();
    }
    public function indexAction()
    {
        //get the user level from session
       
		$session = $this->session->get('auth');
        $user_level=$session['userlevel'];
        if($user_level==2 ){
           //write code for managing countries page
            $this->manageCountriesAction();
            
        }
        elseif($user_level==3){
            //write code for managing agregators page
            $this->manageAgregatorsAction();
                  
           
        }
		elseif($user_level==1){
            //write code for managing agregators page
			if($this->request->get('tp')==1){
				$this->manageAgregatorsAction();
			}
			else{
				$this->manageCountriesAction();
			} 
        }
        else{
            //write some code for this
        }
       
    }
    
    private function manageAgregatorsAction(){
        
        //get information about users
        $user=$this->getUserDetailAction(3);
        $agregatorsUser=new Agregator();
        $getAggregatorUser=$agregatorsUser->getAggregatorUser();
        $this->view->setVar('user',$user);
        $this->view->setVar('agrusr',$getAggregatorUser);
		$this->view->pick('admin/manageAgregators');	
        
    }
    
    public function getAllAgregatorsUsersAction(){
        $this->view->disable();
        $agregatorsUser=new Agregator();
        $getAggregatorUser=$agregatorsUser->getAggregatorUser();
        echo json_encode($getAggregatorUser);

    }
	
	public function getAllCountriesUsersAction(){
        $this->view->disable();
        $countriesUser=new Countries();
        $getCountriesUsers=$countriesUser->getCountriesUsers();
        echo json_encode($getCountriesUsers);

    }
	
   //returns user details
    private function getUserDetailAction($level){
        $user=Users::find([
            "columns"=>"id,username",
             "conditions" => "userlevel=?1 AND username!=?2 ",
            "bind"=> [
            1 => $level,
            2=>'Ana2'
            ],
            "order" => "username",
        ]);
        return $user;
    }
    
   public function displayAgregatorsAction(){

        $this->view->disable();
    
        if($this->request->isPost()){
            $id=$this->request->getPost('id');
            try{
                $agr=new Agregator();
                $usragr=$agr->getUsrAgr($id);
                echo json_encode($usragr);
            }
            catch(Exception $e){
                echo "Message:" .$e->getMessage();
            }
       }
    
    }
     
	public function displayCountriesAction(){

        $this->view->disable();
    
        if($this->request->isPost()){
            $id=$this->request->getPost('id');
            try{
                $ct=new Countries();
                $ctusr=$ct->getUsrCt($id);
                echo json_encode($ctusr);
            }
            catch(Exception $e){
                echo "Message:" .$e->getMessage();
            }
       }
    
    }
	 
	 
	
    public function removeAgregatorsAction(){
		$this->view->disable();
        if($this->request->isPost()){
            $id=$this->request->getPost('id');
			$data1=$this->request->getPost('data');
           try{ 
                $user=Users::findFirst([
                "columns"=>"id,aggregators",
                "conditions" => "id = ?1 AND id!=25 AND userlevel=3",
                "bind"=> [1 => $id ],
                ]);
                 
                //create an array from the aggregators in the database of the selected user
				$ustr= $user->aggregators;
				$ustr=ltrim($ustr,',');
				$ustr=rtrim($ustr,',');
                $data2= explode(",",$ustr);  
                 
                $update= array_diff($data2,$data1);
				
				
                if($data2!=$update)
                {
                    
					$impstring = implode(",",$update);
                    $update_string=($impstring=='')?'':",".$impstring.",";
					
					$users = new Users();
					$users->update_agregators($update_string,$id);
                   
                   // $user->aggregators=$update_string;
				//	$user->countries = new \Phalcon\Db\RawValue();
					//$user->investacess=new \Phalcon\Db\RawValue();
						
                    
                        
                }

                $agr=new Agregator();
                $usragr=$agr->getUsrAgr($id);
                echo json_encode($usragr);
            

            }
            catch(Exception $e){
				echo 'error2';
                echo $e->getMessage();
            }
        }
		
	}
	
	 public function removeCountriesAction(){
		$this->view->disable();
        if($this->request->isPost()){
            $id=$this->request->getPost('id');
			//$data1=$this->request->getPost('data');
			$data1= array_map('strtoupper', $this->request->getPost('data'));
			
           try{ 
                $user=Users::find([
                "columns"=>"id,countries",
                "conditions" => "id = ?1",
                "bind"=> [1 => $id ],
                ]);
                 foreach($user as $su){
                //create an array from the aggregators in the database of the selected user
                    $data2= explode(",",$su->countries);  
                } 
				$data2=(array_map('strtoupper',$data2));
                $update= array_filter(array_diff($data2,$data1));
                if($data2!=$update)
                {
                    
                    $update_string=",".implode(",",$update).",";
                    $user = Users::findFirstById($id);
                    $user->countries=$update_string;
                    if ($user->save() == false) {
                        foreach ($user->getMessages() as $message) {
                            $this->flash->error((string) $message);
                        }
                    } 
                    
                        
                }

                $ct=new Countries();
                $ctusr=$ct->getUsrCt($id);
                echo json_encode($ctusr);
            

            }
            catch(Exception $e){
                echo $e->getMessage();
            }
        }
		
	}
	
	
	
    public function addAgregatorsAction()
    {
        $this->view->disable();
        if($this->request->isPost())
        {
            $array1=$this->request->getPost('agregators');
            $id=$this->request->getPost('id'); 
            try{
                //get the selected user aggregators from the database
                $selected_user=Users::find([
                    "column"=>"aggregators",
                    "conditions" => "id = ?1",
                    "bind"       => [1 => "$id"]
                ]);
                //$user=Users1::findFirstById($id);  //allows quickly perform a retrieval from a table     
                foreach($selected_user as $su){
                //create an array from the aggregators in the database of the selected user
                    $array2=  explode(",",$su->aggregators);  
                } 
                $data=array_filter(array_unique(array_merge($array1,$array2)) );
                $agregators_string=",".implode(",",$data).",";
                //delete the common aggregators form the other users aggregators

                $dataall=Users::find([
                    "columns"=>"aggregators,id"
                ]);
				
                foreach($dataall as $data)
                {
                    $array3=explode(",",$data->aggregators);
                    $update= array_diff($array3,$array1);
                    //to prevent from unnecessary update
                    
                    if($array3!==$update)
                    {
                        $update=array_filter($update);
                        $update_string=",".implode(",",$update). ",";
                        
						$quickupdate = new Users();
						//echo $update_string.' test<br>';
                        $quickupdate->update_agregators($update_string,$data->id);
            
                    }            
                }
				$user = Users::findFirstById($id);
                $user->aggregators=$agregators_string;
				$uupdate = new Users();
				$uupdate->update_agregators($agregators_string,$id);
            }
            catch(Exception $e){
                 echo 'Message: ' .$e->getMessage();
                
            }
        }
        
        $agregatorsUser=new Agregator();
        $getAggregatorUser=$agregatorsUser->getAggregatorUser();
        echo json_encode($getAggregatorUser);
    }
	
	 public function addCountriesAction()
    {
        $this->view->disable();
        if($this->request->isPost())
        {
			$array1= array_map('strtoupper', $this->request->getPost('countries'));
            $id=$this->request->getPost('id'); 
            try{
                //get the selected user aggregators from the database
                $selected_user=Users::find([
                    "column"=>"countries",
                    "conditions" => "id = ?1",
                    "bind"       => [1 => "$id"]
                ]);
                //$user=Users1::findFirstById($id);  //allows quickly perform a retrieval from a table     
                foreach($selected_user as $su){
                    $array2=  explode(",",$su->countries);  
                } 
				$array2=array_map('strtoupper', $array2);
                $data=array_filter(array_unique(array_merge($array1,$array2)) );
                $countries_string=",".implode(",",$data).",";         
                $user = Users::findFirstById($id);
                $user->countries=$countries_string;
				
                if ($user->save() == false) {
					
                    foreach ($user->getMessages() as $message) {
                        $this->flash->error((string) $message);
						
                    }
                }
              
               
            }
            catch(Exception $e){
                 echo 'Message: ' .$e->getMessage();
                
            }
        }
        
        $countriesUser=new Countries();
        $getCountriesUsers=$countriesUser->getCountriesUsers();
        echo json_encode($getCountriesUsers);
    }

	
	
    private function manageCountriesAction(){
		$user=$this->getUserDetailAction(2);
        $getCountries=new Countries();
        $getCountriesUsers=$getCountries->getCountriesUsers();
        $this->view->setVar('user',$user);
        $this->view->setVar('ctusr',$getCountriesUsers);
		$this->view->pick('admin/manageCountries');	
        
    }    
}

<?php

class SecurityController extends ControllerBase
{



    public function indexAction(){

    }
    private function getUsers(){

        //add managed users option
        $users = new Users();
        $users = $users->get_users();


    }

    public function index2Action()
    {
        
        $secur = new Security();
        $incidents = $secur->get_incidents();
        $incidentTable = '<table class="table table-hover">
                              <thead>
                                <tr>
                                  <th>Campaign</th>
                                  <th>Url</th>
                                  <th>RisqIq</th>
                                  <th>dbmal</th>
                                  <th>uimal</th>
                                  <th>phish</th>
                                  <th>Time</th>
                                  <th>Remove</th>
                                </tr>
                              </thead><tbody>';
  
        foreach($incidents as $incident){
            $incidentTable.=' <tr>
                                  <td>'.$incident['campaign'].'</td>
                                  <td>'.$incident['url'].'</td>
                                  <td><a href="'.$incident['risqiqurl'].'">RisqIQ Rep</a></td>
                                  <td>'.$incident['dbmal'].'</td>
                                  <td>'.$incident['uimal'].'</td>
                                  <td>'.$incident['phish'].'</td>
                                  <td>'.$incident['insertTimestamp'].'</td>
                                  <td><a href="http://mobisteinreport.com/security/deletei?iid='.$incident['id'].'" class="btn btn-warning btn-xs" role="button">Remove</a></td>      
                               </tr>';
        }
         $incidentTable.='</tbody></table>';
         $this->view->setVar("itable", $incidentTable);
    }
    public function deleteiAction(){
       
      if($this->request->get('iid')!=null and !empty($this->request->get('iid'))) {
        
          $secur = new Security();
          $secur->delete_urls($this->request->get('iid'));
        
          $this->dispatcher->forward(array(
            "controller" => "security",
            "action" => "index"
        ));
      }
       
    }
    
    private function check_list(){
        
        
        
    }
    
    private function gen_insert($res){
        $i_string = 'INSERT INTO UrlChecks (campaign,url,insertTimestamp) VALUES ';
        $insertTimestamp = date('Y-m-d H:i:s');
        foreach($res as $row){
            $i_string.='("'.$row['campaign'].'","'.$row['rurl'].'","'.$insertTimestamp.'"),';
        }
       // echo rtrim($i_string, ',');
        
        return rtrim($i_string, ',');
        
    }
    private function clean($string) {
        $string = str_replace(',', '-', $string); // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = preg_replace('/-+/', '-', $string);
        $string = '"'.str_replace('-', '","', $string).'"'; // Replaces all spaces with hyphens.
        return $string; // Replaces multiple hyphens with single one.
    }
  
}

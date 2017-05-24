<?php

/**
 * IspController
 *
 * Hit Insertion and presentation
 */
class IspController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Isp Manager');
        parent::initialize();
    }

    public function indexAction()
    {
        
      $groups = $this->get_groups();
      
      $this->view->setVar("group_list", $groups);
      
      
    }
    
    private function get_groups(){
        
        $ispGroup = Isp::find(array('columns' => 'gname','group'=>'gname'));
        $gcombo = '';
        $gnames = $ispGroup->toArray();
        foreach ($gnames as $gname){
            
           $gcombo.='<option val="'.$gname['gname'].'">'.$gname['gname'].'</option>';      
        }
        return $gcombo;
    }
    
     public function get_groupsAction(){
        
        $ispGroup = Isp::find(array('columns' => 'gname','group'=>'gname'));
        $gcombo = '';
        $gnames = $ispGroup->toArray();
        foreach ($gnames as $gname){
            
           $gcombo.='<option val="'.$gname['gname'].'">'.$gname['gname'].'</option>';      
        }
        echo $gcombo;
    }
    
    public function get_groupAction(){
        
        $gname    = $this->request->get('gn');
        $ispGroup = Isp::find(array('order'=>'isp'));
        $isps = ISP::find(array(
                "gname = :gname:",
                'bind' => array('gname' => $gname)
            ));
       $table = '<thead><th>Isp</th><th>Country</th><th>Action</th></thead>'; 
       foreach($isps->toArray() as $isp){
           
           
           $table.='<tr><td>'.$isp['isp'].'</td><td>'.$isp['country'].'</td><td><button id="' . $isp['id'] . '" type="button" class="btn btn-xs btn-warning cll delb" style="margin-top:10px">Delete&nbsp;</button></td></tr>';
           
       }
       $table .='<tr><td id="iline" contenteditable></td><td id="cline" contenteditable></td><td><button id="nline" type="button" class="btn btn-xs btn-primary cll" style="margin-top:10px">Save&nbsp;</button></td></tr>';
       echo $table;
    }
    
    public function create_groupAction(){
        
        $gname   = $this->request->get('gn');
        $is    = $this->request->get('is');
        $country = $this->request->get('co');
        
        $isp = new Isp();
        $isp->gname   = $gname;
        $isp->isp     = $is;
        $isp->country = $country;
        $isp->insertTimestamp = date('Y-m-d H:i:s');
        if($isp->save()!= FALSE)
            echo $gname;
        else 
            echo 0;
    }
    
    public function delete_ispAction(){
        
        $ispid = $this->request->getPost('iid');
        
        if($ispid!=null){
        $isp = Isp::findFirst($ispid);
        
        if($isp!=false){
          
             if ($isp->delete() == false) {
                echo 0;
             }else{
                echo 1;
             }
         }else{
            
            echo 0;
         }
        
        }else
            echo 0;
        
    }
    
   
    
  

   
}

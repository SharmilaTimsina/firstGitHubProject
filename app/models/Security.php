<?php

use Phalcon\Mvc\Model;

class Security extends Model
{
  public function initialize(){
            //$this->setSource("I__InvestMetadata");
        }

  
  public function get_incidents(){
      
     // $this->getDi()->getDb()->query('TRUNCATE TABLE UrlChecks');
      
      $statement = $this->getDi()->getDb()->prepare( 'SELECT * FROM RisqiqIncident WHERE active=1' );
      $res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
      return $res->fetchAll(PDO::FETCH_ASSOC);
      
  }
  

 
  public function delete_urls($id){
      //echo $sql;
     $statement = $this->getDi()->getDb()->prepare('UPDATE RisqiqIncident SET active=0 WHERE id=:id');
     $res       = $this->getDi()->getDb()->executePrepared($statement, array('id'=>$id),array('id'=>\Phalcon\Db\Column::TYPE_INTEGER));
      
  }    
  
  
}

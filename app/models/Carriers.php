<?php

use Phalcon\Mvc\Model;

class Carriers extends Model

{
    public function initialize() 
    {
        $this->setSource("dim__carrier");
    }
	//this function is use for sales stat
	public function getAllCarriers($country){
		try {
            $sql = 'SELECT distinct name FROM dim__carrier as d WHERE country=:country ORDER BY d.name ASC';
            $statement = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement,array(':country'=>$country), array(':country'=>\Phalcon\Db\Column::TYPE_VARCHAR));
            return $exe->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
          return $e->getMessage();
        } 
			
	}
	
}

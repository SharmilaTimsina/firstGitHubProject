<?php

use Phalcon\Mvc\Model;

class Countries extends Model {

    //id, source, domain, countries, category, njump, group, ad, banner, insertDate
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('Countries');
    }
	
	public function getCountriesUsers(){
		try {
         
            $sql='SELECT  c.id as id,u.id as uid, c.name as country, GROUP_CONCAT(u.username) username
			FROM    Countries c
			LEFT JOIN (SELECT id,username,countries FROM users WHERE userlevel=2) as u
            ON FIND_IN_SET(c.id, u.countries) > 0
			GROUP   BY c.id
			ORDER BY country ASC ';
            $statement = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            return $exe->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
          return $e->getMessage();
        } 
			
	}
	
	public function getUsrCt($id) {
        try {
            $sql = 'SELECT c.name as country ,c.id as id FROM Countries c INNER JOIN users u ON u.countries LIKE CONCAT("%,",c.id,",%") WHERE u.id=:id  AND userlevel=:userlevel order by country asc'  ;
            $statement = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array('id'=>$id,'userlevel'=>2), array());
            return $exe->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
          return $e->getMessage();
        } 
    }
	
	//this function is use for CRM
	public function getAllCountries(){
		try {
            $sql = 'SELECT * FROM Countries as c ORDER BY c.name ASC';
            $statement = $this->getDi()->getDb()->prepare($sql);
            $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
            return $exe->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
          return $e->getMessage();
        } 
			
	}

}

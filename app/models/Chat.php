<?php

use Phalcon\Mvc\Model;

class Chat extends Model

{

    public function initialize() 
    {
		 $this->setConnectionService('db4');
        $this->setSource("CRM__Chat");
    }
    
    public function chatSave($text,$uid,$type_chat){
		//insert code 
		try {
			$now = new DateTime();
			$now =$now->format('Y-m-d H:i:s');   
				// MySQL datetime format
            $sql = 'INSERT INTO CRM__Chat(uid,text,time,type) VALUES(:uid,:text,:time,:type)';
            $statement = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(':uid'=>$uid,':text'=>$text,':type'=>$type_chat,':time'=>$now),array(':uid'=>\Phalcon\Db\Column::TYPE_VARCHAR,':text'=>\Phalcon\Db\Column::TYPE_TEXT,':type'=>\Phalcon\Db\Column::TYPE_INTEGER));
           //get all data
		   
			try{
				
				$sql="SELECT c.uid as uid,u.username as name,c.time as time,c.text as text FROM CRM__Chat as c INNER JOIN users as u on c.uid=u.id WHERE type=:type order by c.time ASC";
				$statement = $this->getDi()->getDb4()->prepare($sql);
				$exe = $this->getDi()->getDb4()->executePrepared($statement, array(':type'=>$type_chat), array(':type'=>\Phalcon\Db\Column::TYPE_INTEGER));
				return $exe->fetchAll(PDO::FETCH_ASSOC);
			}
			catch (PDOException $e) {
				return $e->getMessage();
			} 
        } catch (PDOException $e) {
          return $e->getMessage();
        } 
		
	}
	
	public function getIssueInfo(){
		try {
							
			$sql="SELECT c.uid as uid,u.username as name,c.time as time,c.text as text FROM CRM__Chat as c INNER JOIN users as u on c.uid=u.id WHERE type=:type order by c.time ASC";
			$statement = $this->getDi()->getDb4()->prepare($sql);
			$exe = $this->getDi()->getDb4()->executePrepared($statement, array(':type'=>0), array(':type'=>\Phalcon\Db\Column::TYPE_INTEGER));
			return $exe->fetchAll(PDO::FETCH_ASSOC);
			
        } catch (PDOException $e) {
          return $e->getMessage();
        } 
	}
	
	public function getHistoryInfo(){
		try {
		
			$sql="SELECT c.uid as uid,u.username as name,c.time as time,c.text as text FROM CRM__Chat as c INNER JOIN users as u on c.uid=u.id WHERE type=:type order by c.time ASC";
			$statement = $this->getDi()->getDb4()->prepare($sql);
			$exe = $this->getDi()->getDb4()->executePrepared($statement, array(':type'=>1), array(':type'=>\Phalcon\Db\Column::TYPE_INTEGER));
			return $exe->fetchAll(PDO::FETCH_ASSOC);
			
        } catch (PDOException $e) {
          return $e->getMessage();
        } 
		
	}

	
}
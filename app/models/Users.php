<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;

class Users extends Model {

    public function initialize() {
        $this->setConnectionService('db4');
    }

    public function validation() {
        $this->validate(new EmailValidator(array(
            'field' => 'email'
        )));
        /*
          $this->validate(new UniquenessValidator(array(
          'field' => 'email',
          'message' => 'Sorry, The email was registered by another user'
          )));
         */
        $this->validate(new UniquenessValidator(array(
            'field' => 'username',
            'message' => 'Sorry, That username is already taken'
        )));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
	public function update_agregators($upstring,$id){
		$res = $this->getDi()->getDb4()->execute('UPDATE users SET aggregators=? WHERE id=?', array($upstring, $id));
	}
	
	public function getUserAm(){
		try {
            $sql="SELECT initials AS am FROM users1 WHERE userlevel=:userlevel AND initials!='' OR initials!=NULL ";
            $statement = $this->getDi()->getDb()->prepare($sql);
            $exe = $this->getDi()->getDb()->executePrepared($statement, array('userlevel'=>3), array('userlevel'=>\Phalcon\Db\Column::TYPE_INTEGER));
            return $exe->fetchAll(PDO::FETCH_ASSOC);
            
        } 
		catch (PDOException $e) {
          return $e->getMessage();
        } 
	}

}

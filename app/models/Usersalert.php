<?php

use Phalcon\Mvc\Model;

class Usersalert extends Model
{
    public function initialize()
    {
        $this->setSource("user_alerts");
    }
    
    
    
}

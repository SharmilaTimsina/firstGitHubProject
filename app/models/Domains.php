<?php

use Phalcon\Mvc\Model;

class Domains extends Model
{
    //	id,name,campaignType
    public function initialize()
    {
        $this->setConnectionService('db');
        $this->setSource('Domains');
    }
}   
    
    

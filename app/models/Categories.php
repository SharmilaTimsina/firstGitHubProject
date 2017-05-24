<?php

use Phalcon\Mvc\Model;

class Categories extends Model
{
    //	id,name,campaignType
    public function initialize()
    {
        $this->setConnectionService('db4');
        $this->setSource('Categories');
    }
}   
    
    

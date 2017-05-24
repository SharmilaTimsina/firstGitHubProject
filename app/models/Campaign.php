<?php

use Phalcon\Mvc\Model;

class Campaign extends Model {

    public function initialize()
    {
        $this->setConnectionService('db4');
        $this->setSource('Mask');
    }
    
    
    
}

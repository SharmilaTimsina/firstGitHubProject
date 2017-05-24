<?php

use Phalcon\Mvc\Model;

class Dimdevices extends Model {

    //	id,name,campaignType
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('dim__device');
    }

}

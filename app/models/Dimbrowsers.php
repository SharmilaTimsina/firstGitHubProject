<?php

use Phalcon\Mvc\Model;

class Dimbrowsers extends Model {

    //	id,name,campaignType
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('dim__browser');
    }

}

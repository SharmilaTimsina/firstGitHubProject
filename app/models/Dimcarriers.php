<?php

use Phalcon\Mvc\Model;

class Dimcarriers extends Model {

    //	id,name,campaignType
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('dim__carrier');
    }

}

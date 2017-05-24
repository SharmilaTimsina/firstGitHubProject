<?php

use Phalcon\Mvc\Model;

class Dimos extends Model {

    //	id,name,campaignType
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('dim__os');
    }

}

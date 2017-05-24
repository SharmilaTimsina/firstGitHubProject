<?php

use Phalcon\Mvc\Model;

class Dimarea extends Model {

    //	id,name,campaignType
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('offerpack__areatype');
    }

}

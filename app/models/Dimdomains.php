<?php

use Phalcon\Mvc\Model;

class Dimdomains extends Model {

    //	id,name,campaignType
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('dim__domains');
    }

}

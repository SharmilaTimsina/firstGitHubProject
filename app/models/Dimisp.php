<?php

use Phalcon\Mvc\Model;

class Dimisp extends Model {

    //	id,name,campaignType
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('dim__isp');
    }

}

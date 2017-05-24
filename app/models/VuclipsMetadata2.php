<?php

use Phalcon\Mvc\Model;

class VuclipsMetadata2 extends Model {

    //id, source, domain, countries, category, njump, group, ad, banner, insertDate
    public function initialize() {
        $this->setSource('VuclipsMetadata2');
    }

}

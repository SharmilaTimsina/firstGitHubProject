<?php

use Phalcon\Mvc\Model;

class VuclipsCampaignNames extends Model {

    //id, source, domain, countries, category, njump, group, ad, banner, insertDate
    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource("VuclipsCampaignNames");
    }

}

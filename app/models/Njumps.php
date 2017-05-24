<?php

use Phalcon\Mvc\Model;

class Njumps extends Model {

    public $id;
    public $njumphash;
    public $njumpgeneratedname;
    public $globalname;
    public $country;
    public $source;
    public $offerhash;
    public $offername;
    public $linename;
    public $sourcename;
    public $proportion;
    public $carrier;
    public $carrierids;
    public $os;
    public $osids;
    public $lpid;
    public $lpurl;
    public $time;
    public $sback;
    public $isp;
    public $ispids;
    public $domain;
    public $epc;
    public $status;
    public $clicks;
    public $counter;
    public $deleted;
    public $area;
    public $insertTimestamp;
    public $editedTimestamp;
    public $createdby;
    public $favorite;
    public $editedby;

    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('njumps');
    }

}

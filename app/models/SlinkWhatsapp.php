<?php

use Phalcon\Mvc\Model;

class SlinkWhatsapp extends Model {

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $hashMask;

    /**
     * @var string
     */
    public $lpName;

    /**
     * @var string
     */
    public $lpUrl;

    /**
     * @var string
     */
    public $lpOp;

    /**
     * @var string
     */
    public $isp;

    /**
     * @var string
     */
    public $device;

    /**
     * @var string
     */
    public $linkName;

    /**
     * @var string
     */
    public $linkref;

    /**
     * @var integer
     */
    public $percent;

    /**
     * @var integer
     */
    public $action;

    /**
     * @var timestamp
     */
    public $insertTimestamp;

    /**
     * @var c_country
     */
    public $c_country;

    /**
     * @var beginhour
     */
    public $beginhour;

    /**
     * @var endhour
     */
    public $endhour;

    /**
     * @var sback
     */
    public $sback;

    /**
     * @var stype
     */
    public $stype;

    /**
     * @var linkedjump
     */
    public $linkedjump;
    
    /**
     * @var autoop
     */
    public $autoop;
    
    /**
     * @var climiar
     */
    public $climiar;
    
    /**
     * @var clmiar_s
     */
    public $climiar_s;
    /**
     * Initializes correct njump table
     */
    public function initialize() {
        $this->setConnectionService('db');
        $this->setSource('tinas__MultiClick');
//        $this->skipAttributes(
//              [
//                  'lpOp',
//                  'isp',
//                  'device',
//                  'action',
//                  'linkref'
//              ]
//          );
    }
}

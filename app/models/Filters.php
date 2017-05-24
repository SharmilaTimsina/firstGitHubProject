<?php

use Phalcon\Mvc\Model;

class Filters extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $country_code;

    /**
     * @var string
     */
    public $country;

    /**
     * @var int
     */
    public $fk_affid;

    /**
     * @var string
     */
    public $operators;
    
     /**
     * @var timestamp
     */
    public $update_time;
    
    
	
}

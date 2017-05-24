<?php

use Phalcon\Mvc\Model;

class Isp extends Model
{
     /**
     * @var integer
     */
    public $id;
    
     /**
     * @var string
     */
    public $gname;
    
     /**
     * @var string
     */
    public $isp;
    
     /**
     * @var string
     */
    public $country;
    
     /**
     * @var double
     */
    public $insertTimestamp;
    
    /**
    * Initializes correct njump table
    */
    public function initialize()
    {
        $this->setConnectionService('db2');
        $this->setSource('Isps');
    }
}   
    
    

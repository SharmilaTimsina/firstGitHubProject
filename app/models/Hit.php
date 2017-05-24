<?php

use Phalcon\Mvc\Model;

class Hit extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $clicks;

    /**
     * @var integer
     */
    public $conversions;

    /**
     * @var string
     */
    public $revenue;

    /**
     * @var string
     */
    public $insert_date;
    
     /**
     * @var string
     */
    public $display_date;
    
     /**
     * @var string
     */
    public $carrier;
    
     /**
     * @var string
     */
    public $country;
    
    /**
     * @var string
     */
    public $country_code;
    
    /**
     * @var string
     */
    public $c_rate;

    /**
	 * Initializes correct affiliate table
	 */
    public function setAffiliate($aff)
    {
        $this->setSource("report".$aff);
    }
    /**
	 * Returns updated click and CR
	 *
	 * @return string
	 */
	public function updateClick()
	{
		$this->clicks++;
 
	}
        /**
	 * Returns updated conversion and CR
	 *
	 * @return string
	 */
        public function updateConversion($cpa=0)
	{
                
		$this->conversions++;
                $this->revenue+=$cpa;
      
	}
        
}

<?php

use Phalcon\Mvc\Model;

class Sources extends Model
{
    public $id;
    public $sourceName;
    public $affiliate;

    //id, source, domain, countries, category, njump, group, ad, banner, insertDate
    public function initialize()
    {
        $this->setConnectionService('db4');
        $this->setSource('Sources');
    }
	
	public function get_sources() {
		$array_ret = array();

		$statement = $this->getDi()->getDb4()->prepare("SELECT id, sourceName, affiliate, parameters FROM Sources LEFT JOIN sourcesMetadata ON Sources.id=sourcesMetadata.fkID");
		$exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
		$array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);

		return $array_ret;
	}
}   
    
    

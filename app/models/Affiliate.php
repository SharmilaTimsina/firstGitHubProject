<?php

use Phalcon\Mvc\Model;

class Affiliate extends Model
{
     /**
     * @var integer
     */
    public $id;
    
     /**
     * @var string
     */
    public $hash;
    
     /**
     * @var string
     */
    public $campaign;
    
     /**
     * @var int
     */
    public $source;
    
     /**
     * @var double
     */
    public $value;
    
    /**
    * Initializes correct njump table
    */
    public function initialize()
    {
        $this->setConnectionService('db2');
        $this->setSource('MobisteinRevShare');
    }
    
    
    public function getCurrentCampaigns(){
        
        $sql = 'SELECT r.id, hash, campaign, r.source as source, sourceName, value FROM MobisteinRevShare r inner join Sources s on s.id = r.source ORDER BY source, campaign;';
        return $this->getDi()->getDb2()->query($sql)->fetchAll();
    }
    
    public function newAffialite($source, $hash, $payout){
        //already exists?
        $statement = $this->getDi()->getDb2()->prepare('SELECT id FROM MobisteinRevShare WHERE source= :source and hash= :hash');
        $res1 = $this->getDi()->getDb2()->executePrepared($statement, array('source'=>$source, 'hash' => $hash),array('source'=> \Phalcon\Db\Column::TYPE_INTEGER,'hash'=> \Phalcon\Db\Column::TYPE_VARCHAR));
        if($res1->rowCount()>=1){
            return -3;
        }
        
        //campaign exists?
        $statement = $this->getDi()->getDb2()->prepare('SELECT hash, campaign FROM Mask WHERE hash= :hash LIMIT 1');
        $exe = $this->getDi()->getDb2()->executePrepared($statement, array('hash' => $hash),array('hash'=> \Phalcon\Db\Column::TYPE_VARCHAR));
        $res2 = $exe->fetchAll(PDO::FETCH_ASSOC);
        if(!isset($res2[0]['campaign'])){
            return -4;
        }
        
        //source exists?
        $statement = $this->getDi()->getDb2()->prepare('SELECT sourceName FROM Sources WHERE id= :source LIMIT 1');
        $exe = $this->getDi()->getDb2()->executePrepared($statement, array('source' => $source),array('id'=> \Phalcon\Db\Column::TYPE_INTEGER));
        $res3 = $exe->fetchAll(PDO::FETCH_ASSOC);
        if(!isset($res3[0]['sourceName'])){
            return -5;
        }
        
        //inserting...
        $statement = $this->getDi()->getDb2()->prepare('INSERT INTO MobisteinRevShare (hash, campaign, source, value) VALUES (:hash, :campaign, :source, :value)');
        $res4 = $this->getDi()->getDb2()->executePrepared($statement, array('hash' => $hash,'campaign'=>$res2[0]['campaign'],'source'=>$source,'value'=>$payout),
                array('hash'=> \Phalcon\Db\Column::TYPE_VARCHAR, 'sourceName'=> \Phalcon\Db\Column::TYPE_VARCHAR,
                'source'=> \Phalcon\Db\Column::TYPE_VARCHAR,'value'=> \Phalcon\Db\Column::TYPE_VARCHAR));
        return $res4->rowCount();
    }
    
    public function removeAff($source,$hash){
        $statement = $this->getDi()->getDb2()->prepare('DELETE FROM MobisteinRevShare WHERE source= :source and hash= :hash');
        return $this->getDi()->getDb2()->executePrepared($statement, array('source'=>$source, 'hash' => $hash),array('source'=> \Phalcon\Db\Column::TYPE_INTEGER,'hash'=> \Phalcon\Db\Column::TYPE_VARCHAR));
    }
    
    public function updatepayout($source,$hash,$payout){
        $statement = $this->getDi()->getDb2()->prepare('UPDATE MobisteinRevShare SET value= :payout WHERE source= :source and hash= :hash ');
        return $this->getDi()->getDb2()->executePrepared($statement, array('payout'=>$payout,'source'=>$source, 'hash' => $hash),array('value'=> \Phalcon\Db\Column::TYPE_VARCHAR,'source'=> \Phalcon\Db\Column::TYPE_INTEGER,'hash'=> \Phalcon\Db\Column::TYPE_VARCHAR));
    }
}   
    
    

<?php

use Phalcon\Mvc\Model;

class CustomReport extends Model {

    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('reporting__savedreports');
    }

    public function getattributes() {
        $array_ret = array();
        $sql = "SELECT attid, attname, attgroup, "
                . " columntype, combo, disabledids "
                . " FROM reporting__rattributes "
                . " WHERE hidden= 0 and att_type IN (0,1,2) "
                . " ORDER BY attgroup";
        $statement = $this->getDi()->getDb4()->prepare($sql);
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        return $array_ret;
    }

    public function getmeasures() {

        $array_ret = array();
        $sql = "SELECT attid, attname, attgroup, "
                . " columntype, combo, disabledids "
                . " FROM reporting__rattributes "
                . " WHERE hidden= 0 and att_type IN (4) "
                . " ORDER BY attgroup";
        $statement = $this->getDi()->getDb4()->prepare($sql);
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        return $array_ret;
    }
    
    public function getDims(){
        
        $sourceidsarray = $sourcenamesarray = $sourcetypearray = array();
        
        $agregatoridsarray = $agregatornamesarray = array();
        
        $maskhasharray = $masknamesarray = $maskcountryarray = $maskafcarrierarray = array();
        
        $usercountryarray = $categorienamearray = array();
        
        $datearray = array('0'=>'today','1'=>'yesterday','2'=>'last 3 days','3'=>'last 7 days',
            '4'=>'last 30 days','5'=>'this month','6'=>'last month');
        $time = '00:00';
        $i='0';
        $timearray=array();
        $time2array=array();
        do{
            $j = $i;
            if($i<10){ 
                $j = '0'.$i;
            }
            else
                $j = $i;
            $timearray[$i] = $j.':00';
            $time2array[$i] = $j.':59';
            
            $i++;
        }while($i<24);
        
        
        $sql = 'SELECT id, sourceName as name, affiliate,
                CASE affiliate WHEN 0 THEN "adult source" 
                WHEN 1 THEN "affiliate" WHEN 2 THEN "mainstream" ELSE "oldaffiliate" END as sourcetype 
                FROM Sources ';
        $statement = $this->getDi()->getDb4()->prepare($sql);
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        foreach($array_ret as $row){
            $sourceidsarray[$row['id']] = $row['id'];
            $sourcenamesarray[$row['id']] = $row['name'];
            if(!isset($sourcetypearray[$row['affiliate']]))
                $sourcetypearray[$row['affiliate']] = $row['sourcetype'];
        }
        $sql = 'SELECT id, agregator as name FROM Agregators ';
        $statement = $this->getDi()->getDb4()->prepare($sql);
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        foreach($array_ret as $row){
            $agregatoridsarray[$row['id']] = $row['id'];
            $agregatornamesarray[$row['id']] = $row['name'];
        }
        $sql = 'SELECT hash, campaign as name, afcarrier, country FROM Mask ';
        $statement = $this->getDi()->getDb4()->prepare($sql);
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        foreach($array_ret as $row){
            $maskhasharray[$row['hash']] = $row['hash'];
            $masknamesarray[$row['hash']] = $row['name'];
            if(!isset($maskcountryarray[strtoupper($row['country'])])) {
                $maskcountryarray[strtoupper($row['country'])] = strtoupper($row['country']);
                $usercountryarray[strtoupper($row['country'])] = strtoupper($row['country']);
            }
            if(!isset($maskafcarrierarray[strtolower($row['afcarrier'])])) 
                $maskafcarrierarray[strtolower($row['afcarrier'])] = strtolower($row['afcarrier']);
        }
        $sql = 'SELECT id, name FROM Categories ';
        $statement = $this->getDi()->getDb4()->prepare($sql);
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        foreach($array_ret as $row){
            $categorienamearray[$row['id']] = $row['name'];
        }
        
        return array(9=>$sourceidsarray,10=>$sourcenamesarray,11=>$sourcetypearray,
            1=>$agregatoridsarray,2=>$agregatornamesarray,
            7=>$maskafcarrierarray,6=>$maskcountryarray,
                3=>$maskhasharray,4=>$masknamesarray,
            17=>$usercountryarray, 
            45=>$categorienamearray,
            100=>$datearray,111=>$timearray,1112=>$time2array
                );
    }
    
    
}

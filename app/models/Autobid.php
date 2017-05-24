<?php

use Phalcon\Mvc\Model;

class Autobid extends Model
{
    
    public function initialize() {
       
    }
	
	public function getCamp() {
	
		//$statement = $this->getDi()->getDb()->prepare( "SELECT ac.id as id, ac.upOnly as upOnly, z.proccess, z.status ,ac.campaignid as campaignid, ac.campaignName as campaignName, ac.maxbid as maxbid, ac.state as state , ac.account as account, abc.id as idSource, abc.username, z.adRate, z.maxadrate, z.newbid, z.oldbid FROM AutobidCampaigns ac LEFT JOIN AutoBidAccounts abc ON abc.id = ac.account LEFT JOIN (SELECT campaignid, adRate, maxadrate, maxbid, oldbid, newbid, proccess, status FROM LogCampaignsBid az INNER JOIN ( SELECT MAX( id ) AS id FROM LogCampaignsBid GROUP BY campaignId )B ON az.id = B.id) z ON z.campaignid = ac.campaignid WHERE ac.state=1");
		
		$statement = $this->getDi()->getDb()->prepare("SELECT ac.id as id, ac.upOnly as upOnly, z.proccess, z.status , z.timeStamp ,ac.campaignid as campaignid, ac.campaignName as campaignName, ac.maxbid as maxbid, ac.state as state , ac.account as account, abc.id as idSource, abc.username, z.adRate, z.maxadrate, z.newbid, z.oldbid FROM AutobidCampaigns ac LEFT JOIN AutoBidAccounts abc ON abc.id = ac.account LEFT JOIN (SELECT campaignid, adRate, maxadrate, maxbid, oldbid, newbid, proccess, status, timeStamp FROM LogCampaignsBid az INNER JOIN ( SELECT MAX( id ) AS id FROM LogCampaignsBid GROUP BY campaignId )B ON az.id = B.id) z ON z.campaignid = ac.campaignid WHERE ac.state=1");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getUsers() {
		$statement = $this->getDi()->getDb()->prepare( "SELECT id, username FROM users WHERE (userlevel=1 and navtype=1) or (userlevel=2 and navtype=4)");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function addCamp($data) {
		$statement = $this->getDi()->getDb()->prepare("INSERT INTO AutobidCampaigns(campaignId, maxbid, state, account, upOnly) VALUES ('" . $data['campaignId'] . "','" . $data['maxbid'] . "','1', '" . $data['account'] . "', '" . $data['upOnly'] . "')");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
	}
	
	public function editCamp($data) {
		$statement = $this->getDi()->getDb()->prepare("UPDATE AutobidCampaigns SET upOnly='" . $data['upOnly'] . "', maxbid='" . $data['maxbid'] . "' WHERE id='" . $data['idtable'] . "'");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
	}
	
	public function deleteCamp($data) {
		$statement = $this->getDi()->getDb()->prepare("UPDATE AutobidCampaigns SET state='0' WHERE id='" . $data['idtable'] . "'");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
	}
	
	public function getAccounts() {
		$statement = $this->getDi()->getDb()->prepare( "SELECT * FROM AutoBidAccounts");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getReport($data) {
		$dates = explode(' to ' , $data['datepicker']);
		$sdate = $dates[0];
		$edate = $dates[1];
		
		if ($sdate == $edate) {
			$shour = '00:00:00';
			$ehour = '23:59:59';
		} else {
			$shour = '23:59:59';
			$ehour = '23:59:59';
		}
		
		$statement = $this->getDi()->getDb()->prepare( "SELECT username, AutoBidAccounts.id as accountid, LogCampaignsBid.campaignId, campaignName, adRate, maxadrate, LogCampaignsBid.maxbid, oldbid, newbid, 
			CASE proccess 
				WHEN 0 THEN '1' 
				WHEN 1 THEN '2' 
			END AS proccess, timeStamp  
			FROM LogCampaignsBid 
			INNER JOIN AutobidCampaigns, AutoBidAccounts 
			WHERE LogCampaignsBid.campaignId=AutobidCampaigns.campaignid AND AutoBidAccounts.id = AutobidCampaigns.account AND timeStamp BETWEEN '" . $sdate . " " . $shour . "' AND '" . $edate . " " . $ehour . "' AND AutoBidAccounts.id='" . $data['account'] . "'
			ORDER BY LogCampaignsBid.timeStamp DESC");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function resetPassAccount($data) {
		$statement = $this->getDi()->getDb()->prepare("UPDATE AutoBidAccounts SET password='$data[password]' WHERE id='" . $data['username'] . "'");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
	}
	
	public function insertAccount($data) {
		$statement = $this->getDi()->getDb()->prepare("INSERT INTO AutoBidAccounts(idSource, username, password, users) VALUES ('1', '" . $data['username'] . "','" . $data['password'] . "','" . $data['users'] . "')");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		
		$statement = $this->getDi()->getDb()->prepare( "SELECT MAX(id) as lastid FROM AutoBidAccounts");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		$result2 = $res->fetchAll(PDO::FETCH_ASSOC);
		
		$date = date("H:m", strtotime("+30 minutes"));
		$lastid = $result2[0]['lastid'];
	
		$statement = $this->getDi()->getDb()->prepare("INSERT INTO AutoBidProccessRegister(account, runningHour, nextHour, nextProccess, state, lastpidrunning) VALUES ('$lastid','0','$date','0','Terminated','0')");
		$res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		
	}
}   
    
  
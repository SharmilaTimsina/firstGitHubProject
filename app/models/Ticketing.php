<?php

use Phalcon\Mvc\Model;

class Ticketing extends Model {

    public function createTicket($data_array) {

        try {
			$team = '';
			if($data_array['team'] != '') {
				$team = "," . $data_array['team'] . ",";
			}
		
			$statement = $this->getDi()->getDb()->prepare("INSERT INTO Tickets(userId, userName, status, requestName, requestDetails, urgency, dateLimit, insertDate, teamTicket) VALUES ( :userid , :username, :status, :requestName, :requestDetails, :urgency, :timeLimitRequest, :insertdate, :team)");
            
            //'" . $data_array['userid'] . "','" . $data_array['username'] . "','" . $data_array['status'] . "','" . $data_array['requestName'] . "','" . $data_array['requestDetails'] . "','" . $data_array['urgencyRequest'] . "','" . $data_array['timeLimitRequest'] . "','" . $data_array['insertdate'] . "','" . $team . "'

			$exe = $this->getDi()->getDb()->executePrepared($statement, array('userid' => $data_array['userid'], 'username' => $data_array['username'], 'status' => $data_array['status'], 'requestName' => $data_array['requestName'], 'requestDetails' => $data_array['requestDetails'], 'urgency' => $data_array['urgencyRequest'], 'timeLimitRequest' => $data_array['timeLimitRequest'], 'insertdate' => $data_array['insertdate'], 'team' => $team), array());
       
        } catch (PDOException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }

    }

    public function editTicket($data_array) {
		
		$team = '';
		if($data_array['team'] != '' ) {
			$team = "," . $data_array['team'] . "," . $data_array['userid'] . ",";
		}
		
		if($data_array['userid'] == 1 || $data_array['userid'] == 6 || $data_array['userid'] == 20) {
			$statement = $this->getDi()->getDb()->prepare("UPDATE Tickets SET requestName=:requestName,requestDetails=:requestDetails,urgency=:urgency,dateLimit=:datelimit,status=:status, inchargeIt=:incharItRequest, acceptDate=:acceptDateData, comments=:commentsData, expectingDay=:expectingday, teamTicket=:team WHERE id=:idTicket");
				
			$exe = $this->getDi()->getDb()->executePrepared($statement, array('requestName' => $data_array['requestName'], 'requestDetails' => $data_array['requestDetails'], 'urgency' => $data_array['urgencyRequest'], 'datelimit' => $data_array['timeLimitRequest'], 'status' => $data_array['status'], 'incharItRequest' => $data_array['incharItRequest'], 'acceptDateData' => $data_array['acceptedate'], 'commentsData' => $data_array['itcomments'], 'expectingday' => $data_array['expectingday'], 'team' => $team, 'idTicket' => $data_array['idTicket']), array());	
			//$exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
		} else {
			$statement = $this->getDi()->getDb()->prepare("UPDATE Tickets SET requestName=:requestName,requestDetails=:requestDetails,urgency=:urgency,dateLimit=:dateLimit, teamTicket=:team WHERE id=:idTicket");
				
			$exe = $this->getDi()->getDb()->executePrepared($statement, array('requestName' => $data_array['requestName'], 'requestDetails' => $data_array['requestDetails'], 'urgency' => $data_array['urgencyRequest'], 'dateLimit' => $data_array['timeLimitRequest'],'team' => $team, 'idTicket' => $data_array['idTicket']), array());
		}
	}
	
	public function getTableByUser($auth) {
		
	  if($auth['id'] == 1 || $auth['id'] == 6 || $auth['id'] == 20 || $auth['id'] == 4 ) {
		  $statement = $this->getDi()->getDb()->prepare( "SELECT * FROM Tickets");
		  $res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		  return $res->fetchAll(PDO::FETCH_ASSOC);
      } else {
		  $statement = $this->getDi()->getDb()->prepare( "SELECT * FROM Tickets WHERE userId = '" . $auth['id'] . "' OR teamTicket LIKE '%," . $auth['id'] . ",%'" );
		  $res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		  return $res->fetchAll(PDO::FETCH_ASSOC);
	  }
	}
	
	public function getAuthor($ticketid) {
		 $statement = $this->getDi()->getDb()->prepare( "SELECT * FROM Tickets WHERE id='" . $ticketid . "'");
		 $res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		 $result = $res->fetchAll(PDO::FETCH_ASSOC);
			
		 $userIdAuthor = $result[0]['userId'];
		 
		 $statement = $this->getDi()->getDb()->prepare( "SELECT * FROM users WHERE id='" . $userIdAuthor . "'");
		 $res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		 $result = $res->fetchAll(PDO::FETCH_ASSOC);
		 
		 return $result[0]['email'];
	}
	
	public function getInchargeIt($ticketid) {
		 $statement = $this->getDi()->getDb()->prepare( "SELECT * FROM Tickets WHERE id='" . $ticketid . "'");
		 $res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		 $result = $res->fetchAll(PDO::FETCH_ASSOC);
		 
		 return $result[0]['inchargeIt'];
			
	}
	
	public function getAllMembers() {
		 $statement = $this->getDi()->getDb()->prepare( "SELECT id, username FROM users");
		 $res       = $this->getDi()->getDb()->executePrepared($statement, array(),array());
		 $result = $res->fetchAll(PDO::FETCH_ASSOC);
		
		 return $result;
	}
}

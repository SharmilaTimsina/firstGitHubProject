<?php

class TicketingController extends ControllerBase {

	public function initialize()
    {
        $this->tag->setTitle('Ticketing');
        parent::initialize();
    }
	
    public function indexAction() {
		
		$tableTickets = $this->getTable();
		$this->view->setVar("tableTickets", $tableTickets);
		
		$teamSelect = $this->getTeam();
		$this->view->setVar("teamSelect", $teamSelect);
    }
	
	public function getTeam() {
		

		$ticket = new Ticketing();
		$team = $ticket->getAllMembers();

		$teamSelect = '';
		$auth = $this->session->get('auth');
		foreach($team as $member) {
			if($member['id'] != $auth['id'])
				$teamSelect .=  "<option value='$member[id]'>$member[username]</option>";
		}
			
		return $teamSelect;		
	}
	
	public function createTicketAction() {
		 $auth = $this->session->get('auth');
		 
		 $data_array = array(
            'requestName' => $this->request->getPost('requestName'),
            'requestDetails' => $this->request->getPost("requestDetails"),
            'urgencyRequest' => $this->request->getPost("urgencyRequest"),
            'timeLimitRequest' => $this->request->getPost("timeLimitRequest"),
			'userid' => $auth['id'],
			'username' => $auth['name'],
			'insertdate' => date("Y-m-d"),
			'status' => '0', //pending
			'team' => $this->request->getPost("teamTicket") 
        );
		
		$ticket = new Ticketing();
		$result = $ticket->createTicket($data_array);
		
		$tableTickets = $this->getTable();
		echo $tableTickets;
		
		mail('pedro.leonardo@mobipium.com', 'New Ticket Created', 'Ticket created by ' . $auth['name'] . ' with the name : ' . $data_array['requestName']);
		mail('martim.barone@mobipium.com', 'New Ticket Created', 'Ticket created by ' . $auth['name'] . ' with the name : ' . $data_array['requestName']);
		mail('andre.vieira@mobipium.com', 'New Ticket Created', 'Ticket created by ' . $auth['name'] . ' with the name : ' . $data_array['requestName']);
		
    }
	
	public function editTicketAction() {
		 $auth = $this->session->get('auth');
		 
		 if($auth['id'] == 1 || $auth['id'] == 6 || $auth['id'] == 20) {
			 $data_array = array(
				'idTicket' => $this->request->getPost('idTicket'),
				'requestName' => $this->request->getPost('requestName'),
				'requestDetails' => $this->request->getPost("requestDetails"),
				'urgencyRequest' => $this->request->getPost("urgencyRequest"),
				'timeLimitRequest' => $this->request->getPost("timeLimitRequest"),
				'userid' => $auth['id'],
				'status' => $this->request->getPost("statusRequest"),
				'incharItRequest' => $this->request->getPost("incharItRequest"),
				'acceptedate' => date("Y-m-d"),
				'itcomments' => $this->request->getPost("itcomments"),
				'expectingday' => $this->request->getPost("expectingday"),
				'team' => $this->request->getPost("teamTicket") 
			);
			
			$ticket = new Ticketing();
			$author_ticket = $ticket->getAuthor($data_array['idTicket']);
			
			mail($author_ticket, 'Your ticket was edited', 'Ticket "' . $data_array["requestName"] . '" edited by ' . $auth['name']);
			mail('martim.barone@mobipium.com', 'Ticket edit', 'Ticket edit by ' . $auth['name'] . ' with the name : ' . $data_array['requestName']);
			
		 } else {
			 $data_array = array(
				'idTicket' => $this->request->getPost('idTicket'),
				'requestName' => $this->request->getPost('requestName'),
				'requestDetails' => $this->request->getPost("requestDetails"),
				'urgencyRequest' => $this->request->getPost("urgencyRequest"),
				'timeLimitRequest' => $this->request->getPost("timeLimitRequest"),
				'userid' => $auth['id'],
				'username' => $auth['name'],
				'team' => $this->request->getPost("teamTicket") 
			);
			
			$ticket = new Ticketing();
			$it_ticket = $ticket->getInchargeIt($data_array['idTicket']);		

			switch($it_ticket) {
				case 0:
					mail('pedro.leonardo@mobipium.com', 'Ticket edit', 'Ticket edit by ' . $auth['name'] . ' with the name : ' . $data_array['requestName']);
					break;
				case 2:
					mail('andre.vieira@mobipium.com', 'Ticket edit', 'Ticket edit by ' . $auth['name'] . ' with the name : ' . $data_array['requestName']);
					break;
				default:
					mail('andre.vieira@mobipium.com', 'Ticket edit', 'Ticket edit by ' . $auth['name'] . ' with the name : ' . $data_array['requestName']);
					break;
			 }
			
			mail('martim.barone@mobipium.com', 'Ticket edit', 'Ticket edit by ' . $auth['name'] . ' with the name : ' . $data_array['requestName']);
		 }
		
		$ticket = new Ticketing();
		$result = $ticket->editTicket($data_array);
		
		$tableTickets = $this->getTable();
		echo $tableTickets;
				
    }
	
	private function getTable() {		
		
		$urgency = array(
			'0' => 'Low',
			'1' => 'Medium Low',
			'2' => 'Medium',
			'3' => 'Medium High',
			'4' => 'High',
		);
		
		$status = array(
			'0' => 'Pending',
			'1' => 'In progress',
			'2' => 'Closed',
			'3' => 'Refused',
			'4' => 'On hold'
		);
		
		$inchargeIt = array(
			'-1' => '- - -',
			'0' => 'Pedro Leonardo',
			'1' => 'Martim Barone',
			'2' => 'Andre Vieira'
		);
		
		$auth = $this->session->get('auth');
		
		$admin = 0;
		if($auth['id'] == 1 || $auth['id'] == 6 || $auth['id'] == 20 || $auth['id'] == 4) {
			$admin = 1;
		}
	
		$ticket = new Ticketing();
		$result = $ticket->getTableByUser($auth);
			
		$ticketTable = '';
		$trRowUsers = 0;
		foreach ($result as $ticketLine) {
			
			$urgencyLabel = $urgency[$ticketLine['urgency']];
			$statusLabel = $status[$ticketLine['status']];
			$it = (isset($ticketLine['inchargeIt'])) ? $inchargeIt[$ticketLine['inchargeIt']] : '';
			$requestDetails = (isset($ticketLine['requestDetails'])) ? $ticketLine['requestDetails'] : ' ';
			$acceptdate = (isset($ticketLine['acceptDate'])) ? $ticketLine['acceptDate'] : ' ' ;
			$comments = (isset($ticketLine['comments'])) ? $ticketLine['comments'] : ' ' ;
			$idInchargit = (isset($ticketLine['inchargeIt'])) ? $ticketLine['inchargeIt'] : '-1' ;
			$expday = (isset($ticketLine['expectingDay'])) ? $ticketLine['expectingDay'] : ' ' ;
			
			$creator = 0;
			if($ticketLine['userId'] == $auth['id'])
				$creator = 1;
			
			$team = (isset($ticketLine['teamTicket'])) ? $ticketLine['teamTicket'] : ' ' ;
		
			$ticketTable .= "<tr c='$creator' team='$team' admin='$admin' idTicket='$ticketLine[id]' details='$requestDetails' expeday='$expday' accepteddate='$acceptdate' comments='$comments' trNumber = $trRowUsers>
								<td id='userasked'>$ticketLine[userName]</td>
								<td id='requestName'>$ticketLine[requestName]</td>
								<td id='insertDate'>$ticketLine[insertDate]</td>
								<td idUrgency='$ticketLine[urgency]' id='urgency'>$urgencyLabel</td>
								<td id='dateLimit'>$ticketLine[dateLimit]</td>
								<td idInchargit='$idInchargit' id='inchargeit'>$it</td>
								<td idStatus='$ticketLine[status]' id='statusRequest'>$statusLabel</td>
								<td class='iconEdit'>
									<img class='modalIcon' src='/img/details.png' data-toggle='modal' data-target='#detailsTicket' />
								</td>
							</tr>";
							
			$trRowUsers++;
		}
		
		return $ticketTable;
	}
}

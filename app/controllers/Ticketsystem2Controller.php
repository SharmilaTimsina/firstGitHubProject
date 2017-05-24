<?php

class Ticketsystem2Controller extends ControllerBase {

	public function initialize()
    {
        $this->tag->setTitle('Ticket System');
        parent::initialize();
    }
	
    public function indexAction() {
		$auth = $this->session->get('auth');

		$type = 0;
		$type2 = 1;
		if($auth['userarea'] == 5) {
			$type = 0;
			$type2 = 3;
		} else if($auth['userarea'] == 7) {
			$type = 1;
			$type2 = 3;
		} 

		if($auth['userarea'] == 5 || $auth['userarea'] == 7) {
			 $ticket = TicketSystem::find([
                        "status NOT IN (:status:,:status2:,:status3:) AND type IN (:type:,:type2:) ",
                        "bind" => [
                            "status" => 5,
                            "status2" => 7,
                            "status3" => 4,
                            "type" => $type,
                            "type2" => $type2
                        ],
            ]);
		} else {
			 $ticket = TicketSystem::find([
                        "type IN (:type:,:type2:) ",
                        "bind" => [
                            "type" => $type,
                            "type2" => $type2
                        ],
            ]);
		}

        $table = array();
        foreach ($ticket as $value) {
           if($auth['navtype'] == 1 || $auth['id'] == $value->requester || in_array($auth['id'], explode(',', $value->usersView)) || in_array($auth['id'], explode(',', $value->users_area)) ||  $auth['id'] == 35  ) {
                
           		$userRequester = Users::findFirst([
                            "id = :id: ",
                            "bind" => [
                                "id" => $value->requester,
                            ],
                ]);

                $userIncharged = Users::findFirst([
                            "id = :id: ",
                            "bind" => [
                                "id" => $value->incharge,
                            ],
                ]);

                $userAssigned = Users::findFirst([
                            "id = :id: ",
                            "bind" => [
                                "id" => $value->assigned,
                            ],
                ]);

                $userA = '';
                if(isset($userAssigned->username)) {
                    if($userAssigned->username != null && $userAssigned->username != '') {
                        $userA = $userAssigned->username;
                    } else {
                        $userA = ' - - - ';
                    }
                } else {
                    $userA = ' - - - ';
                }

                $userI = '';
                if(isset($userIncharged->username)) {
                    if($userIncharged->username != null && $userIncharged->username != '') {
                        $userI = $userIncharged->username;
                    } else {
                        $userI = ' - - - ';
                    }
                } else {
                    $userI = ' - - - ';
                }

                $deadline = '';
                if(isset($value->deadline)) {
                    if($value->deadline != null && $value->deadline != '') {
                        $deadline = $value->deadline;
                    } else {
                        $deadline = ' - - - ';
                    }
                } else {
                    $deadline = ' - - - ';
                }

                array_push($table, array(
            				'id' => $value->id,
                            'requester' => $userRequester->username,
                            'assignedto' => $userA,
                            'incharge' => $userI,
                            'subject' => $value->subject,
                            'priority' => $value->priority, 
                       		'status' => $value->status, 
                            'created_at' => $value->creation_date,
                            'deadline' => $deadline,
                            'type' => $value->type
                        ));                  
            } 
        }

        $typeEdit = '';
        if($auth['userarea'] == 5 || $auth['userarea'] == 7)
        	$typeEdit = 1;
        else
        	$typeEdit = 2;

        $this->view->setVar('ticketTable', json_encode($table));
        $this->view->setVar('typeEdit', $typeEdit);
    }

    public function createTicketAction() {
        $auth = $this->session->get('auth');
        $this->view->setVar('authID', $auth['id']);

        $tsystem = new TicketSystem();
        $result = $tsystem->getUsers($auth['id']);

        $this->view->setVar('users', json_encode($this->utf8ize($result)));
    }

    private function utf8ize($d) {
	    if (is_array($d)) {
	        foreach ($d as $k => $v) {
	            $d[$k] = $this->utf8ize($v);
	        }
	    } else if (is_string ($d)) {
	        return utf8_encode($d);
	    }
	    return $d;
	}

    public function create_ticketAction() {
        $auth = $this->session->get('auth');
        $array_states = [];
        $date = date('Y-m-d');
        
        $userarea = array();
        $type = $this->request->getPost('type');
        if($type == 0) {
        	$userByArea = Users::find([
	                            "userarea = :ua: ",
	                            "bind" => [
	                                "ua" => 5,
	                            ],
			                ]);

        	foreach ($userByArea as $key => $value) {
        		array_push($userarea, $value->id);
        	}
        } else if($type == 1) {
        	$userByArea = Users::find([
	                            "userarea = :ua: ",
	                            "bind" => [
	                                "ua" => 7,
	                            ],
			                ]);

        	foreach ($userByArea as $key => $value) {
        		array_push($userarea, $value->id);
        	}
        }

        //str_replace('"', '\"', $this->request->getPost('details')),

        $data = array(
                    'subject' => $this->request->getPost('subject'),
                    'details' => $this->request->getPost('details'),
                    'type' => $type,
                    'usersView' => $this->request->getPost('users'),
                    'priority' => $this->request->getPost('priority'),
                    'required_period' => $this->request->getPost('requiredDate'),
                    'requester' => $auth['id'],
                    'creation_date' => $date,
                    'status' => '0',
                    'users_area' => implode(',', $userarea)
                );

        $tsystem = new TicketSystem();
        if($tsystem->save($data) == false) {
            array_push($array_states, FALSE);

            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
        	array_push($array_states, TRUE);

            $id_ticket = $tsystem->id;

            $data_chat = array(
                    'ticket_id' => $id_ticket,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket ' . $id_ticket . ' created by ' . $auth['name'] . ' with the subject: ' . $tsystem->subject . ' , and description: ' . trim(preg_replace('/[\n\r]/', '\\n', $tsystem->details)),
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
            	array_push($array_states, FALSE);

                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                array_push($array_states, TRUE);

                if ($this->request->hasFiles() == true) {
                
	                foreach ($this->request->getUploadedFiles() as $file) {

	                    $baseLocation = '../files/tickets/' . $id_ticket;
	                    if (!is_dir($baseLocation)) {
	                        mkdir($baseLocation , 0777, true);
	                    }
	                    $dir = $baseLocation . "/" . $date . '_' . $file->getName();
	                    $file->moveTo($dir);
	                }

	                $data_file = array(
	                            'ticket_id' => $id_ticket,
	                            'file_date' => date('Y-m-d H:i'),
	                            'file_path' => $dir,
	                            'uploader' => $auth['id'],
	                            'file_name' => $file->getName()
	                        );

	                $tsystemfiles = new TicketFiles(); 
	                if($tsystemfiles->save($data_file) == false) {
	                	array_push($array_states, FALSE);

	                    foreach ($tsystemfiles->getMessages() as $message) {
	                        echo $message, "\n";
	                    }
	                    return;  
	                } else {
	                    array_push($array_states, TRUE);

	                    $data_chat = array(
	                        'ticket_id' => $id_ticket,
	                        'message_date' => date('Y-m-d H:i'),
	                        'sender' => 'System',
	                        'message' => 'File upload: ' . $file->getName() . ' by ' . $auth['name'],
                            'visible' => 1
	                    );

	                    $tsystemchat = new TicketChat(); 
	                    if($tsystemchat->save($data_chat) == false) {
	                    	array_push($array_states, FALSE);

	                        foreach ($tsystemfiles->getMessages() as $message) {
	                            echo $message, "\n";
	                        }
	                        return;  
	                    } else {
	                    	array_push($array_states, TRUE);
	                    }
	                } 
	            }
            }
        }

        if(array_sum($array_states) == count($array_states)) {
        	echo "Complete!";

	        $email = array(
	                'subject' => 'Ticket_' . $tsystem->id . ' opened by ' . $auth['name'] . ' with the subject ' . $tsystem->subject,
	                'body' => 'Ticket_' . $tsystem->id . ' opened by ' . $auth['name'] . ' on ' . $tsystem->creation_date . ':'
	                			. '<br><br>Subject: ' . $tsystem->subject
	                			. '<br><br>Details: ' . preg_replace('/[\n]/', '<br>', $tsystem->details) ,
	                'ticket_id' => $tsystem->id
	            );

	        $this->sendemails($email);
        }
    }

    public function setFilterAction() {
		$auth = $this->session->get('auth');

        $priority = $this->request->getPost('priority');
        $type = $this->request->getPost('type');
        $status = $this->request->getPost('status');

        $ticketS = new TicketSystem();
        $ticket = $ticketS->getTicketsByFilter($priority, $type, $status);

        $table = array();
        foreach ($ticket as $value) {
            if($auth['navtype'] == 1 || $auth['id'] == $value['requester'] || in_array($auth['id'], explode(',', $value['usersView'])) || in_array($auth['id'], explode(',', $value['users_area'])) ||  $auth['id'] == 35  ) {
                
           		$userRequester = Users::findFirst([
                            "id = :id: ",
                            "bind" => [
                                "id" => $value['requester'],
                            ],
                ]);

                $userIncharged = Users::findFirst([
                            "id = :id: ",
                            "bind" => [
                                "id" => $value['incharge'],
                            ],
                ]);

                $userI = '';
                if(isset($userIncharged->username)) {
                    if($userIncharged->username != null) {
                        $userI = $userIncharged->username;
                    } else {
                        $userI = ' - - - ';
                    }
                } else {
                    $userI = ' - - - ';
                }

                $userAssigned = Users::findFirst([
                            "id = :id: ",
                            "bind" => [
                                "id" => $value['assigned'],
                            ],
                ]);

                $userA = '';
                if(isset($userAssigned->username)) {
                    if($userAssigned->username != null && $userAssigned->username != '') {
                        $userA = $userAssigned->username;
                    } else {
                        $userA = ' - - - ';
                    }
                } else {
                    $userA = ' - - - ';
                }

                $deadline = '';
                if(isset($value['deadline'])) {
                    if($value['deadline'] != null) {
                        $deadline = $value['deadline'];
                    } else {
                        $deadline = ' - - - ';
                    }
                } else {
                    $deadline = ' - - - ';
                }

                array_push($table, array(
            				'id' => $value['id'],
                            'requester' => $userRequester->username,
                            'assignedto' => $userA,
                            'incharge' => $userI,
                            'subject' => $value['subject'],
                            'priority' => $value['priority'], 
                       		'status' => $value['status'], 
                            'created_at' => $value['creation_date'],
                            'deadline' => $deadline,
                            'type' => $value['type']
                        ));                  
            } 
        }

        echo json_encode($table);
    }

    public function editticket_itsdesAction() {
        $ticket_id = $this->request->get('idTicket');

        if($ticket_id == '')
            return;

        $auth = $this->session->get('auth');

        $ticket = TicketSystem::findFirst([
                    "id = :id: ",
                    "bind" => [
                        "id" => $ticket_id,
                    ],
        ]);

        $info = $this->getTicketInfo($ticket_id);

        if($auth['navtype'] == 1 || $auth['id'] == $ticket->requester || in_array($auth['id'], explode(',', $ticket->usersView)) || in_array($auth['id'], explode(',', $ticket->users_area)) ||  $auth['id'] == 35  ) {
            $this->view->setVar('ticketInfo', $info);
            $this->view->setVar('ticketFiles', $this->getTicketFiles($ticket_id));
            $this->view->setVar('ticketChat', trim(preg_replace('/[\n\r]/', '\\n', $this->getTicketChat($ticket_id))));
            //$this->view->setVar('details', $info[1]);
            $this->view->setVar('downloadExcel', '/ticketsystem2/downloadExcel?idTicket=' . $ticket_id);
            $this->view->setVar('authID', $auth['id']);

            $tsystem = new TicketSystem();
            $result = $tsystem->getUsers($auth['id']);
            $this->view->setVar('users', json_encode($this->utf8ize($result)));

            $usersAssigned = Users::find([
                        "columns"=>"id,username",
                        "userarea = :area: ",
                        "bind" => [
                            "area" => $auth['userarea']
                        ]
            ]);

            $usersMyArea = array();
            foreach ($usersAssigned as $user) {
                if($user->id != $auth['id']) {
                    array_push($usersMyArea, array(
                            'id' => $user->id,
                            'username' => $user->username
                        ));

                }
            }

            $this->view->setVar('usersMyArea', json_encode($usersMyArea));
        } 
    }

	public function editTicketAction() {
		$ticket_id = $this->request->get('idTicket');

        if($ticket_id == '')
            return;

        $auth = $this->session->get('auth');

        $ticket = TicketSystem::findFirst([
                    "id = :id: ",
                    "bind" => [
                        "id" => $ticket_id,
                    ],
        ]);

        $info = $this->getTicketInfo($ticket_id);
        if($auth['navtype'] == 1 || $auth['id'] == $ticket->requester || in_array($auth['id'], explode(',', $ticket->usersView)) || in_array($auth['id'], explode(',', $ticket->users_area))  ||   $auth['id'] == 35  ) {
            $this->view->setVar('ticketInfo', $info);
            $this->view->setVar('ticketFiles', $this->getTicketFiles($ticket_id));
            $this->view->setVar('ticketChat', trim(preg_replace('/[\n\r]/', '\\n', $this->getTicketChat($ticket_id))));
            //$this->view->setVar('details', $info[1]);
            $this->view->setVar('downloadExcel', '/ticketsystem/downloadExcel?idTicket=' . $ticket_id);
            $this->view->setVar('authID', $auth['id']);

            $tsystem = new TicketSystem();
            $result = $tsystem->getUsers($auth['id']);
            $this->view->setVar('users', json_encode($this->utf8ize($result)));
        } 	
	}

    private function getTicketInfo($ticket_id) {
        if(isset($ticket_id)) {
            $ticket = TicketSystem::findFirst([
                        "id = :id: ",
                        "bind" => [
                            "id" => $ticket_id,
                        ],
            ]);
            if (!empty($ticket)) {
                
                $user = Users::findFirst([
                            "id = :id: ",
                            "bind" => [
                                "id" => $ticket->requester,
                            ],
                ]);

                $userIncharged = Users::findFirst([
                            "id = :id: ",
                            "bind" => [
                                "id" => $ticket->incharge,
                            ],
                ]);

                return json_encode(array(
                        'subject' => $ticket->subject,
                        'type' => $ticket->type,
                        'usersView' => $ticket->usersView,
                        'priority' => $ticket->priority,
                        'required_period' => $ticket->required_period,
                        'requester' => $user->username,
                        'requesterid' => $ticket->requester,
                        'creation_date' => $ticket->creation_date,
                        'status' => $ticket->status,
                        'users_area' => $ticket->users_area, 
                        'incharge' => (isset($userIncharged->username) ? $userIncharged->username : ''), 
                        'incharge_id' => $ticket->incharge,
                        'incharged_at' => $ticket->incharged_at,
                        'id_ticket' => $ticket->id,
                        'is_requester' => ($this->session->get('auth')['id'] == $ticket->requester) ? true : false,
                        'is_incharge' => ($this->session->get('auth')['id'] == $ticket->incharge) ? true : false,
                        'isAssignToMe' => ($this->session->get('auth')['id'] == $ticket->assigned) ? true : false,
                        'deadline' => (isset($ticket->deadline) && $ticket->deadline != null) ? $ticket->deadline : '- - -',
                        'details' => $ticket->details
                    ));
            } else {
                return "Ticket not found!";
            }
        } else {
            return "error";
        }
    }

    private function getTicketChat($ticket_id) {
         if(isset($ticket_id)) {
            $ticketChat = TicketChat::find([
                        "ticket_id = :ticket_id: AND visible = :visi:",
                        "bind" => [
                            "ticket_id" => $ticket_id,
                            "visi" => 1
                        ], "order" => "message_date ASC"
            ]);

            $array_chat = array();
            foreach ($ticketChat as $chatMessage) {
                if (!empty($chatMessage)) {

                    $user = Users::findFirst([
                                "id = :id: ",
                                "bind" => [
                                    "id" => $chatMessage->sender,
                                ]
                    ]);

                    array_push($array_chat, array(
                                           'message_date' => $chatMessage->message_date,
                                           'sender' => ($chatMessage->sender == 'System') ? 'System' : $user->username,
                                           'message' => trim(str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n", "\\\n"), ' ',$chatMessage->message))
                                        ));
                } 
            }
            return json_encode($array_chat);
            
        } else {
            return "error";
        }
    }


    private function getTicketFiles($ticket_id) {
        if(isset($ticket_id)) {
            $ticketFiles = TicketFiles::find([
                        "ticket_id = :ticket_id: ",
                        "bind" => [
                            "ticket_id" => $ticket_id,
                        ],
            ]);

            $array_files = array();
            foreach ($ticketFiles as $file) {
                if (!empty($file)) {

                    $user = Users::findFirst([
                                "id = :id: ",
                                "bind" => [
                                    "id" => $file->uploader,
                                ],
                    ]);

                    array_push($array_files, array(
                                            'file_date' => $file->file_date,
                                            'uploader' => $user->username,
                                            'id_file' => $file->id,
                                            'file_name' => $file->file_name,
                                        ));
                } 
            }
            return json_encode($array_files);
            
        } else {
            return "error";
        }
    }

    public function downloadExcelAction() {
        $ticket_id = $this->request->get('idTicket');

         if(isset($ticket_id)) {
            $ticketChat = TicketChat::find([
                        "ticket_id = :ticket_id: ",
                        "bind" => [
                            "ticket_id" => $ticket_id,
                        ], "order" => "message_date ASC"
            ]);

            $array_chat = array();
            foreach ($ticketChat as $chatMessage) {
                if (!empty($chatMessage)) {

                    $user = Users::findFirst([
                                "id = :id: ",
                                "bind" => [
                                    "id" => $chatMessage->sender,
                                ]
                    ]);

                    array_push($array_chat, array(
                                           'message_date' => $chatMessage->message_date,
                                           'sender' => ($chatMessage->sender == 'System') ? 'System' : $user->username,
                                           'message' => $chatMessage->message
                                        ));
                } 
            }

            $columns = array("message_date", "user", "message");
            $title = 'History_ticket_' . $ticket_id;
            $this->sendExcel($array_chat, $columns, $title);
            
        } else {
            return "error";
        }
    }

    private function sendExcel($data, $columns, $title) {
                
        try {
            $temp = tmpfile();
            $start = "sep=|\n";
            $exColumns = "";
            for ($i = 0; $i < sizeof($columns); $i++) {
                $exColumns .= $columns[$i] . '|';
            }
            fwrite($temp, $start . $exColumns . "\n");

            for ($j = 0; $j < sizeof($data); $j++) {
                $resultRow = '';
                foreach ($data[$j] as $k => $v) {
                    
                    $string1 = str_replace("&#039;", "'",$data[$j][$k]);
                    $string2 = str_replace("\\n", " ",$string1);
                    $string3 = str_replace('\\"', '"',$string2);
                    $string4 = str_replace("&quot;", "'",$string3);
                    $string5 = str_replace("&#039", "'",$string4);

                    $resultRow .= rtrim($string5, ';') . '|';
                }

                fwrite($temp, $resultRow . "\n");
            }
            
            fseek($temp, 0);
            while (($buffer = fgets($temp, 4096)) !== false) {
                echo $buffer;
            }
            fclose($temp);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            $myContentDispositionHeader = 'Content-Disposition: attachment;filename="' . $title . '.csv"';
            header($myContentDispositionHeader);
            header('Expires: 0');
            header('Cache-Control: max-age=0');
            header('Pragma: public');
            exit();

        } catch (Exception $e) {
            Tracer::writeTo('E', $e->getMessage(), 'e', $e->getLine(), __METHOD__, __CLASS__);
            $this->generateErrorResponse();
        }
    }

    public function refreshChatAction() {
        $id_ticket = $this->request->getPost('id_ticket');
        echo $this->getTicketChat($id_ticket);
    }

    public function sendMessageAction() {
        $auth = $this->session->get('auth');
        $message = $this->request->getPost('message');
        $id_ticket = $this->request->getPost('id_ticket');

        $data_chat = array(
                'ticket_id' => $id_ticket,
                'message_date' => date('Y-m-d H:i'),
                'sender' => $this->session->get('auth')['id'],
                'message' => htmlentities($message, ENT_QUOTES),
                'visible' => 1
            );

        $tsystemchat = new TicketChat(); 
        if($tsystemchat->save($data_chat) == false) {
            foreach ($tsystemchat->getMessages() as $message) {
                echo $message, "\n";
            }
            return;  
        } else {
            echo $this->getTicketChat($id_ticket);

            $tsystem = TicketSystem::findFirstById($id_ticket);
            $user = Users::findFirstById($tsystem->requester);

            $email = array(
                    'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . ' - New Message by ' . $auth['name'],
                    'body' => "Ticket_" . $tsystem->id . " opened by " . $user->username . " on " . $tsystem->creation_date . ", with the subject " . $tsystem->subject . " , has a new message:<br><br>" 
                               . "Message by: " . $auth['name'] . "<br>"
                               . "Date: " . date('Y-m-d H:i') . "<br>"
                               . "Message: " . $message . "<br>",
                    'ticket_id' => $tsystem->id
                );

            $this->sendemails($email);
        }
    }

    public function downloadFileAction() {
        try {
            $ticket_id = $this->request->get('id_ticket');
            if ($this->request->get('id_file')) {
                

                 $file = TicketFiles::findFirst([
                            "id = :id: ",
                            "bind" => [
                                "id" => $this->request->get('id_file'),
                            ],
                ]);
                
                if (file_exists($file->file_path)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename='.basename($file->file_path));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file->file_path));
                    ob_clean();
                    flush();
                    readfile($file->file_path);
                    exit;
                }
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E', $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function uploadFileAction() {
       
       $auth = $this->session->get('auth');
       $date = date('Y-m-d H:i');
       $id_ticket = $this->request->getPost('id_ticket');

       if ($this->request->hasFiles() == true) {
           foreach ($this->request->getUploadedFiles() as $file) {

                $baseLocation = '../files/tickets/' . $id_ticket;
                if (!is_dir($baseLocation)) {
                    mkdir($baseLocation , 0777, true);
                }
                $dir = $baseLocation . "/" . $date . '_' . $file->getName();
                $file->moveTo($dir);
            }

            $data_file = array(
                        'ticket_id' => $id_ticket,
                        'file_date' => $date,
                        'file_path' => $dir,
                        'uploader' => $auth['id'],
                        'file_name' => $file->getName()
                    );

            $tsystemfiles = new TicketFiles(); 
            if($tsystemfiles->save($data_file) == false) {
                foreach ($tsystemfiles->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                $data_chat = array(
                    'ticket_id' => $id_ticket,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'File upload: ' . $file->getName()  . ' by ' . $auth['name'],
                    'visible' => 1
                );

                $tsystemchat = new TicketChat(); 
                if($tsystemchat->save($data_chat) == false) {
                    foreach ($tsystemfiles->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    return;  
                } else {
                    echo $this->getTicketFiles($id_ticket);

                    $tsystem = TicketSystem::findFirstById($id_ticket);
                    $user = Users::findFirstById($tsystem->requester);

                    $email = array(
                            'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . ' - Uploaded file by ' . $auth['name'],
                            'body' => "Ticket_" . $tsystem->id . " has receive a file on " . date('Y-m-d H:i') .  '<br><br>'
                                       . "File name: " . $file->getName() . "<br>",
                            'ticket_id' => $tsystem->id
                        );

                    $this->sendemails($email);
                }
            }
        }
    }

    public function edit_ticketAction() {
        $auth = $this->session->get('auth');
        $date = date('Y-m-d');
        $ticket_id = $this->request->getPost('id_ticket');
        
        //$tsystem = new TicketSystem();
        $tsystem = TicketSystem::findFirstById($ticket_id);
        $message = '';

        if($this->request->getPost('subject') != $tsystem->subject) {
            $this->savePrivateMessage(0, $ticket_id, 'Subject was changed by ' . $this->session->get('auth')['name'] . '. Previous: ' . $tsystem->subject);
            $message .= 'Subject was changed by ' . $this->session->get('auth')['name'] . ' to: ' . $this->request->getPost('subject') . '<br><br>';
        } 

        if($this->request->getPost('details') != $tsystem->details) {
            $this->savePrivateMessage(0, $ticket_id, 'Details was changed by ' . $this->session->get('auth')['name'] . '. Previous: ' . preg_replace('/[\n]/', '<br>', $tsystem->details));
            $message .= 'Details was changed by ' . $this->session->get('auth')['name'] . ' to: ' . preg_replace('/[\n]/', '<br>', $this->request->getPost('details')) . '<br><br>';
        } 

        /*
        if($this->request->getPost('type') != $tsystem->type) {
            $this->savePrivateMessage(1, $ticket_id, 'Type has changed by ' . $this->session->get('auth')['name'] . '. Previous: ' . $tsystem->type);
        } 

        if($this->request->getPost('users') != $tsystem->usersView) {
            $this->savePrivateMessage(0, $ticket_id, 'Users has changed by ' . $this->session->get('auth')['name'] . '. Previous: ' . $tsystem->usersView);
        } 

        if($this->request->getPost('priority') != $tsystem->priority) {
            $this->savePrivateMessage(1, $ticket_id, 'Priority has changed by ' . $this->session->get('auth')['name'] . '. Previous: ' . $tsystem->priority);
        }
        */ 

        if($this->request->getPost('requiredDate') != $tsystem->required_period) {
            $this->savePrivateMessage(0, $ticket_id, 'Required Period was changed by ' . $this->session->get('auth')['name'] . '. Previous: ' . $tsystem->required_period);
            $message .= 'Required Date was changed by ' . $this->session->get('auth')['name'] . ' to: ' . $this->request->getPost('requiredDate') . '<br>';
        } 

        $data = array(
                    'subject' => $this->request->getPost('subject'),
                    'details' => $this->request->getPost('details'),
                    'type' => $this->request->getPost('type'),
                    'usersView' => $this->request->getPost('users'),
                    'priority' => $this->request->getPost('priority'),
                    'required_period' => $this->request->getPost('requiredDate')
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
        
            $id_ticket = $tsystem->id;

            
            $data_chat = array(
                    'ticket_id' => $id_ticket,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket ' . $id_ticket . ' edit by ' . $auth['name'],
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                echo "Complete!";

                $tsystem = TicketSystem::findFirstById($id_ticket);
                $user = Users::findFirstById($tsystem->requester);

                $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . ' - Ticket properties has been changed by ' . $auth['name'],
                        'body' => $message,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);
            }
        }
    }

    private function savePrivateMessage($visible, $id_ticket, $message) {
        $data_chat = array(
                'ticket_id' => $id_ticket,
                'message_date' => date('Y-m-d H:i'),
                'sender' => 'System',
                'message' =>  trim(preg_replace('/[\n\r]/', '\\n', $message)),
                'visible' => $visible
            );

        $tsystemchat = new TicketChat(); 
        if($tsystemchat->save($data_chat) == false) {
            foreach ($tsystemchat->getMessages() as $message) {
                echo $message, "\n";
            }
            return;  
        } else {
            return true;
        }
    }

    public function pickTicketAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $deadline = $this->request->getPost('deadline');

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $olderincharge = $tsystem->incharge;

        $data = array(
                    'incharge' => $auth['id'],
                    'status' => 3, //in progress
                    'assigned' => 0,
                    'incharged_at'=> date('Y-m-d'),
                    'deadline' => $deadline
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket picked by ' . $auth['name'] . '. Deadline: ' . $deadline,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {

                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);

                $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket was Picked by ' . $auth['name'],
                        'body' => 'Ticket was Picked by ' . $auth['name'] . '<br>'
                                    . 'Deadline: ' . $deadline,
                        'ticket_id' => $tsystem->id,
                        'olderincharge' => $olderincharge
                    );

                $this->sendemails($email);
            }
        }
    }

    public function sendToValidationAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $message = $this->request->getPost('message');

        $extraM = '';
        if($message != '') {
            $extraM = ' Comments: ' . $message;
        }

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 4, //validation
                    'assigned' => 0
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket sent to validation by ' . $auth['name'] . '.' . $extraM,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);

                $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket sent to validation by ' . $auth['name'],
                        'body' => 'Ticket sent to validation by ' . $auth['name'] . '<br><br>'
                                    . $extraM,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);

            }
        }
    }

    public function putOnHoldAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $message = $this->request->getPost('message');

        $extraM = '';
        if($message != '') {
            $extraM = ' Because: ' . $message;
        }

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 6, //onhold
                    'assigned' => 0
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket set on hold by ' . $auth['name'] . '.' . $extraM,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);

                $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket set On Hold by ' . $auth['name'],
                        'body' => 'Ticket set on hold by ' . $auth['name'] . '<br><br>'
                                    . $extraM,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);
            }
        }
    }

    public function putInProgressAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
       
        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 3, //in progress
                    'assigned' => 0
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket In Progress by ' . $auth['name'] . '.',
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);

                $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket In Progress by ' . $auth['name'],
                        'body' => 'Ticket In Progress by ' . $auth['name'] . '<br><br>'
                                    . $extraM,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);

            }
        }
    }

    public function refuseAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $message = $this->request->getPost('message');

        $extraM = '';
        if($message != '') {
            $extraM = ' Because: ' . $message;
        }

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 7, //refused
                    'assigned' => 0
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket refused by ' . $auth['name'] . '.' . $extraM,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);

                $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket Refused by ' . $auth['name'],
                        'body' => 'Ticket Refused by ' . $auth['name'] . '<br><br>'
                                    . $extraM,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);
            }
        } 
    }

    public function requestAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $message = $this->request->getPost('message');

        $extraM = '';
        if($message != '') {
            $extraM = ' Needed: ' . $message;
        }

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 1, //request
                    'assigned' => 0
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'A request was made ' . $auth['name'] . '.' . $extraM,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);

                $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - A request was made by ' . $auth['name'],
                        'body' => 'A request was made by ' . $auth['name'] . '<br><br>'
                                    . $extraM,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);

            }
        }
    }

    public function assignAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $user = $this->request->getPost('user');

        $userName = Users::findFirst([
                    "id = :id: ",
                    "bind" => [
                        "id" => $user,
                    ]
        ]);

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 2, //assigned
                    'assigned' => $user
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket assigned to ' . $userName->username . ' by ' . $auth['name'] . '.',
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);

                $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket assigned to ' . $userName->username ,
                        'body' => 'Ticket assigned to ' . $userName->username . ' by ' . $auth['name'] . '<br><br>',
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);
            }
        }
    }

    public function ticketokAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $message = $this->request->getPost('message');

        $extraM = '';
        if($message != '') {
            $extraM = ' Comments: ' . $message;
        }

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 5, //closed
                    'assigned' => 0
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket was closed by ' . $auth['name'] . '.' . $extraM,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);

                $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket was closed ',
                        'body' => 'Ticket was closed by ' . $auth['name'] . '<br><br>'
                                    . $extraM,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);

            }
        }
    }

    public function ticketnotokAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $message = $this->request->getPost('message');

        $extraM = '';
        if($message != '') {
            $extraM = ' Because: ' . $message;
        }

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 6, //on hold
                    'assigned' => 0
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket not accepted by ' . $auth['name'] . '.' . $extraM,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
               
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);
                $user2 = Users::findFirstById($tsystem->incharge);

               $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket not accepted ',
                        'body' => 'Ticket is back to ' . $user2->username . '<br><br>'
                                    . $extraM ,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);

            }
        }
    }

    public function infosentAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $message = $this->request->getPost('message');

        $extraM = '';
        if($message != '') {
            $extraM = ' Comments: ' . $message;
        }

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 6, //on hold
                    'assigned' => 0
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Requested information was updated by ' . $auth['name'] . '.' . $extraM,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);
                $user2 = Users::findFirstById($tsystem->incharge);

               $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Requested information sent to ' . $user2->username,
                        'body' => 'Requested information was updated by ' . $auth['name'] . '<br><br>'
                                    . $extraM ,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);
            }
        }
    }

    public function closeticketAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $message = $this->request->getPost('message');

        $extraM = '';
        if($message != '') {
            $extraM = ' Comments: ' . $message;
        }

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 5, //close
                    'assigned' => 0
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket was closed by ' . $auth['name'] . '.' . $extraM,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);
                $user2 = Users::findFirstById($tsystem->incharge);

               $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket was closed by ' . $auth['name'],
                        'body' => 'Ticket was closed by ' . $auth['name'] . '<br><br>'
                                    . $extraM ,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);

            }
        }
    }

    public function reopenticketAction() {

        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $message = $this->request->getPost('message');

        $extraM = '';
        if($message != '') {
            $extraM = ' Comments: ' . $message;
        }

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 6, //on hold
                    'assigned' => 0
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket was reopen by ' . $auth['name'] . '.' . $extraM,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                
                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);
                $user2 = Users::findFirstById($tsystem->incharge);

               $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket was reopen by ' . $auth['name'],
                        'body' => 'Ticket was reopen by ' . $auth['name'] . '<br><br>'
                                    . $extraM ,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);

            }
        }
    }

    public function changeDeadlineAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $deadline = $this->request->getPost('deadline');

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'deadline' => $deadline
                );

        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
            $data_chat = array(
                    'ticket_id' => $ticket_id,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket deadline changed by ' . $auth['name'] . '. New deadline: ' . $deadline,
                    'visible' => 1
                );

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {

                $tsystem = TicketSystem::findFirstById($ticket_id);
                $user = Users::findFirstById($tsystem->requester);

                $email = array(
                        'subject' => 'Ticket_' . $tsystem->id . ' by ' . $user->username .  ' - ' . $tsystem->subject . '  - Ticket deadline changed by ' . $auth['name'],
                        'body' => 'Ticket deadline changed by ' . $auth['name'] . '<br>'
                                    . 'New deadline: ' . $deadline,
                        'ticket_id' => $tsystem->id
                    );

                $this->sendemails($email);
            }
        }
    }

    private function sendEmails($email) {
        $auth = $this->session->get('auth');

        $from = 'Ticket<tickets@mobipium.com>';

        $headers = "" .
               "Reply-To: MobisteinReport" .
               "X-Mailer: PHP/" . phpversion();
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
        $headers .= 'From: ' . $from . "\r\n";

        $tsystem = TicketSystem::findFirstById($email['ticket_id']);

        $olderusers = null;
        if(isset($email['olderincharge']) && $email['olderincharge'] != '') {
            $olderusers = $email['olderincharge'];
        }

        /* for testing 
        $urlTicket = '<br><br>/ticketsystem2/editticket_itsdes?idTicket=' . $email['ticket_id'];
        $body = $email['body'] . $urlTicket;
        $body2 = nl2br($body);
        $body3 = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n", "\\\n"), '<br/>',$body2);
        $body4 = str_replace("&#039", "'",$body3);

        $subject = str_replace("&#039", "'",$email['subject']);

        //mail('andre.vieira@mobipium.com', str_replace("\\\"" , '"', $subject),  str_replace("\\\"" , '"', $body4), $headers, '-fmobisteinreport@mobipium.com');
        */
        
        $result = $tsystem->getEmailsUsers($tsystem, $olderusers);

        $urlTicket = '';
        foreach ($result as $user) {

        	if($user['userarea'] == 5 || $user['userarea'] == 7) {
        		$urlTicket = '<br><br>/ticketsystem2/editticket_itsdes?idTicket=' . $email['ticket_id'];
        	} else {
        		$urlTicket = '<br><br>/ticketsystem2/editTicket?idTicket=' . $email['ticket_id'];
        	}

        	$body = $email['body'] . $urlTicket;
	        $body2 = nl2br($body);
	        $body3 = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n", "\\\n"), '<br/>',$body2);
	        $body4 = str_replace("&#039", "'",$body3);

	        $subject = str_replace("&#039", "'",$email['subject']);

            mail($user['email'], str_replace("\\\"" , '"', $subject),  str_replace("\\\"" , '"', $body4), $headers, '-fmobisteinreport@mobipium.com');
            //echo "email sent to " . $user['email'] . ' . ';
        
        }
		
    }
}

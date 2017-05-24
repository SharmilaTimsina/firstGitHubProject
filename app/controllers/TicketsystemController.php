<?php

class TicketsystemController extends ControllerBase {

	public function initialize()
    {
        $this->tag->setTitle('Ticket System');
        parent::initialize();
    }
	
    public function indexAction() {
		$auth = $this->session->get('auth');

        $ticket = TicketSystem::find([
                        "status != :status: ",
                        "bind" => [
                            "status" => 7,
                        ]
            ]);

        $table = array();
        foreach ($ticket as $value) {
           if($auth['navtype'] == 1 || $auth['id'] == $value->requester || in_array($auth['id'], explode(',', $value->users_area)) || $value->incharge == $auth['id']) {
                
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

                $userI = '';
                if(isset($userIncharged->username)) {
                    if($userIncharged->username != null) {
                        $userI = $userIncharged->username;
                    } else {
                        $userI = '';
                    }
                }

                array_push($table, array(
                                'id' => $value->id,
                                'requester' => $userRequester->username,
                                'subject' => $value->subject, 
                                'incharge' => $userI, 
                                'priority' => $value->priority, 
                                'type' => $value->type, 
                                'status' => $value->status, 
                                'created_at' => $value->creation_date
                            ));                  
            } 
        }

        $this->view->setVar('ticketTable', json_encode($table));
    }

    public function setFilterAction() {
        $auth = $this->session->get('auth');

        $priority = $this->request->getPost('priority');
        $type = $this->request->getPost('type');
        $status = $this->request->getPost('status');

        $ticketS = new TicketSystem();
        $ticket = $ticketS->getTickets($priority, $type, $status);

        $table = array();
        foreach ($ticket as $value) {
           if($auth['navtype'] == 1 || $auth['id'] == $value['requester'] || in_array($auth['id'], explode(',', $value['users_area']))) {
                
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
                        $userI = '';
                    }
                }

                array_push($table, array(
                                'id' => $value['id'],
                                'requester' => $userRequester->username,
                                'subject' => $value['subject'], 
                                'incharge' => $userI, 
                                'priority' => $value['priority'], 
                                'type' => $value['type'], 
                                'status' => $value['status'], 
                                'created_at' => $value['creation_date']
                            ));                  
            } 
        }

        echo json_encode($table);

    }

    public function editTicketAction() {
        
        $ticket_id = $this->request->get('idTicket');

        $auth = $this->session->get('auth');

        $ticket = TicketSystem::findFirst([
                    "id = :id: ",
                    "bind" => [
                        "id" => $ticket_id,
                    ],
        ]);

        $info = $this->getTicketInfo($ticket_id);
        if($auth['navtype'] == 1 || $auth['id'] == $ticket->requester || in_array($auth['id'], explode(',', $ticket->users_area)) || $ticket->incharge == $auth['id']) {
            $this->view->setVar('ticketInfo', $info[0]);
            $this->view->setVar('ticketFiles', $this->getTicketFiles($ticket_id));
            $this->view->setVar('ticketChat', trim(preg_replace('/[\n\r]/', '\\n', $this->getTicketChat($ticket_id))));
            $this->view->setVar('details', $info[1]);
            $this->view->setVar('downloadExcel', 'http://mobisteinreport.com/ticketsystem/downloadExcel?idTicket=' . $ticket_id);
            $this->view->setVar('authID', $auth['id']);
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
            $this->sendExcel($array_chat, $columns, $title, 0);
            
        } else {
            return "error";
        }
    }

    private function sendExcel($data, $columns, $title, $format) {
                
        try {
            $temp = tmpfile();
            $exColumns = "sep=|\n";
            //$exColumns = "";
            for ($i = 0; $i < sizeof($columns); $i++) {
                $exColumns .= $columns[$i] . '|';
            }
            fwrite($temp, $exColumns . "\n");


            for ($j = 0; $j < sizeof($data); $j++) {
                $resultRow = '';
                foreach ($data[$j] as $k => $v) {
                    if($format == 0) {
                        $resultRow .= '="' . $data[$j][$k] . '"|';
                    } else {
                        $resultRow .= $data[$j][$k] . '|';
                    }
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

    private function getTicketChat($ticket_id) {
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

                return [json_encode(array(
                        'subject' => $ticket->subject,
                        'areas' => $ticket->areas,
                        'users_area' => $ticket->users_area,
                        'type' => $ticket->type,
                        'priority' => $ticket->priority,
                        'required_period' => $ticket->required_period,
                        'requester' => $user->username,
                        'requesterid' => $ticket->requester,
                        'creation_date' => $ticket->creation_date,
                        'status' => $ticket->status,
                        'assigned' => $ticket->assigned, 
                        'incharge' => $userIncharged->username, 
                        'incharge_id' => $ticket->incharge,
                        'incharged_at' => $ticket->incharged_at,
                        'id_ticket' => $ticket->id,
                        'can_edit' => $this->canEdit($ticket->incharge, $ticket->requester, $ticket->status),
                        'can_close' => $this->canClose($ticket->requester, $ticket->status),
                        'can_pick' => $this->canPick($ticket->status, $ticket->assigned, $ticket->users_area, $ticket->requester, $ticket->incharge),
                        'can_reopen' => $this->canReopen($ticket->status, $ticket->requester),
                        'is_incharge' => ($this->session->get('auth')['id'] == $ticket->incharge) ? true : false
                    )), $ticket->details];
            } else {
                return "Ticket not found!";
            }
        } else {
            return "error";
        }
    }

    public function canEdit($incharge, $requester, $status) {
        $auth = $this->session->get('auth');
        if((($incharge == $auth['id']) || $auth['id'] == $requester) && $status != 7)
            return true;

        return false;
    }

    public function canClose($requester, $status) {
        $auth = $this->session->get('auth');
        if(($requester == $auth['id']) && $status != 7)
            return true;

        return false;
    }

    public function canPick($status, $assigned, $userarea, $requester, $incharge) {
        $userArea = explode(',' , $userarea);
        $auth = $this->session->get('auth');
        
        if($assigned == 0 || $assigned == '' || $assigned == null) {
            if(($status == 1 && (in_array($auth['id'], $userArea)) || $auth['id'] == $requester) && $auth['id'] != $incharge)
                return true;
        } else {
            if($assigned == $auth['id']) {
                return true;  
            }
        }

        return false;
    }

    public function canReopen($status, $requester) {
        $auth = $this->session->get('auth');
        if($status == 7 && $requester == $auth['id']) {
            return true;
        } 

        return false;
    }

    public function createTicketAction() {
        $auth = $this->session->get('auth');
        $this->view->setVar('authID', $auth['id']);
    }

    public function create_ticketAction() {
        $auth = $this->session->get('auth');
        $date = date('Y-m-d');
        $data = array(
                    'subject' => $this->request->getPost('subject'),
                    'details' => $this->request->getPost('details'),
                    'areas' => $this->request->getPost('areas'),
                    'users_area' => $this->request->getPost('users'),
                    'type' => $this->request->getPost('type'),
                    'priority' => $this->request->getPost('priority'),
                    'required_period' => $this->request->getPost('requiredDate'),
                    'requester' => $auth['id'],
                    'creation_date' => $date,
                    'status' => '1', 
                    'incharge' => $auth['id'],
                    'incharged_at' => $date
                );

        $tsystem = new TicketSystem();
        if($tsystem->save($data) == false) {
            foreach ($tsystem->getMessages() as $message) {
                echo $message, "\n";
            }
            return;                 
        } else {
        
            $id_ticket = $tsystem->id;

            if ($this->request->hasFiles() == true) {
                
                foreach ($this->request->getUploadedFiles() as $file) {

                    $baseLocation = '../files/tickets/' . $id_ticket;
                    if (!is_dir($baseLocation)) {
                        mkdir($baseLocation , 0777, true);
                    }
                    $dir = $baseLocation . "/" . $file->getName() . "_" . $date . ".zip";
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
                    foreach ($tsystemfiles->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    return;  
                } else {
                    $data_chat = array(
                        'ticket_id' => $id_ticket,
                        'message_date' => date('Y-m-d H:i'),
                        'sender' => 'System',
                        'message' => 'File upload: ' . $file->getName() . ' by ' . $auth['name']
                    );

                    $this->sendEmails($id_ticket, 'File upload: ' . $file->getName() . ' by ' . $auth['name']);

                    $tsystemchat = new TicketChat(); 
                    if($tsystemchat->save($data_chat) == false) {
                        foreach ($tsystemfiles->getMessages() as $message) {
                            echo $message, "\n";
                        }
                        return;  
                    }
                }
            }

            $data_chat = array(
                    'ticket_id' => $id_ticket,
                    'message_date' => date('Y-m-d H:i'),
                    'sender' => 'System',
                    'message' => 'Ticket ' . $id_ticket . ' created by ' . $auth['name']
                );

            $this->sendEmails($id_ticket, 'Ticket ' . $id_ticket . ' created by ' . $auth['name']);

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                echo 'Complete!';
            }
        }
    }

    public function refreshChatAction() {
        $id_ticket = $this->request->getPost('id_ticket');
        echo $this->getTicketChat($id_ticket);
    }

    public function sendMessageAction() {
        $message = $this->request->getPost('message');
        $id_ticket = $this->request->getPost('id_ticket');

        $data_chat = array(
                'ticket_id' => $id_ticket,
                'message_date' => date('Y-m-d H:i'),
                'sender' => $this->session->get('auth')['id'],
                'message' => $message
            );

        $this->sendEmails($id_ticket, 'New message from ' . $this->session->get('auth')['name']);

        $tsystemchat = new TicketChat(); 
        if($tsystemchat->save($data_chat) == false) {
            foreach ($tsystemchat->getMessages() as $message) {
                echo $message, "\n";
            }
            return;  
        } else {
            echo $this->getTicketChat($id_ticket);
        }
    }

    public function getUsersMultipleAction() {
        $auth = $this->session->get('auth');
        $areas = $this->request->get('area');

        $tsystem = new TicketSystem();
        $result = $tsystem->getUsersByArea($areas);
        
        $users = array();

        $areas = explode(',', $areas);

        foreach ($result as $key => $value) {
            $array_users_admin = array(2,3,4,5,13,17,19, 28, 1, 20, 6);
            $array_users_affs = array(13,17);
            $array_users_mains = array(19);
            $array_users_adult = array(2,3,4);
            $array_users_sales = array(5);
            $array_users_account = array(28);
            $array_users_it = array(1, 20, 6);
            $array_users_designers = array(35);
            if(in_array($value['id'], $array_users_admin) ) {
                if(in_array(8, $areas) && in_array($value['id'], $array_users_affs)) {
                    array_push($users, array('id' => $value['id'],
                        'name' => $value['username'],
                        'option' => ($auth['id'] == $value['id']) ? '' : '',
                        'area' => 8
                    ));
                } else if(in_array(2, $areas) && in_array($value['id'], $array_users_mains)) {
                    array_push($users, array('id' => $value['id'],
                        'name' => $value['username'],
                        'option' => ($auth['id'] == $value['id']) ? '' : '',
                        'area' => 2
                    ));
                } else if(in_array(4, $areas) && in_array($value['id'], $array_users_adult)) {
                    array_push($users, array('id' => $value['id'],
                        'name' => $value['username'],
                        'option' => ($auth['id'] == $value['id']) ? '' : '',
                        'area' => 4
                    ));
                } else if(in_array(3, $areas) && in_array($value['id'], $array_users_sales)) {
                    array_push($users, array('id' => $value['id'],
                        'name' => $value['username'],
                        'option' => ($auth['id'] == $value['id']) ? '' : '',
                        'area' => 3
                    ));
                } else if(in_array(5, $areas) && in_array($value['id'], $array_users_account)) {
                    array_push($users, array('id' => $value['id'],
                        'name' => $value['username'],
                        'option' => ($auth['id'] == $value['id']) ? '' : '',
                        'area' => 5
                    ));
                } else if(in_array(1, $areas) && in_array($value['id'], $array_users_it)) {
                    array_push($users, array('id' => $value['id'],
                        'name' => $value['username'],
                        'option' => ($auth['id'] == $value['id']) ? '' : '',
                        'area' => 1
                    ));
                } else if(in_array(7, $areas) && in_array($value['id'], $array_users_designers)) {
                    array_push($users, array('id' => $value['id'],
                        'name' => $value['username'],
                        'option' => ($auth['id'] == $value['id']) ? '' : '',
                        'area' => 7
                    ));
                }
            } else {
                array_push($users, array('id' => $value['id'],
                    'name' => $value['username'],
                    'option' => ($auth['id'] == $value['id']) ? '' : ''
                ));
            }
        }

        echo json_encode(array('users' => $users));
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
                $dir = $baseLocation . "/" . $file->getName() . "_" . $date . ".zip";
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
                    'message' => 'File upload: ' . $file->getName()  . ' by ' . $auth['name']
                );

                $this->sendEmails($id_ticket, 'File upload: ' . $file->getName()  . ' by ' . $auth['name']);

                $tsystemchat = new TicketChat(); 
                if($tsystemchat->save($data_chat) == false) {
                    foreach ($tsystemfiles->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    return;  
                } else {
                    //echo "Upload success!";
                    echo $this->getTicketFiles($id_ticket);
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

        $data = array(
                    'subject' => $this->request->getPost('subject'),
                    'details' => $this->request->getPost('details'),
                    'areas' => $this->request->getPost('areas'),
                    'users_area' => $this->request->getPost('users'),
                    'type' => $this->request->getPost('type'),
                    'priority' => $this->request->getPost('priority'),
                    'required_period' => $this->request->getPost('requiredDate'),
                    'status' => ($this->request->getPost('status') != '') ? $this->request->getPost('status') : '5', //waiting incharge person
                    'assigned' => $this->request->getPost('assigned')
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
                    'message' => 'Ticket ' . $id_ticket . ' edited at ' . date('Y-m-d H:i')  . ' by ' . $auth['name']
                );

            $this->sendEmails($id_ticket, 'Ticket ' . $id_ticket . ' edited at ' . date('Y-m-d H:i')  . ' by ' . $auth['name']);

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            } else {
                echo json_encode(array(
                        'chat' => $this->getTicketChat($ticket_id),
                        'files' => $this->getTicketFiles($ticket_id)
                    ));
            }
        }
    }

    public function pickTicketAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'incharge' => $auth['id'],
                    'status' => 0, //waiting incharge person
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
                    'message' => 'Ticket picked by ' . $auth['name']
                );

            $this->sendEmails($ticket_id, 'Ticket picked by ' . $auth['name']);

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            }
        }
    }

    public function closeTicketAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $date = date('Y-m-d');

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 7, //closed
                    'assigned' => 0,
                    'incharge' => $auth['id'],
                    'incharged_at' => '',
                    'closed_at' => $date
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
                    'message' => 'Ticket closed by ' . $auth['name']
                );

            $this->sendEmails($ticket_id, 'Ticket closed by ' . $auth['name']);

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            }
        }
    }

    public function reopenTicketAction() {
        $auth = $this->session->get('auth');
        $ticket_id = $this->request->getPost('id_ticket');
        $date = date('Y-m-d');

        $tsystem = TicketSystem::findFirstById($ticket_id);

        $data = array(
                    'status' => 5, //closed
                    'assigned' => 0,
                    'incharge' => $auth['id'],
                    'incharged_at' => date('Y-m-d'),
                    'closed_at' => '',
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
                    'message' => 'Ticket reopen by ' . $auth['name']
                );

            $this->sendEmails($ticket_id, 'Ticket reopen by ' . $auth['name']);

            $tsystemchat = new TicketChat(); 
            if($tsystemchat->save($data_chat) == false) {
                foreach ($tsystemchat->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;  
            }
        }
    }

    private function sendEmails($ticket_id, $message) {
        $auth = $this->session->get('auth');

        $from = 'Ticket<tickets@mobipium.com>';

        $headers = "" .
               "Reply-To: MobisteinReport" .
               "X-Mailer: PHP/" . phpversion();
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
        $headers .= 'From: ' . $from . "\r\n";


        $tsystem = TicketSystem::findFirstById($ticket_id);

        $result = $tsystem->getEmailsByUser($tsystem->users_area);

        foreach ($result as $user) {
            mail($user['email'], 'Ticket_' . $ticket_id, $message, $headers, '-fmobisteinreport@mobipium.com');
        }
        
        mail($auth['email'], 'Ticket_' . $ticket_id, $message, $headers, '-fmobisteinreport@mobipium.com');
    }
}

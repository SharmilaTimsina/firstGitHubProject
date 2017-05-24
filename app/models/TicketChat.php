<?php

use Phalcon\Mvc\Model;

class TicketChat extends Model {

    public function initialize() {
        $this->setConnectionService('db4');
    }

    public function getSource() {
        return "ticketsystem2__chat";
    }

}

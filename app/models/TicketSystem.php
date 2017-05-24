<?php

use Phalcon\Mvc\Model;

class TicketSystem extends Model {

    public function initialize() {
        $this->setConnectionService('db4');
    }

    public function getSource() {
        return "ticketsystem2__tickets";
    }

    public function getUsersByArea($areas) {

        try {
            $statement = $this->getDi()->getDb4()->prepare("SELECT id, username, navtype as area FROM users WHERE navtype IN ('1', " . $this->filterToString($areas) . ")");

            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());

            $result = $exe->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getEmailsByUser($users) {
        try {

            $u = $this->filterToString($users);

            $statement = $this->getDi()->getDb4()->prepare("SELECT id, username, email FROM users WHERE id IN ($u)");

            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());

            $result = $exe->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }

    /* -------------------------------------------------- */

    public function getUsers($auth) {
        try {
            $statement = $this->getDi()->getDb4()->prepare("SELECT id, fullname, email FROM users WHERE id NOT IN ($auth) AND userarea NOT IN (5,7) AND id NOT IN (8,11,12,13,15,16,17,18,19,22,27,34)");

            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());

            $result = $exe->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }

    private function filterToString($value) {
        $values = explode(',', $value);
        $valuesString = '';
        foreach ($values as $value) {
            $valuesString .= "'" . $value . "',";
        }
        $valuesString = rtrim($valuesString, ",");

        return $valuesString;
    }

    public function getTicketsByFilter($priority, $type, $status) {

        $sql = array();
        if ($priority != '') {
            array_push($sql, "priority IN (" . $this->filterToString($priority) . ")");
        }

        if ($type != '') {
            array_push($sql, "type IN (" . $this->filterToString($type) . ")");
        }

        if ($status != '') {
            array_push($sql, "status IN (" . $this->filterToString($status) . ")");
        }


        if (sizeof($sql) != 0)
            $sqlS = ' WHERE ' . implode(' AND ', $sql);
        else
            $sqlS = '';

        try {
            $sql = "SELECT `id`, `requester`, `subject`, `usersView`, `users_area`, `type`, `priority`, `status`, `assigned`, `incharge`, `required_period`, `creation_date`, `closed_at`, `incharged_at`, `deadline` FROM ticketsystem2__tickets" . $sqlS;
            $statement = $this->getDi()->getDb4()->prepare($sql);
            //mail('pedrorleonardo@gmail.com', 'sql', $sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());

            $result = $exe->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }

    function getEmailsUsers($tsystem, $olderusers) {
        try {

            $u = $this->filterToString($tsystem->usersView);

            if ($tsystem->incharge == null && $tsystem->incharge == '' && $tsystem->assigned == null && $tsystem->assigned == '') {
                $u .= ',' . $this->filterToString($tsystem->users_area);
                $u .= ',' . $this->filterToString($tsystem->requester);
            } else {
                if ($tsystem->incharge != null && $tsystem->incharge != '') {
                    $u .= ',' . $this->filterToString($tsystem->incharge);
                }

                if ($tsystem->assigned != null && $tsystem->assigned != '') {
                    $u .= ',' . $this->filterToString($tsystem->assigned);
                }
            }

            if ($olderusers != '' && $olderusers != null) {
                $u .= ',' . $this->filterToString($olderusers);
            }

            $u .= ',' . $this->filterToString($tsystem->requester);

            $statement = $this->getDi()->getDb4()->prepare("SELECT id, username, email, userarea FROM users WHERE id IN ($u)");

            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());

            $result = $exe->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }

}

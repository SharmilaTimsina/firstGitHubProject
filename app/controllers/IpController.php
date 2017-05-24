<?php

class IpController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('IP Manager');
        parent::initialize();
    }

    public function indexAction() {
        try {
            $sql2 = 'SELECT country FROM OpActiveCountries WHERE status = 1 ORDER BY country ASC; ';
            $return2 = $this->getDi()->getDb4()->fetchAll($sql2, Phalcon\Db::FETCH_ASSOC, array());

            if (empty($return2)) {
                echo '';
                return;
            }
            $res = '';
            foreach ($return2 as $country) {
                $res .= '<option value="' . $country['country'] . '">' . $country['country'] . '</option>';
            }
            $this->view->setVar('countries', $res);
        } catch (Exception $e) {

        }
    }

    public function getIpsAction() {
        ini_set("memory_limit", "3000M");
        $country = $this->request->get('cc');


        $ipM = new IP();
        $result = $ipM->getIps($country);

        $title = "IPs_" . $country;
        $columns = array("lowerip", "upperip", "lip", "uip", "countrycode", "carrier");
        $this->sendExcel($result, $columns, $title);
    }

    private function sendExcel($data, $columns, $title) {
        ini_set("memory_limit", "2048M");

        try {
            $temp = tmpfile();
            $exColumns = "sep=;\n";
            for ($i = 0; $i < sizeof($columns); $i++) {
                $exColumns .= $columns[$i] . ';';
            }
            fwrite($temp, $exColumns . "\n");


            for ($j = 0; $j < sizeof($data); $j++) {
                $resultRow = '';
                foreach ($data[$j] as $k => $v) {
                    $resultRow .= $data[$j][$k] . ";";
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

    public function getCustomIPsAction() {
        try {
            if ($this->request->hasFiles() == true && $this->request->getPost('carrier') != null && $this->request->getPost('country') != null) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    $filename = $file->getTempName();
                    $csvAsArray = array_map('str_getcsv', file($filename));

                    $array_status = array();
                    if ($csvAsArray[0][0] == 'ipcidr') {
                        $ipcidr = true;
                    } else if ($csvAsArray[0][0] == 'lowerip' && $csvAsArray[0][1] = 'upperip') {
                        $iprange = true;
                    } else {
                        echo 'File missing Headers. ipcidr or lowerip;upperip';
                        return;
                    }
                    $carrier = $this->request->getPost('carrier');
                    $country = strtoupper($this->request->getPost('country'));
                    if (strlen($country) != 2) {
                        echo 'Country is wrong';
                        return;
                    }

                    if (isset($ipcidr)) {
                        $string = $this->generateipcidrstring(array_slice($csvAsArray, 1));
                        $parseips = new ParseIPs($country, $string, null, $carrier);
                    } else {
                        $string = $this->generateiprangesstring(array_slice($csvAsArray, 1));
                        $parseips = new ParseIPs($country, null, $string, $carrier);
                    }
                    $insertquery = $parseips->finalres();
                    try {
                        $this->getDi()->getDb4()->execute($insertquery);
                    } catch (Exception $ex) {
                        echo "COULD NOT INSERT NEW IPS, contact IT Team\n";
                        echo $ex->getMessage();
                        return;
                    }
                    echo '0';
                    return;
                    break;
                }
            } else {
                echo 'please upload a correct file';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getclientipsAction() {
        try {
            if ($this->request->get('country') == null || $this->request->get('carrier') == null) {
                echo '1';
                return;
            }
            $sql2 = 'SELECT lip as lowerip, uip as upperip, op as carrier FROM OpRanges WHERE country = "' . $this->request->get('country') . '"';
            $return2 = $this->getDi()->getDb4()->fetchAll($sql2, Phalcon\Db::FETCH_ASSOC, array());

            if (empty($return2)) {
                echo 'No IPs For selected Country / Carrier';
                return;
            }
            $title = "CustomIPs_" . $this->request->get('country');
            $columns = array("lowerip", "upperip", "carrier");
            $this->sendExcel($return2, $columns, $title);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getcarriersAction() {
        try {
            if ($this->request->get('country') != null) {
                $sql2 = 'SELECT UPPER(op) as op FROM OpRanges WHERE country = "' . strtoupper($this->request->get('country')) . '" GROUP BY op';
                $return2 = $this->getDi()->getDb4()->fetchAll($sql2, Phalcon\Db::FETCH_ASSOC, array());
                if (!empty($return2)) {
                    echo '<option disabled selected>Carrier</option>';
                    foreach ($return2 as $val) {
                        echo '<option value="' . $val['op'] . '">' . $val['op'] . '</option>';
                    }
                }
                return;
            } else {
                echo '0';
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function generateipcidrstring($array) {
        try {
            $string = '';

            foreach ($array as $value) {
                $string .= str_replace(' ', '', $value[0]) . ',';
            }
            return rtrim($string, ',');
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function generateiprangesstring($array) {
        try {
            $string = '';

            foreach ($array as $value) {
                $string .= str_replace(' ', '', $value[0]) . '-' . str_replace(' ', '', $value[1]) . ',';
            }

            return rtrim($string, ',');
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function addNewCarrierAction() {
        try {
            if ($this->request->get('country') != null && $this->request->get('country') != 'null' && $this->request->get('country') != 'undefined' && $this->request->get('carrier') != null && $this->request->get('carrier') != 'null' && $this->request->get('carrier') != 'undefined') {
                if (strlen($this->request->get('country')) != 2) {
                    echo 'wrong country';
                    return;
                }
                $connection = $this->getDi()->getDb4();
                $sql = 'INSERT INTO OpRanges (lrange,urange,lip,uip,op,country) VALUES (1,1,1,1,:carrier,"' . strtoupper($this->request->get('country')) . '")';
                $statement = $connection->prepare($sql);
                $connection->executePrepared($statement, array('carrier' => strtolower($this->request->get('carrier'))), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                echo 0;
                return;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function addNewCountryAction() {
        try {
            $this->view->disable();

            if ($this->request->get('country') != null && $this->request->get('country') != 'null' && $this->request->get('country') != 'undefined' && $this->request->get('carrier') != null && $this->request->get('carrier') != 'null' && $this->request->get('carrier') != 'undefined') {
                if (strlen($this->request->get('country')) != 2) {
                    echo 'wrong country';
                    return;
                }
                $sql = $this->getDi()->getDb4()->prepare('SELECT country FROM OpRanges WHERE country LIKE :countryarg LIMIT 1; ');
                $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('countryarg' => ($this->request->get('country'))), array(\Phalcon\Db\Column::TYPE_CHAR));
                $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($array_ret)) {
                    echo 'Country Already exists';
                    return;
                }
                $sql2 = $this->getDi()->getDb4()->prepare('SELECT id FROM Countries WHERE id LIKE :country LIMIT 1 ');
                $prepared2 = $this->getDi()->getDb4()->executePrepared($sql2, array('country' => ($this->request->get('country'))), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $c = $prepared2->fetchAll(PDO::FETCH_ASSOC);
                if (empty($c)) {
                    echo 'No country found for that countrycode';
                    exit();
                }
                $sql = 'INSERT INTO OpRanges (lrange,urange,lip,uip,op,country) VALUES (1,1,1,1,:carrier,"' . strtoupper($this->request->get('country')) . '")';
                $statement = $this->getDi()->getDb4()->prepare($sql);
                $this->getDi()->getDb4()->executePrepared($statement, array('carrier' => strtolower($this->request->get('carrier'))), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $sql = 'INSERT INTO OpActiveCountries (country,status) VALUES (:countryy,1)';
                $statement = $this->getDi()->getDb4()->prepare($sql);
                $this->getDi()->getDb4()->executePrepared($statement, array('countryy' => strtoupper($this->request->get('country'))), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                echo 0;
                return;
            } else {
                echo 'params missing';
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return;
        }
    }

    public function testphalconshitAction() {
        try {
            $connection = $this->getDi()->getDb();
            $sql = 'INSERT INTO testingstuff (inteironormal,caracteresrandom) VALUES (2,"lalala"); ';
            $connection->begin();
            $this->insertRow($connection, $sql);
            $manager = new \Phalcon\Mvc\Model\Transaction\Manager();
            $transaction = $manager->get();
            $cat = new Categories();
            $cat->setTransaction($transaction);
            $this->anothermethodthatwillfail($cat, $transaction);
            $connection->commit();
            echo 'bu';
            $transaction->commit();
        } catch (Exception $ex) {
            if (isset($connection))
                $connection->rollback();
            if (isset($transaction))
                $transaction->rollback('pimbasss');
            echo $ex->getMessage() . ' byebyes';
        }
    }

    private function insertRow($connection, $sql) {
        try {
            $connection->execute($sql);
            return;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function anothermethodthatwillfail($cat, $transaction) {
        try {

            $cat->name = 'bunga';
            $cat->campaignType = 10;
            if ($cat->save() == false) {
                foreach ($cat->getMessages() as $mes)
                    echo $mes;
            }
            throw new Exception('VOU PARAR');
        } catch (Exception $ex) {

            throw $ex;
        }
    }

}

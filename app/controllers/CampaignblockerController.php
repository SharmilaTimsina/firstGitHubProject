<?php

class CampaignblockerController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Campaign Blocker');
        parent::initialize();
        date_default_timezone_set('Europe/Lisbon');
    }

    public function indexAction() {
        $bs = Blocker::find(array('(userid = ' . $this->session->get('auth')['id'] . ' OR ' . $this->session->get('auth')['userlevel'] . ' < 2) AND status = 0'));
        $backupstable = '';
        if (!empty($bs)) {
            foreach ($bs as $b) {
                $backupstable .= '<tr>
                        <td>' . date('Y-m-d H:i:s', strtotime($b->insertTimestamp)) . '</td>
                        <td>' . $b->backupname . '</td>
                        <td>' . $b->backupdescription . '</td>
                        <td>' . $b->campaign . '</td>
                        <td class="iconEdit"><img id="' . $b->backuphash . '" class="modalIcon" src="/img/reverse.png" ></a></td>
                    </tr>';
            }
        }
        $sql = 'SELECT nb.backuptime as backuptime,n.offerhash as offerhash,n.offername as offername FROM njumpsbackup nb inner join njumps n ON n.id = nb.id GROUP BY backuptime, n.offername ORDER BY backuptime DESC';
        $statement2 = $this->getDi()->getDb4()->prepare($sql);
        $exe = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
        $aaa = $exe->fetchAll(PDO::FETCH_ASSOC);
        $newbackuptable = '';
        if (!empty($aaa)) {
            foreach ($aaa as $row) {
                $newbackuptable .= '<tr id="' . $row['offername'] . '">
                        <td>' . $row['offername'] . '</td>
                        <td>' . $row['backuptime'] . '</td>
                        <td class="iconEdit"><img  backuptime="' . $row['backuptime'] . '" offername = "' . $row['offername'] . '" class="modalIcon2 temprestore" src="/img/reverse.png" ></a></td>
                    </tr>';
            }
        }
        $aggres = $this->getAggregators();
        $this->view->setVar('tableTempBlocker', $newbackuptable);
        $this->view->setVar('tableBackups', $backupstable);
        $this->view->setVar('aggregatorsList', $aggres);
        $this->view->setVar('aggregatorsList', $aggres);
    }

    public function blockbynameAction() {
        try {
            $offersname = $this->request->get('offersname');
            if (empty($offersname) || $offersname == 'null' || $offersname == 'undefined') {
                echo 0;
                return;
            }
            $offersname = trim($offersname);
            $offers = explode(',', $offersname);
            $date = date('Y-m-d H:i:s');
            $db = $this->getDi()->getDb4();
            foreach ($offers as $littleoffer) {
                $insertsql = 'INSERT INTO njumpsbackup (id, proportion, backuptime) SELECT id,proportion,"' . $date . '" FROM njumps WHERE offername LIKE "' . $littleoffer . '" AND proportion > 0 AND deleted = 0';
                $db->query($insertsql);
                $update = 'UPDATE njumps SET proportion = 0 WHERE offername LIKE "' . $littleoffer . '" AND deleted = 0;';
                $db->query($update);
            }
            $sql = 'SELECT nb.backuptime as backuptime,n.offerhash as offerhash,n.offername as offername FROM njumpsbackup nb inner join njumps n ON n.id = nb.id GROUP BY backuptime, n.offername ORDER BY backuptime DESC';
            $statement2 = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
            $aaa = $exe->fetchAll(PDO::FETCH_ASSOC);
            $newbackuptable = '';
            if (!empty($aaa)) {
                foreach ($aaa as $row) {
                    $newbackuptable .= '<tr id="' . $row['offername'] . '">
                        <td>' . $row['offername'] . '</td>
                        <td>' . $row['backuptime'] . '</td>
                        <td class="iconEdit"><img  backuptime="' . $row['backuptime'] . '" offername = "' . $row['offername'] . '" class="modalIcon2 temprestore" src="/img/reverse.png" ></a></td>
                    </tr>';
                }
            }
            echo $newbackuptable;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__, $ex->getMessage());
            echo '1';
        }
    }

    public function executetemprestoreAction() {
        try {
            $offername = $this->request->get('offername');
            $backuptime = $this->request->get('backuptime');
            if (empty($offername) || $offername == 'null' || $offername == 'undefined' || empty($backuptime) || $backuptime == 'null' || $backuptime == 'undefined') {
                echo 0;
                return;
            }
            $db = $this->getDi()->getDb4();
            $update = 'UPDATE njumps n inner join njumpsbackup nb ON nb.id = n.id AND n.offername = "' . $offername . '" AND n.deleted = 0 AND nb.backuptime = "' . $backuptime . '" SET n.proportion = nb.proportion WHERE n.deleted = 0;';
            $db->query($update);
            //mail('pedrorleonardo@gmail.com', 'sql', $update);
            $sql = 'SELECT nb.backuptime as backuptime,n.offerhash as offerhash,n.offername as offername FROM njumpsbackup nb inner join njumps n ON n.id = nb.id GROUP BY backuptime, n.offername ORDER BY backuptime DESC';
            $statement2 = $db->prepare($sql);
            $exe = $db->executePrepared($statement2, array(), array());
            $aaa = $exe->fetchAll(PDO::FETCH_ASSOC);
            $newbackuptable = '';
            if (!empty($aaa)) {
                foreach ($aaa as $row) {
                    $newbackuptable .= '<tr id="' . $row['offername'] . '">
                        <td>' . $row['offername'] . '</td>
                        <td>' . $row['backuptime'] . '</td>
                        <td class="iconEdit"><img  backuptime="' . $row['backuptime'] . '" offername = "' . $row['offername'] . '" class="modalIcon2 temprestore" src="/img/reverse.png" ></a></td>
                    </tr>';
                }
            }
            echo $newbackuptable;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__, $ex->getMessage());
            echo '1';
        }
    }

    public function getcampaignsAction() {
        try {
            if ($this->request->get('aggID') != null) {
                $filter = '';
                if ($this->session->get('auth')['userlevel'] > 1) {
                    $filter = ' AND aff_flag IN (4,' . $this->session->get('auth')['utype'] . ') ';
                }
                $jumps = Jump::find(array('agregator = ' . $this->request->get('aggID') . $filter, "order" => "campaign"));
                if (empty($jumps)) {
                    echo 0;
                    return;
                }
                $arr = array();
                $i = 0;
                foreach ($jumps as $jump) {
                    $arr[$i]['hash'] = $jump->hash;
                    $arr[$i]['name'] = $jump->campaign;
                    $i++;
                }
                echo json_encode($arr);
            } else
                echo 1;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__, $ex->getMessage());
            echo '1';
        }
    }

    public function getAffectedAction() {
        try {
            if ($this->request->getPost('campaignName') == null) {
                echo 0;
                return;
            } else {
                $jump = Jump::findFirst(array('campaign = "' . $this->request->getPost('campaignName') . '" '));
                if (empty($jump) || empty($jump->hash)) {
                    echo 'campaign not found';
                    return;
                }
                $b = new Blocker();
                //njumps, mjumps counter
                $arr = $b->checkAffectedRows($jump->hash);
                //echo $jump->hash;
                $tablenjumps = '';
                $tablemjumps = '';
                if (empty($arr[0])) {
                    $njumpcounter = 0;
                } else {
                    $njumpcounter = $arr[0][0]['totalAffected'];
                }
                if (empty($arr[1])) {
                    $mjumpcounter = 0;
                } else {
                    $mjumpcounter = $arr[1][0]['totalAffected'];
                }
                if ($njumpcounter > 0) {
                    foreach ($arr[2] as $njumpname) {
                        $tablenjumps .= '<tr>'
                                . '<td>' . $njumpname['njumpName'] . '</td>'
                                . '</tr>';
                    }
                }
                if ($mjumpcounter > 0) {
                    foreach ($arr[3] as $mjumpname) {
                        $tablemjumps .= '<tr>'
                                . '<td>' . $mjumpname['mjumpName'] . '</td>'
                                . '</tr>';
                    }
                }

                echo json_encode(array(
                    'njumpstotal' => $njumpcounter,
                    'mjumpstotal' => $mjumpcounter,
                    'njumpstable' => $tablenjumps,
                    'mjumpstable' => $tablemjumps));
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__, $ex->getMessage());
        }
    }

    public function executeblockAction() {
        try {
            if ($this->request->getPost('campaign') == null ||
                    $this->request->getPost('backupname') == null ||
                    $this->request->getPost('backupdescription') == null) {
                echo 0;
                return;
            }
            $campaign = $this->request->getPost('campaign');
            $backupname = $this->request->getPost('backupname');
            $backupdescription = $this->request->getPost('backupdescription');
            $backuphash = uniqid($this->session->get('auth')['id'] . '-');

            $jump = Jump::findFirst(array('campaign = "' . $campaign . '" '));
            if (empty($jump) || empty($jump->hash)) {
                echo 'campaign not found';
                return;
            }
            $b = new Blocker();
            $result = $b->executeBackup($jump->hash, $backupname, $backupdescription, $backuphash, $campaign, $this->session->get('auth')['id']);
            $njumptable = '';
            $mjumptable = '';
            $backupstable = '';
            if (!empty($result[0])) {
                foreach ($result[0] as $row) {
                    $njumptable .= '<tr>'
                            . '<td>' . $row['lpName'] . '</td>'
                            . '</tr>';
                }
            }

            if (!empty($result[1])) {
                foreach ($result[1] as $row) {
                    $mjumptable .= '<tr>'
                            . '<td>' . $row['lpName'] . '</td>'
                            . '</tr>';
                }
            }
            if (!empty($result[2])) {
                foreach ($result[2] as $row) {
                    $backupstable .= '<tr>'
                            . '<td>' . date('Y-m-d H:i:s', strtotime($row['insertTimestamp'])) . '</td>'
                            . '<td>' . $row['backupname'] . '</td>'
                            . '<td>' . $row['backupdescription'] . '</td>'
                            . '<td>' . $row['campaign'] . '</td>'
                            . '<td class="iconEdit"><img id="' . $row['backuphash'] . '" class="modalIcon" src="/img/reverse.png" ></a></td>'
                            . '</tr>';
                }
            }
            echo json_encode(array('njumpstable' => $njumptable,
                'mjumpstable' => $mjumptable, 'backupstable' => $backupstable));
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__, $ex->getMessage());
            echo '0';
        }
    }

    public function executerestoreAction() {
        try {
            if ($this->request->get('backuphash') == null) {
                echo 0;
                return;
            }
            $backuphash = $this->request->get('backuphash');
            if ($backuphash == '' || empty($backuphash))
                return;
            $b = new Blocker();
            $result = $b->executeRestore($backuphash, $this->session->get('auth')['id'], $this->session->get('auth')['userlevel']);
            $backupstable = '';

            if (!isset($result) || $result[0] != 1) {
                echo 0;
                return;
            }

            if (!empty($result[1])) {
                foreach ($result[1] as $row) {
                    $backupstable .= '<tr>'
                            . '<td>' . date('Y-m-d H:i:s', strtotime($row['insertTimestamp'])) . '</td>'
                            . '<td>' . $row['backupname'] . '</td>'
                            . '<td>' . $row['backupdescription'] . '</td>'
                            . '<td>' . $row['campaign'] . '</td>'
                            . '<td class="iconEdit"><img id="' . $row['backuphash'] . '" class="modalIcon" src="/img/reverse.png" ></a></td>'
                            . '</tr>';
                }
            }
            echo $backupstable;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__, $ex->getMessage());
            echo '0';
        }
    }

    private function getAggregators() {
        $cache = $this->di->get("viewCache");
        $comboString = $cache->get('aggregators');
        $comboString = null;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getAggregators();
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString .= '<option value="' . $row['id'] . '">' . $row['agregator'] . '-' . $row['id'] . '</option>';
            }
            $cache->save('aggregators', $comboString);
        }

        return $comboString;
    }

}

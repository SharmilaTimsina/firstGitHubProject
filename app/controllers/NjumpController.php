<?php

class NjumpController extends ControllerBase {

    var $filter = false;
    var $auth, $area, $countriesauth, $sourcesauth, $userid;
    var $njumpfields;

//id,njumphash,njumpgeneratedname,globalname,country,source,offerhash,offername,
//linename,sourcename,proportion,carrier,os,lpid,lpurl,time,sback,isp,epc,
//status,clicks,counter,deleted,area,insertTimestamp,editedTimestamp,createdby,
//favorite,editedby
    public function initialize() {
        $this->tag->setTitle('NJump');
        parent::initialize();
        $this->auth = $this->session->get('auth');
        $this->filter = false;
        $this->userid = $this->auth['id'];
        $this->njumpfields = array('id', 'njumphash', 'njumpgeneratedname', 'globalname',
            'country', 'source', 'offerhash', 'offername', 'linename', 'sourcename', 'domain',
            'proportion', 'carrier', 'carrierids', 'os', 'osids', 'lpid', 'lpurl', 'time', 'sback', 'isp', 'ispids',
            'epc', 'status', 'clicks', 'counter', 'deleted', 'area', 'insertTimestamp',
            'editedTimestamp', 'createdby', 'favorite', 'editedby');
        if ($this->auth['userlevel'] > 1) {
            $this->area = $this->auth['utype'];
            $this->countriesauth = (empty($this->auth['countries']) ? null : $this->auth['countries']);
            $this->sourcesauth = (empty($this->auth['sources']) ? null : $this->auth['sources']);
            $this->filter = true;
        }
    }

    public function changeGlobalNameAction() {
        try {
            if ($this->request->getPost('njumphash') != null && $this->request->getPost('name') != null) {
                $filter = '';
                $db = $this->getDi()->getDb4();
                if (isset($this->filter)) {
                    if (isset($this->countriesauth)) {
                        $filter .= " AND find_in_set(country,'$this->countriesauth') ";
                    }
                    if (isset($this->sourcesauth)) {
                        $filter .= " AND find_in_set(source,'$this->sourcesauth') ";
                    }
                    if (isset($this->area)) {
                        $filter .= " AND area = $this->area ";
                    }
//                if (isset($this->area)) {
//                    $sql .= " OR ( createdby = $this->userid OR editedby = $this->userid )";
//                }
                }
                $sql = 'UPDATE njumps SET globalname = :name, editedTimestamp = editedTimestamp WHERE njumphash = :njumphash ' . $filter;
                $sql2 = $db->prepare($sql);
                $db->executePrepared($sql2, array('njumphash' => $this->request->getPost('njumphash'), 'name' => $this->request->getPost('name')), array());
                echo 0;
            }
            //echo 'la';
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            echo 1;
        }
    }

    public function mresetAction() {
        try {
            $njumps = $this->getFavoriteOnes();
            $v = '';
            if (!empty($njumps)) {
                foreach ($njumps as $row) {
//if favorite class = favoritenjump
                    $v .= '<a class="resultsSearch ' . ($row['status'] >= 2 ? 'njumplistbadnjump' : '') . '" hash="' . $row['njumphash'] . '" href="/njump/njumpeditm?njumphash=' . $row['njumphash'] . '"><div class="divNjumplist ' . ($row['favorite'] == 1 ? ' favoritenjump ' : '' ) . '">' . $row['generatedname'] . '</div></a>';
                }
            }
            echo $v;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            echo 1;
        }
    }

    public function indexmAction() {
        try {
            $njumps = $this->getFavoriteOnes();
            $v = '';
            if (!empty($njumps)) {
                foreach ($njumps as $row) {
//if favorite class = favoritenjump
                    $v .= '<a class="resultsSearch ' . ($row['status'] >= 2 ? 'njumplistbadnjump' : '') . '" hash="' . $row['njumphash'] . '" href="/njump/njumpeditm?njumphash=' . $row['njumphash'] . '"><div class="divNjumplist ' . ($row['favorite'] == 1 ? ' favoritenjump ' : '' ) . '">' . $row['globalname'] . '-' . $row['generatedname'] . '</div></a>';
                }
            }
            $this->view->setVar('njumps', $v);
            $sourcearray = '';
            $countries = '';
            if ($this->filter) {
                if (isset($this->area))
                    $sourcearray = "affiliate = $this->area";
                if (isset($this->countriesauth))
                    $countries = " find_in_set(id,'$this->countriesauth') ";
            }
            $ss = Sources::find(array($sourcearray, 'columns' => 'id, sourceName'))->toArray();
            $ssencode = json_encode($ss, JSON_NUMERIC_CHECK);
            $cc = Countries::find(array($countries, 'columns' => 'id, name'))->toArray();
            $ccencode = json_encode($cc, JSON_NUMERIC_CHECK);
            $this->view->setVar('sourcesvar', $ssencode);
            $this->view->setVar('countriesvar', $ccencode);
//            if ($this->filter) {
//                $area = '';
//            } else {
//                $area = Dimarea::find(array('columns' => 'id, name'))->toArray();
//            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function indexAction() {
        try {
            $njumps = $this->getFavoriteOnes();
            $this->view->setVar('njumps', json_encode($njumps));
            $sourcearray = '';
            $countries = '';
            if ($this->filter) {
                if (isset($this->area))
                    $sourcearray = "affiliate = $this->area";
                if (isset($this->countriesauth))
                    $countries = " find_in_set(id,'$this->countriesauth') ";
            }

            $this->view->setVar('sourcesvar', json_encode(Sources::find(array($sourcearray, 'columns' => 'id, sourceName'))->toArray(), JSON_NUMERIC_CHECK));
            $this->view->setVar('countriesvar', json_encode(Countries::find(array($countries, 'columns' => 'id, name'))->toArray(), JSON_NUMERIC_CHECK));
            if ($this->filter) {
                $area = '';
            } else {
                $area = Dimarea::find(array('columns' => 'id, name'))->toArray();
            }
            $this->view->setVar('areasvar', json_encode($area, JSON_NUMERIC_CHECK));
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function njumpclonemultiplelinesAction() {
        try {
            $njumpclone = $this->request->getPost('njumpsClone');
            $idslines = $this->request->getPost('idsLines');
            $idslines = explode(',', $idslines);
            $sqlfilter = '';
            if (isset($this->filter)) {
                if (isset($this->countriesauth)) {
                    $sqlfilter .= " AND find_in_set(country,'$this->countriesauth') ";
                }
                if (isset($this->sourcesauth)) {
                    $sqlfilter .= " AND find_in_set(source,'$this->sourcesauth') ";
                }
                if (isset($this->area)) {
                    $sqlfilter .= " AND area = $this->area ";
                }
                /* if (isset($this->area)) {
                  $sqlfilter .= " AND ( createdby = $this->userid OR editedby = $this->userid OR 1=1 )";
                  } */
            }
            $njumps = explode(',', $njumpclone);
            foreach ($njumps as $njumpclone) {
                $select = 'SELECT id,njumphash,njumpgeneratedname,globalname,'
                        . 'country,source,offerhash,offername,linename,'
                        . 'sourcename,domain,proportion,carrier,carrierids,'
                        . 'os,osids,lpid,lpurl,time,sback,isp,ispids,'
                        . 'epc,status,clicks,counter,deleted,area,insertTimestamp,favorite '
                        . ' FROM njumps WHERE deleted = 0 AND njumphash = :njumphash ' . $sqlfilter . ' LIMIT 1';
                $sqlstatment = $this->getDi()->getDb4()->prepare($select);
                $exe = $this->getDi()->getDb4()->executePrepared($sqlstatment, array('njumphash' => $njumpclone), array());
                $v = $exe->fetchAll(PDO::FETCH_ASSOC);
                if (empty($v))
                    continue;
                $sql = 'INSERT INTO njumps (njumphash,njumpgeneratedname,globalname,country,source,'
                        . 'offerhash,offername,linename,sourcename,domain,proportion,carrier,carrierids,os,osids,'
                        . 'lpid,lpurl,time,sback,isp,ispids,status,counter,area,'
                        . 'insertTimestamp,editedTimestamp,createdby,favorite,editedby) '
                        . ' SELECT "' . $v[0]['njumphash'] . '", "' . $v[0]['njumpgeneratedname'] . '", "' . $v[0]['globalname'] . '",'
                        . ' "' . $v[0]['country'] . '", ' . $v[0]['source'] . ','
                        . 'offerhash,offername,linename,"' . $v[0]['sourcename'] . '",' . $v[0]['domain'] . ',proportion,carrier,carrierids,os,osids, '
                        . ' lpid,lpurl,time,sback,isp,ispids,status,counter,area,'
                        . '"' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '",createdby,favorite,' . $this->userid
                        . ' FROM njumps WHERE id IN (' . str_repeat('?,', count($idslines) - 1) . '?' . ') AND njumphash LIKE ? AND deleted = 0 ' . $sqlfilter;
//mail('pedrorleonardo@gmail.com', 'subject', $sql);
                $sqlstatment2 = $this->getDi()->getDb4()->prepare($sql);
                $idslines[] = $this->request->getPost('njumphash');
                $sqlstatment2->execute($idslines);
            }
            echo 0;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function getnjumpsbycountryAction() {
        try {
            $sql = 'SELECT njumphash,MAX(globalname) as globalname,MAX(globalname) as name '
                    . ' FROM njumps WHERE deleted = 0 AND njumphash NOT LIKE  :njumphash AND country = :country ';
            if ($this->filter) {
                if (isset($this->area)) {
                    $sql .= " AND area = $this->area ";
                }
                if (isset($this->countriesauth))
                    $sql .= " AND find_in_set(country,'$this->countriesauth') ";
            }
            $sql .= ' GROUP BY njumphash ORDER BY name ASC';
            $sqlstatment = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($sqlstatment, array('njumphash' => $this->request->getPost('njumphash'), 'country' => $this->request->getPost('country')), array());
            $v = $exe->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($v)) {
                $res = array();
                foreach ($v as $njump) {
                    $row['njumphash'] = $njump['njumphash'];
                    $row['name'] = $njump['name'];
                    $res[] = $row;
                }
                echo json_encode($res);
            } else
                echo 2;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function njumpdeletemultiplerowAction() {
        try {
            $idslines = $this->request->getPost('idsLines');
            $idslines = explode(',', $idslines);
            $sqlfilter = '';
            if (isset($this->filter)) {
                if (isset($this->countriesauth)) {
                    $sqlfilter .= " AND find_in_set(country,'$this->countriesauth') ";
                }
                if (isset($this->sourcesauth)) {
                    $sqlfilter .= " AND find_in_set(source,'$this->sourcesauth') ";
                }
                if (isset($this->area)) {
                    $sqlfilter .= " AND area = $this->area ";
                }
                /* if (isset($this->area)) {
                  $sqlfilter .= " AND ( createdby = $this->userid OR editedby = $this->userid OR 1=1 )";
                  } */
            }
            $sql = 'UPDATE njumps SET deleted = 1, editedTimestamp = editedTimestamp WHERE id IN (' . str_repeat('?,', count($idslines) - 1) . '?' . ') AND njumphash = ? ' . $sqlfilter;
            $idslines[] = $this->request->getPost('njumphash');
            $sqlstatment2 = $this->getDi()->getDb4()->prepare($sql);
            $sqlstatment2->execute($idslines);
            $db = $this->getDi()->getDb4();
            $prep = $db->prepare('CALL sp_checknjumpstatus(?,NULL,@myout1,@myout2);');
            $njumphash = $this->request->getPost('njumphash');
            $prep->bindParam(1, $njumphash, PDO::PARAM_STR);
            $prep->execute();
            $prep->closeCursor();
            echo 0;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function getNjumpsAction() {
        try {
            $country = $this->request->get('country');
            $source = $this->request->get('source');
            $inputstr = $this->request->get('searchquery');
            $mobile = $this->request->get('mobile');

            $sql = ' WHERE deleted = 0 ';
            $filterarr = array();
            if ($this->issetRequest($country)) {
                $filterok = 1;
                $sql .= ' AND country LIKE :country ';
                $filterarr['country'] = $country;
            }
            if ($this->issetRequest($source)) {
                $filterok = 1;
                $sql .= ' AND source = :source ';
                $filterarr['source'] = $source;
            }
            if ($this->issetRequest($inputstr)) {
                if (!isset($filterok) && strlen($inputstr) > 1)
                    $filterok = 1;
                $sql .= ' AND (njumphash LIKE :strinput OR globalname LIKE :strinput OR offername LIKE :strinput OR '
                        . 'njumpgeneratedname LIKE :strinput ) ';
                $filterarr['strinput'] = ('%' . $inputstr . '%');
                $filterarr['strinput'] = str_replace('_', '\_', $filterarr['strinput']);
            }

            if (isset($this->filter)) {
                if (isset($this->countriesauth)) {
                    $sql .= " AND find_in_set(country,'$this->countriesauth') ";
                }
                if (isset($this->sourcesauth)) {
                    $sql .= " AND find_in_set(source,'$this->sourcesauth') ";
                }
                if (isset($this->area)) {
                    $sql .= " AND area = $this->area ";
                }
//                if (isset($this->area)) {
//                    $sql .= " OR ( createdby = $this->userid OR editedby = $this->userid )";
//                }
            }
            if (!isset($filterok)) {
                echo '';
                return;
            }
            $sql = 'SELECT njumpgeneratedname as njumpgeneratedname,globalname, njumphash, MAX(favorite) as favorite, MAX(domain) as domain, MAX(status) as status FROM njumps ' . $sql . ' GROUP BY njumpgeneratedname,globalname, njumphash ORDER BY globalname ';
            $sqlstatment = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($sqlstatment, $filterarr, array());
            $v = $exe->fetchAll(PDO::FETCH_ASSOC);
            //HelpingFunctions::writetolog('I' . __CLASS__, $sql . serialize($filterarr) . 'this area:' . $this->area);
            if (!empty($v)) {
                if (ob_get_level() == 0)
                    ob_start();
                foreach ($v as $row) {
                    echo '<a class="resultsSearch ' . ($row['status'] >= 2 ? 'njumplistbadnjump' : '') . '" hash="' . $row['njumphash'] . '" href="/njump/njumpedit' . ($mobile ? 'm' : '') . '?njumphash=' . $row['njumphash'] . '"><div class="divNjumplist"><b>' . $row['globalname'] . '</b> - ' . $row['njumpgeneratedname'] . '</div></a>';
                    ob_flush();
                    flush();
                }
                if (ob_get_length())
                    ob_end_clean();
            }
            echo '';
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    public function favoriteNjumpAction() {
        try {
            $njumphash = $this->request->get('njumphash');
            if (empty($njumphash)) {
                echo '1';
                return;
            }
            $sql = 'UPDATE njumps SET favorite = NOT favorite, editedTimestamp = editedTimestamp, editedBy =' . $this->userid . ', counter = ( SELECT counter FROM (SELECT MAX(counter) as counter FROM njumps WHERE njumphash = :njumphash )B ) +1 WHERE njumphash = :njumphash ';
            $sqlstatment = $this->getDi()->getDb4()->prepare($sql);
            $this->getDi()->getDb4()->executePrepared($sqlstatment, array('njumphash' => $this->request->get('njumphash')), array());
            echo 0;
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    public function newnjumpAction() {
        try {
            $this->view->disable();
            if (!$this->issetRequest($this->request->getPost('country')) || !$this->issetRequest($this->request->getPost('source')) || !$this->issetRequest($this->request->getPost('name'))) {
                echo '1';
                return;
            }
            $source = Sources::findFirst(array('id = ?1 ', "bind" => [
                            1 => $this->request->getPost('source'),
            ]));
            $country = Countries::findFirst(array('id = ?1 ', "bind" => [
                            1 => $this->request->getPost('country'),
            ]));
            if (empty($country)) {
                echo 1;
//echo 'could not find country';
                return;
            }
            if (empty($source)) {
                echo 1;
//echo 'could not find source';
                return;
            }
            if (!$this->filter) {
                if (!$this->issetRequest($this->request->getPost('area'))) {
                    echo 1;
//echo "Please provide area";
                    return;
                }
                $area = Dimarea::findFirst(array('id = ?1 ', "bind" => [
                                1 => $this->request->getPost('area'),
                ]));
                if (empty($area)) {
                    echo 1;
//echo "Please provide correct area";
                    return;
                }
            }
            if (!isset($area)) {
                $area = Dimarea::findFirst(array('id = ?1 ', "bind" => [
                                1 => $this->area,
                ]));
                if (empty($area)) {
                    echo 1;
//echo "Please provide correct area";
                    return;
                }
            }

            $njump = new Njumps();
            $njump->njumphash = uniqid();
            $njump->globalname = $this->request->getPost('name');
            $generatedname = $this->findnewid($country->id, $source->id, $area);
            $njump->njumpgeneratedname = $generatedname;
            $njump->country = $country->id;
            $njump->source = $this->request->getPost('source');
            $njump->offerhash = 'changeme';
            $njump->offername = '';
            $njump->linename = 'changeme';
            $njump->sourcename = $source->sourceName;
            $njump->proportion = 0;
            /* $njump->carrier = new \Phalcon\Db\RawValue('default');
              $njump->lpurl = new \Phalcon\Db\RawValue('default');
              $njump->time = new \Phalcon\Db\RawValue('default');
              $njump->isp = new \Phalcon\Db\RawValue('default');
             *
             */
            $njump->clicks = new \Phalcon\Db\RawValue('default');
            $njump->sback = new \Phalcon\Db\RawValue('default');
            if ($this->request->getPost('source') == 91 || $this->request->getPost('source') == 386 || $this->request->getPost('source') == 166 || $this->request->getPost('source') == 362) {
                $domainid = 58;
            } else
                $domainid = new \Phalcon\Db\RawValue('default');
            $njump->domain = $domainid;
            $njump->epc = 0;
            $njump->status = 2;
            $njump->counter = 1;
            $njump->deleted = 0;
            $njump->area = $area->id;
            $njump->insertTimestamp = date('Y-m-d H:i:s');
            $njump->editedTimestamp = date('Y-m-d H:i:s');
            $njump->createdby = $this->userid;
            $njump->editedby = $this->userid;
            $njump->favorite = 0;
            if ($njump->create() == false) {
                HelpingFunctions::writetolog("E\t" . __CLASS__, serialize($njump->getMessages()));
                echo 1;
                return;
            }

            $njump->save();

            echo $njump->njumphash;
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    public function njumpcloneAction() {
        try {
            $this->view->disable();
            if (!$this->issetRequest($this->request->getPost('country')) || !$this->issetRequest($this->request->getPost('clonehash')) || !$this->issetRequest($this->request->getPost('source')) || !$this->issetRequest($this->request->getPost('name'))) {
                echo '1';
//echo 'checkrequest failed at some point';
                return;
            }
            $source = Sources::findFirst(array('id = ?1 ', "bind" => [
                            1 => $this->request->getPost('source'),
            ]));
            $country = Countries::findFirst(array('id = ?1 ', "bind" => [
                            1 => $this->request->getPost('country'),
            ]));
            if (empty($country)) {
                echo 1;
//echo 'could not find country';
                return;
            }
            if (empty($source)) {
                echo 1;
//echo 'could not find source';
                return;
            }
            if (!$this->filter) {
                if (!$this->issetRequest($this->request->getPost('area'))) {
                    echo 1;
//echo "Please provide area1";
                    return;
                }
                $area = Dimarea::findFirst(array('id = ?1 ', "bind" => [
                                1 => $this->request->getPost('area'),
                ]));
                if (empty($area)) {
                    echo 1;
//echo "Please provide correct area2";
                    return;
                }
            }
            if (!isset($area)) {
                $area = Dimarea::findFirst(array('id = ?1 ', "bind" => [
                                1 => $this->area,
                ]));
                if (empty($area)) {
                    echo 1;
//echo "Please provide correct area3";
                    return;
                }
            }
            $domainid = 'domain';
            if ($source->id == 386 || $source->id == 166 || $source->id == 91 || $source->id == 362) {
                $domainid = '58';
            }
            $globalname = $this->request->getPost('name');
            $newhash = uniqid();
            $generatedname = $this->findnewid($country->id, $source->id, $area);
            $sql = "INSERT INTO njumps (`njumphash`, `njumpgeneratedname`, `globalname`, "
                    . "`country`, `source`, `offerhash`, `offername`, `linename`, `sourcename`, "
                    . "`proportion`, `carrier`, carrierids,os,osids,lpid, `lpurl`, `time`, `sback`, `isp`, ispids, domain,`epc`, `status`, "
                    . "`clicks`, `counter`, `deleted`, `area`, `insertTimestamp`, `editedTimestamp`, "
                    . "`createdby`, `favorite`, `editedby`)"
                    . " SELECT  '$newhash', '$generatedname', '$globalname', "
                    . "'$country->id', $source->id, offerhash, offername, linename, '$source->sourceName', "
                    . "proportion,carrier,carrierids,os,osids,lpid, lpurl,time,sback, isp,ispids,$domainid,null,CASE WHEN status NOT IN (0,1) THEN status ELSE 0 END,"
                    . "0,1,0,$area->id,'" . date('Y-m-d H:i:s') . "','" . date('Y-m-d H:i:s') . "',"
                    . "$this->userid,favorite,$this->userid FROM njumps WHERE njumphash = :njumphash and deleted = 0 " . ($this->filter ? " and area = $this->area " : '');
            HelpingFunctions::writetolog("I\t" . __CLASS__, $sql);
            $sql = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($sql, array('njumphash' => $this->request->get('clonehash')), array());
            echo $newhash;
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    public function njumpeditmAction() {
        try {
            if (!$this->issetRequest($this->request->get('njumphash'))) {
                echo '1';
                return;
            }
            $countries = '';
            $sourcearray = '';
            if ($this->filter) {
                if (isset($this->area))
                    $sourcearray = "affiliate = $this->area";
                if (isset($this->countriesauth))
                    $countries = " find_in_set(id,'$this->countriesauth') ";
            }
            $sql = 'SELECT njumps.id as id,njumps.njumphash as njumphash,njumpgeneratedname,globalname,country,source,offerhash,offername,
                    linename,sourcename,proportion,Coalesce(carrierids,"") as carrierids,
                    njumps.status as status,favorite,(SELECT MAX(status) FROM njumps WHERE njumps.njumphash = :njumphash and njumps.deleted IN(0,2) LIMIT 1) as globalstatus,area,domain, COALESCE(ss.name,"") as params
                    FROM njumps left join njumpstats ns ON ns.id = njumps.id
                    left join dim__sourceparams ss ON njumps.source = ss.id
                    WHERE njumps.njumphash = :njumphash and njumps.deleted IN(0,2) ORDER BY njumps.status DESC, njumps.carrier ASC, njumps.clicks, njumps.offername';
//mail('pedrorleonardo@gmail.com', 'njumps', $sql . ' blaaaaa ' . $this->request->get('njumphash'));
            $sql = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($sql, array('njumphash' => $this->request->get('njumphash')), array());
            $v = $exe->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($v) && !empty($v[0]) && !empty($v[0]['country'])) {
                $country = strtoupper($v[0]['country']);
                $cname = Countries::findFirst(array('id = "' . $country . '"'));
                if (!empty($cname))
                    $this->view->setVar('countryname', json_encode(array($country, $cname->name), JSON_NUMERIC_CHECK));
                $this->view->setVar('sourcenamevar', json_encode((!empty($v[0]['sourcename']) ? $v[0]['sourcename'] : ''), JSON_NUMERIC_CHECK));
                $this->view->setVar('njumphash', json_encode((!empty($v[0]['njumphash']) ? $v[0]['njumphash'] : ''), JSON_NUMERIC_CHECK));
                $this->view->setVar('globalsourcevar', json_encode(!empty($v[0]['source']) ? $v[0]['source'] : ''));
                $this->view->setVar('globalnamevar', json_encode(!empty($v[0]['globalname']) ? $v[0]['globalname'] : '', JSON_NUMERIC_CHECK));
                $this->view->setVar('statusvar', json_encode(!empty($v[0]['globalstatus']) ? $v[0]['globalstatus'] : '', JSON_NUMERIC_CHECK));
                $this->view->setVar('params', json_encode(!empty($v[0]['params'] ? $v[0]['params'] : ''), JSON_NUMERIC_CHECK));
                $this->view->setVar('favoritevar', json_encode(empty($v[0]['favorite']) ? '0' : $v[0]['favorite'], JSON_NUMERIC_CHECK));
                $this->view->setVar('njumpgeneratednamevar', json_encode($v[0]['njumpgeneratedname'], JSON_NUMERIC_CHECK));
                $areafound = Dimarea::findFirst(array('id = ' . $v[0]['area']));
                $this->view->setVar('domainvar', json_encode($v[0]['domain']));
                $this->view->setVar('areanamevar', json_encode($areafound->name, JSON_NUMERIC_CHECK));
                $this->view->setVar('domainsvar', json_encode(Dimdomains::find(array(($v[0] == 4 ? '' : ("area IN( " . $v[0]['area']) . ",4) " )))->toArray(), JSON_NUMERIC_CHECK));
                $this->view->setVar('njumpsvar', json_encode($v));
                $this->view->setVar('carriersvar', json_encode(Dimcarriers::find(array('country = "' . $country . '"'))->toArray(), JSON_NUMERIC_CHECK));
            }else {
                echo '1';
                return;
            }
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage() . 'LINE');
        }
    }

    public function njumpeditAction() {
        try {
            //$a = new DateTime();
            $db = $this->getDi()->getDb4();
            if (!$this->issetRequest($this->request->get('njumphash'))) {
                echo '1';
                return;
            }
            $countries = '';
            $sourcearray = '';
            if ($this->filter) {
                if (isset($this->area))
                    $sourcearray = "affiliate = $this->area";
                if (isset($this->countriesauth))
                    $countries = " find_in_set(id,'$this->countriesauth') ";
            }
            $db = $this->getDi()->getDb4();
            $sqlset = 'SET @posn:=1;';
            $sqlset2 = $db->query($sqlset);
            $sqlset3 = 'SET @pid:="";';
            $sqlset4 = $db->query($sqlset3);
            $sql = 'SELECT njumps.id as id,IF(@pid=COALESCE(carrierids,0),@posn:=@posn,@posn:=@posn*-1)  as pos,@pid:=COALESCE(carrierids,0) as something,njumps.njumphash as njumphash,njumpgeneratedname,globalname,country,source,offerhash,offername,
                    linename,sourcename,proportion,Coalesce(carrier,"") as carrier,Coalesce(carrierids,"") as carrierids,Coalesce(lpid,"") as lpid,Coalesce(time,"") as time,sback as sback,Coalesce(ispids,"") as ispids,Coalesce(isp,"") as isp,Coalesce(os,"") as os,Coalesce(osids,"") as osids,
                    Coalesce(ns.epc,0) as epc,Coalesce(ns.rev,0) as rev,Coalesce(ns.clicks,0) as clicks,'
                    //Coalesce(ns.epc3days,0) as epc,Coalesce(ns.rev3days,0) as rev,Coalesce(ns.clicks3days,0) as clicks,
                    . 'njumps.status as status,(SELECT MAX(status) FROM njumps WHERE njumps.njumphash = :njumphash and njumps.deleted IN(0,2) LIMIT 1) as globalstatus,area,domain,
                    favorite,njumps.insertTimestamp as insertTimestamp,njumps.editedTimestamp as editedTimestamp, COALESCE(ss.name,"") as params FROM njumps left join njumpstats ns ON ns.id = njumps.id
                    left join dim__sourceparams ss ON njumps.source = ss.id
                    WHERE njumps.njumphash = :njumphash and njumps.deleted IN( 0,2) ORDER BY njumps.carrier ASC, njumps.clicks, offername';
//mail('pedrorleonardo@gmail.com', 'njumps', $sql . ' blaaaaa ' . $this->request->get('njumphash'));
            $sql = $db->prepare($sql);
            $exe = $db->executePrepared($sql, array('njumphash' => $this->request->get('njumphash')), array());
            $v = $exe->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($v) && !empty($v[0]) && !empty($v[0]['country'])) {
                $country = strtoupper($v[0]['country']);
                $cname = Countries::findFirst(array('id = "' . $country . '"'));
                if (!empty($cname))
                    $this->view->setVar('countryname', json_encode(array($country, $cname->name), JSON_NUMERIC_CHECK));
                $this->view->setVar('sourcenamevar', json_encode($v[0]['sourcename'], JSON_NUMERIC_CHECK));
                $this->view->setVar('globalsourcevar', json_encode($v[0]['source']));
                $this->view->setVar('globalnamevar', json_encode($v[0]['globalname'], JSON_NUMERIC_CHECK));
                $this->view->setVar('statusvar', json_encode($v[0]['globalstatus'], JSON_NUMERIC_CHECK));
                $this->view->setVar('paramsvar', json_encode($v[0]['params'], JSON_NUMERIC_CHECK));
                $this->view->setVar('favoritevar', json_encode($v[0]['favorite'], JSON_NUMERIC_CHECK));
                $this->view->setVar('njumpgeneratednamevar', json_encode($v[0]['njumpgeneratedname'], JSON_NUMERIC_CHECK));
                $areafound = Dimarea::findFirst(array('id = ' . $v[0]['area']));
                $this->view->setVar('domainvar', json_encode($v[0]['domain']));
                $this->view->setVar('areanamevar', json_encode($areafound->name, JSON_NUMERIC_CHECK));
                $this->view->setVar('domainsvar', json_encode(Dimdomains::find(array(($v[0] == 4 ? '' : ("area IN( " . $v[0]['area']) . ",4) " )))->toArray(), JSON_NUMERIC_CHECK));
            }else {
                echo '1';
                return;
            }
            $this->view->setVar('ispsvar', json_encode(Dimisp::find(array('country = "' . $country . '"', 'limit' => 100))->toArray()));
            $db = $db;


            $sql2statement = 'SELECT m.hash as hash, m.campaign as campaign,CONCAT("http://jump.youmobistein.com/?jp=",m.hash,"&id=",m.agregator,"_",m.country,"_1_",m.campaign,"_99_99_99") as url FROM Mask m LEFT JOIN offerpack__offerpack o ON o.hashMask = m.hash WHERE '
                    //. ' (o.status != 4 OR o.status IS NULL) AND '
                    . ' m.country IN ("af","ww","' . $country . '") and area != 1 '
                    //. ($this->filter ? " AND (o.area = $this->area OR o.area = 4 )"
                    . ($this->filter ? (isset($this->countriesauth) ? ' AND find_in_set(m.country,"' . $this->countriesauth . '")' : '' ) : '' )
                    . ' ORDER BY campaign ASC';
//$sql2 = $db->prepare($sql2statement);
            $exe2 = $db->query($sql2statement);

            $masks = $exe2->fetchAll(PDO::FETCH_ASSOC);


            $this->view->setVar('offersvar', json_encode($masks));

            $this->view->setVar('njumpsvar', json_encode($v));
            $this->view->setVar('carriersvar', json_encode(Dimcarriers::find(array('country = "' . $country . '"'))->toArray(), JSON_NUMERIC_CHECK));
            $this->view->setVar('osvar', json_encode(Dimos::find()->toArray(), JSON_NUMERIC_CHECK));
            $sql2 = "SELECT id, name,url
FROM dim__landingpages
where ( EXISTS (SELECT * FROM dim__countrylanguages WHERE countrycode LIKE :country AND languages LIKE CONCAT('%',dim__landingpages.languages,'%')) OR dim__landingpages.languages LIKE  'en' ) and status = 1  ";
//HelpingFunctions::writetolog("I\t" . __CLASS__.$ex->getLine(), $sql2);
            $sql2 = $db->prepare($sql2);
            $exe2 = $db->executePrepared($sql2, array('country' => $country), array());
            $v2 = $exe2->fetchAll(PDO::FETCH_ASSOC);


            $this->view->setVar('lpsvar', json_encode($v2));

            $this->view->setVar('sbacksvar', json_encode(HelpingFunctions::getSback(), JSON_NUMERIC_CHECK));

            $this->view->setVar('sourcesvar', json_encode(Sources::find(array($sourcearray, 'columns' => 'id, sourceName'))->toArray(), JSON_NUMERIC_CHECK));
            $this->view->setVar('countriesvar', json_encode(Countries::find(array($countries, 'columns' => 'id, name'))->toArray(), JSON_NUMERIC_CHECK));
            if ($this->filter) {
                $area = '';
            } else {
                $area = Dimarea::find(array('columns' => 'id, name'))->toArray();
            }
            $this->view->setVar('areasvar', json_encode($area, JSON_NUMERIC_CHECK));
            //$b = new DateTime();
            //$interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'njump edit took' . $interval, $sql2statement);
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    public function njumpsortbyAction() {
        try {
            $njumphash = $this->request->getPost('njumphash');
            $sortby = $this->request->getPost('sorter');
            $order = $this->request->getPost('order');
            $table = 'ns';
            if (empty($njumphash) || !isset($sortby))
                throw new Exception('No sort by defined. (or njump) ');
            switch ($sortby) {
                case 0:
                    $table = '';
                    $sortbycolumn = '';
                    break;
                case 1:
                    $table = 'n';
                    $sortbycolumn = 'n.id';
                    break;
                case 2:
                    $table = 'ns';
                    $sortbycolumn = 'clicks';
                    $periodicity = $this->request->getPost('periodicity') != null ? $this->request->getPost('periodicity') : '0';
                    switch ($periodicity) {
                        case '0':
                            $sortbycolumn = 'clicks';
                            break;
                        case '1':
                            $sortbycolumn = 'clicks1days';
                            break;
                        case '2':
                            $sortbycolumn = 'clicks3days';
                            break;
                        case '3':
                            $sortbycolumn = 'clicks7days';
                            break;
                        case '4':
                            $sortbycolumn = 'clicks1hours';
                            break;
                        case '5':
                            $sortbycolumn = 'clicks3hours';
                            break;
                        case '6':
                            $sortbycolumn = 'clicks6hours';
                            break;
                        case '7':
                            $sortbycolumn = 'clicks12hours';
                            break;
                        default:
                            break;
                    }
                    break;
                case 3:
                    $sortbycolumn = 'epc';
                    $table = 'ns';
                    $periodicity = $this->request->getPost('periodicity') != null ? $this->request->getPost('periodicity') : '0';
                    switch ($periodicity) {
                        case '0':
                            $sortbycolumn = 'epc';
                            break;
                        case '1':
                            $sortbycolumn = 'epc1days';
                            break;
                        case '2':
                            $sortbycolumn = 'epc3days';
                            break;
                        case '3':
                            $sortbycolumn = 'epc7days';
                            break;
                        case '4':
                            $sortbycolumn = 'epc1hours';
                            break;
                        case '5':
                            $sortbycolumn = 'epc3hours';
                            break;
                        case '6':
                            $sortbycolumn = 'epc6hours';
                            break;
                        case '7':
                            $sortbycolumn = 'epc12hours';
                            break;
                        default:
                            break;
                    }
                    break;
                case 4:
                    $table = 'n';
                    $sortbycolumn = 'proportion';
                    break;
                case 5:
                    $table = 'n';
                    $sortbycolumn = 'offername';
                    break;
                case 6://prelanding
                    $table = 'n';
                    $sortbycolumn = 'lpid';
                    break;
                default:
                    $table = 'n';
                    $sortbycolumn = 'carrierids';
                    break;
            }
            $db = $this->getDi()->getDb4();
            $sqlset = 'SET @posn:=1;';
            $sqlset2 = $db->query($sqlset);
            $sqlset3 = 'SET @pid:="";';
            $sqlset4 = $db->query($sqlset3);

            $sql = 'SELECT id as id,IF(@pid=COALESCE(carrierids,0),@posn:=@posn,@posn:=@posn*-1)  as pos,@pid:=COALESCE(carrierids,0) as something FROM ( SELECT n.id,n.carrierids' . (!empty($table) ? (', ' . $table . '.' . $sortbycolumn . ' as ' . $sortbycolumn ) : '') . ' FROM njumps n LEFT JOIN njumpstats ns ON ns.id = n.id WHERE n.njumphash = :njumphash ORDER BY carrierids' . (empty($order) ? ' DESC' : ' ASC') . (!empty($table) ? (',' . $table . '.' . $sortbycolumn . (empty($order) ? ' DESC' : ' ASC') ) : '' ) . ') J ';
            //mail('pedrorleonardo@gmail.com', 'sql', $sql);
            $sql2 = $db->prepare($sql);
            $exe = $db->executePrepared($sql2, array('njumphash' => $this->request->get('njumphash')), array());
            $v = $exe->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($v)) {
                $str = '';
                foreach ($v as $val)
                    $str .= $val['id'] . '&' . $val['pos'] . ',';
                echo rtrim($str, ',');
                return;
            }
            echo 1;
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage() . $sql);
        }
    }

    public function njumpdeleteAction() {
        try {
            if (!$this->issetRequest($this->request->get('njumphash'))) {
                echo '1';
                return;
            }
            $sql = 'UPDATE njumps SET deleted = 1 WHERE njumphash = :njumphash ' . ($this->filter ? // " AND ( createdby = $this->userid OR editedby = $this->userid) "
                    " AND area = $this->area " : '' );
            $sql = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($sql, array('njumphash' => $this->request->get('njumphash')), array());
            if ($exe->rowCount() > 0)
                echo '0';
            else
                echo '1';
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    public function njumpnewrowAction() {
        try {
//id,njumphash,njumpgeneratedname,globalname,country,source,offerhash,offername,
//linename,sourcename,proportion,carrier,os,lpid,lpurl,time,sback,isp,epc,
//status,clicks,counter,deleted,area,insertTimestamp,editedTimestamp,createdby,
//favorite,editedby
            if (!$this->issetRequest($this->request->getPost('njumphash'))) {
                echo '1';
                return;
            }
            $arr = array();
            $clone = false;
            if ($this->request->getPost('id') != null) {
                $clone = true;
            }
            $sql = '';
            if ($this->filter) {
                $sql = (isset($this->countriesauth) ? ' AND find_in_set(country, "' . $this->countriesauth . '")' : '') . ' AND area = ' . $this->area;
            }
            if (!$clone) {
                $arr[0] = $this->request->getPost('njumphash');
                $njump = Njumps::findFirst(array('1=1 ' . $sql . ' AND njumphash LIKE ?0 ', "bind" => $arr));
                if (empty($njump)) {
                    echo 1;
                    return;
                }
                $newnjump = $this->newnjumprow($njump, $this->request->getPost('offerhash'));
            } else {
                $arr[0] = $this->request->getPost('id');
                $arr[1] = $this->request->getPost('njumphash');
                $njump = Njumps::findFirst(array('1=1 ' . $sql . ' AND id = ?0 AND njumphash LIKE ?1 ', "bind" => $arr));
                if (empty($njump)) {
                    echo 1;
                    return;
                }
                $newnjump = $this->newnjumpclonerow($njump);
            }


            if (!$newnjump) {
                echo '1';
                return;
            } else
                $this->echonewnjump($newnjump);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__ . __METHOD__, $ex->getMessage());
            echo 1;
        }
    }

    private function newnjumprow($njump, $offerhash = null) {
        try {
            if (isset($offerhash)) {
                $off = Mask::findFirst(array('hash = ?0', 'bind' => array($offerhash)));
                if (empty($off)) {
                    throw Exception('No offer found');
                }
            }
            $newnjump = new Njumps();
            $newnjump->njumphash = $njump->njumphash;
            $newnjump->njumpgeneratedname = $njump->njumpgeneratedname;
            $newnjump->globalname = $njump->globalname;
            $newnjump->country = $njump->country;
            $newnjump->source = $njump->source;
            $newnjump->offerhash = isset($off) ? $off->hash : 'changeme';
            $newnjump->offername = isset($off) ? $off->campaign : '';
            $newnjump->linename = isset($off) ? $off->campaign : 'changeme';
            $newnjump->sourcename = $njump->sourcename;
            $newnjump->proportion = '0';
            $newnjump->status = 0;
            /* $newnjump->carrier = $njump->carrier;
              $newnjump->os = $njump->os;
              $newnjump->lpid = $njump->lpid;
              $newnjump->lpurl = $njump->lpurl;
              $newnjump->time = $njump->time;
              $newnjump->sback = $njump->sback;
              $newnjump->isp = $njump->isp;
              $newnjump->epc = $njump->epc;
              $newnjump->status = $njump->status;
              $newnjump->clicks = $njump->clicks; */
            $newnjump->domain = $njump->domain;
            $newnjump->sback = 0;
            $newnjump->clicks = 0;
            $newnjump->epc = 0;
            $newnjump->counter = $njump->counter;
            $newnjump->deleted = 0;
            $newnjump->area = $njump->area;
            $newnjump->insertTimestamp = date('Y-m-d H:i:s');
            $newnjump->editedTimestamp = date('Y-m-d H:i:s');
            $newnjump->createdby = $this->userid;
            $newnjump->favorite = $njump->favorite;
            $newnjump->editedby = $this->userid;
            if ($newnjump->save() == false) {
                $arr = '';
                foreach ($newnjump->getMessages() as $message)
                    $arr .= $message;
                throw new Exception($arr);
            }
            return $newnjump;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__ . __METHOD__, $ex->getMessage());
            return null;
        }
    }

    private function newnjumpclonerow($njump) {
        try {
            $newnjump = new Njumps();
            $newnjump->njumphash = $njump->njumphash;
            $newnjump->njumpgeneratedname = $njump->njumpgeneratedname;
            $newnjump->globalname = $njump->globalname;
            $newnjump->country = $njump->country;
            $newnjump->source = $njump->source;
            $newnjump->offerhash = $njump->offerhash;
            $newnjump->offername = $njump->offername;
            $newnjump->linename = 'clone' . $njump->linename;
            $newnjump->sourcename = $njump->sourcename;
            $newnjump->proportion = $njump->proportion;
            $newnjump->carrier = $njump->carrier;
            $newnjump->carrierids = $njump->carrierids;
            $newnjump->status = $njump->status == 1 ? 0 : $njump->status;
            $newnjump->os = $njump->os;
            $newnjump->osids = $njump->osids;
            $newnjump->lpid = $njump->lpid;
            $newnjump->lpurl = $njump->lpurl;
            $newnjump->time = $njump->time;
            $newnjump->sback = $njump->sback;
            $newnjump->isp = $njump->isp;
            $newnjump->clicks = 0;
            $newnjump->epc = 0;
            $newnjump->ispids = $njump->ispids;
            $newnjump->domain = $njump->domain;
            $newnjump->counter = $njump->counter;
            $newnjump->deleted = $njump->deleted;
            $newnjump->area = $njump->area;
            $newnjump->insertTimestamp = date('Y-m-d H:i:s');
            $newnjump->editedTimestamp = date('Y-m-d H:i:s');
            $newnjump->createdby = $this->userid;
            $newnjump->favorite = $njump->favorite;
            $newnjump->editedby = $this->userid;
            if ($newnjump->save() == false) {
                throw new Exception(json_encode($newnjump->getMessages()));
            }
            return $newnjump;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__ . __METHOD__, $ex->getMessage());
            return null;
        }
    }

    private function echonewnjump($njump) {
        try {
            $returnnjump = array();
//$defaultvalue = new \Phalcon\Db\RawValue('default');
            foreach ($this->njumpfields as $field) {
//if (isset($njump->$field))
                if (($field == 'clicks' || $field == 'sback') && empty($njump->$field)) {
                    $returnnjump[$field] = '0';
                } else if ($field == 'epc') {
                    $returnnjump[$field] = '0.000';
                } else
                    $returnnjump[$field] = (!isset($njump->$field) || $njump->$field == 'default') ? '' : $njump->$field;
            }
            /* array_walk_recursive($returnnjump, function (&$item, $key) {
              $item = utf8_encode($item);
              }); */
            echo json_encode($returnnjump);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__ . __METHOD__, $ex->getMessage());
            echo 1;
            return null;
        }
    }

    public function njumpdeleterowAction() {
        try {
            if (!$this->issetRequest($this->request->get('id')) || !$this->issetRequest($this->request->get('njumphash'))) {
                echo '1';
                return;
            }
            $sql = 'UPDATE njumps SET deleted = 1 WHERE njumphash = :njumphash AND id = :id ' . ($this->filter ? // " AND ( createdby = $this->userid OR editedby = $this->userid) "
                    " AND area = $this->area " : '' );
            $sql = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($sql, array('njumphash' => $this->request->get('njumphash'), 'id' => $this->request->get('id')), array());
            $db = $this->getDi()->getDb4();
            $prep = $db->prepare('CALL sp_checknjumpstatus(?,NULL,@myout1,@myout2);');
            $njumphash = $this->request->getPost('njumphash');
            $prep->bindParam(1, $njumphash, PDO::PARAM_STR);
            $prep->execute();
            $prep->closeCursor();
            if ($exe->rowCount() > 0)
                echo '0';
            else
                echo '1';
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    public function getnjumpAction() {
        try {
            if (!$this->issetRequest($this->request->get('njumphash'))) {
                echo '2'; //no njump
                return;
            }
            $sql = 'SELECT id, njumphash, globalname, country, source, offerhash, offername,
            linename, sourcename, proportion, carrier, lpurl, time, sback, isp,
            epc, status, area,
            favorite, insertTimestamp, editedTimestamp, FROM njumps WHERE njumphash = :njumphash and deleted = 0 ORDER BY clicks, offername';
            $sql = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($sql, array(), array());
            $v = $exe->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($v);
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    public function getStatisticsAction() {
        try {
            if (!$this->issetRequest($this->request->get('njumphash'))) {
                echo '1'; //no njump
                return;
            }
            $sql = 'SELECT ns.id as id, COALESCE(ns.clicks,0) as clicks,COALESCE(ns.rev,0) as rev,'
                    . 'COALESCE(ns.epc,0) as epc,COALESCE(clicks1days,0) as clicks1days,'
                    . 'COALESCE(rev1days,0) as rev1days,COALESCE(epc1days,0) as epc1days,'
                    . 'COALESCE(clicks3days,0) as clicks3days,'
                    . 'COALESCE(rev3days,0) as rev3days,'
                    . 'COALESCE(epc3days,0) as epc3days,'
                    . 'COALESCE(clicks7days,0) as clicks7days,'
                    . 'COALESCE(rev7days,0) as rev7days,'
                    . 'COALESCE(epc7days,0) as epc7days,'
                    . 'COALESCE(clicks1hours,0) as clicks1hours,'
                    . 'COALESCE(rev1hours,0) as rev1hours,'
                    . 'COALESCE(epc1hours,0) as epc1hours,'
                    . 'COALESCE(clicks3hours,0) as clicks3hours,'
                    . 'COALESCE(rev3hours,0) as rev3hours,'
                    . 'COALESCE(epc3hours,0) as epc3hours,'
                    . 'COALESCE(clicks6hours,0) as clicks6hours,'
                    . 'COALESCE(rev6hours,0) as rev6hours,'
                    . 'COALESCE(epc6hours,0) as epc6hours,'
                    . 'COALESCE(clicks12hours,0) as clicks12hours,'
                    . 'COALESCE(rev12hours,0) as rev12hours,'
                    . 'COALESCE(epc12hours,0) as epc12hours '
                    . 'FROM njumpstats ns  '
                    . ' WHERE ns.njumphash = :njumphash and ns.deleted = 0 ';
            $sql = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($sql, array('njumphash' => $this->request->get('njumphash')), array());
            $v = $exe->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($v);
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    public function updatecellAction() {
        try {
            $a = new DateTime();

            $val = 1;
            if ($this->issetRequest($this->request->getPost('type')) && $this->issetRequest($this->request->getPost('njumphash'))) {
                $val = $this->theBigSwitch($this->request->getPost('type'), $this->request->getPost('njumphash'), $this->request->getPost('idLine'), $this->request->getPost('content'));
                if ($val == 0) {
                    $tableidstatus = 0;
                    $njumpstatus = 0;
                    $njumphash = $this->request->getPost('njumphash');
                    $njumpid = $this->request->getPost('idLine');
                    $db = $this->getDi()->getDb4();
                    $prep = $db->prepare('CALL sp_checknjumpstatus(?,?,@myout1,@myout2);');
                    $prep->bindParam(1, $njumphash, PDO::PARAM_STR);
                    $prep->bindParam(2, $njumpid, PDO::PARAM_INT);
                    $prep->execute();
                    $prep->closeCursor();
//sleep(15);
                    $res = $db->query("SELECT @myout1 as myout1, @myout2 as myout2");
                    $res = $res->fetchAll(PDO::FETCH_ASSOC);
                    if (isset($res) && isset($res[0])) {
                        $njumpstatus = $res[0]['myout1'];
                        $tableidstatus = $res[0]['myout2'];
                    }
                }
            }
            echo json_encode(array('error' => $val, 'tableidstatus' => isset($tableidstatus) ? $tableidstatus : '0', 'njumpstatus' => isset($njumpstatus) ? $njumpstatus : '0'));
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
//mail('pedrorleonardo@gmail.com', 'njump edit took' . $interval, "");
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    private function theBigSwitch($type, $njumphash, $njumpid, $value) {
        try {
            $val = 1;
            switch ($type) {
//offerhash
                case 0:
                    $val = $this->updateoffer($njumphash, $njumpid, $value);
                    break;
//linename
                case 1:
                    $val = $this->updatesimple('linename', $njumphash, $njumpid, $value);
                    break;
//proportion
                case 2:
                    $val = $this->updatesimple('proportion', $njumphash, $njumpid, $value);
                    break;
//carrier
                case 3:
                    $val = $this->updateComplex('carrier', $njumphash, $njumpid, $value);
                    break;
//lpurl
                case 4:
                    $val = $this->updateComplex('lpurl', $njumphash, $njumpid, $value);
                    break;
//os
                case 5:
                    $val = $this->updateComplex('os', $njumphash, $njumpid, $value);
                    break;
//isp
                case 6:
                    $val = $this->updateComplex('isp', $njumphash, $njumpid, $value);
                    break;
//time
                case 7:
                    $val = $this->updatesimple('time', $njumphash, $njumpid, $value);
                    break;
//sback
                case 8:
                    if (!empty($value) && $value == '1,2,3') {
                        $value = '0';
                    }
                    $val = $this->updatesimple('sback', $njumphash, $njumpid, $value);
                    break;
//domain
                case 9:
                    $val = $this->updateDomain($njumphash, $value);
                    break;
                default:
                    break;
            }
            return $val;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            return 1;
        }
    }

    private function updateDomain($njumphash, $value) {
        try {
            $filtersql = '';
            $arrwithoutempties = array();
            if ($this->filter) {
                $filtersql = (isset($this->area) ? " AND area = $this->area " : '' )
                        . (isset($this->countriesauth) ? ' AND find_in_set(country, "' . $this->countriesauth . '")' : '' )
                        . (isset($this->sourcesauth) ? ' AND find_in_set(source, "' . $this->sourcesauth . '")' : '' );
            }
            $sql = "UPDATE njumps n, (SELECT id FROM dim__domains WHERE id = ? ) B"
                    . " SET n.domain=B.id, n.editedTimestamp = n.editedTimestamp,n.editedBy=$this->userid "
                    . " WHERE njumphash = ? " . $filtersql . ';
            UPDATE njumps SET counter = ( SELECT counter FROM (SELECT MAX(counter) as counter FROM njumps WHERE njumphash = ? )B ) +1, editedTimestamp = editedTimestamp WHERE njumphash = ?;';
            $arrwithoutempties[] = $value;
            $arrwithoutempties[] = $njumphash;
            $arrwithoutempties[] = $njumphash;
            $arrwithoutempties[] = $njumphash;
//HelpingFunctions::writetolog("I\t", $sql . serialize($arrwithoutempties));
            $sqlstatment = $this->getDi()->getDb4()->prepare($sql);
            $this->getDi()->getDb4()->executePrepared($sqlstatment, $arrwithoutempties, array());
            return 0;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, (isset($sql) ? $sql : '') . $ex->getMessage());
            return 1;
        }
    }

    private function updateComplex($type, $hash, $id, $val) {
        try {
            $filtersql = '';
            if ($this->filter) {
                $filtersql = (isset($this->area) ? " AND area = $this->area " : '' )
                        . (isset($this->countriesauth) ? ' AND find_in_set(country, "' . $this->countriesauth . '")' : '' )
                        . (isset($this->sourcesauth) ? ' AND find_in_set(source, "' . $this->sourcesauth . '")' : '' );
            }
            $arrwithoutempties = array();
            $arr2 = explode(',', $val);
            sort($arr2);
            $val = implode(',', $arr2);
            if (empty($arr2)) {
                $arrwithoutempties[] = '-1';
            } else {
                $arrwithoutempties = $arr2;
            }

            $namecolumn = "name";
            if ($type == 'carrier') {
                $typeids = "carrierids";
                $table = 'dim__carrier';
            } else if ($type == 'os') {
                $typeids = "osids";
                $table = 'dim__os';
            } else if ($type == 'isp') {
                $typeids = "ispids";
                $table = 'dim__isp';
            } else {
                $typeids = "lpid";
                $namecolumn = "url";
                $table = 'dim__landingpages';
            }

            $sql = "UPDATE njumps n, (SELECT GROUP_CONCAT(IF(id IS NULL, NULL,id) ) as ids, GROUP_CONCAT(IF($namecolumn='' OR name IS NULL, NULL,$namecolumn) ) as name FROM $table " . (!empty($val) ? ' WHERE id IN (' . str_repeat('?,', count($arr2) - 1) . '?' . ')' : " WHERE id IN (?)") . ") B"
                    . " SET n.$type = B.name, n.$typeids = B.ids, n.editedBy = $this->userid "
                    . " WHERE id = ? " . $filtersql . ';
            UPDATE njumps SET counter = ( SELECT counter FROM (SELECT MAX(counter) as counter FROM njumps WHERE njumphash = ? )B ) +1, editedTimestamp = editedTimestamp WHERE njumphash = ?;';

            $arrwithoutempties[] = $id;
            $arrwithoutempties[] = $hash;
            $arrwithoutempties[] = $hash;

            /* HelpingFunctions::writetolog("I\t", $sql . serialize($arrwithoutempties));
              HelpingFunctions::writetolog("I\t", str_repeat('?,', count($arr2) - 1));
              HelpingFunctions::writetolog("I\t", count($arr2));
             */
            $sqlstatment = $this->getDi()->getDb4()->prepare($sql);
            $this->getDi()->getDb4()->executePrepared($sqlstatment, $arrwithoutempties, array());
            return 0;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__ . ' ', (isset($sql) ? $sql : '') . $ex->getMessage());
            return 1;
        }
    }

    private function updatesimple($column, $hash, $rowid, $value) {
        try {
            $filtersql = '';
            if ($this->filter) {
                $filtersql = (isset($this->area) ? " AND area = $this->area " : '' )
                        . (isset($this->countriesauth) ? ' AND find_in_set(country, "' . $this->countriesauth . '")' : '' )
                        . (isset($this->sourcesauth) ? ' AND find_in_set(source, "' . $this->sourcesauth . '")' : '' );
            }
            $inputparameters = array();
            if ($value == 'null' || $value == '{}') {
                $valueparam = 'DEFAULT';
            } else {
                $valueparam = ':val';
                $inputparameters['val'] = $value;
            }
            $sql = 'UPDATE njumps SET ' . $column . " = $valueparam, editedBy = $this->userid WHERE id = :number " . $filtersql . ';
            UPDATE njumps SET counter = ( SELECT counter FROM (SELECT MAX(counter) as counter FROM njumps WHERE njumphash = :njumphash )B ) +1, editedTimestamp = editedTimestamp WHERE njumphash = :njumphash;
            ;';
            $sqlstatment = $this->getDi()->getDb4()->prepare($sql);
            $inputparameters['number'] = $rowid;
            $inputparameters['njumphash'] = $hash;
            //HelpingFunctions::writetolog("I\t", $sql . serialize($inputparameters));

            $this->getDi()->getDb4()->executePrepared($sqlstatment, $inputparameters, array());
            return 0;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, (isset($sql) ? $sql : '') . $ex->getMessage());
            return 1;
        }
    }

    private function updateOffer($hash, $id, $offerhash) {
        try {

            $filtersql = '';
            if ($this->filter) {
                $filtersql = (isset($this->area) ? " AND area = $this->area " : '' )
                        . (isset($this->countriesauth) ? ' AND find_in_set(country, "' . $this->countriesauth . '")' : '' )
                        . (isset($this->sourcesauth) ? ' AND find_in_set(source, "' . $this->sourcesauth . '")' : '' );
            }

            $sql = 'UPDATE njumps SET editedBy=' . $this->userid . ', offerhash = :offerhash, offername = (SELECT campaign FROM Mask WHERE hash = :offerhash LIMIT 1)
            WHERE id = :number ' . $filtersql . ';
            UPDATE njumps SET counter = ( SELECT counter FROM (SELECT MAX(counter) as counter FROM njumps WHERE njumphash = :njumphash )B ) +1, editedTimestamp = editedTimestamp WHERE njumphash = :njumphash;CALL sp_checknjumpsoffers();';
            $sqlstatment = $this->getDi()->getDb4()->prepare($sql);
            $this->getDi()->getDb4()->executePrepared($sqlstatment, array('offerhash' => $offerhash, 'number' => $id, 'njumphash' => $hash), array());
            return 0;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            return 1;
        }
    }

    private function getFavoriteOnes() {
        try {
            $njumps = "SELECT MAX(njumpgeneratedname) as generatedname, MAX(globalname) as globalname, MAX(favorite) as favorite, MAX(counter) as counter, nj.njumphash, "
                    . "Coalesce(SUM(ns.clicks), 0) as clicks, COALESCE(SUM(ns.clicks3days), 0) as clicks3days, MAX(nj.status) as status, IF(SUM(ns.clicks) IS NULL, 0.0000, FORMAT(SUM(ns.rev)/SUM(ns.clicks),6)) as epc, COALESCE(IF(SUM(ns.clicks3days) IS NULL, 0.0000, FORMAT(SUM(ns.rev3days)/SUM(ns.clicks3days),6)),'0.000000') as epc3days, FORMAT(Coalesce(SUM(ns.rev), 0),2) as rev, FORMAT(Coalesce(SUM(rev3days), 0),2) as rev3days, "
                    . "MAX(CASE WHEN topoffer = 1 THEN offername ELSE NULL END) as topoffer, MAX(CASE WHEN worstoffer = 1 THEN offername ELSE NULL END) as worstoffer, "
                    . "nj.njumphash, nj.country as country, nj.source as source, nj.sourcename as sourcename, CONCAT('http://njump.', COALESCE(d.domain, 'youmobistein.com'), '/?jp=', nj.njumphash, COALESCE(ss.name, ''), '&linkref=', source, '_') as url FROM (SELECT njumphash FROM njumps WHERE deleted = 0 and (createdby = $this->userid OR editedby = $this->userid) GROUP BY njumphash ORDER BY favorite DESC, counter DESC LIMIT 12 ) n "
                    . "LEFT JOIN njumps nj ON nj.njumphash = n.njumphash left join njumpstats ns ON ns.id = nj.id LEFT JOIN dim__domains d ON d.id = nj.domain LEFT JOIN dim__sourceparams ss ON ss.id = nj.source WHERE nj.deleted = 0 GROUP BY nj.njumphash ORDER BY favorite DESC, counter desc";
            $sqlstatment = $this->getDi()->getDb4()->prepare($njumps);
            $exe = $this->getDi()->getDb4()->executePrepared($sqlstatment, array(), array());
            $v = $exe->fetchAll(PDO::FETCH_ASSOC);
            //mail('pedrorleonardo@gmail.com', 'sql', $njumps);
            //throw new Exception($njumps);
            return $v;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
        }
    }

    private function findnewid($country, $source, $area) {
        try {

            $generatedname = strtolower($country) . '_' . $source . "_" . strtolower($area->name) . '_';
            $sql = 'SELECT id, CAST( REPLACE(njumpgeneratedname, :generatedname, "") AS UNSIGNED) as generatednewname FROM njumps WHERE country LIKE :country AND source = :source AND area = :area AND njumpgeneratedname RLIKE :rgeneratedname ORDER by generatednewname DESC LIMIT 1';
            //mail('pedrorleonardo@gmail.com','test',$sql);
            //exit();
            $sql = $this->getDi()->getDb4()->prepare($sql);
            $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('generatedname' => $generatedname, 'country' => $country, 'source' => $source, 'area' => $area->id, 'rgeneratedname' => ('^' . $generatedname . '[0-9]*$' )), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_INTEGER, \Phalcon\Db\Column::TYPE_INTEGER, \Phalcon\Db\Column::TYPE_VARCHAR));
            $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
            if (empty($array_ret)) {
                $njumpname = $generatedname . '1';
                //mail('pedrorleonardo@gmail.com', 'entrei', 'fuck' . serialize($array_ret));
            } else {
                //mail('pedrorleonardo@gmail.com', 'entrei2', 'fuck2' . serialize($array_ret));
                $newnjumpid = ( (!empty($array_ret[0]) && !empty($array_ret[0]['generatednewname']) ) ? ($array_ret[0]['generatednewname'] + 1) : '1' );
                $njumpname = $generatedname . ($newnjumpid);
                //mail('pedrorleonardo@gmail.com', 'entrei2', $njumpname);
            }
            HelpingFunctions::writetolog("I" . "\t" . __CLASS__, $njumpname);
            return $njumpname;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            return 1;
        }
    }

    private function issetRequest($variable = null) {
        try {
            if (!isset($variable) || $variable == 'undefined' || $variable == '' || $variable == ' ') {
                //echo 'not set';
                return false;
            } else {
                //echo 'setted';
                return true;
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());

            echo $ex->getMessage() . __LINE__;
            return false;
        }
    }

    /*
     * id,njumphash,globalname,country,source,offerhash,offername,
      linename,sourcename,proportion,carrier,lpurl,time,sback,isp,
      epc,status,counter,deleted,area,
      insertTimestamp,editedTimestamp,createdby,favorite,editedby,
     */
}

<?php

class LandingmanagerController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Landing Manager');
        parent::initialize();
    }

    public function indexAction() {
        $auth = $this->session->get('auth');
    }

    public function getDimsAction() {

        $lp = new LpManager();

        $res = $lp->getFilters();

        echo json_encode($res);
    }

    public function createeditlpAction() {
        try {
            //$this->view->disable();
            if ($this->request->get('idLp') != null) {
                $query = "SELECT GROUP_CONCAT(DISTINCT l.name) as languages, lp.languages as language, lp.verticals as verticals, lp.domain as domainid, lp.servername as servernameid , lp.comments, lp.url, d.name as domain, lp.id as id, s.name as servername, GROUP_CONCAT(DISTINCT v.name) as vertical, lp.id as id, lp.name as name, CAST(lp.insertTimestamp as DATE) as createdate, u.username as createdBy "
                        . "FROM dim__landingpages lp inner join dim__languages l ON CONCAT(',',lp.languages,',') LIKE CONCAT('%,',l.id,',%') "
                        . "inner join dim__domains d ON lp.domain LIKE d.id inner join dim__servers s ON lp.servername LIKE s.id "
                        . "inner join offerpack__vertical v ON CONCAT(',',lp.verticals,',') LIKE CONCAT('%,',v.id,',%') "
                        . "inner join users u ON lp.createdBy = u.id "
                        . "WHERE 1 = 1 AND lp.id = ? GROUP BY lp.id";
                $arr = array($this->request->get('idLp'));
                $sql = $this->getDi()->getDb4()->prepare($query);
                $prepared = $this->getDi()->getDb4()->executePrepared($sql, $arr, array());
                $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($array_ret)) {
                    $this->view->setVar('edit', 'true');
                    $this->view->setVar('editid', $this->request->get('idLp'));
                    $this->view->setVar('lpvar', json_encode(array('vertical' => $array_ret[0]['verticals']
                        , 'servername' => $array_ret[0]['servernameid']
                        , 'languages' => $array_ret[0]['language']
                        , 'domain' => $array_ret[0]['domainid']
                        , 'comments' => $array_ret[0]['comments']
                        , 'name' => $array_ret[0]['name']
                        , 'url' => $array_ret[0]['url'])));
                }
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E', $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function setFilter2Action() {
        try {
            $verticals = $this->request->getPost('verticals');
            $domains = $this->request->getPost('domains');
            $serversname = $this->request->getPost('serversname');
            $languages = $this->request->getPost('languages');
            $sql = '';
            $arr = array();
            if (!empty($verticals)) {
                $sql .= " AND ( ";
                $verticals = explode(',', $verticals);
                foreach ($verticals as $key => $vert) {
                    $arr[] = '%,' . $vert . ',%';
                    $sql .= " CONCAT(',',lp.verticals,',') LIKE (?) OR";
                }
                $sql = rtrim($sql, 'OR');
                $sql .= ' )';
            }
            if (!empty($domains)) {
                $domains = explode(',', $domains);
                $in = str_repeat('?,', count($domains) - 1) . '?';
                $sql .= " AND lp.domain IN ($in) ";
                $arr = array_merge($arr, $domains);
            }
            if (!empty($serversname)) {
                $serversname = explode(',', $serversname);
                $in = str_repeat('?,', count($serversname) - 1) . '?';
                $sql .= " AND lp.servername IN ($in) ";
                $arr = array_merge($arr, $serversname);
            }
            if (!empty($languages)) {
                $sql .= " AND ( ";
                $languages = explode(',', $languages);
                foreach ($languages as $lang) {
                    $arr[] = '%,' . $lang . ',%';
                    $sql .= " CONCAT(',',lp.languages,',') LIKE (?) OR";
                }
                $sql = rtrim($sql, 'OR');
                $sql .= ' )';
            }


            $query = "SELECT GROUP_CONCAT(DISTINCT l.name) as languages,lp.url, d.domain as domain, lp.id as id, s.name as servername, GROUP_CONCAT(DISTINCT v.name) as vertical, lp.id as id, lp.name as name, CAST(lp.insertTimestamp as DATE) as createdate, u.username as createdBy "
                    . "FROM dim__landingpages lp inner join dim__languages l ON CONCAT(',',lp.languages,',') LIKE CONCAT('%,',l.id,',%') "
                    . "inner join dim__domains d ON lp.domain LIKE d.id inner join dim__servers s ON lp.servername LIKE s.id "
                    . "inner join offerpack__vertical v ON CONCAT(',',lp.verticals,',') LIKE CONCAT('%,',v.id,',%') "
                    . "inner join users u ON lp.createdBy = u.id "
                    . "WHERE 1 = 1 $sql GROUP BY lp.id";
            //mail('pedrorleonardo@gmail.com', 'mensagem de query', $query);
            $sql = $this->getDi()->getDb4()->prepare($query);
            $prepared = $this->getDi()->getDb4()->executePrepared($sql, $arr, array());
            $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($array_ret)) {
                $finalres = array();
                foreach ($array_ret as $row)
                    $finalres[] = array('id' => $row['id'], 'name' => $row['name'], 'vertical' => $row['vertical'], 'language' => $row['languages'], 'url' => $row['url'], 'domain' => $row['domain'], 'servername' => $row['servername'], 'createdat' => $row['createdate'], 'createdby' => $row['createdBy']);

                echo json_encode($finalres);
            } else
                echo 0;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E', $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function newEditLpAction() {
        try {

            if ($this->request->get('lpId') == null) {
                $a = new LpManager();
            } else
                $a = LpManager::findFirst(array('id = :id: ', 'bind' => array('id' => $this->request->get('lpId'))));


            $a->name = $this->request->getPost('name');
            $a->url = $this->request->getPost('url');
            $a->description = $this->request->getPost('comments');
            $a->verticals = $this->request->getPost('vertical');
            $a->servername = $this->request->getPost('servername');
            $a->domain = $this->request->getPost('domain');
            $a->languages = $this->request->getPost('language');
            $a->name = $this->request->getPost('name');
            $a->createdBy = $this->session->get('auth')['id'];
            $a->insertTimestamp = date('Y-m-d H:i:s');
            $a->editTimestamp = new \Phalcon\Db\RawValue('default');
            $a->status = new \Phalcon\Db\RawValue('default');
            if ($a->save() == false) {
                $texts = $a->getMessages();
                HelpingFunctions::writetolog('E', 'could not create lp because ' . implode(',', $texts));
                echo 'NOT OKAY,cause:' . implode(',', $texts);
            } else {
                echo 'ok';
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E', $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function getlpinfoAction() {
        try {

            if ($this->request->get('lpId') == null) {
                echo '<br><span class="spanTooltip">Description:</span> No description :(';
            } else
                $a = LpManager::findFirst(array('id = :id: ', 'bind' => array('id' => $this->request->get('lpId'))));

            if (!empty($a) && $a->comments)
                echo '<br><span class="spanTooltip">Description:</span> ' . $a->comments;
            else
                echo '<br><span class="spanTooltip">Description:</span> No description :(';
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E', $ex->getMessage());
            echo $ex->getMessage();
        }
    }



    public function getFiltersAction() {
        try {

            $lp = new LpManager();

            $res = $lp->getFiltersLpManager();

            echo json_encode($res);           

        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E', $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function getOfferByGeoAction() {
        try {

            $geo = $this->request->getPost('geo');
            $client = $this->request->getPost('client');

            if(isset($geo) && isset($client)) {
                $lp = new LpManager();

                $res = $lp->getOffers($geo, $client);

                echo json_encode($res);
            } else {
                echo "Please select geo and client.";
            }

        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E', $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function setFilterAction() {
        try {

            $array_data = array(
                            'verticals' => $this->request->getPost('verticals'),
                            'languages' => $this->request->getPost('languages'),
                            'domain' => $this->request->getPost('domains'),
                            'countries' => $this->request->getPost('geos'),
                            'ethnicity' => $this->request->getPost('ethini'),
                            'clients' => $this->request->getPost('clients'),
                            'offers' => $this->request->getPost('offers'),
                            'id' => $this->request->getPost('id'),
                            'name' => $this->request->getPost('name')
                        );

            $lp = new LpManager();

            $res = $lp->getFilterRows($array_data);

            echo json_encode($res);

        } catch (Exception $ex) {
            //HelpingFunctions::writetolog('E', $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function newlpAction() {
       
    }

    public function viewlpAction() {
       
    }

    public function getLpInformationViewAction() {
        $lpId = $this->request->getPost('lpId');

        $lp = new LpManager();

        $resp = $lp->getLpView($lpId);

        echo json_encode($resp[0]);        
    }

    public function getLpInformationAction() {
        $lpId = $this->request->getPost('lpId');

        $lp = new LpManager();

        $resp = $lp->getLp($lpId);

        echo json_encode($resp);
    }

    public function saveLpAction() {
        $auth = $this->session->get('auth');

        $array_data = array(
                        'verticals' => $this->request->getPost('verticals'),
                        'languages' => $this->request->getPost('languages'),
                        'domains' => $this->request->getPost('domains'),
                        'countries' => $this->request->getPost('countries'),
                        'ethnicity' => $this->request->getPost('ethnicity'),
                        'clients' => $this->request->getPost('clients'),
                        'offers' => $this->request->getPost('offers'),
                        'url' => $this->request->getPost('url'),
                        'name' => $this->request->getPost('name'),
                        'comments' => $this->request->getPost('comments'),
                        'saveType' => $this->request->getPost('saveType'), 
                        'lpId' => $this->request->getPost('lpId')
                    );



        $lp = new LpManager();

        $resp = $lp->insertLp($array_data, $auth);
       
        echo $resp;
    
    }

    public function deleteLpAction() {

        $lpid = $this->request->getPost('lpid');

        $lp = new LpManager();

        $resp = $lp->deleteLpbyid($lpid);

    }

}

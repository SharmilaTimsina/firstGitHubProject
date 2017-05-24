<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OperationController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Operation');
        parent::initialize();
    }

    public function indexAction() {
        $auth = $this->session->get('auth');
        if ($this->request->get('s') != null and $this->request->get('e') != null and $this->request->get('c') != null and $this->request->get('val') != null) {
            $sdate = $this->request->get('s');
            $edate = $this->request->get('e');
            $campaignID = $this->request->get('c');
            $newCPA = $this->request->get('val');
            $operation = new Operation();
            $bol = $operation->updateCPA($sdate, $edate, $campaignID, $newCPA);

            $bol ? $this->view->setVar("cmdCompleted", '<div class="alert alert-success" role="alert">Completed</div>') :
                            $this->view->setVar("cmdCompleted", '<div class="alert alert-warning" role="alert">Something went wrong. Contact your administrator.</div>');
        }
        if ($this->dispatcher->getParam("jump") != null) {
            $this->view->setVar("createJump", $this->dispatcher->getParam("jump"));
        }
        $res = $this->getCampaigns();
        $aggres = $this->getAggregators();
        $sourcesRes = $this->getSources();
        $db = $this->getDi()->getDb4();
        $msourcesres = $db->query('select id, sourceName FROM Sources WHERE affiliate = 2;')->fetchAll();
        $msources = '<option></option>';
        foreach ($msourcesres as $source) {
            $msources .= '<option value="' . $source['id'] . '">' . $source['sourceName'] . '-' . $source['id'] . '</option>';
        }
        $categoriesRes = $this->getCategories();
        $this->view->setVar("campaignSelectList", $res);
        $this->view->setVar("userLevel", $this->session->get('auth')['userlevel']);
        $this->view->setVar("userid", $this->session->get('auth')['id']);
        $this->view->setVar("aggregatorsList", $aggres);
        $this->view->setVar("sourcesList", $sourcesRes);
        $this->view->setVar("msources", $msources);
        $this->view->setVar("categoriesList", $categoriesRes);
        $this->view->setVar("lvl", $auth['userlevel']);
        $this->view->setVar("nav", $auth['navtype']);
        $this->view->setVar("uid", $auth['id']);
        $this->view->setVar("selectboxsourcestocopy", $this->getSourcesInvestment());
        $this->view->setVar("selectboxsourcestocopy2", $this->getSourcesInvestment2());

        return;
    }

    public function mainstreamsourcenewnameAction() {
        $newname = $this->request->get('newname');
        $sourceid = $this->request->get('sourceid');
        if (!empty($newname) && !empty($sourceid)) {
            $db = $this->getDi()->getDb4();
            $prepared = $db->prepare('UPDATE Sources SET sourceName = :sourceName WHERE affiliate = 2 and id = :id');
            $db->executePrepared($prepared, array('sourceName' => $newname, 'id' => $sourceid), array());
        }
        $result = '<div class="alert alert-success" role="alert">Name changed</div>';
        $this->redirectWithResponse($result);
        return;
    }

    public function jumpAction() {
        $auth = $this->session->get('auth');
        if ($this->request->get('agg') and $this->request->get('country') != null and $this->request->get('campaignName') != null and $this->request->get('campaignURL') != null
                and $this->request->get('campaigncpa') != null and $this->request->get('campaigncurrency') != null
        ) {
            $agg = $this->request->get('agg');
            $country = $this->request->get('country');
            $campaignName = $this->request->get('campaignName');
            if (preg_match('/[^a-zA-Z0-9]+/', $campaignName)) {
                $result = '<div class="alert alert-warning" role="alert">Please use only letters and numbers for campaign name.</div>';
                $this->redirectWithResponse($result);
                return false;
            }
            $mask = new Mask();
            if (!($mask->checkCampaign($campaignName))) {
                $result = '<div class="alert alert-warning" role="alert">Campaign already exists. Please, use other name.</div>';
                $this->redirectWithResponse($result);
                return false;
            }
            $mainstreamtype = ($this->request->get('mainstreamtype') != null && $this->request->get('mainstreamtype') == 1) ? 2 : 0;
            $mainstreamCategory = $mainstreamtype == 2 ? $this->request->get('newjumpcategory') : null;
            $campaignURL = $this->request->get('campaignURL');
            $campaignURL = $this->addhttp($campaignURL);
            $campaigncpa = $this->request->get('campaigncpa');
            $campaigncurrency = $this->request->get('campaigncurrency');
            $affiliate = '';
            if (null != $this->request->get('affiliate')) {
                $affiliate = strtolower($this->request->get('affiliate'));
            }

            $hash = uniqid();
            $cpaField = 'cpa';
            $currencyType = 'USD';
            if ($campaigncurrency != 'USD') {
                $cpaField = 'cpaOriginalValue';
                $currencyType = $campaigncurrency;
            }
            $campaignName = str_replace(' ', '', $campaignName);
            $campaignURL = str_replace(' ', '', $campaignURL);
            $res = $mask->createMask($cpaField, $hash, $agg, $country, $campaignName, $campaignURL, $campaigncpa, $affiliate, $currencyType, $mainstreamtype);
            if (isset($mainstreamCategory) && $mainstreamCategory != '') {
                $operation = new Operation();
                $operation->insertCampaignCategory($hash, $mainstreamCategory);
            }
            if ($agg == 76) {//vuclips client, check its reports
                $vuclipsname = $this->request->get('vuclipcname');
                $newvuclip = new VuclipsCampaignNames();
                $newvuclip->hashMask = $hash;
                $newvuclip->vuclipsoffer = strtolower($vuclipsname);
                $newvuclip->status = 0;
                $newvuclip->insertTimestamp = date('Y-m-d H:i:s');
                if ($newvuclip->save() == false) {
                    $errors = '';
                    foreach ($newvuclip->getMessages() as $message) {
                        $errors .= $message;
                    }
                    mail('pedrorleonardo@gmail.com', 'could not create vuclip offer at vuclipsmetadata', $errors);
                }
            }
            $domain = $auth['userlevel'] > 2 ? 'http://jump.mobipiumlink.com/?jp=' : 'http://jump.youmobistein.com/?jp=';
            $generatedLink = $domain . $hash . '&id=' . $agg . '_' . $country . '_1_' . $campaignName . '_';
            $result = '<div class="alert alert-warning" role="alert"><a>Old link : ' . $campaignURL . '</a><br><a>New link : ' . $generatedLink . '</a></div>';
            $this->redirectWithResponse($result);
        } else {
            $result = '<div class="alert alert-warning" role="alert"><a>Something went wrong. Contact your administrator.</div>';
            $this->redirectWithResponse($result);
        }
    }

    public function updateCampaignAction() {
        $auth = $this->session->get('auth');
        if ($this->request->get('selectedCampaign') != null and $this->request->get('linkCamp') != null) {
            $cpa_originalValue = NULL;
            $cpaValue = NULL;
            $curtype = NULL;
            if ((null != $this->request->get('updatecpa')) && ($this->request->get('updatecpa') == '1') && (null != $this->request->get('campaigncpa'))) {
                $curtype = 'USD';
                if ($this->request->get('cpaCurrency') != 'USD') {
                    //we'll need to fix cpa to its conversation rate EUR -> USD etc etc
                    //get rate for the specific currency
                    $curtype = $this->request->get('cpaCurrency');
                    $currencyRate = $this->getRate($curtype);
                    $cpaValue = number_format($this->request->get('campaigncpa') * $currencyRate, 4);
                    $cpa_originalValue = $this->request->get('campaigncpa');
                } else {
                    $cpa_originalValue = '';
                    $cpaValue = $this->request->get('campaigncpa');
                }
            }

            $carrier = '';
            if (null != $this->request->get('affiliateCarrier')) {
                $carrier = strtolower($this->request->get('affiliateCarrier'));
            }
            $mainstreamtype = ($this->request->get('mainstreamtype') != null && $this->request->get('mainstreamtype') == 1) ? 2 : null;
            $mainstreamCategory = $mainstreamtype == 2 ? $this->request->get('campaignCategory') : null;
            $mask = new Mask();
            $mask->updateValues(NULL, NULL, NULL, NULL, NULL, $this->request->get('linkCamp'), $cpaValue, $cpa_originalValue, $curtype, $carrier, $this->request->get('selectedCampaign'), $mainstreamtype);
            $operation = new Operation();
            if ($mainstreamtype == 0) {
                $operation->removeCampaignCategory($this->request->get('selectedCampaign'));
            } else if (isset($mainstreamCategory) && $mainstreamCategory != '') {
                $operation->insertCampaignCategory($this->request->get('selectedCampaign'), $mainstreamCategory);
            }
            $result = '<div class="alert alert-warning" role="alert"><a>Campaign Updated</div>';
            $this->redirectWithResponse($result);
        } else {
            $result = '<div class="alert alert-warning" role="alert"><a>Something went wrong. Contact your administrator. Information missing.</div>';
            $this->redirectWithResponse($result);
        }
    }

    public function getCampaignLinkAction() {
        $mask = new Mask();
        if ($this->request->get('campID') != null) {
            $campID = $this->request->get('campID');
            $res = $mask->getCampaignLink($campID);
            echo $res;
        }
    }

    public function createSourceAction() {
        $auth = $this->session->get('auth');

        $operator = new Operation();

        $id = '';
        if ($this->request->get('src') != null) {
            $newSource = $this->request->get('src');
            $id = $operator->insertSource(strtolower($newSource), $this->request->get('sourcetype'), $auth);
            echo $id;
        }

        if ($this->request->get('investment') != false) {
            if ($this->request->get('copyfromthis') != 0) {
                $operator->insertIntoMetadata($this->request->get('copyfromthis'), $id, $this->request->get('bulks'));
            }
        }

        /*
          if ($this->request->get('src') != null) {
          $newSource = $this->request->get('src');
          //$id = $operator->insertSource(strtolower($newSource), $this->request->get('sourcetype'), $auth);
          //echo $id;
          }
         */
    }

    public function copySourceAction() {
        $auth = $this->session->get('auth');

        $operator = new Operation();

        $id = $this->request->get('copytothis');

        if ($this->request->get('investment') != false) {
            if ($this->request->get('copyfromthis') != 0) {
                $operator->insertIntoMetadata($this->request->get('copyfromthis'), $id, $this->request->get('bulks'));
            }
        }

        echo 2;

        /*
          if ($this->request->get('src') != null) {
          $newSource = $this->request->get('src');
          //$id = $operator->insertSource(strtolower($newSource), $this->request->get('sourcetype'), $auth);
          //echo $id;
          }
         */
    }

    public function createCategoryAction() {
        $operator = new Operation();

        if ($this->request->get('cat') != null) {
            $newCat = $this->request->get('cat');
            $id = $operator->insertCategory($newCat);
            echo $id;
        }
    }

    public function createAggregatorAction() {
        try {
            $operator = new Operation();
            $auth = $this->session->get('auth');
            if ($this->request->get('agg') != null && $this->request->get('tracking') != null) {
                $newSource = $this->request->get('agg');
                $newSource = str_replace('_', '-', $newSource);
                $tracking = $this->request->get('tracking');
                $company = $this->request->get('company');
                $res = $operator->insertAggregator($newSource, $tracking, $auth, $company);
                $auth = $this->session->get('auth');
                $cache = $this->di->get("viewCache");
                $cache->save('aggregators' . $auth['id'], null, 900);
                echo $res;
            }
        } catch (Exception $ex) {
            echo 0;
        }
    }

    public function findLatestConvAction() {
        try {
            $this->view->disable();
            if ($this->request->get('campaignname') != null) {
                $campaignname = $this->request->get('campaignname');
                $res = null;
                //$res = $this->getDi()->getDb2()->fetchAll('SELECT insertTimestamp, clickId,ccpa,CONCAT(fkSource,"_",format,"_",adNumber) as subid FROM Conversions' . date('mY') . ' WHERE campaignName = :name ORDER BY id DESC LIMIT 1 ', Phalcon\Db::FETCH_ASSOC, array('name' => $campaignname));
                $res2 = $this->getDi()->getDb7()->fetchAll('SELECT insertTimestamp, clickId,ccpa,CONCAT(fkSource,"_",format,"_",adNumber) as subid FROM Conversions' . date('mY') . ' WHERE campaignName = :name ORDER BY id DESC LIMIT 1 ', Phalcon\Db::FETCH_ASSOC, array('name' => $campaignname));
                if (!empty($res) && !empty($res2)) {
                    if ($res[0]['insertTimestamp'] < $res2[0]['insertTimestamp']) {
                        $res = $res2;
                    }
                    echo "<p><strong>Click ID</strong>:" . $res[0]['clickId'] . "</p>" . "<p><strong>Timestamp</strong>:" . $res[0]['insertTimestamp'] . "</p>" . "<p><strong>Revenue</strong>: $" . $res[0]['ccpa'] . "</p>" . "<p><strong>Subid</strong>: " . $res[0]['subid'] . "</p>";
                    return;
                } else if (empty($res) || !empty($res2)) {
                    $res = $res2;
                }
//                $res = $this->getDi()->getDb2()->query('SELECT insertTimestamp, clickId,ccpa FROM Conversions'.date('mY').' WHERE campaignName = "'.$campaignname.'" ORDER BY id DESC LIMIT 1 ')->fetchAll();
                if (!empty($res) && !empty($res[0]) && !empty($res[0]['clickId'])) {
                    echo "<p><strong>Click ID</strong>:" . $res[0]['clickId'] . "</p>" . "<p><strong>Timestamp</strong>:" . $res[0]['insertTimestamp'] . "</p>" . "<p><strong>Revenue</strong>: $" . $res[0]['ccpa'] . "</p>" . "<p><strong>Subid</strong>: " . $res[0]['subid'] . "</p>";
                } else
                    echo '0';
            }
        } catch (Exception $ex) {
            echo 0;
        }
    }

    public function getAggInfoAction() {
        try {
            if ($this->request->get('aggID') == null || $this->request->get('aggID') == 'null') {
                echo '';
                return;
            }
            $res = Agregator::findFirst(array("id = :aggid:", 'bind' => array('aggid' => $this->request->get('aggID'))));
            echo $res->connector . '--.--' . $res->trackingParam;
        } catch (Exception $e) {
            return;
        }
    }

    private function redirectWithResponse($res) {
        $this->dispatcher->forward(array(
            "controller" => "operation",
            "action" => "index",
            "params" => array('jump' => $res)
        ));
    }

    private function getRate($cur) {
        $operation = new Operation();
        $res = $operation->getRate($cur);
        return $res[0]['currate'];
    }

    private function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    public function getClientInfoAction() {
        try {
            $this->view->disable();
            $a = new DateTime();
            $db = $this->getDi()->getDb4();
            $sql = 'SELECT SUM(revenue) as revenue, clientid, monthyear FROM r__clientxtotals WHERE clientid = :clientid AND monthyear BETWEEN "' . date('Y-m-01', strtotime('-3 months')) . '" AND "' . date('Y-m-01') . '" GROUP BY clientid, monthyear';
            $sql2 = $db->prepare($sql);
            $sql2 = $db->executePrepared($sql2, array('clientid' => $this->request->get('clientid')), array());
            $res = $sql2->fetchAll(PDO::FETCH_ASSOC);

            $sql = 'SELECT SUM(revenue) as last7days, agregator as clientid FROM MainReport FORCE INDEX (ix_client_date) WHERE agregator = :clientid AND insertDate BETWEEN "' . date('Y-m-d', strtotime('-6 days')) . '" AND "' . date('Y-m-d') . '" GROUP BY agregator LIMIT 1';
            $sql2 = $db->prepare($sql);
            $sql2 = $db->executePrepared($sql2, array('clientid' => $this->request->get('clientid')), array());
            $res2 = $sql2->fetchAll(PDO::FETCH_ASSOC);

            $sql = 'SELECT SUM(revenue) as revenue, insertDate as date, agregator as clientid FROM MainReport FORCE INDEX (ix_client_date) WHERE agregator = :clientid AND insertDate BETWEEN "' . date('Y-m-d', strtotime('-6 days')) . '" AND "' . date('Y-m-d') . '" GROUP BY agregator, insertDate ';
            $sql2 = $db->prepare($sql);
            $sql2 = $db->executePrepared($sql2, array('clientid' => $this->request->get('clientid')), array());
            $res3 = $sql2->fetchAll(PDO::FETCH_ASSOC);

            $sql = 'SELECT campaign, o.offername as offer FROM Mask m inner join offerpack__offerpack o ON m.hash = o.hashMask WHERE agregator = :clientid ORDER BY m.insertTimestamp DESC LIMIT 5';
            $sql2 = $db->prepare($sql);
            $sql2 = $db->executePrepared($sql2, array('clientid' => $this->request->get('clientid')), array());
            $res4 = $sql2->fetchAll(PDO::FETCH_ASSOC);
            $finalresult = array();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            if (empty($res) && empty($res2) && empty($res3)) {
                echo json_encode(array('data' => 'empty', 'processingtime' => $interval));
                return;
            }

            if (!empty($res))
                $finalresult['lastmonths'] = $res;
            if (!empty($res2))
                $finalresult['lastsevendaysincludingtoday'] = $res2;
            if (!empty($res3))
                $finalresult['daybyday'] = $res3;
            if (!empty($res4))
                $finalresult['lastfiveoffers'] = $res4;


            $finalresult2['data'] = $finalresult;
            $finalresult2['processingtime'] = $interval;
            //mail('pedrorleonardo@gmail.com', 'OperatorReport2 ' . $interval, $sql);
            echo json_encode($finalresult2);
        } catch (Exception $ex) {
            echo 1;
            HelpingFunctions::writetolog("E\t" . __CLASS__, $ex->getMessage());
            return null;
        }
    }

    public function getCampaigns() {

        $cache = $this->di->get("viewCache");
        $res = $cache->get('campaigns');
        $res = null;
        if ($res == null) {
            $mask = new Mask();
            $dbres = $mask->getAllCampaigns();
            $res = '';
            foreach ($dbres as $hashAndCampaign) {
                $res .= '<option value ="' . $hashAndCampaign['hash'] . '" >' . $hashAndCampaign['campaign'] . '</option>';
            }
            $cache->save('campaigns', $res);
        }
        return $res;
    }

    private function getCampaignsjson() {
        $mask = new Mask();
        return json_encode($mask->getAllCampaigns());
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

    private function getClients() {

        $cache = $this->di->get("viewCache");
        $comboString = $cache->get('clients');
        $comboString = null;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getClients();
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString .= '<option value="' . $row['id'] . '">' . $row['clientName'] . '-' . $row['id'] . '</option>';
            }
            $cache->save('sources', $comboString);
        }
        return $comboString;
    }

    public function getSources() {
        $cache = $this->di->get("viewCache");
        $comboString = $cache->get('sources');
        $comboString = null;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getSources();
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString .= '<option value="' . $row['id'] . '">' . $row['sourceName'] . '-' . $row['id'] . '</option>';
            }
            $cache->save('sources', $comboString);
        }
        return $comboString;
    }

    public function link_checkerAction() {

        $client = $this->request->get('c');
        $url = $this->request->get('u');
        $param = $this->request->get('p');
        $sinfo = '';
        $connector = '&';

        if ($client != 0) {

            $operation = new Operation();

            $aggr_info = $operation->getAgregatorParams($client);

            $param = $aggr_info['trackingParam'];
            $sinfo = $aggr_info['sinfo'];
            $connector = $aggr_info['connector'];
            $furl = ($sinfo == '') ? $url . $connector . $param . '={tracking}' : $url . $connector . $param . '={tracking}&' . $sinfo . '={subid}';
        } else {
            $connector = (substr_count($url, '?') == 0) ? '?' : '&';
            $furl = $url . $connector . $param . '={tracking}';
        }

        $error_string = '';

        $qstarter_count = substr_count($furl, '?');
        $qamp_count = substr_count($furl, '&');
        $qpos = strpos($furl, '?');
        $amppos = strpos($furl, '&');

        $pcounter_count = substr_count($furl, $param);

        if ($qstarter_count > 1) {
            $error_string .= 'The "?" connector is duplicated, only one allowed.';
        }
        if ($qstarter_count == 0 and $qamp_count > 0) {
            $error_string .= 'There is a "&" connector present without any "?"';
        }
        if ($pcounter_count > 1) {
            $error_string .= 'The parameter "' . $param . '" is duplicated, only one allowed.';
        }
        if ($qstarter_count == 1 and $qamp_count > 0 and $amppos < $qpos) {
            $error_string .= ' The connector "?" should always appear before "&".';
        }
        if (filter_var($furl, FILTER_VALIDATE_URL) === FALSE and $error_string == '') {
            $error_string .= 'The url is incorrect, check with IT crew.';
        }
        if ($error_string == '') {
            $error_string = 'Link seems to be ok!';
        }
        echo $error_string . "\n" . "Final link example:" . "\n" . "\n" . $furl;
    }

    public function campaign_urlAction() {

        $url = $this->request->get('u');
        $operation = new Operation();

        $campaigns = $operation->getCampaignsByUrl($url);

        $clist = sizeof($campaigns) . ' campaigns were found:' . "\n";
        foreach ($campaigns as $campaign) {
            $clist .= $campaign['campaign'] . "\n";
        }

        echo $clist;
    }

    public function getCategories() {
        $cache = $this->di->get("viewCache");
        $comboString = $cache->get('categories');
        $comboString = null;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getCategories();
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString .= '<option value="' . $row['id'] . '">' . $row['name'] . '-' . $row['id'] . '</option>';
            }
            $cache->save('categories', $comboString);
        }
        return $comboString;
    }

    private function login_access() {

        $auth = $this->session->get('auth');

        if ($auth['countries'] != '') {
            $country = str_replace(',', '","', $auth['countries']);
            $country = '"' . $country . '"';
        } else
            $country = null;

        $sources = $auth['sources'] == '' ? null : $auth['sources'];
        $aggs = $auth['aggregators'] == '' ? null : $auth['aggregators'];
        $aff = null; // Ric and Commercials
        if ($auth['userlevel'] == 2 && $auth['utype'] == 0) {//CM's Ivo Pedro
            $aff = 0;
        } else if ($auth['utype'] == 1) { // Affiliate manager
            $sources = $auth['affiliates'];
            $aff = 1;
        } else if ($auth['userlevel'] == 2 && $auth['utype'] == 2) { // mainstream CM's  like Arrojado
            $aff = 2;
        } else if ($auth['userlevel'] == 1 && ($auth['id'] == 3)) { // hello I'm Guille and Facha and I'm different
            $aff = 3;
        } else if ($auth['userlevel'] == 1 && ($auth['id'] == 2)) { // hello I'm Guille and Facha and I'm different,
            $aff = 4;
        }
        return array($country, $sources, $aggs, $auth['id'], $aff);
    }

    private function getSourcesInvestment() {

        $auth = $this->session->get('auth')['id'];
        $invest = $this->session->get('auth')['invest'];

        $operation = new Operation();

        $sources = $operation->getSourcesInv($auth, $invest);

        return $sources;
    }
private function getSourcesInvestment2() {

        $auth = $this->session->get('auth')['id'];
        $invest = $this->session->get('auth')['invest'];

        $operation = new Operation();

        $sources = $operation->getSourcesInv2($auth, $invest);

        return $sources;
    }

}

<?php

/**
 * ReportController
 *
 * Report Insertion and presentation
 */
class SlinkController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('SLink');
        parent::initialize();
    }

    public function indexAction() {

        $auth = $this->session->get('auth');
        $group_info = $this->group_combo();
        $this->view->setVar("group_list", $group_info);
    }

    public function ajaxtableAction() {
        $this->view->disable();
        $table = '';
        if ($this->request->get('hash') != null) {
            $datepc = ($this->request->get('period') != null) ? $this->request->get('period') : 0;

            $table = ($datepc != 0) ? $this->get_group_info($this->request->get('hash'), $datepc) : $this->get_group_info2($this->request->get('hash'));
        }
        echo $table;
    }

    public function ajaxinsertmgroupAction() {
        $this->view->disable();
//        echo $this->request->getPost('nhash');
//        echo '<pre>';
//        print_r($this->request->getPost('adata'));
//        echo '<pre>';
//        $dataarr = $this->request->getPost('adata');
        $njump = new Slink();
        $auth = $this->session->get('auth');
        if (!$njump->insert_multiple_line($this->request->getPost('adata'), $this->request->getPost('nhash'), $this->request->getPost('cname'), $auth))
            return 0;
        echo 1;
    }

    public function ajaxinsertgroupAction() {
        $this->view->disable();
//         echo '<pre>';
//         print_r($_REQUEST);
//         echo '<pre>';
        if ($this->request->getPost('nlurl') != null) {

            $hash = 0;
            if ($this->request->getPost('nhash') != null) {
                $hash = $this->request->getPost('nhash');
            }

            $response = $this->insert_njump($this->request->getPost('ngroup'), $this->request->getPost('nlurl'), $this->request->getPost('nlname'), $this->request->getPost('nlop'), $this->request->getPost('nlisp'), $this->request->getPost('nlper'), $hash, $this->request->getPost('dev'), $this->request->getPost('bhour'), $this->request->getPost('ehour'), $this->request->getPost('nlsback'));
            echo json_encode($response);
        } else {
            echo json_encode(array(0));
        }
    }

    public function ajaxgroupcomboAction() {

        $combo = $this->group_combo();
        echo $combo;
    }

    public function ajaxnameAction() {

        if ($this->request->getPost('nhash') != null and $this->request->getPost('nname') != null) {

            $njump = new Slink();
            $res = $njump->update_name($this->request->getPost('nhash'), $this->request->getPost('nname'));
            echo $res;
        } else {

            echo 'Error';
        }
    }

    public function ajaxcloneAction() {

        if ($this->request->getPost('nhash') != null and $this->request->getPost('clonen') != null) {

            $njump = new Slink();
            $njump->initialize();
            $res = $njump->find(array(
                "hashMask = '" . $this->request->getPost('nhash') . "'"
            ));
            $auth = $this->session->get('auth');
            $nhash = $njump->clone_insert($res, $this->request->getPost('clonen'), $auth);

            echo json_encode(array(1, $nhash));
            //echo $res;
        } else {

            echo 'Error';
        }
    }

    public function ajaxclonerowAction() {

        if ($this->request->getPost('did') != null) {

            $njump = new Slink();
            $njump->initialize();
            $res = $njump->find(array(
                "id = " . $this->request->getPost('did')
            ));

            $auth = $this->session->get('auth');
            $njump->clone_line_insert($res, $auth);

            echo 1;
        } else {

            echo 'Error';
        }
    }

    public function ajaxdeleteAction() {

        if ($this->request->getPost('nhash') != null) {

            $njump = new Slink();
            $njump->initialize();
            $auth = $this->session->get('auth');
            if (!$this->validateAction($auth, $this->request->getPost('nhash'), 'hashMask'))
                return;

            $njump->delete_njump($this->request->getPost('nhash'));
            echo 1;
        }else {
            echo 'Error';
        }
    }

    public function ajaxupdatecellAction() {

        if ($this->request->getPost('id') != null /* and $this->request->getPost('text')!=null */) {
            $auth = $this->session->get('auth');
            $idArr = explode('_', $this->request->getPost('id'));
            $textval = $this->request->getPost('text');
            $colName = 'undefined';
            $ispControl = 0;
            switch ($idArr[1]) {
                case 1:
                    $colName = "lpUrl";
                    $textval = strip_tags(trim($textval));
                    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $textval)) {
                        echo 'Invalid Url';
                        return;
                    }
                    break;
                case 2:
                    $colName = "linkName";
                    break;
                case 3:
                    $colName = "lpOp";
                    $textval = strtoupper(trim($textval));
                    break;
                case 4:
                    $colName = "isp";
                    $textval = strtoupper(trim($textval));
                    $ispControl = 1;
                    break;
                case 5:
                    $colName = "percent";
                    break;
                case 6:
                    $colName = "device";
                    $textval = strtolower(trim($textval));
                    break;
                case 7:
                    $colName = "beginhour";
                    if ($textval != '' && $textval != null && !$this->isTimeWellFormed($textval)) {
                        echo 'Error. First period is not well-formed';
                        return;
                    }
                    $timeupdate = true;
                    break;
                case 8:
                    $colName = "endhour";
                    if ($textval != '' && $textval != null && (!$this->isTimeWellFormed($textval))) {
                        echo 'Error. Second period is not well-formed';
                        return;
                    }
                    $timeupdate = true;
                    break;
                case 9:
                    $colName = "sback";
                    break;
            }

            if (isset($timeupdate) && $timeupdate) {
                if ($this->isTimeWellFormed($this->request->getPost('ehour')) && $this->isTimeWellFormed($this->request->getPost('bhour')) || ($this->request->getPost('bhour') == '' && $this->request->getPost('ehour') == '') || ($this->request->getPost('bhour') == null && $this->request->getPost('ehour') == null)) {
                    //update both values;
                    $updateTimes = true;
                } else {
                    //wait for the other value :)
                    echo 1;
                    return;
                }
            }

            $njump = new Slink();
            $njump->initialize();
            //check old or new URL
            //check its country
            if (!$this->validateAction($auth, $idArr[0], 'id'))
                return;
            //check if beginhour or endhour
            //if so, check if the oposite hour from request already has value.
            //it has -> update both
            //it doesn't return 1, but don't update
            if (isset($updateTimes) && $updateTimes) {
                $njump->update_field('beginhour', $idArr[0], $this->request->getPost('bhour') == null ? null : $this->request->getPost('bhour'));
                $njump->update_field('endhour', $idArr[0], $this->request->getPost('ehour') == null ? null : $this->request->getPost('ehour'));
                echo 1;
                return;
            }
            $njump->update_field($colName, $idArr[0], $textval);

            if ($ispControl == 1 and $textval != '') {
                $njump->update_field('lpOp', $idArr[0], 'ISP');
            }

            echo 1;
        } else {
            echo 'Error';
        }
    }

    public function ajaxdeleterowAction() {

        if ($this->request->getPost('did') != null) {

            $njump = new Slink();
            $njump->initialize();
            $auth = $this->session->get('auth');
            if (!$this->validateAction($auth, $this->request->getPost('did'), 'id'))
                return;
            $njump->delete_row($this->request->getPost('did'));
            echo 1;
        }else {
            echo 'Error';
        }
    }

    private function get_group_info($hash, $datepc) {


        $njump = new Slink();
        $njump->initialize();
        $res = $njump->find(array(
            "hashMask = '" . $hash . "'",
            "order" => "linkName"
        ));

        $repc = $this->epc_finder($res, $hash, $datepc);
        $table = '';
        $innerTable = '';
        if (!empty($res)) {
            foreach ($res as $row) {
                $jumphash = $this->get_string_between($row->lpUrl, '?jp=', '&id=');
                $val = (isset($repc[$jumphash]) and $jumphash != '' and $repc[$jumphash]['clicks'] > 0 ) ? number_format($repc[$jumphash]['revenue'] / $repc[$jumphash]['clicks'], 4) : 0;

                $l = 1;
                $innerTable .= '<tr class="trow" id="' . $row->id . '">
                        <th id="' . $row->id . '_2' . '" class="cell" lin="' . $l . '" col="2" contenteditable>' . $row->linkName . '</th>
                        <th id="' . $row->id . '_1' . '" class="cell" lin="' . $l . '" col="1" contenteditable>' . $row->lpUrl . '</th>
                        <th id="' . $row->id . '_7' . '" class="cell devc" lin="' . $l . '" col="7" contenteditable>' . $row->beginhour . '</th>
                        <th id="' . $row->id . '_8' . '" class="cell devc" lin="' . $l . '" col="8" contenteditable>' . $row->endhour . '</th>
                        <th id="' . $row->id . '_3' . '" class="cell opec" lin="' . $l . '" col="3" contenteditable>' . $row->lpOp . '</th>
                        <th id="' . $row->id . '_6' . '" class="cell devc" lin="' . $l . '" col="6" contenteditable>' . $row->device . '</th>
                        <th id="' . $row->id . '_4' . '" class="cell ispc" lin="' . $l . '" col="4" contenteditable>' . $row->isp . '</th>
                        <th id="' . $row->id . '_5' . '" class="cell" lin="' . $l . '" col="5" contenteditable>' . $row->percent . '</th>
                        <th lin="' . $l . '" col="9"><select id="' . $row->id . '_9' . '" class="sbbox">' . $this->selecthelper($row->sback) . '</select></th>
                        <th id="' . $row->id . '_10' . '" class="cell" lin="' . $l . '" col="5">' . $val . '</th>
						<th id=""><button did="' . $row->id . '" type="button" class="btn btn-xs btn-danger del">Delete</button>&nbsp;<button did="' . $row->id . '" type="button" class="btn btn-xs btn-warning cll" style="margin-top:10px">Clone&nbsp;</button></th></tr>';
            }
        }
        $table = ' <table id="maint" class="table table-striped table-bordered">
                            <thead>
                                <tr style="background-color:#01A9DB">
                                    <th style="color:black">Name</th>
                                    <th style="color:black">Url</th>
                                    <th style="color:black">Begin</th>
                                    <th style="color:black">End</th>
                                    <th style="color:black">Operator</th>
                                    <th style="color:black">Device</th>
                                    <th style="color:black">ISP</th>
                                    <th style="color:black">Percentage</th>
                                    <th style="color:black">Sback</th>
                                    <th style="color:black">Epc</th>
									<th style="color:black">Action</th>
                                </tr>
                            </thead>
                            <tbody>' .
                $innerTable
                . ' <tr class="linesr">
                                        <th id="zlname" class="nlc" contenteditable></th>
                                        <th id="zlurl" class="nlc" contenteditable></th>
                                        <th id="zlop" class="nlc" contenteditable></th>
                                        <th id="zbhour" class="nlc" contenteditable></th>
                                        <th id="zehour" class="nlc" contenteditable></th>
                                        <th id="zdev" class="nlc" contenteditable></th>
                                        <th id="zlisp" class="nlc" contenteditable></th>
                                        <th id="zlper" class="nlc" contenteditable></th>
                                        <th id="zlsback" class="nlc sbox"><select id="zlsback">
                                                                         <option value="0">Default</option>
                                                                         <option value="1">Rotation</option>
                                                                         <option value="2">Back</option>
                                                                         <option value="3">B-R</option>
                                                                  </select></th>
                                        <th class="nlc"> <button id="newl2" type="button" class="btn btn-xs btn-primary">Save</button>&nbsp;<button id="aline" type="button" class="btn btn-xs btn-primary">+</button></th>
                                    </tr>
                               </tbody>
                            </table>';

        return $table;
    }

    private function get_group_info2($hash) {


        $njump = new Slink();
        $njump->initialize();
        $res = $njump->find(array(
            "hashMask = '" . $hash . "'",
            "order" => "linkName"
        ));

        $table = '';
        $innerTable = '';
        if (!empty($res)) {
            foreach ($res as $row) {

                $l = 1;
                $innerTable .= '<tr class="trow" id="' . $row->id . '">
                        <th id="' . $row->id . '_2' . '" class="cell" lin="' . $l . '" col="2" contenteditable>' . $row->linkName . '</th>
                        <th id="' . $row->id . '_1' . '" class="cell" lin="' . $l . '" col="1" contenteditable>' . $row->lpUrl . '</th>
                        <th id="' . $row->id . '_7' . '" class="cell devc" lin="' . $l . '" col="7" contenteditable>' . $row->beginhour . '</th>
                        <th id="' . $row->id . '_8' . '" class="cell devc" lin="' . $l . '" col="8" contenteditable>' . $row->endhour . '</th>
                        <th id="' . $row->id . '_3' . '" class="cell opec" lin="' . $l . '" col="3" contenteditable>' . $row->lpOp . '</th>
                        <th id="' . $row->id . '_6' . '" class="cell devc" lin="' . $l . '" col="6" contenteditable>' . $row->device . '</th>
                        <th id="' . $row->id . '_4' . '" class="cell ispc" lin="' . $l . '" col="4" contenteditable>' . $row->isp . '</th>
                        <th id="' . $row->id . '_5' . '" class="cell proportationNumbers" lin="' . $l . '" col="5" contenteditable>' . $row->percent . '</th>
                        <th lin="' . $l . '" col="9"><select id="' . $row->id . '_9' . '" class="sbbox">' . $this->selecthelper($row->sback) . '</select></th>

						<th id=""><button did="' . $row->id . '" type="button" class="btn btn-xs btn-danger del">Delete</button>&nbsp;<button did="' . $row->id . '" type="button" class="btn btn-xs btn-warning cll" style="margin-top:10px">Clone&nbsp;</button></th>

						<th class="percentageCells" contenteditable></th></tr>';
            }
        }
        $table = ' <table id="maint" class="table table-striped table-bordered">
                            <thead>
                                <tr style="background-color:#01A9DB">
                                    <th style="color:black">Name</th>
                                    <th style="color:black">Url</th>
                                    <th style="color:black">Begin</th>
                                    <th style="color:black">End</th>
                                    <th style="color:black">Operator</th>
                                    <th style="color:black">Device</th>
                                    <th style="color:black">ISP</th>
                                    <th style="color:black">Proportion</th>
                                    <th style="color:black">Sback</th>

									<th style="color:black">Action</th>
									<th style="color:black">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>' .
                $innerTable
                . ' <tr class="linesr">
                                        <th id="zlname" class="nlc" contenteditable></th>
                                        <th id="zlurl" class="nlc" contenteditable></th>
                                        <th id="zlop" class="nlc" contenteditable></th>
                                        <th id="zbhour" class="nlc" contenteditable></th>
                                        <th id="zehour" class="nlc" contenteditable></th>
                                        <th id="zdev" class="nlc" contenteditable></th>
                                        <th id="zlisp" class="nlc" contenteditable></th>
                                        <th id="zlper" class="nlc" contenteditable></th>
                                        <th id="zlsback" class="nlc sbox"><select id="zlsback">
                                                                         <option value="0">Default</option>
                                                                         <option value="1">Rotation</option>
                                                                         <option value="2">Back</option>
                                                                         <option value="3">B-R</option>
                                                                  </select></th>
                                        <th class="nlc"> <button id="newl2" type="button" class="btn btn-xs btn-primary">Save</button>&nbsp;<button id="aline" type="button" class="btn btn-xs btn-primary">+</button></th>
										<th class="nlc"></th>
                                    </tr>
                               </tbody>
                            </table>';

        return $table;
    }

    private function epc_finder($resArray, $njump, $period) {

        $timeshift = ($period == 0) ? date("Y-m-d") : $period;
        $jumplist = '';
        foreach ($resArray as $row) {

            $nlink = $this->get_string_between($row->lpUrl, '?jp=', '&id=');
            $jumplist .= '"' . $nlink . '",';

            //jump.youmobistein.com/?jp=545226098b275&id=56_fr_109_frouibouyguesauto_1_1_1
        }
        $jumplist = rtrim($jumplist, ',');

        $slink = new Slink();
        return $slink->get_epc($jumplist, $njump, $timeshift);
    }

    private function get_string_between($string, $start, $end) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0)
            return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    private function selecthelper($sel) {
        $sstring = '<option value="0">Default</option>
                 <option value="1">Rotation</option>
                 <option value="2">Back</option>
                 <option value="3">B-R</option>';
        return str_replace($sel, $sel . '" selected="selected"', $sstring);
    }

    private function group_combo() {

        $njumps = new Slink();
        $njumps->initialize();
        $limits = $this->login_access();
        $auth = $this->session->get('auth');
        //echo $limits[0];
        if (isset($auth['userlevel']) && $auth['userlevel'] > 1) {
            $filter = array('0=0' . (isset($limits[0]) ? ' AND c_country IN (' . $limits[0] . ')' : '') . ( ($limits[4] != 0) ? ' AND stype = ' . $limits[4] : '' ));
        } else
            $filter = array();
        $filter['group'] = 'hashMask';
        $filter['order'] = 'lpName';
        $res = $njumps->find($filter);


        $combo = '';
        //echo '<pre>';
        //print_r($res);
        //echo '<pre>';

        foreach ($res as $row) {
            $combo .= '<option class="mlist" ccountry="' . strtolower($row->c_country) . '" linkref="' . (($row->linkref == '') ? '0' : $row->linkref ) . '" value="' . $row->hashMask . '">' . $row->lpName . '</option>';
        }
        return $combo;
    }

    private function insert_njump($group, $url, $name, $op = '', $isp = '', $percent, $hash, $device = '', $beginhour = null, $endhour = null, $sback) {
        try {
            $url = trim($url);
            $country = '';
            $auth = $this->session->get('auth');
            $url = strip_tags($url);
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
                return array(0, "Invalid URL");
            }
            if (strpos($url, '://mjump.') !== false) {
                $country = $this->findMjpCountry($url);
            } else if (strpos($url, '://jump.') !== false) {
                $country = $this->getJUMPcountry($url);
            }
            if ($country === '')
                return array(0, 'No country found for that URL');
            if ($country !== '' && $auth['userlevel'] > 1 && $auth['countries'] != '') {
                $countries = explode(',', $auth['countries']);
                if (array_search(strtolower($country), array_map('strtolower', $countries)) === false)
                    return array(0, 'Error. This country does not belong to this user');
            }
            if ($name == '' or $group == '' or $percent == '') {
                return array(0, 'Only ISP field can be empty');
            }
            if ($op == 'ISP' and $isp == '') {
                return array(0, 'Please define Isp');
            }
            if ($op == '' or $op == NULL) {
                $op = new \Phalcon\Db\RawValue('""');
            }
            if ($isp == '' or $isp == NULL) {
                $isp = new \Phalcon\Db\RawValue('""');
            }
            if ($device == '' or $device == NULL) {
                $device = new \Phalcon\Db\RawValue('""');
            }
            if ($sback == '' or $sback == NULL) {
                $sback = 0;
            }
            if (($beginhour == '' or $beginhour == NULL) && ($endhour == '' or $endhour == NULL)) {
                $beginhour = new \Phalcon\Db\RawValue('NULL');
                $endhour = new \Phalcon\Db\RawValue('NULL');
            } else if (!$this->isTimeWellFormed($beginhour) || !$this->isTimeWellFormed($endhour)) {
                return array(0, 'Error. Provide the right periods for this njump.');
            }

            $njump = new Slink();
            $njumpWhat = new SlinkWhatsapp();

            if ($hash == 0) {
                $uniq = uniqid();
            } else {
                $uniq = $hash;
            }

            $njump->initialize();
            $njump->hashMask = $uniq;
            $njump->linkName = $name;
            $njump->lpUrl = $url;
            $njump->lpName = $group;
            $njump->lpOp = $op;
            $njump->isp = $isp;
            $njump->percent = $percent;
            $njump->device = $device;
            $njump->beginhour = $beginhour;
            $njump->endhour = $endhour;
            $njump->action = new \Phalcon\Db\RawValue('""');
            $njump->linkref = new \Phalcon\Db\RawValue('""');
            $njump->c_country = $country;
            $njump->sback = $sback;
            $njump->insertTimestamp = date('Y-m-d H:i:s');
            $njump->stype = isset($auth['utype']) ? $auth['utype'] : '0';
            $njump->linkedjump = $njump->getCampaignHash($url);
            $njump->autoop = 0;
            $njump->climiar = 0;
            $njump->climiar_s = 0;
            $njump->deleted = 0;

            $njumpWhat->initialize();
            $njumpWhat->hashMask = $uniq;
            $njumpWhat->linkName = $name;
            $njumpWhat->lpUrl = $url;
            $njumpWhat->lpName = $group;
            $njumpWhat->lpOp = $op;
            $njumpWhat->isp = $isp;
            $njumpWhat->percent = $percent;
            $njumpWhat->device = $device;
            $njumpWhat->beginhour = $beginhour;
            $njumpWhat->endhour = $endhour;
            $njumpWhat->action = new \Phalcon\Db\RawValue('""');
            $njumpWhat->linkref = new \Phalcon\Db\RawValue('""');
            $njumpWhat->c_country = $country;
            $njumpWhat->sback = $sback;
            $njumpWhat->insertTimestamp = date('Y-m-d H:i:s');
            $njumpWhat->stype = isset($auth['utype']) ? $auth['utype'] : '0';
            $njumpWhat->linkedjump = $njump->getCampaignHash($url);
            $njumpWhat->autoop = 0;
            $njumpWhat->climiar = 0;
            $njumpWhat->climiar_s = 0;

            if (($njump->save() == false) || ($njumpWhat->save() == false)) {
                foreach ($njump->getMessages() as $message) {
                    echo $message;
                }
                foreach ($njumpWhat->getMessages() as $message) {
                    echo $message;
                }
                return array(0, 'fail');
            } else {
                return array(1, $uniq);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
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
        return array($country, $sources, $aggs, $auth['id'], $auth['utype']);
    }

    private function validateAction($auth, $slinkid, $type) {
        if ($auth['userlevel'] > 1 && (isset($auth['countries']) && $auth['countries'] != '')) {
            $countries = explode(',', $auth['countries']);
            //echo $this->request->getPost('nhash');
            $slinks = Slink::find(
                            array(
                                "conditions" => "" . $type . " = ?1",
                                "bind" => array(1 => $slinkid)
                            )
            );
            foreach ($slinks as $slink) {
                if (array_search(strtolower($slink->c_country), array_map('strtolower', $countries)) === false) {
                    echo 'Error. This country does not belong to this user';
                    return false;
                }
                break;
            }
        }
        return true;
    }

    private function findMjpCountry($mjpurl) {
        $parts = parse_url($mjpurl);
        parse_str($parts['query'], $query);
        $jp = isset($query['jp']) ? $query['jp'] : '';
        if ($jp === '')
            return '';
        $mjump = Smlink::findFirst(array('hashMask = "' . $jp . '"'));
        if (empty($mjump) || !isset($mjump->linkref) || empty($mjump->linkref))
            return '';
        return $this->getJUMPcountry($mjump->linkref);
    }

    private function getJUMPcountry($str) {
        $parts = parse_url($str);
        parse_str($parts['query'], $query);
        if (isset($query['id'])) {
            $exploded = explode('_', $query['id']);
            if (isset($exploded[1]))
                return $exploded[1];
        }
        return '';
    }

    private function isTimeWellFormed($time) {
        if (!isset($time) || $time == '')
            return false;
        $a = preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $time);
        if ($a === false || $a == false) {
            return false;
        }
        return true;
    }

    public function set_countryAction() {
        $data_array = array(
            'hashMask' => $this->request->getPost('hashMask'),
            'c_country' => $this->request->getPost('country')
        );

        $slink = new Slink();
        $slink->updateMultiClick($data_array, 1);
    }

    public function unset_countryAction() {
        $data_array = array(
            'hashMask' => $this->request->getPost('hashMask')
        );

        $slink = new Slink();
        $slink->updateMultiClick($data_array, 2);
    }

}

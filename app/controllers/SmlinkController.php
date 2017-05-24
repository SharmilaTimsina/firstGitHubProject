<?php

/**
 * ReportController
 *
 * Report Insertion and presentation
 */
class SmlinkController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('SmLink');
        parent::initialize();
    }

    public function indexAction() {

        $auth = $this->session->get('auth');
        $group_info = $this->group_combo($auth['utype']);
        $this->view->setVar("group_list", $group_info);
    }

    public function ajaxtableAction() {
        $this->view->disable();
        $table = '';
        if ($this->request->get('hash') != null) {
            $auth = $this->session->get('auth');
            $epcdate = ($this->request->get('epcdate') != null) ? $this->request->get('epcdate') : 0;
            $table = $this->get_group_info($this->request->get('hash'), $auth['utype'], $epcdate);
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
        $auth = $this->session->get('auth');
        $njump = new Smlink();
        $njump->insert_multiple_line($this->request->getPost('adata'), $this->request->getPost('nhash'), $this->request->getPost('cname'), $auth['utype']);
        echo 1;
    }

    public function ajaxinsertgroupAction() {
        $this->view->disable();
//         echo '<pre>';
//         print_r($_REQUEST);
//         echo '<pre>';
        $auth = $this->session->get('auth');
        if ($this->request->getPost('nlurl') != null) {

            $hash = 0;
            if ($this->request->getPost('nhash') != null) {
                $hash = $this->request->getPost('nhash');
            }

            $response = $this->insert_njump($this->request->getPost('ngroup'), $this->request->getPost('nlurl'), $this->request->getPost('nlname'), $this->request->getPost('nlref'), $this->request->getPost('nlper'), $hash, $auth['utype']);


            echo json_encode($response);
        } else {
            echo json_encode(array(0));
        }
    }

    public function ajaxgroupcomboAction() {
        $auth = $this->session->get('auth');
        $combo = $this->group_combo($auth['utype']);
        echo $combo;
    }

    public function ajaxnameAction() {

        if ($this->request->getPost('nhash') != null and $this->request->getPost('nname') != null) {

            $njump = new Smlink();
            $res = $njump->update_name($this->request->getPost('nhash'), $this->request->getPost('nname'));
            echo $res;
        } else {

            echo 'Error';
        }
    }

    public function ajaxcloneAction() {

        if ($this->request->getPost('nhash') != null and $this->request->getPost('clonen') != null) {
            $auth = $this->session->get('auth');
            $njump = new Smlink();
            $njump->initialize();
            $res = $njump->find(array(
                "hashMask = '" . $this->request->getPost('nhash') . "'" . (isset($auth['utype']) ? " AND stype = " . $auth['utype'] : '')
            ));

            $nhash = $njump->clone_insert($res, $this->request->getPost('clonen'), $auth['utype']);

            echo json_encode(array(1, $nhash));
            //echo $res;
        } else {

            echo 'Error';
        }
    }

    public function ajaxclonerowAction() {

        if ($this->request->getPost('did') != null) {
            $auth = $this->session->get('auth');
            $njump = new Smlink();
            $njump->initialize();
            $res = $njump->find(array(
                "id = " . $this->request->getPost('did') . (isset($auth['utype']) ? ' and stype=' . $auth['utype'] : '' )
            ));

            $njump->clone_line_insert($res, $auth['utype']);

            echo 1;
        } else {

            echo 'Error';
        }
    }

    public function ajaxdeleteAction() {

        if ($this->request->getPost('nhash') != null) {

            $njump = new Smlink();
            $njump->initialize();
            $njump->delete_njump($this->request->getPost('nhash'));
            echo 1;
        } else {
            echo 'Error';
        }
    }

    public function ajaxupdatecellAction() {

        if ($this->request->getPost('id') != null /* and $this->request->getPost('text')!=null */) {

            $idArr = explode('_', $this->request->getPost('id'));
            $textval = $this->request->getPost('text');
            $colName = 'undefined';
            switch ($idArr[1]) {
                case 1:
                    $colName = "lpUrl";
                    break;
                case 2:
                    $colName = "linkName";
                    break;
                case 4:
                    $colName = "linkref";
                    break;
                case 3:
                    $colName = "percent";
                    break;
                case 5:
                    $colName = "lpOp";
            }
            $njump = new Smlink();
            $njump->initialize();
            $textval = trim($textval);
            $njump->update_field($colName, $idArr[0], $textval);


            echo 1;
        } else {
            echo 'Error';
        }
    }

    public function ajaxdeleterowAction() {

        if ($this->request->getPost('did') != null) {

            $njump = new Smlink();
            $njump->initialize();
            $njump->delete_row($this->request->getPost('did'));
            echo 1;
        } else {
            echo 'Error';
        }
    }

    private function get_group_info($hash, $stype, $epcdate = 0) {


        $njump = new Smlink();
        $njump->initialize();
        $res = $njump->find(array(
            "hashMask = '" . $hash . "'" . (isset($stype) ? " AND stype=" . $stype : ''),
            "order" => "linkName"
        ));
        $auth = $this->session->get('auth');
        $table = '';
        $innerTable = '';
        if ($res[0]->stype == 2) {
            $epclist = $njump->get_epc($hash, $epcdate);
            $epcarray = array();
            foreach ($epclist as $line) {
                $epcarray[$line['mjumpid']] = ($line['clicks'] > 0) ? number_format($line['rev'] / $line['clicks'], 3) : 0.000;
            }
            //print_r($epclist);
        }
        if (!empty($res)) {
            foreach ($res as $row) {

                $l = 1;
                $xparam = '';
                $epcrow = '';
                if ($auth['utype'] == 2 or ( $auth['userlevel'] == 1 and $row->stype == 2)) {
                    $xparam = '</th><th id="' . $row->id . '_5' . '" class="cell" lin="' . $l . '" col="4" contenteditable>' . $row->lpOp . '</th>';
                    $valepc = (isset($epcarray[$row->id])) ? $epcarray[$row->id] : "0.000";
                    $epcrow = '<th>' . $valepc . '</th>';
                }
                $innerTable .= '<tr class="trow" id="' . $row->id . '"><th id="' . $row->id . '_2' . '" class="cell" lin="' . $l . '" col="2" contenteditable>' . $row->linkName . '</th><th id="' . $row->id . '_1' . '" class="cell" lin="' . $l . '" col="1" contenteditable>' . $row->lpUrl . '</th><th id="' . $row->id . '_4' . '" class="cell" lin="' . $l . '" col="3" contenteditable>' . $row->linkref . '</th>' . $xparam . '<th id="' . $row->id . '_3' . '" class="cell" lin="' . $l . '" col="3" contenteditable>' . $row->percent . '</th><th id=""><button did="' . $row->id . '" type="button" class="btn btn-xs btn-danger del" style="margin-top:10px">Delete</button>&nbsp;<button did="' . $row->id . '" type="button" class="btn btn-xs btn-warning cll" style="margin-top:10px">Clone&nbsp;</button></th>' . $epcrow . '</tr>';
            }
        }
        $xparamh = '';
        $xparamu = '';
        $epc = '';
        if ($auth['utype'] == 2 or ( $auth['userlevel'] == 1 and $row->stype == 2)) {
            $xparamh = '<th style="color:black">Operator</th>';
            $xparamu = '<th id="zlop" class="nlc" contenteditable></th>';
            $epc = '<th>Epc</th>';
        }
        if ($row->stype == 2) {
            $epc = '<th>Epc</th>';
        }

        $table = ' <table id="maint" class="table table-striped table-bordered">
                            <thead>
                                <tr style="background-color:#01A9DB">
                                    <th style="color:black">Name</th>
                                    <th style="color:black">Url</th>
                                    <th style="color:black">Linkref</th>' . $xparamh . '
                                    <th style="color:black">Percentage</th>
                                    <th style="color:black">Action</th>' . $epc . '
                                </tr>
                            </thead>
                            <tbody>' .
                $innerTable
                . ' <tr class="linesr">
                                        <th id="zlname" class="nlc" contenteditable></th>
                                        <th id="zlurl" class="nlc" contenteditable></th>
                                        <th id="zlref" class="nlc" contenteditable></th>' . $xparamu . '
                                        <th id="zlper" class="nlc" contenteditable></th>
                                        <th class="nlc"><div class="btn-group inline pull-left" data-toggle="buttons-checkbox"><button id="aline" type="button" class="btn btn-xs btn-warning" style="margin-top:10px">&nbsp;+&nbsp;</button><button id="newl2" type="button" class="btn btn-xs btn-warning" style="margin-top:10px">Save</button></div></th>
                                    </tr>
                               </tbody>
                            </table>';

        return $table;
    }

    private function group_combo($stype) {

        $njumps = new Smlink();
        $njumps->initialize();
        $res = $njumps->find(array((isset($stype) ? "stype =" . $stype : ''),
            "group" => "hashMask",
            "order" => "lpName",
        ));


        $combo = '';
//        echo '<pre>';
//        print_r($res);
//        echo '<pre>';
        foreach ($res as $row) {
            $combo .= '<option class="mlist" value="' . $row->hashMask . '">' . $row->lpName . '</option>';
        }
        return $combo;
    }

    private function insert_njump($group, $url, $name, $linkref, $percent, $hash, $stype) {


        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
            return array(0, "Invalid URL");
        }
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $linkref)) {
            return array(0, "Invalid URL");
        }
        if ($name == '' or $group == '' or $percent == '') {
            return array(0, 'Fill every field please');
        }


        $njump = new Smlink();

        if ($hash == 0) {
            $uniq = uniqid();
        } else {
            $uniq = $hash;
        }

        $auth = $this->session->get('auth');

        $njump->initialize();
        $njump->hashMask = $uniq;
        $njump->linkName = $name;
        $njump->lpUrl = $url;
        $njump->lpName = $group;
        $njump->lpOp = new \Phalcon\Db\RawValue('""');
        $njump->percent = $percent;
        $njump->device = new \Phalcon\Db\RawValue('""');
        $njump->action = new \Phalcon\Db\RawValue('""');
        $njump->linkref = $linkref;
        $njump->autoop = 0;
        $njump->climiar = 0;
        $njump->climiar_s = 0;
        $njump->insertTimestamp = date('Y-m-d H:i:s');
        $njump->stype = isset($stype) ? $stype : 0;
        if ($njump->save() == false) {
            foreach ($njump->getMessages() as $message) {
                echo $message;
            }
            return array(0, 'fail', 'fail');
        } else {
            return array(1, $uniq, $group);
        }
    }

}

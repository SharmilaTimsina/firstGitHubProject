<?php

class DashboardController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Dashboard');
        date_default_timezone_set('Europe/Lisbon');

        parent::initialize();
    }

    public function indexAction() {
        $auth = $this->session->get('auth');
        try {
            
            //mail('pedrorleonardo@gmail.com','something','bunga');
            if ($auth['navtype'] != 7)
                $this->last7Days();
            else {
                echo "<style>.greybox { display: none; }</style>";
            }
        } catch (Exception $e) {

        }
    }

    public function last7Days() {

        $auth = $this->session->get('auth');
        $dash = new Dashboard();

        $countries = '';
        $sources = '';
        $aggregators = '';
        $arrayData = array();
        $chartinfo = '';
        $tableinfo = '';

        if (($auth['userlevel'] == 1 && $auth['utype'] == 0) || $auth['userlevel'] == 4) {

            //pode ver tudo
            $type = 1;

            $result = $dash->get_last7Days($type, $arrayData, $auth);
            $chartinfo = json_encode($result);

            $result2 = $dash->percentShift($type, $arrayData, $auth);
            $tableinfo = json_encode($result2);
        } else if ($auth['userlevel'] == 2 && $auth['utype'] == 0) {
            //affiliate = 0 -> adulto

            $type = 2;

            ($auth['countries'] != "") ? ($countries = $auth['countries']) : '';

            ($auth['sources'] != "") ? ($sources = $auth['sources']) : '';

            ($auth['aggregators'] != "") ? ($aggregators = $auth['aggregators']) : '';

            array_push($arrayData, array('countries' => $countries, 'sources' => $sources, 'aggregators' => $aggregators));

            $result = $dash->get_last7Days($type, $arrayData, $auth);
            $chartinfo = json_encode($result);

            $result2 = $dash->percentShift($type, $arrayData, $auth);
            $tableinfo = json_encode($result2);
        } else if ($auth['userlevel'] == 2 && $auth['utype'] == 1) {
            $type = 3;
            //tudo dos afiliados

            $result = $dash->get_last7Days($type, $arrayData, $auth);
            $chartinfo = json_encode($result);

            $result2 = $dash->percentShift($type, $arrayData, $auth);
            $tableinfo = json_encode($result2);
        } else if ($auth['userlevel'] == 2 && $auth['utype'] == 2) {
            $type = 4;

            ($auth['sources'] != "") ? ($sources = $auth['sources']) : ($sources = '');

            array_push($arrayData, array('sources' => $sources));


            $result = $dash->get_last7Days($type, $arrayData, $auth);
            $chartinfo = json_encode($result);

            $result2 = $dash->percentShift($type, $arrayData, $auth);
            $tableinfo = json_encode($result2);
        } else if ($auth['userlevel'] == 3 && $auth['utype'] == 0) {
            $type = 5;
            //bloqueado por agregator (cms)

            ($auth['aggregators'] != "") ? ($aggregators = $auth['aggregators']) : '';
            array_push($arrayData, array('aggregators' => $aggregators));

            $result = $dash->get_last7Days($type, $arrayData, $auth);
            $chartinfo = json_encode($result);

            $result2 = $dash->percentShift($type, $arrayData, $auth);
            $tableinfo = json_encode($result2);
        }

        $this->view->setVar("tableinfo", $tableinfo);
        $this->view->setVar("chartinfo", $chartinfo);
        //affiliate = 0 -> adulto, 1 -> afiliado . 2 -> mainstream, 3 -> afiliados antigo que so correm no mobistein
    }

}

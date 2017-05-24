<?php

/**
 * ReportController
 *
 * Report Insertion and presentation
 */
class AlertController extends ControllerBase {

    private $reportObject;

    public function initialize() {
        $this->tag->setTitle('Alerts');
        parent::initialize();
        $this->reportObject = new Report();
    }

    public function indexAction() {
        try {
            $auth = $this->session->get('auth');
            $operation = new Operation();
            $aggs = $this->getAggregators();
            $srcs = $this->getSources();
            $this->view->setVar("sourcelist", $srcs);
            $this->view->setVar("aggslist", $aggs);
            $this->view->setVar("userLevel", $auth['userlevel']);
        } catch (Exception $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    public function getCountriesAlertsAction() {
        try {
            $auth = $this->session->get('auth');

            $res = '';
            $countryuser_alerts = Usersalert::find('userid = ' . $auth['id'] . ' AND alerttype = 0 AND countries IS NOT NULL AND sources IS NULL and clients IS NULL AND sourcetype IS NULL');
            foreach ($countryuser_alerts as $alert) {
                $res .= $alert->id . '$' . $alert->countries . '$' . $alert->metricType . '$' . $alert->dayscompare . '$' . $alert->variation . '$' . $alert->periodicity . '$' . $alert->active . '-';
                //<th>Metric</th><th>Days to compare</th><th> % Variation</th><th>Periodicity</th>
            }
            echo $res;
        } catch (Exception $ex) {
            
        }
    }
    public function getSourceTypeAlertsAction() {
        try {
            $auth = $this->session->get('auth');

            $res = '';
            $countryuser_alerts = Usersalert::find('userid = ' . $auth['id'] . ' AND alerttype = 0 AND sourcetype IS NOT NULL AND sources IS NULL and clients IS NULL');
            foreach ($countryuser_alerts as $alert) {
                $res .= $alert->id . '$' . $alert->sourcetype . '$' . $alert->metricType . '$' . $alert->dayscompare . '$' . $alert->variation . '$' . $alert->periodicity . '$' . $alert->active . '-';
                //<th>Metric</th><th>Days to compare</th><th> % Variation</th><th>Periodicity</th>
            }
            echo $res;
        } catch (Exception $ex) {
            
        }
    }

    public function getSourcesAlertsAction() {
        try {
            $auth = $this->session->get('auth');

            $res = '';
            $sourcesuser_alerts = Usersalert::find('userid = ' . $auth['id'] . ' AND alerttype = 0 AND sources IS NOT NULL AND sourcetype IS NULL');
            foreach ($sourcesuser_alerts as $alert) {
                $res .= $alert->id . '$' . $alert->countries . '$' . $alert->sources . '$' . $alert->metricType . '$' . $alert->dayscompare . '$' . $alert->variation . '$' . $alert->periodicity . '$' . $alert->active . '-';
                //<th>Metric</th><th>Days to compare</th><th> % Variation</th><th>Periodicity</th>
            }
            echo $res;
        } catch (Exception $ex) {
            
        }
    }

    public function getClientsAlertsAction() {
        try {
            $auth = $this->session->get('auth');
            $res = '';
            $clientsuser_alerts = Usersalert::find('userid = ' . $auth['id'] . ' AND alerttype = 0 AND clients IS NOT NULL AND sourcetype IS NULL');
            foreach ($clientsuser_alerts as $alert) {
                $res .= $alert->id . '$' . $alert->countries . '$' . $alert->clients . '$' . $alert->metricType . '$' . $alert->dayscompare . '$' . $alert->variation . '$' . $alert->periodicity . '$' . $alert->active . '-';
                //<th>Metric</th><th>Days to compare</th><th> % Variation</th><th>Periodicity</th>
            }
            echo $res;
        } catch (Exception $ex) {
            
        }
    }

    public function getTablesAlertsAction() {
        try {
            $auth = $this->session->get('auth');
            $user_tablealerts = Usersalert::findFirst("userid = " . $auth['id'] . " AND alerttype = 1");
            echo $user_tablealerts->periodicity . '$' . $user_tablealerts->active . '$' . $user_tablealerts->dayscompare;
        } catch (Exception $ex) {
            
        }
    }

    public function newCountryAlertAction() {
        try {
            //newCountryAlert/?country=af&metric=0&daystocompare=1&variation=10&periodicity=1&status=0
            $auth = $this->session->get('auth');
            $country = $this->request->get('country');
            $metric = $this->request->get('metric');
            $daystocompare = $this->request->get('daystocompare');
            $variation = $this->request->get('variation');
            $periodicity = $this->request->get('periodicity');
            $status = $this->request->get('status');
            if (!isset($metric) || !isset($daystocompare) || !isset($variation) || !isset($periodicity) || !isset($status)) {
                echo '0';
                return;
            }

            $newcountryalert = new Usersalert();
            $newcountryalert->periodicity = $periodicity;
            $newcountryalert->countries = $country;
            $newcountryalert->sources = new \Phalcon\Db\RawValue('default');
            ;
            $newcountryalert->clients = new \Phalcon\Db\RawValue('default');
            ;
            $newcountryalert->metricType = $metric;
            $newcountryalert->alerttype = 0;

            $newcountryalert->dayscompare = $daystocompare;
            $newcountryalert->variation = $variation;
            $newcountryalert->active = $status;
            $newcountryalert->emails = $auth['email'];
            $newcountryalert->userlevel = $auth['userlevel'];
            $newcountryalert->userid = $auth['id'];
            $newcountryalert->specifictime = new \Phalcon\Db\RawValue('default');
            if ($newcountryalert->create() == false) {
                foreach ($newcountryalert->getMessages() as $message) {
                    echo $message, "\n";
                }
            } else {
                echo 1;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    public function newSourceTypeAlertAction() {
        try {
            //newCountryAlert/?country=af&metric=0&daystocompare=1&variation=10&periodicity=1&status=0
            $auth = $this->session->get('auth');
            $sourcetype = $this->request->get('sourcetype');
            $metric = $this->request->get('metric');
            $daystocompare = $this->request->get('daystocompare');
            $variation = $this->request->get('variation');
            $periodicity = $this->request->get('periodicity');
            $status = $this->request->get('status');
            if (!isset($metric) || !isset($daystocompare) || !isset($variation) || !isset($periodicity) || !isset($status)) {
                echo '0';
                return;
            }

            $newsourcetypealert = new Usersalert();
            $newsourcetypealert->periodicity = $periodicity;
            $newsourcetypealert->countries = 'ZZ';
            $newsourcetypealert->sourcetype = $sourcetype;
            $newsourcetypealert->sources = new \Phalcon\Db\RawValue('default');
            ;
            $newsourcetypealert->clients = new \Phalcon\Db\RawValue('default');
            ;
            $newsourcetypealert->metricType = $metric;
            $newsourcetypealert->alerttype = 0;

            $newsourcetypealert->dayscompare = $daystocompare;
            $newsourcetypealert->variation = $variation;
            $newsourcetypealert->active = $status;
            $newsourcetypealert->emails = $auth['email'];
            $newsourcetypealert->userlevel = $auth['userlevel'];
            $newsourcetypealert->userid = $auth['id'];
            $newsourcetypealert->specifictime = new \Phalcon\Db\RawValue('default');
            if ($newsourcetypealert->create() == false) {
                foreach ($newsourcetypealert->getMessages() as $message) {
                    echo $message, "\n";
                }
            } else {
                echo 1;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function newSrcAlertAction() {
        try {
            //newCountryAlert/?country=af&metric=0&daystocompare=1&variation=10&periodicity=1&status=0
            $auth = $this->session->get('auth');
            $country = $this->request->get('country');
            $source = $this->request->get('source');
            $metric = $this->request->get('metric');
            $daystocompare = $this->request->get('daystocompare');
            $variation = $this->request->get('variation');
            $periodicity = $this->request->get('periodicity');
            $status = $this->request->get('status');
            if (!isset($metric) || !isset($daystocompare) || !isset($variation) || !isset($periodicity) || !isset($status)) {
                echo '0';
                return;
            }

            $newsrcalert = new Usersalert();
            $newsrcalert->periodicity = $periodicity;
            $newsrcalert->countries = $country;
            $newsrcalert->sources = $source;
            $newsrcalert->clients = new \Phalcon\Db\RawValue('default');
            ;
            $newsrcalert->metricType = $metric;
            $newsrcalert->alerttype = 0;

            $newsrcalert->dayscompare = $daystocompare;
            $newsrcalert->variation = $variation;
            $newsrcalert->active = $status;
            $newsrcalert->emails = $auth['email'];
            $newsrcalert->userlevel = $auth['userlevel'];
            $newsrcalert->userid = $auth['id'];
            $newsrcalert->specifictime = new \Phalcon\Db\RawValue('default');
            if ($newsrcalert->create() == false) {
                foreach ($newsrcalert->getMessages() as $message) {
                    echo $message, "\n";
                }
            } else {
                echo 1;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function newAggAlertAction() {
        try {
            //newCountryAlert/?country=af&metric=0&daystocompare=1&variation=10&periodicity=1&status=0
            $auth = $this->session->get('auth');
            $country = $this->request->get('country');
            $agg = $this->request->get('client');
            $metric = $this->request->get('metric');
            $daystocompare = $this->request->get('daystocompare');
            $variation = $this->request->get('variation');
            $periodicity = $this->request->get('periodicity');
            $status = $this->request->get('status');
            if (!isset($metric) || !isset($daystocompare) || !isset($variation) || !isset($periodicity) || !isset($status)) {
                echo '0';
                return;
            }

            $newaggalert = new Usersalert();
            $newaggalert->periodicity = $periodicity;
            $newaggalert->countries = $country;
            $newaggalert->clients = $agg;
            $newaggalert->sources = new \Phalcon\Db\RawValue('default');
            ;
            $newaggalert->metricType = $metric;
            $newaggalert->alerttype = 0;

            $newaggalert->dayscompare = $daystocompare;
            $newaggalert->variation = $variation;
            $newaggalert->active = $status;
            $newaggalert->emails = $auth['email'];
            $newaggalert->userlevel = $auth['userlevel'];
            $newaggalert->userid = $auth['id'];
            $newaggalert->specifictime = new \Phalcon\Db\RawValue('default');
            if ($newaggalert->create() == false) {
                foreach ($newaggalert->getMessages() as $message) {
                    echo $message, "\n";
                }
            } else {
                echo 1;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function editAlertAction() {
        try {
            //editAlert/?type=0&idrow="+id+"&country="+"&metric="+"&daystocompare="+"&variation="+"&periodicity="+"&status="
            $auth = $this->session->get('auth');
            $country = $this->request->get('country')!= null ? $this->request->get('country') :'ZZ';
            $sourcetype = $this->request->get('sourcetype')!= null ? $this->request->get('sourcetype') :null;
            $type = $this->request->get('type');
            $idrow = $this->request->get('idrow');
            $alert = Usersalert::findFirst("userid = " . $auth['id'] . " AND alerttype = 0 AND id=" . $idrow);
            if (empty($alert)) {
                echo '0';
                return;
            }
            $source = null;
            
            $agg = null;
            if ($type == 1) {
                $source = $this->request->get('source');
            } else if ($type == 2) {
                $agg = $this->request->get('client');
            }
            
            $metric = $this->request->get('metric');
            $daystocompare = $this->request->get('daystocompare');
            $variation = $this->request->get('variation');
            $periodicity = $this->request->get('periodicity');
            $status = $this->request->get('status');
            if (!isset($metric) || !isset($daystocompare) || !isset($variation) || !isset($periodicity) || !isset($status)) {
                echo '0';
                return;
            }
            $alert->periodicity = $periodicity;
            $alert->countries = $country;
            if($type == 2 && isset($sourcetype))
                $alert->sourcetype=$sourcetype;
            else 
                $alert->sourcetype= new \Phalcon\Db\RawValue('default');
            if ($type == 1)
                $alert->sources = $source;
            else
                $alert->sources = new \Phalcon\Db\RawValue('default');
            if ($type == 2)
                $alert->clients = $agg;
            else
                $alert->clients = new \Phalcon\Db\RawValue('default');
            $alert->metricType = $metric;
            $alert->alerttype = 0;

            $alert->dayscompare = $daystocompare;
            $alert->variation = $variation;
            $alert->active = $status;
            $alert->emails = $auth['email'];
            $alert->userlevel = $auth['userlevel'];
            $alert->userid = $auth['id'];
            $alert->specifictime = new \Phalcon\Db\RawValue('default');
            if ($alert->save() == false) {
                foreach ($alert->getMessages() as $message) {
                    echo $message, "\n";
                }
            } else {
                echo 1;
            }
        } catch (Exception $ex) {
            
        }
    }

    public function editTablesAction() {
        try {
            $auth = $this->session->get('auth');
            $periodicity = $this->request->get('periodicity');
            $status = $this->request->get('status');
            $daysTocompare = $this->request->get('dayscompare');
            if (!isset($periodicity) || !isset($status) || !isset($daysTocompare)) {
                echo '0';
                return;
            }
            $row = Usersalert::findFirst("userid = " . $auth['id'] . " AND alerttype = 1");
            if (empty($row)) {
                $row = new Usersalert();

                $row->countries = new \Phalcon\Db\RawValue('default');
                $row->clients = new \Phalcon\Db\RawValue('default');
                $row->sources = new \Phalcon\Db\RawValue('default');
                $row->metricType = new \Phalcon\Db\RawValue('default');
                $row->alerttype = 1;
                $row->variation = new \Phalcon\Db\RawValue('default');

                $row->emails = $auth['email'];
                $row->userlevel = $auth['userlevel'];
                $row->userid = $auth['id'];
                $row->specifictime = new \Phalcon\Db\RawValue('default');
            }
            $row->dayscompare = $daysTocompare;
            $row->active = $status;
            $row->periodicity = $periodicity;
            if ($row->save() == false) {
                foreach ($row->getMessages() as $message) {
                    echo $message, "\n";
                }
            } else {
                echo 1;
            }
        } catch (Exception $ex) {
            
        }
    }

    public function removeAlertAction() {
        try {
            $auth = $this->session->get('auth');
            if ($this->request->get('idrow') == null || $this->request->get('idrow') == 'undefined') {
                echo '0';
                return;
            }
            $idrow = $this->request->get('idrow');
            $alert = Usersalert::findFirst("userid = " . $auth['id'] . " AND alerttype = 0 AND id=" . $idrow);
            if (empty($alert)) {
                echo '0';
                return;
            } else {
                if ($alert->delete() == false) {
                    foreach ($alert->getMessages() as $message) {
                        echo $message, "\n";
                    }
                } else {
                    echo 1;
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    private function getSources() {
        $cache = $this->di->get("viewCache");
        $comboString = $cache->get('sources');
        $comboString = nUlL;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getSources();
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString.='<option value="' . $row['id'] . '">' . $row['sourceName'] . '-' . $row['id'] . '</option>';
            }
            $cache->save('sources', $comboString);
        }
        return $comboString;
    }

    private function getAggregators() {
        $cache = $this->di->get("viewCache");
        $arrLogin = $this->login_access();
        $comboString = $cache->get('aggregators' . $arrLogin[3]);
        $comboString = nuLL;
        if ($comboString == null) {
            $operation = new Operation();
            $dbres = $operation->getAggregators($arrLogin[2]);
            $comboString = '';
            foreach ($dbres as &$row) {
                $comboString.='<option value="' . $row['id'] . '">' . $row['agregator'] . '-' . $row['id'] . '</option>';
            }
            $cache->save('aggregators' . $arrLogin[3], $comboString);
        }

        return $comboString;
    }

    private function genTablesTable($tableModel) {
        try {
            
        } catch (Exception $ex) {
            
        }
    }

    private function generateSelectOption($dbres, $id, $value) {
        if (empty($dbres)) {
            return null;
        }
        $option = '';
        if (isset($id)) {
            foreach ($dbres as $row) {
                $option .= '<option value="' . $row[$id] . '">' . $row[$value] . '</option>';
            }
        } else {
            foreach ($dbres as $row) {
                $option .= '<option value="' . $row . '">' . $row . '</option>';
            }
        }
        return $option;
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
        $aff = null; // plain CM's as Ivo Facha PNeves
        if ($auth['userlevel'] == 2 && $auth['utype'] == 1) {//report to affiliate manager 
            $sources = $auth['affiliates'];
            $aff = 1;
        } else if ($auth['id'] == 4 || $auth['id'] == 6 || $auth['userlevel'] == 3) { // users == Ric Me and Commercials
            $aff = 2;
        } else if ($auth['userlevel'] == 2 && $auth['utype'] == 2) { // mainstream CM's  like Arrojado
            $aff = 3;
        }

        return array($country, $sources, $aggs, $auth['id'], $aff);
    }

}

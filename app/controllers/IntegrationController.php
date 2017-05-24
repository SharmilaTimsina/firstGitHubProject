<?php

use Phalcon\Db\Column as Column;

class IntegrationController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Integration');
        parent::initialize();
    }

    public function indexAction() {

        $auth = $this->session->get('auth');

        $agre = new Agregator();
        $result = $agre->get_agregators($auth);

        $agregatorsTable = "";
        $agregatorsSelect = "";
        $trRowAgregators = 0;
        foreach ($result as $agregator) {

            $agregatorsTable .= "<tr trNumber = $trRowAgregators>
                            <td id='id'>$agregator[id]</td>
                            <td id='nameAgregator'>$agregator[agregator]</td>
                            <td id='trackParameter'>$agregator[trackingParam]</td>
                            <td id='sinfo'>$agregator[sinfo]</td>
                            <td id='custom_url'>$agregator[custom_url]</td>
							<td id='currency'>$agregator[currency]</td>
							<td id='currencyParam'>$agregator[currencyRequestKey]</td>
							<td id='payoutParam'>$agregator[payoutRequestKey]</td>

                            <td class='iconEdit'><img class='modalIcon' src='/img/iconEdit.svg' data-toggle='modal' data-target='#editAgregator' /></td>
                        </tr>";

            $trRowAgregators++;

            $agregatorsSelect .= "<option value='$agregator[id]'>$agregator[agregator]</option>";
        }

        if ($auth['userlevel'] != 3 && $auth['userlevel'] != 4) {
            $sour = new Sources;
            $sources = $sour->get_sources();

            $sourcesTable = "";
            $trRowSources = 0;
            foreach ($sources as $source) {

                ($source['affiliate'] == 0) ? $edit = "<img class='modalIcon2' src='/img/iconEdit.svg' data-toggle='modal' data-target='#editSource' />" : $edit = "-";

                $sourcesTable .= "<tr trNumber = $trRowSources>
                            <td id='idSource'>$source[id]</td>
                            <td id='nameSource'>$source[sourceName]</td>
							<td id='externalParamSource'>$source[parameters]</td>
                            <td class='iconEdit'>
                                $edit
                            </td>
                        </tr>";

                $trRowSources++;
            }

            $this->view->setVar("sourceFields", $sourcesTable);
        }

        if ($auth['userlevel'] == 1) {
            $usersTable = "";
            $trRowUsers = 0;

            $users = Users::find(
                            array(
                                "userlevel = 2 AND utype = 0"
            ));

            foreach ($users as $user) {

                $usersTable .= "<tr trNumber = $trRowUsers>
                            <td id='nameUser'>$user->username</td>
                            <td id='countriesUser'><div class='countries'> $user->countries </div></td>
                            <td id='sourcesUser'><div class='sources'> $user->sources </div></td>
                            <td class='iconEdit'>
                                <img class='modalIcon3' src='/img/iconEdit.svg' data-toggle='modal' data-target='#editUser' />
                            </td>
                        </tr>";

                $trRowUsers++;
            }

            $this->view->setVar("usersFields", $usersTable);

            //////////////////////////////

            $trRowMClick = 0;
            $multiClickTable = "";

            $links = new Slink();
            $resp = $links->get_newTable();

            foreach ($resp as $row) {

                $multiClickTable .= "<tr idGroup='$row[idGroup]' trNumber = $trRowMClick>
                            <td id='descriptionMultiClick'>$row[description]</td>
                            <td id='searchMultiClick'>$row[search]</td>
                            <td id='dateMultiClick'>$row[insertTimestamp]</td>
                            <td class='iconEdit'>
                                <img class='modalIcon5' src='/img/reverse.png' data-toggle='modal' data-target='#revertMultiClick' />
                            </td>
                        </tr>";

                $trRowMClick++;
            }

            $this->view->setVar("mClickFields", $multiClickTable);
        }

        $this->view->setVar("agregatorsSelect", $agregatorsSelect);
        $this->view->setVar("agregatorsFields", $agregatorsTable);

        if ($auth['userlevel'] == 1 || ($auth['userlevel'] == 2 && $auth['utype'] == 2)) {
            $domainsTable = "";
            $trRowDomains = 0;

            $domains = Domains::find();

            foreach ($domains as $domain) {

                $domainsTable .= "<tr idDomain='$domain->id' trNumber = $trRowDomains>
                            <td id='domainDomain'>$domain->domain</td>
                            <td id='countriesDomain'>$domain->countries</td>
                            <td id='sourcesDomain'>$domain->sources</td>
							<td id='facebookPageIdDomain'>$domain->facebookPageId</td>
                            <td class='iconEdit'>
                                <img class='modalIcon4' src='/img/iconEdit.svg' data-toggle='modal' data-target='#editDomain' />
                                <img class='modalIcon44' src='/img/iconDelete.png' data-toggle='modal' data-target='#deleteDomain' />
                            </td>
                        </tr>";

                $trRowDomains++;
            }

            $this->view->setVar("domainsFields", $domainsTable);
        }

        ////////////////////

        $risqiqTable = "";
        $trRowrisqiq = 0;

        $integr = new Integration();
        $lines = $integr->getInfoLines();

        /*
          echo "<pre>";
          print_r($lines);
          echo "</pre>";
         */


        foreach ($lines as $line) {

            $risqiqTable .= "<tr idRisqIq=$line[linkedjump] trNumber =$trRowrisqiq>
						<td id='idRisqIq'>$line[id]</td>
						<td id='hashmaskRisqIq'>$line[hashMask]</td>
						<td id='lpurlRisqIq'>$line[lpUrl]</td>
						<td id='campaignRisqIq'>$line[campaign]</td>
						<td id='rurlRisqIq'>$line[rurl]</td>
						<td id='percentRisqIq'>$line[percent]</td>
						<td class='iconEdit'>
							<img class='modalIcon77' src='/img/iconDelete.png' data-toggle='modal' data-target='#deleteRisqIq' />
						</td>
					</tr>";



            $trRowrisqiq++;
        }

        $this->view->setVar("risqIqFields", $risqiqTable);


        ////////////////////

        if ($auth['userlevel'] == 1 || $auth['id'] == 21) {
            $sourcesMainstreamUsers = "";

            $integr = new Integration();
            $lines = $integr->getMainstreamUsers();

            /*
              echo "<pre>";
              print_r($lines);
              echo "</pre>";
             */


            foreach ($lines as $line) {

                $sourcesMainstreamUsers .= "<tr>
                            <td>$line[name]</td>
                            <td>$line[investaccess]</td>
                        </tr>";



                $trRowrisqiq++;
            }

            $this->view->setVar("sourcesMainstreamUsers", $sourcesMainstreamUsers);
        }
    }

    public function delete_risqiqAction() {

        $integr = new Integration();

        $hash = $this->request->getPost('hash');

        $integr->deleteLine($hash);

        $risqiqTable = "";
        $trRowrisqiq = 0;

        $lines = $integr->getInfoLines();

        foreach ($lines as $line) {

            $risqiqTable .= "<tr idRisqIq=$line[linkedjump] trNumber =$trRowrisqiq>
						<td id='idRisqIq'>$line[id]</td>
						<td id='hashmaskRisqIq'>$line[hashMask]</td>
						<td id='lpurlRisqIq'>$line[lpUrl]</td>
						<td id='campaignRisqIq'>$line[campaign]</td>
						<td id='rurlRisqIq'>$line[rurl]</td>
						<td id='percentRisqIq'>$line[percent]</td>
						<td class='iconEdit'>
							<img class='modalIcon77' src='/img/iconDelete.png' data-toggle='modal' data-target='#deleteRisqIq' />
						</td>
					</tr>";



            $trRowrisqiq++;
        }

        echo $risqiqTable;
    }

    public function save_agregatorAction() {
        $auth = $this->session->get('auth');
        $data_array = array(
            'id' => $this->request->getPost('id'),
            'agregator' => $this->request->getPost("name"),
            'trackingParam' => $this->request->getPost("trackingparameter"),
            'sinfo' => $this->request->getPost("sinfo"),
            'custom_url' => $this->request->getPost("curl"),
            'currency' => $this->request->getPost("currency"),
            'currencyRequestKey' => $this->request->getPost("currencyParam"),
            'payoutRequestKey' => $this->request->getPost("payoutParam")
        );

        $array_final = array();
        foreach ($data_array as $key => $value) {
            if ($value == "") {
                if ($key == "sinfo")
                    $array_final[$key] = new \Phalcon\Db\RawValue('default');
                else if ($key == "custom_url")
                    unset($data_array[$key]);
            } else
                $array_final[$key] = rtrim($value);
        }


        $agregator = Agregator::findFirst($data_array["id"]);

        foreach ($array_final as $key => $value) {
            $agregator->$key = $value;
        }

        if ($agregator->save() == false) {
            foreach ($agregator->getMessages() as $message) {
                echo $message, "\n";
            }
        } else {

            $agre = new Agregator();
            $agregator->meta_dataHandle($data_array['id'], $data_array);



            $result = $agre->get_agregators($auth);

            $agregatorsTable = "";
            $trRowAgregators = 0;
            foreach ($result as $agregator) {

                $agregatorsTable .= "<tr trNumber = $trRowAgregators>
                            <td id='id'>$agregator[id]</td>
                            <td id='nameAgregator'>$agregator[agregator]</td>
                            <td id='trackParameter'>$agregator[trackingParam]</td>
                            <td id='sinfo'>$agregator[sinfo]</td>
                            <td id='custom_url'>$agregator[custom_url]</td>
							<td id='currency'>$agregator[currency]</td>
							<td id='currencyParam'>$agregator[currencyRequestKey]</td>
							<td id='payoutParam'>$agregator[payoutRequestKey]</td>

                            <td class='iconEdit'><img class='modalIcon' src='/img/iconEdit.svg' data-toggle='modal' data-target='#editAgregator' /></td>
                        </tr>";

                $trRowAgregators++;
            }

            echo $agregatorsTable;
        }
    }

    public function save_sourceAction() {

        $data_array = array(
            'id' => $this->request->getPost('id'),
            'sourceName' => $this->request->getPost("sourceName")
        );

        $array_final = array();
        foreach ($data_array as $key => $value) {
            if ($value == "")
                unset($data_array[$key]);
            else
                $array_final[$key] = $value;
        }

        $source = Sources::findFirst($data_array["id"]);

        foreach ($array_final as $key => $value) {
            $source->$key = $value;
        }

        if ($source->save() == false) {
            foreach ($source->getMessages() as $message) {
                echo $message, "\n";
            }
        } else {

            $sour = new Sources;
            $sources = $sour->get_sources();

            $sourcesTable = "";
            $trRowSources = 0;
            foreach ($sources as $source) {

                ($source['affiliate'] == 0) ? $edit = "<img class='modalIcon2' src='/img/iconEdit.svg' data-toggle='modal' data-target='#editSource' />" : $edit = "-";

                $sourcesTable .= "<tr trNumber = $trRowSources>
                            <td id='idSource'>$source[id]</td>
                            <td id='nameSource'>$source[sourceName]</td>
							<td id='externalParamSource'>$source[parameters]</td>
                            <td class='iconEdit'>
                                $edit
                            </td>
                        </tr>";

                $trRowSources++;
            }


            echo $sourcesTable;
        }
    }

    public function save_userAction() {
        $auth = $this->session->get('auth');

        $data_array = array(
            'username' => $this->request->getPost('username'),
            'countries' => trim($this->request->getPost("countriesUser")),
            'sources' => trim($this->request->getPost("sourcesUser"))
        );



        $array_final = array();
        foreach ($data_array as $key => $value) {
            /*
              if ($value == "")
              unset($data_array[$key]);
              else
             */

            $array_final[$key] = $value;
        }

        $user = Users::findFirst('username = "' . $data_array["username"] . '"');

        foreach ($array_final as $key => $value) {
            $user->$key = $value;
        }

        if ($user->save() == false) {
            foreach ($user->getMessages() as $message) {
                echo $message, "\n";
            }
        } else {

            if ($auth['userlevel'] == 1) {
                $usersTable = "";
                $trRowUsers = 0;

                $users = Users::find(
                                array(
                                    "userlevel = 2 AND utype = 0"
                ));

                foreach ($users as $user) {

                    $usersTable .= "<tr trNumber = $trRowUsers>
                            <td id='nameUser'>$user->username</td>
                            <td id='countriesUser'><div class='countries'> $user->countries </div></td>
                            <td id='sourcesUser'><div class='sources'> $user->sources </div></td>
                            <td class='iconEdit'>
                                <img class='modalIcon3' src='/img/iconEdit.svg' data-toggle='modal' data-target='#editUser' />
                            </td>
                        </tr>";

                    $trRowUsers++;
                }

                echo $usersTable;
            }
        }
    }

    public function save_domainAction() {
        $auth = $this->session->get('auth');
        $data_array = array(
            'id' => $this->request->getPost('id'),
            'domain' => $this->request->getPost('domain'),
            'countries' => trim($this->request->getPost("countriesDomain")),
            'sources' => trim($this->request->getPost("sourcesDomain")),
            'facebookPageId' => trim($this->request->getPost("facebookpageDomain"))
        );

        $array_final = array();
        foreach ($data_array as $key => $value) {
            if ($value == "")
                unset($data_array[$key]);
            else
                $array_final[$key] = $value;
        }

        $domain = Domains::findFirst($data_array["id"]);

        foreach ($array_final as $key => $value) {
            $domain->$key = $value;
        }

        if ($domain->save() == false) {
            foreach ($domain->getMessages() as $message) {
                echo $message, "\n";
            }
        } else {

            $domainsTable = "";
            $trRowDomains = 0;

            $domains = Domains::find();

            foreach ($domains as $domain) {

                $domainsTable .= "<tr idDomain='$domain->id' trNumber = $trRowDomains>
                            <td id='domainDomain'>$domain->domain</td>
                            <td id='countriesDomain'>$domain->countries</td>
                            <td id='sourcesDomain'>$domain->sources</td>
                            <td id='facebookPageIdDomain'>$domain->facebookPageId</td>
                            <td class='iconEdit'>
                                <img class='modalIcon4' src='/img/iconEdit.svg' data-toggle='modal' data-target='#editDomain' />
                                <img class='modalIcon44' src='/img/iconDelete.png' data-toggle='modal' data-target='#deleteDomain' />
                            </td>
                        </tr>";

                $trRowDomains++;
            }

            echo $domainsTable;
        }
    }

    public function delete_domainAction() {
        $auth = $this->session->get('auth');
        $data_array = array(
            'id' => $this->request->getPost('id')
        );

        $domain = Domains::findFirst($data_array["id"]);

        if ($domain->delete() == false) {
            foreach ($domain->getMessages() as $message) {
                echo $message, "\n";
            }
        } else {

            $domainsTable = "";
            $trRowDomains = 0;

            $domains = Domains::find();

            foreach ($domains as $domain) {

                $domainsTable .= "<tr idDomain='$domain->id' trNumber = $trRowDomains>
                            <td id='domainDomain'>$domain->domain</td>
                            <td id='countriesDomain'>$domain->countries</td>
                            <td id='sourcesDomain'>$domain->sources</td>
                            <td id='facebookPageIdDomain'>$domain->facebookPageId</td>
                            <td class='iconEdit'>
                                <img class='modalIcon4' src='/img/iconEdit.svg' data-toggle='modal' data-target='#editDomain' />
                                <img class='modalIcon44' src='/img/iconDelete.png' data-toggle='modal' data-target='#deleteDomain' />
                            </td>
                        </tr>";

                $trRowDomains++;
            }

            echo $domainsTable;
        }
    }

    public function new_domainAction() {
        $auth = $this->session->get('auth');
        $data_array = array(
            'insertdate' => date("Y-m-d"),
            'domain' => $this->request->getPost('newdomain'),
            'countries' => trim($this->request->getPost("newcountriesDomain")),
            'sources' => trim($this->request->getPost("newsourcesDomain")),
            'facebookPageId' => trim($this->request->getPost("newfacebookpageDomain")),
        );

        $domain = new Domains();

        foreach ($data_array as $key => $value) {
            $domain->$key = $value;
        }

        if ($domain->save() == false) {
            foreach ($domain->getMessages() as $message) {
                echo $message, "\n";
            }
        } else {

            $domainsTable = "";
            $trRowDomains = 0;

            $domains = Domains::find();

            foreach ($domains as $domain) {

                $domainsTable .= "<tr idDomain='$domain->id' trNumber = $trRowDomains>
                            <td id='domainDomain'>$domain->domain</td>
                            <td id='countriesDomain'>$domain->countries</td>
                            <td id='sourcesDomain'>$domain->sources</td>
                            <td id='facebookPageIdDomain'>$domain->facebookPageId</td>
                            <td class='iconEdit'>
                                <img class='modalIcon4' src='/img/iconEdit.svg' data-toggle='modal' data-target='#editDomain' />
                                <img class='modalIcon44' src='/img/iconDelete.png' data-toggle='modal' data-target='#deleteDomain' />
                            </td>
                        </tr>";

                $trRowDomains++;
            }

            echo $domainsTable;
        }
    }

    public function get_clicksInfoAction() {
        $data_array = array(
            'description' => $this->request->getPost('editClickDescription'),
            'searchclick' => $this->request->getPost('editClickLPSearch')
        );

        $links = new Slink();
        $resp = $links->get_InfoClicksNumber($data_array);

        print_r(json_encode($resp));
    }

    public function setEditClicksAction() {
        $data_array = array(
            'description' => $this->request->getPost('editClickDescription'),
            'searchclick' => $this->request->getPost('editClickLPSearch')
        );

        if ($data_array['description'] != '' && $data_array['searchclick'] != '' && strlen($data_array['searchclick']) > 5) {
            $links = new Slink();
            $number = $links->get_InfoClicksNumber($data_array);

            if ($number[0]['numberLines'] != 0) {

                $resp = $links->editclicksTable($data_array);

                $this->printTableMultiClick($resp);
            }
        } else {
            echo "error";
        }
    }

    private function printTableMultiClick($resp) {
        $trRowMClick = 0;
        $multiClickTable = "";
        foreach ($resp as $row) {

            $multiClickTable .= "<tr idGroup='$row[idGroup]' trNumber = $trRowMClick>
                            <td id='descriptionMultiClick'>$row[description]</td>
                            <td id='searchMultiClick'>$row[search]</td>
                            <td id='dateMultiClick'>$row[insertTimestamp]</td>
                            <td class='iconEdit'>
                                <img class='modalIcon5' src='/img/reverse.png' data-toggle='modal' data-target='#revertMultiClick' />
                            </td>
                        </tr>";

            $trRowMClick++;
        }

        echo $multiClickTable;
    }

    public function reverse_multiclickAction() {
        $idGroup = $this->request->getPost('id');


        $links = new Slink();
        $resp = $links->set_changeMultiClick($idGroup);

        $this->printTableMultiClick($resp);
    }

    public function report_conversionAction() {
        ini_set("memory_limit", "2048M");

        $data_array = array(
            'yearAndMonth' => $this->request->get('yearAndMonth'),
            'startDay' => $this->request->get("startDay"),
            'endDay' => $this->request->get("endDay"),
            'duplicate' => $this->request->get("duplicate"),
            'agregator' => $this->request->get("agregator")
        );

        $integrationInfo = new Integration();
        $resp = $integrationInfo->get_conversionReport($data_array);

        $title = "Conversions Report";
        $columns = array("Country", "IP", "Date", "clickId", "CPA");
        $monthYear = explode("/", $data_array['yearAndMonth']);
        if ($monthYear[0] >= 08 && $monthYear[1] >= 2016) {
            $columns[] = "requestmade";
        }
        $columns[] = 'campaign';
        $columns[] = 'URL';
        $this->sendExcel($resp, $columns, $title);
    }

    public function get_excelAction() {
        $type = $this->request->get('type');

        $auth = $this->session->get('auth');

        if ($type == 1) {
            //download excel agregators
            $array_final = array();

            $agre = new Agregator();
            $result = $agre->get_agregators($auth);

            foreach ($result as $agregator) {
                array_push($array_final, array("id" => $agregator['id'], "agregator_name" => $agregator['agregator']));
            }

            $title = "Agregator Report";
            $columns = array("id", "agregator_name");
            $this->sendExcel($array_final, $columns, $title);
        } else if ($type == 2) {
            //download excel sources

            if ($auth['userlevel'] != 3) {
                $array_final = array();
                $sources = Sources::find();
                foreach ($sources as $source) {
                    array_push($array_final, array("id" => $source->id, "sourceName" => $source->sourceName));
                }

                $title = "Sources Report";
                $columns = array("id", "source_name");
                $this->sendExcel($array_final, $columns, $title);
            }
        }
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

    public function save_risqiqAction() {
        $data_array = array(
            'urlRisk' => $this->request->getPost('urlTestRisk'),
            'nameRisk' => $this->request->getPost('nameTestRisk')
        );

        $integration = new Integration();
        $integration->save_test($data_array);
    }

}

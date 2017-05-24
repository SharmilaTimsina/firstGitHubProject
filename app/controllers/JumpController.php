<?php

class JumpController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Jump Manager');
        parent::initialize();
    }

    public function indexAction() {
        
    }

    public function uploadCsvAction() {

        if (isset($_FILES["file"])) {
            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
            } else {
                $tmpName = $_FILES['file']['tmp_name'];
                $csvAsArray = array_map('str_getcsv', file($tmpName));

                $array_status = array();
                foreach (array_slice($csvAsArray, 1) as $line) {
                    $info = array(
                        'aggregatorid' => $line[0],
                        'aggregatorname' => trim(strtolower($line[1])),
                        'country' => trim(strtolower($line[2])),
                        'campaignurl' => $line[3],
                        'cpa' => $line[4],
                        'currency' => $line[5],
                        'typeProduct' => $line[6],
                        'connection' => trim(strtolower($line[7]))
                    );

                    //campaign name
                    $newcampaignName = NULL;
                    switch ($info['typeProduct']) {
                        case 1:  //Adult
                            $newcampaignName = $info['country'] . $info['aggregatorname'] . $info['connection'];
                            break;
                        case 2:  //Mainstream
                            $newcampaignName = 'ms' . $info['country'] . $info['aggregatorname'] . $info['connection'];
                            break;
                        case 3:  //Dating
                            $newcampaignName = $info['country'] . 'dat' . $info['aggregatorname'] . $info['connection'];
                            break;
                    }

                    /*
                      $jumpsNames = Jump::find(
                      array(
                      //"campaign LIKE '$newcampaignName%'"
                      "campaign REGEXP '^" . $newcampaignName . "[0-9]*$'"
                      )
                      );
                     */
                    $jump = new Jump();
                    $jumpsNames = $jump->getJumpNames($newcampaignName);

                    $numbers = array();
                    foreach ($jumpsNames as $jump) {
                        array_push($numbers, str_replace($newcampaignName, '', $jump['campaign']));
                    }

                    $maxNumber = (isset($numbers) && !empty($numbers)) ? max($numbers) : 0;
                    $newNumber = $maxNumber + 1;

                    //currency and cpa
                    $cpaHandle = array();
                    if ($info['currency'] != 'USD') {
                        //conversion
                        $jump = new Jump();
                        $rate = $jump->getCurrencyHistory($info['currency']);

                        $convertedcpa = $info['cpa'] * $rate[0]['rate'];
                        $cpaHandle = array(
                            'cpa' => $convertedcpa,
                            'cpaOriginalValue' => $info['cpa'],
                            'curtype' => $info['currency']
                        );
                    } else {
                        $cpaHandle = array(
                            'cpa' => $info['cpa'],
                            'cpaOriginalValue' => ' ',
                            'curtype' => $info['currency']
                        );
                    }

                    $hash = uniqid();
                    $jump = new Jump();
                    $jump->hash=  $hash;
                    $jump->agregator=  $info['aggregatorid'];
                    $jump->client=  1;
                    $jump->country=  $info['country'];
                    $jump->campaign=  $newcampaignName . $newNumber;
                    $jump->source= new \Phalcon\Db\RawValue('default');
                    $jump->format=  0;
                    $jump->adnumber=  ' ';
                    $jump->rurl=  $info['campaignurl'];
                    $jump->cpa=  $cpaHandle['cpa'];
                    $jump->cpaOriginalValue=  $cpaHandle['cpaOriginalValue'];
                    $jump->curtype=  $cpaHandle['curtype'];
                    $jump->afcarrier=  ' ';
                    $jump->aff_flag=  0;
                    $jump->geo_flag=  NULL;
                    $jump->insertTimestamp=  date("Y-m-d H:i:s");
                    $jump->type_flag=  $info['typeProduct'];
                    if ($jump->save() == false) {

                        $errors = '';
                        foreach($jump->getMessages() as $message) {
                            $errors .= $message;
                        }
                        
                        array_push($array_status, array(
                            "hash" => $hash,
                            "source" => 'empty',
                            "agregator" => $info['aggregatorid'],
                            "client" => 1,
                            "country" => $info['country'],
                            "campaign" => $newcampaignName . $newNumber,
                            "format" => 0,
                            "adnumber" => ' ',
                            "rurl" => $info['campaignurl'],
                            "cpa" => $cpaHandle['cpa'],
                            "cpaOriginalValue" => $cpaHandle['cpaOriginalValue'],
                            "curtype" => $cpaHandle['curtype'],
                            "afcarrier" => ' ',
                            "aff_flag" => 0,
                            "geo_flag" => NULL,
                            "insertTimestamp" => date("Y-m-d H:i:s"),
                            "type_flag" => $info['typeProduct'],
                            "error" => $errors,
                            "jump" => 'http://jump.mobipiumlink.com/?jp=' . $hash . '&id=' . $info['aggregatorid'] . '_' . $info['country'] . '_1_' . $newcampaignName . $newNumber . '_'
                        ));
                    } else {

                        array_push($array_status, array(
                            "hash" => $hash,
                            "agregator" => $info['aggregatorid'],
                            "client" => 1,
                            "country" => $info['country'],
                            "campaign" => $newcampaignName . $newNumber,
                            "source" => 'empty',
                            "format" => 0,
                            "adnumber" => ' ',
                            "rurl" => $info['campaignurl'],
                            "cpa" => $cpaHandle['cpa'],
                            "cpaOriginalValue" => $cpaHandle['cpaOriginalValue'],
                            "curtype" => $cpaHandle['curtype'],
                            "afcarrier" => ' ',
                            "aff_flag" => 0,
                            "geo_flag" => NULL,
                            "insertTimestamp" => date("Y-m-d H:i:s"),
                            "type_flag" => $info['typeProduct'],
                            "error" => '0',
                            "jump" => 'http://jump.mobipiumlink.com/?jp=' . $hash . '&id=' . $info['aggregatorid'] . '_' . $info['country'] . '_1_' . $newcampaignName . $newNumber . '_'
                        ));
                    }
                }

                echo $this->getTable($array_status);
            }
        } else {
            echo "No file selected";
        }
    }

    private function getTable($array_status) {
        $table = '';
        $trRow = 0;
        foreach ($array_status as $jump) {

            $error = ($jump['error'] == '0') ? '/img/ok.gif' : '/img/nok.png';

            $type = '';
            switch ($jump['type_flag']) {
                case 1:
                    $type = 'Adult';
                    break;
                case 2:
                    $type = 'Mainstream';
                    break;
                case 3:
                    $type = 'Dating';
                    break;
            }

            $table .= "<tr trNumber = $trRow>
							<td>$trRow</td>
                            <td>$jump[agregator]</td>
                            <td>$jump[country]</td>
                            <td>$jump[campaign]</td>
                            <td>$jump[rurl]</td>
							<td>$jump[jump]</td>
							<td>$jump[cpa]</td>
							<td>$jump[cpaOriginalValue]</td>
							<td>$jump[curtype]</td>
							<td>$type</td>
                            <td class='iconEdit'><img class='iconstate' src='$error'/></td>
                        </tr>";

            $trRow++;
        }

        return $table;
    }

}

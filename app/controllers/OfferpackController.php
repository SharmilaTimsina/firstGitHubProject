<?php

class OfferpackController extends ControllerBase {

    private $modelnames;
    private $verticalnames;
    private $flownames;
    private $ownership;
    private $statusnames;
    private $areanames;
    private $amnames;
    private $cmnames;
    private $image_extensions = array('jpg', 'png', 'jpeg', 'gif', 'bmp', 'tiff', 'exif', 'ppm', 'pgm', 'pbm', 'pnm', 'svg');

    public function initialize() {
        date_default_timezone_set('Europe/Lisbon');
        $this->tag->setTitle('OfferPack');
        parent::initialize();
        $this->populateNames();
    }

    public function indexAction() {
        try {
            if ($this->request->get('pumbas') != null) {
                $this->view->disable();
                $this->findweird();
                return;
                $type = $this->request->get('pumbas') == 1 ? 'banner' : 'screenshot';
                $this->view->disable();
                if ($type == 'banner')
                    $this->filesunzip($type);
                else
                    $this->screenshotunzip();
                //$this->bannerunzip();
            }
            if ($this->request->get('njumpa') != null) {
                $array_filter = array(
                    'jump_name' => $this->request->get('njump')
                );

                $offerpack = new Offerpack();
                $result = $offerpack->getFilteredContent($array_filter, $this->session->get('auth'));
                $this->view->setVar('offervar', "'" . json_encode($result) . "'");
                //echo json_encode($result);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage() . __LINE__;
        }
    }

    private function screenshotunzip() {
        try {
            $db = $sql = $this->getDi()->getDb4();
            $sql = 'SELECT screenshot,hashMask FROM offerpack__offerpack WHERE screenshot is NOT NULL ; ';
            $prepared = $db->prepare($sql);
            $db->executePrepared($prepared, array(), array());
            $resultarray = $prepared->fetchAll(PDO::FETCH_ASSOC);
            //print_r($resultarray);
            if (empty($resultarray)) {
                echo 'empty';
                return;
            }
            foreach ($resultarray as $row) {
                $screenshot = empty($row['screenshot']) ? null : $row['screenshot'];
                echo $screenshot;
                $uu = 5;
                if (file_exists($screenshot)) {
                    if ('zip' == pathinfo($screenshot, PATHINFO_EXTENSION)) {
                        $ziparchive = new ZipArchive();
                        $ziparchive->open($screenshot);
                        if ($ziparchive->numFiles > 5) {
                            //copy($banner, $newfile);
                            $db->query('INSERT INTO offerpack__offerscreenshotfiles (offerhash,location) VALUES (?,?) ;', array($row['hashMask'], $row['screenshot']));
                            $ziparchive->close();
                            continue;
                        }
                        for ($i = 0; $i < $ziparchive->numFiles; $i++) {
                            $stat = $ziparchive->statIndex($i);
                            $file = dirname($row['screenshot']) . '/' . $stat['name'];

                            /* if (array_search(pathinfo($stat['name'], PATHINFO_EXTENSION), $this->image_extensions) && $uu == 5) {
                              echo 'its an image' . "\n";
                              $db->query('UPDATE offerpack__offerpack SET screenshotpreview = ? WHERE hashMask = ? ;', array($file, $row['hashMask']));
                              $uu = 2;
                              } */
                            try {
                                $db->query('INSERT INTO offerpack__offerscreenshotfiles (offerhash,location) VALUES (?,?) ;', array($row['hashMask'], $file));
                            } catch (Exception $e) {
                                echo $e->getMessage() . "\n";
                                continue;
                            }
                        }
                        for ($j = 0; $j < $ziparchive->numFiles; $j++) {
                            $filename = $ziparchive->getNameIndex($j);
                            $extension = pathinfo($filename, PATHINFO_EXTENSION);
                            copy("zip://" . $row['screenshot'] . "#" . $filename, "./screenshot" . $j . "." . $extension);
                        }
                        echo 'done';
                    } else {
                        $db->query('INSERT INTO offerpack__offerscreenshotfiles (offerhash,location) VALUES (?,?) ;', array($row['hashMask'], $row['screenshot']));
                        continue;
                    }
                } else {
                    echo 'file does not exist';
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function findweird() {
        try {
            $db = $this->getDi()->getDb4();
            $sql = 'SELECT id,offerhash,location FROM `offerpack__offerscreenshotfiles` WHERE insertTimestamp <= "2017-05-02 17:34:05"  ORDER BY `offerpack__offerscreenshotfiles`.`location` ASC';
            $prepared = $db->prepare($sql);
            $db->executePrepared($prepared, array(), array());
            $resultarray = $prepared->fetchAll(PDO::FETCH_ASSOC);
            $i = 0;
            echo count($resultarray);
            foreach ($resultarray as $row) {


                //$echofinal = dirname($row['location']) . '/scrennshot' . $i . '.' . pathinfo($row['location'], PATHINFO_EXTENSION);
                //echo $echofinal . ' - ' . $row['location'];
                //echo '<br>';
                //echo dirname($row['location']);
                //echo '<br>';
                foreach (glob(dirname($row['location']) . '/*') as $filename) {
                    if (is_dir($filename) || pathinfo($filename, PATHINFO_EXTENSION) == 'zip' || pathinfo($filename, PATHINFO_EXTENSION) == 'rar' || pathinfo($filename, PATHINFO_EXTENSION) == '7z')
                        continue;

                    $newname = dirname($filename) . '/screenshot' . $i . '.' . pathinfo($filename, PATHINFO_EXTENSION);

                    echo $filename;
                    echo $newname;
                    if (rename($filename, $newname)) {
                        $update = 'UPDATE offerpack__offerscreenshotfiles SET location = "' . $newname . '" WHERE id = ' . $row['id'] . ';';
                        $db->query($update, array(), array());
                        //echo $update;
                        echo '<br>';
                    }
                    $i++;



                    //echo $filename;
                    break;
                }
                continue;

                break;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function filesunzip($type) {
        try {
            $db = $sql = $this->getDi()->getDb4();
            $sql = 'SELECT ' . $type . ',offerhash FROM offerpack__offer' . $type . 's WHERE banner is NOT NULL; ';
            $prepared = $db->prepare($sql);
            $db->executePrepared($prepared, array(), array());
            $resultarray = $prepared->fetchAll(PDO::FETCH_ASSOC);
            //print_r($resultarray);
            if (empty($resultarray)) {
                echo 'empty';
                return;
            }
            foreach ($resultarray as $row) {
                $banner = empty($row[$type]) ? null : $row[$type];
                echo $banner;
                $uu = 5;
                if (file_exists($banner)) {
                    if ('zip' == pathinfo($banner, PATHINFO_EXTENSION)) {
                        $ziparchive = new ZipArchive();
                        $ziparchive->open($banner);
                        if ($ziparchive->numFiles > 5) {
                            //copy($banner, $newfile);
                            $db->query('INSERT INTO offerpack__offer' . $type . 'files (offerhash,location) VALUES (?,?) ;', array($row['offerhash'], $row[$type]));
                            $ziparchive->close();
                            continue;
                        }
                        for ($i = 0; $i < $ziparchive->numFiles; $i++) {
                            $stat = $ziparchive->statIndex($i);
                            $file = dirname($row[$type]) . '/' . $stat['name'];

                            /* if (array_search(pathinfo($stat['name'], PATHINFO_EXTENSION), $this->image_extensions) && $uu == 5) {
                              //echo 'its an image' . "\n";
                              $db->query('UPDATE offerpack__offerpack SET bannerpreview = ? WHERE hashMask = ? ;', array($file, $row['offerhash']));
                              $uu = 2;
                              } */
                            try {
                                $db->query('INSERT INTO offerpack__offer' . $type . 'files (offerhash,location) VALUES (?,?) ;', array($row['offerhash'], $file));
                            } catch (Exception $e) {
                                echo $e->getMessage() . "\n";
                                continue;
                            }
                        }
                        if ($ziparchive->extractTo(dirname($banner))) {
                            echo 'done';
                            $ziparchive->close();
                        } else {
                            echo 'notdone';
                        }
                    } else {
                        $db->query('INSERT INTO offerpack__offer' . $type . 'files (offerhash,location) VALUES (?,?) ;', array($row['offerhash'], $row[$type]));
                        continue;
                    }
                } else {
                    echo 'file does not exist';
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function index2Action() {
        try {
            if ($this->request->get('njumpa') != null) {
                $array_filter = array(
                    'jump_name' => $this->request->get('njump')
                );

                $offerpack = new Offerpack();
                $result = $offerpack->getFilteredContent2($array_filter, $this->session->get('auth'));
                $this->view->setVar('offervar', "'" . json_encode($result) . "'");
                //echo json_encode($result);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function getCampaignJumpNameAction() {

        $offerpack = new Offerpack();
        $auth = $this->session->get('auth');
        $campaigns_name = $offerpack->getCampaigns($auth);
        //$jumps_names = $offerpack->getJumps();

        $result = array(
            'campaigns' => $campaigns_name,
                //'jumps' => $jumps_names
        );

        //echo json_encode($result);
    }

    public function setFilterAction() {
        $array_filter = array(
            'countries' => $this->request->getPost('countries'),
            'carriers' => $this->request->getPost('carriers'),
            'aggs' => $this->request->getPost('aggs'),
            'area' => $this->request->getPost('area'),
            'vertical' => $this->request->getPost('vertical'),
            'model' => $this->request->getPost('model'),
            'status' => $this->request->getPost('status'),
            'account' => $this->request->getPost('account'),
            'campaign_name' => $this->request->getPost('campaign_hash'),
            'jump_name' => $this->request->getPost('jump_hash'),
            'searchInput' => $this->request->getPost('searchInput')
        );

        $offerpack = new Offerpack();
        $result = $offerpack->getFilteredContent($array_filter, $this->session->get('auth'));

        echo json_encode($result);
    }

    public function setFilter2Action() {
        try {
            $array_filter = array(
                'countries' => $this->request->getPost('countries'),
                'carriers' => $this->request->getPost('carriers'),
                'aggs' => $this->request->getPost('aggs'),
                'area' => $this->request->getPost('area'),
                'vertical' => $this->request->getPost('vertical'),
                'model' => $this->request->getPost('model'),
                'status' => $this->request->getPost('status'),
                'account' => $this->request->getPost('account'),
                'campaign_name' => $this->request->getPost('campaign_hash'),
                'jump_name' => $this->request->getPost('jump_hash'),
                'exclusive' => $this->request->getPost('exclusive'),
                'searchInput' => $this->request->getPost('searchInput')
            );
            $excel = $this->request->getPost('excel') == '1' ? 1 : 0;
            $offerpack = new Offerpack();
            $result = $offerpack->getFilteredContent2($array_filter, $this->session->get('auth'), $excel);

            if ($excel) {
                if (empty($result)) {
                    echo '0';
                    exit();
                }

                $columns = array(
                    array('Date', 's', 0),
                    array('Country', 's', 0),
                    array('Carrier', 's', 0),
                    array('AdvertiserID', 's', 0),
                    array('AdvertiserName', 's', 0),
                    array('Area', 's', 0),
                    array('Model', 's', 0),
                    array('Vertical', 's', 0),
                    array('Offercode', 's', 0),
                    array('JumpName', 's', 0),
                    array('CampaignName', 's', 0),
                    array('Cpa', 'd', 3),
                    //array('DailyCap', 'i', 0),
                    array('Currency', 's', 0),
                    array('JumpURL', 's', 0),
                    array('ClientURL', 's', 0),
                    /* array('ScreenshotURL', 's', 0),
                      array('BannerURL', 's', 0),
                      array('AccountManager', 's', 0),
                      array('CampaignManager', 's', 0),
                      array('Exclusive', 's', 0), */
                    array('PayoutHistory', 's', 0),
                    array('StatusHistory', 's', 0)
                );
                $sqlColumns = 'insertTimestamp,country,carrier,advertiser,advertiserName,offerarea,offermodel,vertical,mask,jumpname,campaign_name,cpa,'
                        //. 'dailycap,'
                        . 'curtype,jumpurl,clienturl'
                        //. ',screenshotzip,banner'
                        //. ',offerexclusive,AccountManager,CampaignManager'
                        . ',cpahistory,statushistory';
                $this->generateexcelpackedoffers($columns, $sqlColumns, $result);
            } else {
                //header('Content-Type: application/json; charset=utf-8');
                echo json_encode($this->utf8ize($result));
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            return '';
        }
    }

    private function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    private function generateexcelpackedoffers($columns, $sqlColumns, $res) {
        try {
            $auth = $this->session->get('auth');
            $decimalchar = (($auth['decimalchar'] != null) ? $auth['decimalchar'] : '.');

            $exColumns = "sep=;\n";
            for ($i = 0; $i < sizeof($columns); $i++) {
                $exColumns .= $columns[$i][0] . ';';
            }
            echo $exColumns . "\n";
            $sqlColumns = explode(',', $sqlColumns);
            foreach ($res as &$row) {
                $resultRow = '';
                $j = 0;
                foreach ($sqlColumns as $c) {
                    $resultRow .= ($columns[$j][1] == 's' ? $row[$c] : number_format((float) $row[$c], $columns[$j][2], $decimalchar, '')) . ';';
                    $j++;
                }
                echo $resultRow . "\n";
            }
            exit();
        } catch (Exception $ex) {
            echo '0';
        }
    }

    public function updateCpaAction() {
        $hashs = explode(',', $this->request->getPost('ids'));
        foreach ($hashs as $hash) {
            $m = Mask::findFirst([
                        "hash = :hash: ",
                        "bind" => [
                            "hash" => $hash,
                        ],
            ]);
            if (empty($m)) {
                echo 'next';
                continue;
            }
            $m->source = $m->source ? $m->source : new \Phalcon\Db\RawValue('default');
            $m->adnumber = $m->adnumber ? $m->adnumber : new \Phalcon\Db\RawValue('default');
            $m->afcarrier = $m->afcarrier ? $m->afcarrier : new \Phalcon\Db\RawValue('default');
            if ($m->curtype != 'USD') {
                $cpa = $this->convertToUSD($m->curtype, $this->request->getPost('valueCpa'));
            } else {
                $cpa = $this->request->getPost('valueCpa');
            }

            $m->cpaOriginalValue = $this->request->getPost('valueCpa');
            $m->cpa = $cpa;
            if ($m->save() == false) {
                foreach ($m->getMessages() as $message)
                    echo $message;
            }
        }
    }

    public function disableOfferAction() {
        if ($this->request->getPost('mask') != null) {

            $offerpack = new Offerpack();
            $result = $offerpack->disableOffer($this->request->getPost('mask'));

            $array_filter = array(
                'countries' => $this->request->getPost('countries'),
                'carriers' => $this->request->getPost('carriers'),
                'aggs' => $this->request->getPost('aggs'),
                'area' => $this->request->getPost('area'),
                'vertical' => $this->request->getPost('vertical'),
                'model' => $this->request->getPost('model'),
                'status' => $this->request->getPost('status'),
                'account' => $this->request->getPost('account'),
                'campaign_name' => $this->request->getPost('campaign_hash'),
                'jump_name' => $this->request->getPost('jump_hash'),
                'searchInput' => $this->request->getPost('searchInput')
            );

            $offerpack = new Offerpack();
            $result = $offerpack->getFilteredContent($array_filter);

            echo json_encode($result);
        }
    }

    public function offerpackeditAction() {
        try {
            if ($this->request->get('offerhash') != null) {
                $mask = Mask::findFirst([
                            "hash = :hash: ",
                            "bind" => [
                                "hash" => $this->request->get('offerhash'),
                            ],
                ]);
                if (empty($mask)) {
                    echo 'Campaign does not exist';
                    return;
                }
                $maskfields = array('hash', 'campaign', 'agregator', 'rurl', 'curtype', 'afcarrier', 'aff_flag', 'category', 'status');

                $res = array();
                foreach ($maskfields as $field) {
                    $res[$field] = empty($mask->$field) ? '' : $mask->$field;
                }
                $res['country'] = empty($mask->country) ? '' : strtoupper($mask->country);
                if (!empty($mask->curtype) && $mask->curtype != 'USD') {
                    $res['cpa'] = $mask->cpaOriginalValue;
                } else {
                    $res['cpa'] = $mask->cpa;
                }


                $packoffer = Offerpack::findFirst([
                            "hashMask = :hashMask: ",
                            "bind" => [
                                "hashMask" => $this->request->get('offerhash'),
                            ],
                ]);
                if (!empty($packoffer)) {
                    $arroffer = array("offername", "carrier", "status", "verticalid", "vertical", "flow", "flowid", "modelid", "model", "dailycap", "accountmanager", "campaignmanager", "area", "description", "exclusive", "ownershipid", "insertTimestamp");
                    foreach ($arroffer as $off) {
                        $res[$off] = (!isset($packoffer->$off) || $packoffer->$off != "") ? $packoffer->$off : '';
                    }
                }
                $sql = $this->getDi()->getDb4()->prepare('SELECT GROUP_CONCAT(r.name SEPARATOR ",") as name, GROUP_CONCAT(orr.regulationid SEPARATOR ",") as regulations FROM offerpack__regulations r inner join offerpack__offerregulations orr ON orr.regulationid = r.id WHERE orr.offerhash LIKE :campaign ');
                $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('campaign' => ($this->request->get('offerhash'))), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($array_ret) && !empty($array_ret[0]) && !empty($array_ret[0]['regulations']))
                    $res['regulations'] = $array_ret[0]['regulations'];
                else
                    $res['regulations'] = '';


                $sql = $this->getDi()->getDb4()->prepare('SELECT offerhash FROM offerpack__offerbanners WHERE offerhash LIKE :offerhash ');
                $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('offerhash' => ($this->request->get('offerhash'))), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $array_ret2 = $prepared->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($array_ret2) && !empty($array_ret2[0]) && !empty($array_ret2[0]['offerhash']))
                    $bannerhash = $array_ret2[0]['offerhash'];
                else
                    $bannerhash = null;

                $this->view->setVar('downloadBanners', (isset($bannerhash) ? '/offerpack/getBanners?offerhash=' . $bannerhash : ''));
                $this->view->setVar('downloadScreenshot', (!empty($packoffer->screenshot) ? '/offerpack/getScreenshot?offerhash=' . $packoffer->hashMask : ''));
                $this->view->setVar('deleteBanners', (isset($bannerhash) ? '/offerpack/deletebanner?offerhash=' . $bannerhash : ''));
                $this->view->setVar('deleteScreenshot', (!empty($packoffer->screenshot) ? '/offerpack/deletescreenshot?offerhash=' . $packoffer->hashMask : ''));
                $this->view->setVar('offervar', json_encode($res));
                $this->view->setVar('clone', ($this->request->get('clone') != null && $this->request->get('clone') == '1' ? 'true' : 'false'));
            }
            $this->view->setVar('clone', ($this->request->get('clone') != null && $this->request->get('clone') == '1' ? 'true' : 'false'));
        } catch (Exception $ex) {
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function offerpackedit2Action() {
        try {
            $offerhash = $this->request->get('offerhash');
            if (empty($offerhash)) {
                $offerhash = $this->request->get('clone');
            }
            if (!empty($offerhash)) {
                $mask = Mask::findFirst([
                            "hash = :hash: ",
                            "bind" => [
                                "hash" => $offerhash,
                            ],
                ]);
                if (empty($mask)) {
                    echo 'Campaign does not exist';
                    return;
                }
                $maskfields = array('hash', 'campaign', 'agregator', 'rurl', 'curtype', 'afcarrier', 'aff_flag', 'category', 'status');

                $res = array();
                foreach ($maskfields as $field) {
                    $res[$field] = empty($mask->$field) ? '' : $mask->$field;
                }
                $res['country'] = empty($mask->country) ? '' : strtoupper($mask->country);
                if (!empty($mask->curtype) && $mask->curtype != 'USD') {
                    $res['cpa'] = $mask->cpaOriginalValue;
                } else {
                    $res['cpa'] = $mask->cpa;
                }


                $packoffer = Offerpack::findFirst([
                            "hashMask = :hashMask: ",
                            "bind" => [
                                "hashMask" => $offerhash,
                            ],
                ]);
                if (!empty($packoffer)) {
                    $arroffer = array("offername", "carrier", "status", "verticalid", "vertical", "flow", "flowid", "modelid", "model", "dailycap", "accountmanager", "campaignmanager", "area", "description", "exclusive", "ownershipid", "insertTimestamp");
                    foreach ($arroffer as $off) {
                        $res[$off] = (!isset($packoffer->$off) || $packoffer->$off != "") ? $packoffer->$off : '';
                    }
                }
                $sql = $this->getDi()->getDb4()->prepare('SELECT GROUP_CONCAT(r.name SEPARATOR ",") as name, GROUP_CONCAT(orr.regulationid SEPARATOR ",") as regulations FROM offerpack__regulations r inner join offerpack__offerregulations orr ON orr.regulationid = r.id WHERE orr.offerhash LIKE :campaign ');
                $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('campaign' => ($offerhash)), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($array_ret) && !empty($array_ret[0]) && !empty($array_ret[0]['regulations']))
                    $res['regulations'] = $array_ret[0]['regulations'];
                else
                    $res['regulations'] = '';
                $sql = $this->getDi()->getDb4()->prepare("SELECT SUBSTRING_INDEX(GROUP_CONCAT(CASE WHEN currency = 'USD' THEN TRUNCATE(cpa,2) ELSE TRUNCATE(cpaOriginalValue,2) END,'|',insertDate,'|',currency ORDER BY insertDate DESC), ',', 3) as cpahistory,hashmask FROM `offerpack__cpahistory` WHERE  hashmask LIKE :offerhash GROUP BY hashmask ");
                $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('offerhash' => ($offerhash)), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($array_ret) && !empty($array_ret[0]) && !empty($array_ret[0]['cpahistory']))
                    $res['cpahistory'] = $array_ret[0]['cpahistory'];
                else
                    $res['cpahistory'] = '';
                $sql = $this->getDi()->getDb4()->prepare("SELECT SUBSTRING_INDEX(GROUP_CONCAT(os.name,'|',insertDate,'|',statusreason ORDER BY insertDate DESC), ',', 3) as statushistory,hashmask FROM `offerpack__statushistory` o inner join offerpack__status os ON os.id = o.status WHERE hashmask LIKE :offerhash GROUP BY hashmask ");
                $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('offerhash' => ($offerhash)), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($array_ret) && !empty($array_ret[0]) && !empty($array_ret[0]['statushistory']))
                    $res['statushistory'] = $array_ret[0]['statushistory'];
                else
                    $res['statushistory'] = '';


                $sql = $this->getDi()->getDb4()->prepare('SELECT id,SUBSTRING_INDEX(location, "/", -1) as filename,CONCAT("/",REPLACE(location, "/home/whatsapp/public_html/mobisteinreport.com/public/", "")) as link FROM offerpack__offerbannerfiles WHERE offerhash LIKE :offerhash ');
                $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('offerhash' => ($offerhash)), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $bannerres = $prepared->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($bannerres)) {
                    $banner_res = 1;
                }
                $sql = $this->getDi()->getDb4()->prepare('SELECT id,SUBSTRING_INDEX(location, "/", -1) as filename,CONCAT("/",REPLACE(location, "/home/whatsapp/public_html/mobisteinreport.com/public/", "")) as link FROM offerpack__offerscreenshotfiles WHERE offerhash LIKE :offerhash ');
                $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('offerhash' => ($offerhash)), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $screenshotres = $prepared->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($screenshotres)) {
                    $screenshot_res = 1;
                }





                $this->view->setVar('offervar', json_encode($res));
                //$this->view->setVar('clone', ($this->request->get('clone') != null && $this->request->get('clone') == '1' ? 'true' : 'false'));
            }
            //mail('pedrorleonardo@gmail.com', 'yo', 'varr');
            $this->view->setVar('downloadBanners', (isset($banner_res) ? '/offerpack/getzip?offerhash=' . $offerhash . '&type=banner' : ''));
            $this->view->setVar('eachbanner', (isset($banner_res) ? json_encode($bannerres) : '{}'));

            $this->view->setVar('downloadScreenshots', (isset($screenshot_res) ? '/offerpack/getzip?offerhash=' . $offerhash . '&type=screenshot' : ''));
            $this->view->setVar('eachscreenshot', (isset($screenshot_res) ? json_encode($screenshotres) : '{}'));
            //$this->view->setVar('clone', ($this->request->get('clone') != null && $this->request->get('clone') == '1' ? 'true' : 'false'));
        } catch (Exception $ex) {
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function deletescreenshotAction() {
        try {
            $packoffer = Offerpack::findFirst([
                        "hashMask = :hashMask: ",
                        "bind" => [
                            "hashMask" => $this->request->get('offerhash'),
                        ],
            ]);
            if (empty($packoffer) || empty($packoffer->screenshot)) {
                echo '<script>alert("No screenshot to delete");window.close()</script>';
                return;
            }
            $packoffer->screenshot = new \Phalcon\Db\RawValue('default');
            if ($packoffer->save() == false) {
                $messages = $packoffer->getMessages();
                foreach ($messages as $message) {
                    echo $message, "\n";
                }
                return null;
            }
            echo '<script>alert("Screenshots deleted");window.close()</script>';
        } catch (Exception $ex) {
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function getofferinfoAction() {
        try {
            $packoffer = Offerpack::findFirst([
                        "hashMask = :hashMask: ",
                        "bind" => [
                            "hashMask" => $this->request->get('offerhash'),
                        ],
            ]);
            if (empty($packoffer)) {
                echo 'No packoffer selected';
                return;
            }

            $arroffer = array("offername", "carrier", "status", "verticalid", "vertical", "flow", "flowid", "modelid", "model", "dailycap", "accountmanager", "campaignmanager", "area", "description", "exclusive", "ownership", "statusreason", "insertTimestamp");
            foreach ($arroffer as $off) {
                if ($off == 'exclusive') {
                    $res[$off] = (!isset($packoffer->$off) || $packoffer->$off == '0') ? 'Not exclusive' : ($packoffer->$off == '1' ? 'Exclusive' : 'Exclusive google');
                } else
                    $res[$off] = (!isset($packoffer->$off) || $packoffer->$off != "") ? $packoffer->$off : '';
            }
            $mask = Mask::findFirst([
                        "hash = :hashMask: ",
                        "bind" => [
                            "hashMask" => $this->request->get('offerhash'),
                        ],
            ]);
            if (!empty($mask)) {
                $res['clienturl'] = (!isset($mask->rurl) || $mask->rurl != "") ? $mask->rurl : '';
            }

            $sql = $this->getDi()->getDb4()->prepare('SELECT GROUP_CONCAT(r.name SEPARATOR " , ") as name, GROUP_CONCAT(orr.regulationid SEPARATOR ",") as regulations FROM offerpack__regulations r inner join offerpack__offerregulations orr ON orr.regulationid = r.id WHERE orr.offerhash LIKE :campaign ');
            $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('campaign' => ($this->request->get('offerhash'))), array(\Phalcon\Db\Column::TYPE_VARCHAR));
            $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($array_ret) && !empty($array_ret[0]) && !empty($array_ret[0]['regulations']))
                $res['regulations'] = $array_ret[0]['name'];
            else
                $res['regulations'] = '';

            $info = '<span class="spanTooltip">Offername:</span> ' . $res['offername']
                    . '<br><span class="spanTooltip">Carrier:</span> ' . $res['carrier']
                    . '<br><span class="spanTooltip">Ownership:</span> ' . $res['ownership']              //
                    . '<br><span class="spanTooltip">Exclusive:</span> ' . $res['exclusive']                //
                    . '<br><span class="spanTooltip">Description:</span> ' . $res['description']
                    . '<br><span class="spanTooltip">Flow:</span> ' . $res['flow']
                    . (($res['status'] != 2 && $res['status'] != 3 ) ? '' : ($res['status'] == 2 ? '<br><span class="spanTooltip">Paused by Mobipium:</span>' : '<br><span class="spanTooltip">Paused by Client:</span> ' ) . $res['statusreason'] )//
                    . '<br><span class="spanTooltip">Vertical:</span> ' . $res['vertical']                  //
                    . '<br><span class="spanTooltip">Client URL:</span> ' . $res['clienturl']
                    . '<br><span class="spanTooltip">Regulations:</span> ' . $res['regulations'];

            //echo json_encode($res);
            echo $info;
            return;
        } catch (Exception $ex) {
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function deletebannerAction() {
        try {
            $sql = $this->getDi()->getDb4()->prepare('DELETE FROM offerpack__offerbanners WHERE offerhash LIKE :offerhash ');
            $this->getDi()->getDb4()->executePrepared($sql, array('offerhash' => ($this->request->get('offerhash'))), array(\Phalcon\Db\Column::TYPE_VARCHAR));
            echo '<script>alert("Banners deleted");window.close()</script>';
        } catch (Exception $ex) {
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function addOffersExcelAction() {
        try {
            if (isset($_FILES["file"])) {
                if ($_FILES["file"]["error"] > 0) {
                    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
                } else {
                    $tmpName = $_FILES['file']['tmp_name'];
                    $csvAsArray = array_map('str_getcsv', file($tmpName));
                    $array_status = array();
                    $i = 1;
                    foreach (array_slice($csvAsArray, 1) as $key => $line) {
                        try {
                            $row = $this->processline($line);
                            $this->createrowofferpack($row);
                            //$campaignname,$agregatorid,$country,$jumpurl,
                            //$cpa,$currencytype,$carrier,$area,$category,$status,$offername,$carrier,$status,
                            //$vertical,$model,$flow,$dailycap,$accountmanager,$campaignmanager,$area,$description,$exclusive,$ownership
                            //$line[0],
                            //trim(strtolower($line[1])),
                            //trim(strtolower($line[2])),
                            $i++;
                        } catch (Exception $e) {
                            echo "Problem found for line number: $i" . "<br>";
                            echo $e->getMessage() . "<br>";
                        } /* finally {
                          $i++;
                          continue;
                          } */
                    }
                }
            } else {
                echo "No file selected";
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCMAction() {
        try {
            if (!$this->checkRequest($this->request->get('country'))) {
                echo 'missing country';
                return;
            }
            $sql = $this->getDi()->getDb4()->prepare('SELECT id FROM users WHERE CONCAT(",",countries,",") LIKE :country AND userarea NOT IN (6,5,1,4,3) ORDER BY id DESC');
            $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('country' => ('%,' . $this->request->get('country') . ',%')), array(\Phalcon\Db\Column::TYPE_VARCHAR));
            $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
            if (empty($array_ret)) {
                echo '0';
                return;
            }

            echo $array_ret[0]['id'];
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAccountAction() {
        try {
            if (!$this->checkRequest($this->request->get('agg'))) {
                echo 'missing agg';
                return;
            }
            $sql = $this->getDi()->getDb4()->prepare('SELECT id FROM users WHERE CONCAT(",",aggregators,",") LIKE :agg AND id != 25 AND userarea NOT IN (6,5,0,4,3) AND id NOT IN (8,25) ORDER BY id ASC');
            $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('agg' => ('%,' . $this->request->get('agg') . ',%')), array(\Phalcon\Db\Column::TYPE_INTEGER));
            $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);

            $agregatorakaclient = Agregator::findFirst(["id = :id: ", "bind" => [
                            "id" => $this->request->get('agg'),
            ]]);
            $connector = !empty($agregatorakaclient->custom_url) ? $agregatorakaclient->custom_url : '';
            $param = $agregatorakaclient->trackingParam;
            $param2 = (!empty($agregatorakaclient) && !empty($agregatorakaclient->sinfo) ? '<p class="infot">PUB ID PARAMETER // ' . $agregatorakaclient->sinfo . '</p>' : '');


            echo json_encode(array('info' => (!empty($connector) ? '<p class="infot">CustomURL: ' . $connector . '</p>' : '') . '<p class="infot">PARAMETER // ' . $param . '</p>' . (!empty($param2) ? $param2 : ''), 'account' => empty($array_ret) ? "25" : $array_ret[0]['id'])
            );
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDimsAction() {
        try {
            $dims = new PackOffersDims();
            $cache = $this->di->get("viewCache");
            $auth = $this->session->get('auth');
            if ($auth['userarea'] == 0) {// cm
                $cmdefault = $auth['id'];
                $amdefault = '';
            } else if ($auth['userarea'] == 1) { //sales
                $amdefault = $auth['id'];
            } else {
                $cmdefault = '';
                $amdefault = '';
            }
//            $carriersres = $cache->get('carriersofferpack');
//            $carriersres =null;
//            if(!isset($carriersres)){
//                $carriers = $dims->getcarriervalues();
//                if(!empty($carriers)){
//                    $carriersres = ($carriers);
//                    $cache->save('carriersofferpack', $carriersres);
//                }
//            }
            //$curtype = array('USD','EUR','GBP','MXN','BRL');
            $curtyperes = $cache->get('currencyofferpack');
            //$curtyperes =null;
            if (!isset($curtyperes)) {
                $curtype = $dims->getcurrenciesvalues();
                if (!empty($curtype)) {
                    $curtyperes = ($curtype);
                    $cache->save('currencyofferpack', $curtyperes);
                }
            }

            $ownershipres = $cache->get('ownershipofferpack');
            //$curtyperes =null;
            if (!isset($ownershipres)) {
                $ownership = $dims->getownershipvalues();
                if (!empty($ownership)) {
                    $ownershipres = ($ownership);
                    $cache->save('ownershipofferpack', $ownershipres);
                }
            }

            $cmsres = $cache->get('cmsofferpack');
            $cmsres = null;
            if (!isset($cmsres)) {
                $cms = $dims->getcmsvalues();
                if (!empty($cms)) {
                    $cmsres = ($cms);
                    $cache->save('cmsofferpack', $cmsres);
                }
            }
            $accountsres = $cache->get('accountsofferpack');
            $accountsres = null;
            if (!isset($accountsres)) {
                $accounts = $dims->getaccountvalues();
                if (!empty($accounts)) {
                    $accountsres = ($accounts);
                    $cache->save('accountsofferpack', $accountsres);
                }
            }

            $flowres = $cache->get('flowofferpack');
            //$flowres = null;
            if (!isset($flowres)) {
                $flow = $dims->getflowvalues();
                if (!empty($flow)) {
                    $flowres = ($flow);
                    $cache->save('flowofferpack', $flowres);
                }
            }

            $regsres = $cache->get('regsofferpack');
            //$regsres = null;
            if (!isset($regsres)) {
                $regs = $dims->getregulationvalues();
                if (!empty($regs)) {
                    $regsres = ($regs);
                    $cache->save('regsofferpack', $regsres);
                }
            }

            $countryres = $cache->get('countryofferpack');
            //$countryres = null;
            if (!isset($countryres)) {
                $country = $dims->getcountriesvalues();
                if (!empty($country)) {
                    $countryres = ($country);
                    $cache->save('countryofferpack', $countryres);
                }
            }

            $modelres = $cache->get('modelofferpack');
            //$modelres = null;
            if (!isset($modelres)) {
                $model = $dims->getmodelvalues();
                if (!empty($model)) {
                    $modelres = ($model);
                    $cache->save('modelofferpack', $modelres);
                }
            }

            $virtualres = $cache->get('virtualofferpack');
            //$virtualres = null;
            if (!isset($virtualres)) {
                $virtual = $dims->getverticalvalues();
                if (!empty($virtual)) {
                    $virtualres = ($virtual);
                    $cache->save('virtualofferpack', $virtualres);
                }
            }

            $aggsres = $cache->get('agregatorsofferpack');
            $aggsres = null;
            if (!isset($aggsres)) {
                $aggs = $dims->getaggsvalues($this->session->get('auth'));
                if (!empty($aggs)) {
                    $aggsres = ($aggs);
                    $cache->save('agregatorsofferpack', $aggsres);
                }
            }

            $statusres = $cache->get('statusofferpack');
            //$statusres = null;
            if (!isset($statusres)) {
                $status = $dims->getstatusvalues();
                if (!empty($status)) {
                    $statusres = ($status);
                    $cache->save('statusofferpack', $statusres);
                }
            }


            $exclusive = array(array('id' => 0, 'name' => 'Not Exclusive'), array('id' => 1, 'name' => 'Exclusive'), array('id' => 2, 'name' => 'Exclusive Google'));



            echo json_encode(array('flow' => $flowres,
                'model' => $modelres,
                'vertical' => $virtualres,
                'status' => $statusres, 'curtype' => $curtyperes,
                'countries' => $countryres,
                'area' => $this->areares, 'aggs' => $aggsres, 'cms' => $cmsres,
                'accounts' => $accountsres, 'ownership' => $ownershipres,
                'exclusive' => $exclusive, 'regulations' => $regsres), JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getCarriersAction() {
        try {
            //$carriersres = $cache->get('carriersofferpack');
            //$carriersres =null;
            $dims = new PackOffersDims();
            $carriers = $dims->getcarriervalues($this->request->get('country'));
            $sql = $this->getDi()->getDb4()->prepare('SELECT id FROM users WHERE CONCAT(",",countries,",") LIKE :country AND userarea NOT IN (6,5,1,4,3) AND id NOT IN (3,4) ORDER BY id DESC');
            $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('country' => ('%,' . $this->request->get('country') . ',%')), array(\Phalcon\Db\Column::TYPE_VARCHAR));
            $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array('carriers' => $carriers, 'cms' => (isset($array_ret[0]) && isset($array_ret[0]['id'])) ? $array_ret[0]['id'] : '2'));
        } catch (Exception $ex) {
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function getCarriersMultipleAction() {
        try {

            $dims = new PackOffersDims();
            $carriers = $dims->getcarriervaluesMultiple($this->request->get('country'));
            echo json_encode(array('carriers' => $carriers));
        } catch (Exception $ex) {
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function newOfferpackAction() {
        try {
            $auth = $this->session->get('auth');
            if(!isset($auth['id'])){
                echo 'PLEASE RELOGIN';
                return;
            }
            if ($this->request->get('offerhash') != null && $this->request->get('offerhash') != 'undefined' && $this->request->get('offerhash') != '') {
                $this->editOffer();
            } else {
                $this->createnewoffer();
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function newOfferpack2Action() {
        try {
            $auth = $this->session->get('auth');
            if(!isset($auth['id'])){
                echo 'PLEASE RELOGIN';
                return;
            }
            if ($this->request->get('offerhash') != null && $this->request->get('offerhash') != 'undefined' && $this->request->get('offerhash') != '') {
                $this->editOffer(2);
            } else {
                $this->createnewoffer(2);
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function getScreenshotAction() {
        try {
            if (!$this->checkRequest($this->request->get('offerhash'))) {
                echo 'Choose the correct offerpack';
                return;
            }
            $of = Offerpack::findFirst([
                        "hashMask = :hash: ",
                        "bind" => [
                            "hash" => $this->request->get('offerhash'),
                        ],
            ]);
            if (empty($of) || empty($of->screenshot)) {
                echo '';
                return;
            }

            if (strpos($of->screenshot, '/home/whatsapp/public_html/mobisteinreport.com') > -1)
                $fileToCp = $of->screenshot;
            else
                $fileToCp = getcwd() . '/' . $of->screenshot;
            //$fileToCp = getcwd() . '/' . $of->screenshot;
            $ext = pathinfo($fileToCp, PATHINFO_EXTENSION);
            $tempFile = './files/Screenshot' . uniqid() . '.' . $ext;
            //echo $fileToCp;
            copy($fileToCp, $tempFile);
            switch ($ext) {
                case "gif": $ctype = "image/gif";
                    break;
                case "PNG":
                case "png" : $ctype = "image/png";
                    break;
                case "jpeg":
                case "jpg": $ctype = "image/jpeg";
                    break;
                default:
                    $ctype = "file";
            }
            //header('Content-type: ' . $ctype);
            //header('Content-Type: image/gif');
            $image = file_get_contents($tempFile);
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header('Content-Disposition: attachment; filename="' . basename($fileToCp) . '.' . $ext . '"');
            if ($ext != 'zip') {
                echo $image;
            } else {
//            header('Content-type:  application/pdf');
//            header('Content-Length: ' . filesize($tempFile));
//            header('Content-Disposition: attachment; filename="' . basename($fileToCp) . '"');
                while (ob_get_level()) {
                    ob_end_clean();
                }
                readfile($tempFile);
            }
            ignore_user_abort(true);
            if (connection_aborted()) {
                unlink($tempFile);
            }
            unlink($tempFile);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
        }
    }

    public function getBannersAction() {
        try {

            if (!$this->checkRequest($this->request->get('offerhash'))) {
                echo 'Choose the correct offerpack';
                return;
            }
            $sql = $this->getDi()->getDb4()->prepare('SELECT banner FROM offerpack__offerbanners WHERE offerhash = :offerhash ');
            $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('offerhash' => $this->request->get('offerhash')), array(\Phalcon\Db\Column::TYPE_VARCHAR));
            $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);

            if (empty($array_ret)) {
                echo '<script>alert("No banners for this packoffer");window.close()</script>';
                return;
            }

            if (substr($array_ret[0]['banner'], 0, 1) == '/')
                $fileToCp = $array_ret[0]['banner'];
            else
                $fileToCp = getcwd() . '/' . $array_ret[0]['banner'];
            $ext = pathinfo($fileToCp, PATHINFO_EXTENSION);
            header('Content-type:  application/' . $ext);
            header('Content-Length: ' . filesize($fileToCp));
            header('Content-Disposition: attachment; filename="' . basename($fileToCp) . '"');
            while (ob_get_level()) {
                ob_end_clean();
            }
            readfile($fileToCp);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
        }
    }

    private function editOffer($type2 = null) {
        try {
            if ($this->checkRequest($this->request->getPost('jumpurl'))) {
                $i = preg_match('(https:\/\/|http:\/\/)', $this->request->getPost('jumpurl'), $array);
                if ($i !== 1) {
                    echo '999';
                    return;
                }
            }
            $offerhash = $this->request->get('offerhash');
            $mask = Mask::findFirst([
                        "hash = :hash: ",
                        "bind" => [
                            "hash" => $this->request->get('offerhash'),
                        ],
            ]);
            if (empty($mask))
                return null;
            $manager = new \Phalcon\Mvc\Model\Transaction\Manager();
            $manager->setDbService("db4");
            $transaction = $manager->get();

            $connection = $this->getDi()->getDb4();
            $connection->begin();

            $jumpurl = $this->request->getPost('jumpurl') != null ? rtrim(trim($this->request->getPost('jumpurl')), '?') : $this->request->getPost('jumpurl');
            $cpa = $this->request->getPost('cpa');
            $currencytype = $this->request->getPost('currencytype');
            //$carrier = $this->request->getPost('carrier');
            $status = $this->request->getPost('status');
            $category = $this->request->getPost('category');
            $area = $this->request->getPost('area');

            $mask = $this->editMask($transaction, $mask, $jumpurl, $cpa, $currencytype, $status, $category, $area);

            if (!isset($mask)) {
                throw new Exception('Could not edit jump');
            }

            $packoffer = Offerpack::findFirst([
                        "hashMask = :hashMask: ",
                        "bind" => [
                            "hashMask" => $offerhash,
                        ],
            ]);
            if (empty($packoffer)) {
                throw new Exception('Offer not found');
            }

            $description = $this->request->getPost('description');
            $ownership = $this->request->getPost('ownership');
            $exclusive = $this->request->getPost('exclusive') != null ? $this->request->getPost('exclusive') : 0;
            $flow = $this->request->getPost('flow');
            $model = $this->request->getPost('model');
            $dailycap = $this->request->getPost('dailycap') != null ? $this->request->getPost('dailycap') : 0;
            $vertical = $this->request->getPost('vertical');
            $offername = $this->request->getPost('offername');
            $accountmanager = $this->request->getPost('accountmanager');
            $campaignmanager = $this->request->getPost('campaignmanager');

            $offer = $this->editOfferPack($transaction, $connection, $packoffer, $offername, $status, $vertical, $model, $flow, $dailycap, $accountmanager, $campaignmanager, $area, $description, $exclusive, $ownership, $type2);
            if (!isset($offer)) {
                throw new Exception('Could not edit offerpack');
            }
            //delete current regulations
            $sql2 = 'DELETE FROM offerpack__offerregulations WHERE offerhash = :offerhash ;';
            $statement2 = $connection->prepare($sql2);
            $connection->executePrepared($statement2, array(':offerhash' => $offerhash), array(\Phalcon\Db\Column::TYPE_VARCHAR));
            //set new ones
            if ($this->checkRequest($this->request->getPost('regulationsArr'))) {
                $regulationsarr = explode(',', $this->request->getPost('regulationsArr'));
                $sql = 'INSERT INTO offerpack__offerregulations (offerhash,regulationid) VALUES ';
                $i = 0;
                $arrval = array();
                $arrtypecolumns = array();
                foreach ($regulationsarr as $regid) {
                    $sql .= '("' . $offerhash . '",:regid' . $i . '),';
                    $arrval[':regid' . $i] = $regid;
                    $arrtypecolumns[] = \Phalcon\Db\Column::TYPE_INTEGER;
                    $i++;
                }
                $sql = rtrim($sql, ',') . ';';
                $statement = $connection->prepare($sql);
                $connection->executePrepared($statement, $arrval, $arrtypecolumns);
            }
            echo '0:' . $mask->campaign;
            $connection->commit();
            $transaction->commit();
        } catch (Exception $ex) {
            if (isset($connection))
                $connection->rollback();
            if (isset($transaction))
                $transaction->rollback();
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
        }
    }

    private function createnewoffer($type2 = Null) {
        try {
            if (!$this->checkRequest($this->request->getPost('offername'))) {
                echo 'Missing offername';
                return;
            }
            if (!$this->checkRequest($this->request->getPost('country'))) {
                echo 'Missing country';
                return;
            }
            if (!$this->checkRequest($this->request->getPost('agregatorid'))) {
                echo 'Missing client';
                return;
            }
            if (!$this->checkRequest($this->request->getPost('jumpurl'))) {
                echo 'Missing url';
                return;
            }
            if (!$this->checkRequest($this->request->getPost('currencytype'))) {
                echo 'Missing currency';
                return;
            }
            if (!$this->checkRequest($this->request->getPost('cpa'))) {
                echo 'Missing cpa';
                return;
            }
            if (!$this->checkRequest($this->request->getPost('area'))) {
                echo 'Missing area';
                return;
            }
            if ($this->checkRequest($this->request->getPost('jumpurl'))) {
                $i = preg_match('(https:\/\/|http:\/\/)', $this->request->getPost('jumpurl'), $array);
                if ($i !== 1) {
                    echo '999';
                    return;
                }
            }

            $findagg = "id = " . $this->request->getPost('agregatorid');
            $agg = Agregator::findFirst($findagg);
            if (empty($agg))
                return 'Some problem with agregator info';
            $generatedname = strtolower($this->request->getPost('country')
                    . substr(preg_replace('/[^\da-zA-Z0-9_]/i', '', $agg->agregator), 0, 10) . substr(preg_replace('/[^\da-zA-Z0-9_]/i', '', $this->request->getPost('carrier')), 0, 4));
            $connection = $this->getDi()->getDb4();
            $connection->begin();
            $generatedname = str_replace(' ', '', $generatedname);
            $campaignname = $this->findcampaignName($generatedname);
            $campaignname = preg_replace('/[^\da-zA-Z0-9_]/i', '', $campaignname);
            $hash = uniqid();
            $manager = new \Phalcon\Mvc\Model\Transaction\Manager();
            $manager->setDbService("db4");
            $transaction = $manager->get();

            //only Mask
            $country = $this->request->getPost('country');
            $jumpurl = $this->request->getPost('jumpurl') != null ? rtrim(trim($this->request->getPost('jumpurl')), '?') : null;
            $cpa = $this->request->getPost('cpa');
            $currencytype = $this->request->getPost('currencytype');
            $agregatorid = $this->request->getPost('agregatorid');

            //shared
            $carrier = $this->request->getPost('carrier');
            $status = $this->request->getPost('status');
            $category = $this->request->getPost('category');


            //$mainstream = $this->request->getPost('mainstream');
            //area = 0 adult, 1 afiliate, 2 mainstream, 3 oldaffiliates, 4 adult+ms
            //define tambem as permissoes
            $area = $this->request->getPost('area');

            //$uniqid,$campaignname,$agregatorid,$country,$rurl,$cpa,$originalcpa,$curtype,$afcarrier,$area,$status
            $mask = $this->createMask($transaction, $hash, $campaignname, $agregatorid, $country, $jumpurl, $cpa, $currencytype, $carrier, $area, $category, $status);
            if (!isset($mask))
                throw new Exception('could not create jump');

            //only offerpack
            $description = $this->request->getPost('description');
            $ownership = $this->request->getPost('ownership');
            $exclusive = $this->request->getPost('exclusive') != null ? $this->request->getPost('exclusive') : 0;
            $flow = $this->request->getPost('flow');
            $model = $this->request->getPost('model');
            $dailycap = $this->request->getPost('dailycap') != null ? $this->request->getPost('dailycap') : 0;
            $vertical = $this->request->getPost('vertical');
            $offername = $this->request->getPost('offername');
            $accountmanager = $this->request->getPost('accountmanager');
            $campaignmanager = $this->request->getPost('campaignmanager');


            $offerpack = $this->createOfferPack($connection, $transaction, $hash, $offername, $carrier, $status, $vertical, $model, $flow, $dailycap, $accountmanager, $campaignmanager, $area, $description, $exclusive, $ownership, $type2);
            if (!isset($offerpack))
                throw new Exception('Offerpack was not created');
            //regulationarr
            if ($this->checkRequest($this->request->getPost('regulationsArr'))) {

                $regulationsarr = explode(',', $this->request->getPost('regulationsArr'));
                $sql = 'INSERT INTO offerpack__offerregulations (offerhash,regulationid) VALUES ';
                $i = 0;
                $arrval = array();
                $arrtypecolumns = array();
                foreach ($regulationsarr as $regid) {
                    $sql .= '("' . $hash . '",:regid' . $i . '),';
                    $arrval[':regid' . $i] = $regid;
                    $arrtypecolumns[] = \Phalcon\Db\Column::TYPE_INTEGER;
                    $i++;
                }
                $sql = rtrim($sql, ',') . ';';
                $statement = $connection->prepare($sql);
                $connection->executePrepared($statement, $arrval, $arrtypecolumns);
            }
            echo '0:' . $campaignname;
            $connection->commit();
            $transaction->commit();
            if ($agregatorid == 76) {//vuclips client, check its reports
                $newvuclip = new VuclipsCampaignNames();
                $newvuclip->hashMask = $hash;
                $newvuclip->vuclipsoffer = $offername;
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
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage();
            if (isset($transaction))
                $transaction->rollback();
            if (isset($connection))
                $connection->rollback();
        }
    }

    private function findcampaignName($generatedname) {
        try {
            $campaignname = '';
            //$sql = 'SELECT id, campaign FROM Mask WHERE campaign RLIKE :campaign ORDER BY id LIMIT 1';
            $sql = 'SELECT id, CAST( REPLACE(lower(campaign), :campaignname, "") AS UNSIGNED) as campaignnewname FROM Mask WHERE campaign RLIKE :campaign ORDER by campaignnewname DESC LIMIT 1';
            //mail('pedrorleonardo@gmail.com','test',$sql);
            //exit();
            $sql = $this->getDi()->getDb4()->prepare($sql);
            $prepared = $this->getDi()->getDb4()->executePrepared($sql, array('campaignname' => $generatedname, 'campaign' => ('^' . $generatedname . '[0-9]*$' )), array(\Phalcon\Db\Column::TYPE_VARCHAR));
            $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
            if (empty($array_ret)) {
                $campaignname = $generatedname . '1';
                //mail('pedrorleonardo@gmail.com', 'entrei', 'fuck' . serialize($array_ret));
            } else {
                //mail('pedrorleonardo@gmail.com', 'entrei2', 'fuck2' . serialize($array_ret));
                $newcampaignid = ( (!empty($array_ret[0]) && !empty($array_ret[0]['campaignnewname']) ) ? ($array_ret[0]['campaignnewname'] + 1) : '1' );
                $campaignname = $generatedname . ($newcampaignid);
            }
            return $campaignname;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
        }
    }

    private function createOfferPack($connection, $transaction, $uniqid, $offername, $carrier, $status, $vertical, $model, $flow, $dailycap, $accountmanager, $campaignmanager, $area, $description, $exclusive, $ownership, $type2) {
        try {
            $offerpack = new Offerpack();
            $offerpack->setTransaction($transaction);
            $offerpack->hashMask = $uniqid;
            $offerpack->offername = $offername;
            $offerpack->carrier = $carrier;
            $offerpack->status = $this->checkRequest($status) ? $status : new \Phalcon\Db\RawValue('default');
            $offerpack->verticalid = $this->checkRequest($vertical) ? $vertical : new \Phalcon\Db\RawValue('default');
            $offerpack->vertical = ($this->checkRequest($vertical) && isset($this->verticalnames[$vertical])) ? $this->verticalnames[$vertical] : new \Phalcon\Db\RawValue('default');
            $offerpack->flow = ($this->checkRequest($flow) && isset($this->flownames[$flow])) ? $this->flownames[$flow] : new \Phalcon\Db\RawValue('default');
            $offerpack->flowid = $this->checkRequest($flow) ? $flow : new \Phalcon\Db\RawValue('default');
            $offerpack->modelid = $this->checkRequest($model) ? $model : new \Phalcon\Db\RawValue('default');
            $offerpack->model = ($this->checkRequest($model) && isset($this->modelnames[$model])) ? $this->modelnames[$model] : new \Phalcon\Db\RawValue('default');
            $offerpack->dailycap = $dailycap;
            $offerpack->accountmanager = $this->checkRequest($accountmanager) ? $accountmanager : new \Phalcon\Db\RawValue('default');
            $offerpack->campaignmanager = $this->checkRequest($campaignmanager) ? $campaignmanager : new \Phalcon\Db\RawValue('default');
            $offerpack->area = $area;
            $offerpack->description = $description;
            $offerpack->exclusive = $exclusive ? $exclusive : new \Phalcon\Db\RawValue('default');
            $offerpack->ownershipid = $this->checkRequest($ownership) ? $ownership : new \Phalcon\Db\RawValue('default');
            $offerpack->ownership = ($this->checkRequest($ownership) && isset($this->ownership[$ownership])) ? $this->ownership[$ownership] : $this->ownership[1];
            $offerpack->screenshot = new \Phalcon\Db\RawValue('default');
            $offerpack->insertTimestamp = date('Y-m-d H:i:s');
            $auth = $this->session->get('auth');
            $offerpack->editedby = $auth['id'];
            $offerpack->editedTimestamp = date('Y-m-d H:i:s');



            if ($offerpack->save() == false) {
                $messages = $offerpack->getMessages();
                foreach ($messages as $message) {
                    HelpingFunctions::writetolog('E' . __CLASS__ . __LINE__ , $message);
                    echo $message, "\n";
                }
                return null;
            }
            if (isset($type2))
                $this->addNewOrEditOffer($offerpack->hashMask, $connection);
            else {
                $this->setNewBanners($offerpack->hashMask, $connection);
                $this->setNewScreenshot($offerpack->hashMask, $offerpack);
            }
            return $offerpack;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
            return null;
        }
    }

    //uniqid,campaignname,agregatorid,country,rurl,cpa,originalcpa,curtype,afcarrier,aff_flag,status
    private function createMask($transaction, $uniqid, $campaignname, $agregatorid, $country, $rurl, $cpa, $curtype, $afcarrier, $area, $category, $status) {
        try {
            $mask = new Mask();
            $originalcpa = $cpa;
            if ($curtype != 'USD') {
                $cpa = $this->convertToUSD($curtype, $cpa);
            }
            $mask->setTransaction($transaction);
            $mask->hash = $uniqid;
            $mask->campaign = $campaignname;
            $mask->agregator = $agregatorid;
            $mask->country = strtolower($country);
            $mask->rurl = $rurl;
            $mask->cpa = $cpa;
            $mask->cpaOriginalValue = $originalcpa;
            $mask->curtype = $curtype;
            $mask->afcarrier = $afcarrier;
            $mask->aff_flag = $area; //$aff_flag
            $mask->category = $category;
            $mask->status = $status;
            $mask->source = new \Phalcon\Db\RawValue('default');
            $mask->format = new \Phalcon\Db\RawValue('default');
            $mask->client = 1;
            $mask->adnumber = new \Phalcon\Db\RawValue('default');
            $mask->insertTimestamp = date('Y-m-d H:i:s');
            $mask->duplicatedconvs = 0;
            $auth = $this->session->get('auth');
            $mask->createdby = $auth['id'];
            $mask->editedby = $auth['id'];
            $mask->editedTimestamp = date('Y-m-d H:i:s');

            if ($mask->save() == false) {
                $messages = $mask->getMessages();
                foreach ($messages as $message) {
                    echo $message, "\n";
                    HelpingFunctions::writetolog('E' . __CLASS__ . __LINE__ , $message);
                }
                return null;
            }
            return $mask;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            return $ex;
        }
    }

    private function editMask($transaction, $mask, $jumpurl, $cpa, $currencytype, $status, $category, $area) {
        try {
            $mask->setTransaction($transaction);
            $originalcpa = $cpa;
            if ($currencytype != 'USD') {
                $cpa = $this->convertToUSD($currencytype, $cpa);
            }
            $mask->rurl = $jumpurl;
            $mask->cpa = $cpa;
            $mask->cpaOriginalValue = $originalcpa;
            $mask->curtype = $currencytype;
            $mask->aff_flag = $area; //$aff_flag
            $mask->category = $category;
            $mask->status = $status;
            $auth = $this->session->get('auth');
            $mask->editedby = $auth['id'];
            $mask->editedTimestamp = date('Y-m-d H:i:s');
            $mask->source = $mask->source ? $mask->source : new \Phalcon\Db\RawValue('default');
            $mask->adnumber = $mask->adnumber ? $mask->adnumber : new \Phalcon\Db\RawValue('default');
            $mask->afcarrier = $mask->afcarrier ? $mask->afcarrier : new \Phalcon\Db\RawValue('default');
            if ($mask->save() == false) {
                $messages = $mask->getMessages();
                foreach ($messages as $message) {
                    echo $message, "\n";
                    HelpingFunctions::writetolog('E' . __CLASS__ . __LINE__ , $message);
                }
                return null;
            }

            return $mask;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage();
            return null;
        }
    }

    private function editOfferPack($transaction, $connection, $offerpack, $offername, $status, $vertical, $model, $flow, $dailycap, $accountmanager, $campaignmanager, $area, $description, $exclusive, $ownership, $type2) {
        try {
            $offerpack->setTransaction($transaction);
            $offerpack->offername = $offername;
            $offerpack->status = $this->checkRequest($status) ? $status : new \Phalcon\Db\RawValue('default');
            $offerpack->verticalid = $this->checkRequest($vertical) ? $vertical : new \Phalcon\Db\RawValue('default');
            $offerpack->vertical = ($this->checkRequest($vertical) && isset($this->verticalnames[$vertical])) ? $this->verticalnames[$vertical] : new \Phalcon\Db\RawValue('default');
            $offerpack->flow = ($this->checkRequest($flow) && isset($this->flownames[$flow])) ? $this->flownames[$flow] : new \Phalcon\Db\RawValue('default');
            $offerpack->flowid = $this->checkRequest($flow) ? $flow : new \Phalcon\Db\RawValue('default');
            $offerpack->modelid = $this->checkRequest($model) ? $model : new \Phalcon\Db\RawValue('default');
            $offerpack->model = ($this->checkRequest($model) && isset($this->modelnames[$model])) ? $this->modelnames[$model] : new \Phalcon\Db\RawValue('default');
            $offerpack->dailycap = $dailycap;
            $offerpack->accountmanager = $this->checkRequest($accountmanager) ? $accountmanager : new \Phalcon\Db\RawValue('default');
            $offerpack->campaignmanager = $this->checkRequest($campaignmanager) ? $campaignmanager : new \Phalcon\Db\RawValue('default');
            $offerpack->area = $area;
            $offerpack->description = $description;
            $offerpack->exclusive = $exclusive;
            $offerpack->ownershipid = $this->checkRequest($ownership) ? $ownership : new \Phalcon\Db\RawValue('default');
            $offerpack->ownership = ($this->checkRequest($ownership) && isset($this->ownership[$ownership])) ? $this->ownership[$ownership] : $this->ownership[1];
            $auth = $this->session->get('auth');
            $offerpack->editedby = $auth['id'];
            $offerpack->editedTimestamp = date('Y-m-d H:i:s');

            if ($offerpack->save() == false) {
                $messages = $offerpack->getMessages();
                foreach ($messages as $message) {
                    echo $message, "\n";
                    HelpingFunctions::writetolog('E' . __CLASS__ . __LINE__ , $message);
                }
                return null;
            }
            if (isset($type2))
                $this->addNewOrEditOffer($offerpack->hashMask, $connection);
            else {
                $this->setNewBanners($offerpack->hashMask, $connection);
                $this->setNewScreenshot($offerpack->hashMask, $offerpack);
            }
            return $offerpack;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
            return null;
        }
    }

    private function setNewBanners($offerhash, $connection) {
        try {
            $bannersexists = null;
            $alreadyrun = false;
            $baseLocation = $this->getBaseLocation($offerhash);
            $baseLocation = $baseLocation . $offerhash;
            if ($this->request->hasFiles() == true) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    if (strpos($file->getKey(), 'banners') > -1) {
                        HelpingFunctions::writetolog("I\t", 'THERE ARE BANNERS FOR THIS OFFER: ' . $offerhash);
                        $bannersexists = true;
                    }
                }
                if (isset($bannersexists)) {
                    //find how many folder are inside files
                    if (!is_dir($baseLocation . '/banners')) {
                        HelpingFunctions::writetolog("I\t", 'creating dirs', null, null, __METHOD__, __CLASS__);
                        mkdir($baseLocation . '/banners', 0777, true);
                    }
                    $zip = new ZipArchive;
                    $zipname = $baseLocation . '/banners/banners.zip';
                    $zip->open($zipname, ZipArchive::CREATE);
                    foreach ($this->request->getUploadedFiles() as $file) {
                        if (strpos($file->getKey(), 'banners') > -1) {
                            HelpingFunctions::writetolog("I\t", 'zipiing file: ' . $file->getTempName() . ' - name: ' . $file->getName() . ' KEY : ' . $file->getKey());
                            $zip->addFile($file->getTempName(), $file->getName());
                            $added = true;
                        }
                    }
                    if (isset($added)) {
                        $zip->close();
                        $sql = 'INSERT INTO offerpack__offerbanners (offerhash,banner) VALUES ';
                        $dir = $baseLocation . '/banners/banners.zip';
                        $sql .= '(:offerhash,:banner)';
                        $statement = $connection->prepare($sql);
                        //delete other banners (only from db)
                        if (!$alreadyrun) {
                            $deletesql = 'DELETE FROM offerpack__offerbanners WHERE offerhash = :offerhash ;';
                            $statementdelete = $connection->prepare($deletesql);
                            $connection->executePrepared($statementdelete, array(':offerhash' => $offerhash), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                            $alreadyrun = true;
                        }
                        $connection->executePrepared($statement, array(':offerhash' => $offerhash, ':banner' => $dir), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_VARCHAR));
                        return;
                    }
                    //nothing added
                    else {
                        unlink($zipname);
                        rmdir($baseLocation . '/banners/');
                        return;
                    }
                }
            }

            if ($this->request->getPost('clonehash') != null && $this->request->getPost('clonehash') != 'undefined' && $this->request->getPost('clonehash') != '' && !isset($bannersexists) && $this->request->getPost('removebanner') != 1) {
                $sql = $this->getDi()->getDb4()->prepare('SELECT banner FROM offerpack__offerbanners WHERE offerhash = :clonehash ORDER BY id DESC LIMIT 1');
                $exe = $this->getDi()->getDb4()->executePrepared($sql, array(':clonehash' => $this->request->getPost('clonehash')), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $v = $exe->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($v) && !empty($v[0]) && !empty($v[0]['banner'])) {
                    if (!is_dir($baseLocation . '/banners')) {
                        HelpingFunctions::writetolog("I\t", 'creating dir: ' . $baseLocation . '/banners', null, null, __METHOD__, __CLASS__);
                        mkdir($baseLocation . '/banners', 0777, true);
                    }
                    $newfile = $baseLocation . '/banners/' . basename($v[0]['banner']);
                    HelpingFunctions::writetolog("I\t", $v[0]['banner'] . ' copying to $newfile' . $newfile);
                    if (!copy($v[0]['banner'], $newfile)) {
                        throw new Exception('COULD NOT COPY FILE');
                    }
                    $sql = 'INSERT INTO offerpack__offerbanners (offerhash,banner) VALUES (:newhash, :newbanner) ';
                    $statement = $connection->prepare($sql);
                    $connection->executePrepared($statement, array(':newhash' => $offerhash, ':newbanner' => $newfile), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_VARCHAR));
                }
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
            return null;
        }
    }

    private function getBaseLocation($offerhash) {
        try {
            $path = $this->getLastZipPath($offerhash);
            if (isset($path)) {
                if (!is_dir($path)) {
                    HelpingFunctions::writetolog("I\t", 'creating dir from getlastzippath:' . $path, null, null, __METHOD__, __CLASS__);
                    mkdir($path, 0777, true);
                }
                return $path;
            }
            $folderroot = getcwd() . '/files/';
            $currentfolderroot = getcwd() . '/files/';
            $fi = new FilesystemIterator($folderroot, FilesystemIterator::SKIP_DOTS);
            //if more than 100 folder, create a new one, and this shall be the new baselocation for the banners
            if (iterator_count($fi) > 50) {
                //create new folder
                $i = 0;
                $newfolderroot = $folderroot . 'offers';
                $currentfolderroot = $newfolderroot . $i . '/';
                if (!is_dir($currentfolderroot)) {
                    HelpingFunctions::writetolog("I\t", 'creating dir:' . $currentfolderroot, null, null, __METHOD__, __CLASS__);
                    mkdir($currentfolderroot, 0777, true);
                }
                $fi = new FilesystemIterator($currentfolderroot, FilesystemIterator::SKIP_DOTS);
                while (iterator_count($fi) > 50) {
                    $i++;
                    $currentfolderroot = $newfolderroot . $i . '/';
                    if (!is_dir($currentfolderroot)) {
                        HelpingFunctions::writetolog("I\t", 'creating dir:' . $currentfolderroot . $i, null, null, __METHOD__, __CLASS__);
                        mkdir($currentfolderroot, 0777, true);
                    }
                    $fi = new FilesystemIterator($currentfolderroot, FilesystemIterator::SKIP_DOTS);
                }
            }
            return $currentfolderroot;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
            return null;
        }
    }

    private function getLastZipPath($offerhash) {
        try {
            $sql = $this->getDi()->getDb4()->prepare('SELECT banner FROM offerpack__offerbanners where offerhash = :offerhash ORDER BY ID DESC LIMIT 1');
            $exe = $this->getDi()->getDb4()->executePrepared($sql, array('offerhash' => $offerhash), array(\Phalcon\Db\Column::TYPE_VARCHAR));
            $v = $exe->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($v) && !empty($v[0]) && !empty($v[0]['banner'])) {
                // /home/whatsapp/public_html/mobisteinreport.com/public/files/offers0/
                HelpingFunctions::writetolog("I\t", 'THIS IS THE DIR:' . $v[0]['banner']);
                $offerpath = substr($v[0]['banner'], 0, strpos($v[0]['banner'], $offerhash . '/'));
                return $offerpath;
            } else {
                $sql = $this->getDi()->getDb4()->prepare('SELECT screenshot FROM offerpack__offerpack where hashMask = :offerhash ORDER BY ID DESC LIMIT 1');
                $exe = $this->getDi()->getDb4()->executePrepared($sql, array('offerhash' => $offerhash), array(\Phalcon\Db\Column::TYPE_VARCHAR));
                $v = $exe->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($v) && !empty($v[0]) && !empty($v[0]['screenshot'])) {
                    HelpingFunctions::writetolog("I\t", 'THIS IS THE DIR:' . $v[0]['screenshot']);
                    // /home/whatsapp/public_html/mobisteinreport.com/public/files/offers0/
                    $offerpath = substr($v[0]['screenshot'], 0, strpos($v[0]['screenshot'], $offerhash . '/'));
                    return $offerpath;
                }
                return null;
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
            return null;
        }
    }

    private function setNewScreenshot($offerhash, $offerobject) {
        try {
            $screenshotexists = null;
            $alreadyrun = false;
            $baseLocation = $this->getBaseLocation($offerhash);
            $baseLocation = $baseLocation . $offerhash;

            if ($this->request->hasFiles() == true) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    if (strpos($file->getKey(), 'screenshot') > -1) {
                        $screenshotexists = true;
                    }
                }
                if (isset($screenshotexists)) {
                    //find how many folder are inside files
                    if (!is_dir($baseLocation . '/screenshots')) {
                        HelpingFunctions::writetolog("I\t", 'creating dirs', null, null, __METHOD__, __CLASS__);
                        mkdir($baseLocation . '/screenshots', 0777, true);
                    }
                    $zip = new ZipArchive;
                    $zipname = $baseLocation . '/screenshots/screenshots.zip';
                    $zip->open($zipname, ZipArchive::CREATE);
                    foreach ($this->request->getUploadedFiles() as $file) {
                        if (strpos($file->getKey(), 'screenshot') > -1) {
                            HelpingFunctions::writetolog("I\t", 'zipiing file: ' . $file->getTempName() . ' - name: ' . $file->getName() . ' KEY : ' . $file->getKey());
                            $zip->addFile($file->getTempName(), $file->getName());
                            $added = true;
                        }
                    }
                    if (isset($added)) {
                        $zip->close();
                        $offerobject->screenshot = $zipname;
                        if ($offerobject->save() == false) {
                            $messages = $offerobject->getMessages();
                            foreach ($messages as $message) {
                                echo $message, "\n";
                            }
                            return null;
                        }
                    }
                    //nothing added
                    else {
                        unlink($zipname);
                        rmdir($baseLocation . '/screenshot/');
                    }
                }
            }
            if ($this->request->getPost('clonehash') != null && $this->request->getPost('clonehash') != 'undefined' && $this->request->getPost('clonehash') != '' && !isset($screenshotexists) && $this->request->getPost('removescreenshot') != 1) {

                $packoffer = Offerpack::findFirst([
                            "hashMask = :hashMask: ",
                            "bind" => [
                                "hashMask" => $this->request->getPost('clonehash'),
                            ],
                ]);
                if (empty($packoffer) || empty($packoffer->screenshot))
                    return;

                if (!is_dir($baseLocation . '/screenshots')) {
                    HelpingFunctions::writetolog("I\t", 'creating dir: ' . $baseLocation . '/screenshots', null, null, __METHOD__, __CLASS__);
                    mkdir($baseLocation . '/screenshots', 0777, true);
                }
                $newfile = $baseLocation . '/screenshots/' . basename($packoffer->screenshot);
                HelpingFunctions::writetolog("I\t", $packoffer->screenshot . ' copying to ' . $newfile);
                if (!copy($packoffer->screenshot, $newfile)) {
                    throw new Exception('COULD NOT COPY FILE');
                }
                $offerobject->screenshot = $newfile;
                if ($offerobject->save() == false) {
                    $messages = $offerobject->getMessages();
                    foreach ($messages as $message) {
                        echo $message, "\n";
                    }
                    return null;
                }
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
            return null;
        }
    }

    private function convertToUSD($curtype, $cpa) {
        try {
            $sql = $this->getDi()->getDb4()->prepare('SELECT rate FROM CurrencyHistory WHERE currency = :currency ORDER BY id DESC LIMIT 1');
            $currencyarr = isset($curtype) ? array('currency' => strtoupper($curtype)) : array();
            $currencyarrtype = isset($curtype) ? array(\Phalcon\Db\Column::TYPE_CHAR) : array();
            $exe = $this->getDi()->getDb4()->executePrepared($sql, $currencyarr, $currencyarrtype);
            $v = $exe->fetchAll(PDO::FETCH_ASSOC);
            if (empty($v) || empty($v[0]) || empty($v[0]['rate'])) {
                return $cpa;
            }
            return ($cpa * $v[0]['rate']);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
            return null;
        }
    }

    private function checkRequest($variable = null) {
        try {
            if (!isset($variable) || $variable == 'undefined' || $variable == '') {
                //echo 'not set';
                return false;
            } else {
                //echo 'setted';
                return true;
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());

            echo $ex->getMessage() . __LINE__;
            return false;
        }
    }

    private function createrowofferpack($row) {
        try {
            //$campaignname, $agregatorid, $country, $rurl, $cpa, $curtype, $afcarrier, $area, $category, $status
            //$uniqid, $offername, $carrier, $status, $vertical, $model, $flow, $dailycap, $accountmanager, $campaignmanager, $area, $description, $exclusive, $ownership, $screenshot
            //aggid,aggname,country,jumpurl,cpa,curtype,carrier,offername,areaid,area,verticalid,vertical,statusid,status,modelid,model,ownership,ownershipid
            //missing: status description exclusive screenshot flow dailycap amid cmid
            $generatedname = strtolower($row['country']
                    . substr($row['aggname'], 0, 10) . substr($row['carrier'], 0, 4));
            $generatedname = str_replace(" ", "", $generatedname);
            $connection = $this->getDi()->getDb4();
            $connection->begin();
            $campaignname = $this->findcampaignName($generatedname);
            $hash = uniqid();
            $manager = new \Phalcon\Mvc\Model\Transaction\Manager();
            $manager->setDbService("db4");
            $transaction = $manager->get();
            $mask = $this->createMask($transaction, $hash, $campaignname, $row['aggid'], $row['country'], $row['jumpurl'], $row['cpa'], $row['curtype'], $row['carrier'], $row['areaid'], $row['verticalid'], $row['status']);
            if (!isset($mask))
                throw new Exception('Could not create jump');
            $offerpack = $this->createOfferPack($connection, $transaction, $hash, $row['offername'], $row['carrier'], $row['status'], $row['verticalid'], $row['modelid'], $row['flowid'], $row['dailycap'], $row['amid'], $row['cmid'], $row['areaid'], $row['description'], $row['exclusive'], $row['ownershipid']);
            if (!isset($offerpack))
                throw new Exception('Could not create offerpack');


            /* if ($this->checkRequest($this->request->getPost('regulationsArr'))) {

              $regulationsarr = explode(',', $this->request->getPost('regulationsArr'));
              $sql = 'INSERT INTO offerpack__offerregulations (offerhash,regulationid) VALUES ';
              $i = 0;
              $arrval = array();
              $arrtypecolumns = array();
              foreach ($regulationsarr as $regid) {
              $sql .= '("' . $hash . '",:regid' . $i . '),';
              $arrval[':regid' . $i] = $regid;
              $arrtypecolumns[] = \Phalcon\Db\Column::TYPE_INTEGER;
              $i++;
              }
              $sql = rtrim($sql, ',') . ';';
              $statement = $connection->prepare($sql);
              $connection->executePrepared($statement, $arrval, $arrtypecolumns);

              } */
            $connection->commit();
            $transaction->commit();
        } catch (Exception $ex) {
            if (isset($transaction))
                $transaction->rollback();
            if (isset($connection))
                $connection->rollback();
            throw $ex;
        }
    }

    //returns arraylist with correct row information
    private function processline($line) {
        try {
            $line = array_map('trim', $line);
            $res = array();
            //array(id, aggname)
            $agginfo = $this->findAgregator($line[0], $line[1]);
            $res['aggid'] = $agginfo[0];
            $res['aggname'] = $agginfo[1];

            if (empty($line[2])) {
                throw new Exception('No country defined for this campaign');
            }
            $res['country'] = $line[2];

            if (empty($line[3])) {
                throw new Exception('No jump url defined for this campaign');
            }
            $res['jumpurl'] = $line[3];

            if (empty($line[4])) {
                throw new Exception('No cpa defined for this campaign');
            }
            $res['cpa'] = $line[4];

            if (empty($line[5])) {
                throw new Exception('No currency defined for this campaign');
            }
            $res['curtype'] = $line[5];

            if (empty($line[6])) {
                throw new Exception('No carrier defined for this campaign');
            }
            $res['carrier'] = $line[6];

            if (empty($line[7])) {
                throw new Exception('No offer name defined for this campaign');
            }
            $res['offername'] = $line[7];

            $res['areaid'] = '';
            $res['area'] = empty($line[8]) ? '' : $line[8];
            if (!empty($res['area'])) {
                $id = $this->findidbyname('area', $res['area']);
                if (!empty($id))
                    $res['areaid'] = $id;
            }

            $res['verticalid'] = '';
            $res['vertical'] = empty($line[9]) ? '' : $line[9];
            if (!empty($res['vertical'])) {
                $id = $this->findidbyname('vertical', $res['vertical']);
                if (!empty($id))
                    $res['verticalid'] = $id;
            }

            $res['statusid'] = '';
            $res['status'] = empty($line[10]) ? '' : $line[10];
            if (!empty($res['status'])) {
                $id = $this->findidbyname('status', $res['status']);
                if (!empty($id))
                    $res['statusid'] = $id;
            }

            $res['modelid'] = '';
            $res['model'] = empty($line[11]) ? '' : $line[11];
            if (!empty($res['model'])) {
                $id = $this->findidbyname('model', $res['model']);
                if (!empty($id))
                    $res['modelid'] = $id;
            }

            $res['ownershipid'] = '';
            $res['ownership'] = empty($line[12]) ? '' : $line[12];
            if (!empty($res['ownership'])) {
                $id = $this->findidbyname('ownership', $res['ownership']);
                if (!empty($id))
                    $res['ownershipid'] = $id;
            }

            $res['flowid'] = '';
            $res['flow'] = empty($line[13]) ? '' : $line[13];
            if (!empty($res['flow'])) {
                $id = $this->findidbyname('flow', $res['flow']);
                if (!empty($id))
                    $res['flowid'] = $id;
            }
            $res['statusid'] = '';
            $res['status'] = empty($line[14]) ? '' : $line[14];
            if (!empty($res['status'])) {
                $id = $this->findidbyname('status', $res['status']);
                if (!empty($id))
                    $res['statusid'] = $id;
            }

            $res['exclusive'] = isset($line[15]) ? 1 : 0;
            $res['screenshot'] = isset($line[16]) ? $line[16] : '';
            $res['dailycap'] = isset($line[17]) ? $line[17] : '';

            $res['amid'] = '';
            $res['am'] = empty($line[18]) ? '' : $line[18];
            if (!empty($res['am'])) {
                $id = $this->findidbyname('am', $res['am']);
                if (!empty($id))
                    $res['amid'] = $id;
            }

            $res['cmid'] = '';
            $res['cm'] = empty($line[19]) ? '' : $line[19];
            if (!empty($res['cm'])) {
                $id = $this->findidbyname('cm', $res['cm']);
                if (!empty($id))
                    $res['cmid'] = $id;
            }
            $res['description'] = isset($line[20]) ? $line[20] : '';

            //description screenshot dailycap amid cmid
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function findAgregator($aggid, $aggname) {
        try {
            if (empty($aggname) && $aggid)
                throw new Exception('Agregator not found');
            if (!empty($aggname)) {
                //find its id

                $agg = Agregator::findFirst("agregator LIKE :agregator: ", ["bind" => [$aggname]]);
                //try to find by id
                if (empty($agg) && !empty($aggid)) {
                    $agg = Agregator::findFirst("id = :agregator: ", ["bind" => [$aggid]]);
                    if (empty($agg))
                        throw new Exception('Agregator not found');
                    else
                        return array($agg->id, $agg->name);
                }else if (!empty($agg))
                    return array($agg->id, $agg->name);
                else
                    throw new Exception('Agregator not found');
            }
            else if (!empty($aggid)) {
                $agg = Agregator::findFirst("id = :agregator: ", ["bind" => [$aggid]]);
                if (empty($agg))
                    throw new Exception('Agregator not found');
                else
                    return array($agg->id, $agg->name);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function populateNames() {
        try {
            $cache = $this->di->get("viewCache");
            $dims = new PackOffersDims();
            $this->flownames = $cache->get('flowofferpackbyid');
            //$flowres = null;
            if (!isset($this->flownames)) {
                $flow = $dims->getflowvalues();
                if (!empty($flow)) {
                    foreach ($flow as $row) {
                        $this->flownames[$row['id']] = $row['name'];
                    }
                    $cache->save('flowofferpackbyid', $this->flownames, 86000);
                }
            }

            $this->ownership = $cache->get('ownershipbyid');
            //$flowres = null;
            if (!isset($this->ownership)) {
                $ownership = $dims->getownershipvalues();
                if (!empty($ownership)) {
                    foreach ($ownership as $row) {
                        $this->ownership[$row['id']] = $row['name'];
                    }
                    $cache->save('ownershipbyid', $this->ownership, 86000);
                }
            }

            $this->modelnames = $cache->get('modelofferpackbyid');
            //$flowres = null;
            if (!isset($this->modelnames)) {
                $model = $dims->getmodelvalues();
                if (!empty($model)) {
                    foreach ($model as $row) {
                        $this->modelnames[$row['id']] = $row['name'];
                    }
                    $cache->save('modelofferpackbyid', $this->modelnames, 86000);
                }
            }

            $this->verticalnames = $cache->get('verticalofferpackbyid');
            //$flowres = null;
            if (!isset($this->verticalnames)) {
                $vertical = $dims->getverticalvalues();
                if (!empty($vertical)) {
                    foreach ($vertical as $row) {
                        $this->verticalnames[$row['id']] = $row['name'];
                    }
                    $cache->save('verticalofferpackbyid', $this->verticalnames, 86000);
                }
            }

            $this->statusnames = $cache->get('statusofferpackbyid');
            //$flowres = null;
            if (!isset($this->statusnames)) {
                $status = $dims->getstatusvalues();
                if (!empty($status)) {
                    foreach ($status as $row) {
                        $this->statusnames[$row['id']] = $row['name'];
                    }
                    $cache->save('statusofferpackbyid', $this->statusnames, 86000);
                }
            }

            $this->cmnames = $cache->get('cmofferpackbyid');
            //$flowres = null;
            if (!isset($this->cmnames)) {
                $cm = $dims->getcmsvalues();
                if (!empty($cm)) {
                    foreach ($cm as $row) {
                        $this->cmnames[$row['id']] = $row['name'];
                    }
                    $cache->save('cmofferpackbyid', $this->cmnames, 86000);
                }
            }

            $this->amnames = $cache->get('amofferpackbyid');
            //$flowres = null;
            if (!isset($this->amnames)) {
                $am = $dims->getaccountvalues();
                if (!empty($am)) {
                    foreach ($am as $row) {
                        $this->amnames[$row['id']] = $row['name'];
                    }
                    $cache->save('amofferpackbyid', $this->amnames, 86000);
                }
            }

            $this->areares = $cache->get('areateamtypecache');
            if (!isset($this->areares)) {
                $am = $dims->getareatypevalues();
                if (!empty($am)) {
                    foreach ($am as $row) {
                        $a = array();
                        $a['id'] = $row['id'];
                        $a['name'] = $row['name'];
                        $this->areares[] = $a;
                    }
                    $cache->save('areateamtypecache', $this->areares, 86000);
                }
            }
            foreach ($this->areares as $area) {
                $this->areanames[$area['id']] = $area['name'];
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function findidbyname($dim, $name) {
        try {
            switch ($dim) {
                case 'vertical':
                    foreach ($this->verticalnames as $key => $vertical)
                        if (stripos($vertical, $name) !== FALSE) {
                            return $key;
                        }
                    break;
                case 'status':
                    foreach ($this->statusnames as $key => $status)
                        if (stripos($status, $name) !== FALSE) {
                            return $key;
                        }
                    break;
                case 'ownership':
                    foreach ($this->ownership as $key => $ownership)
                        if (stripos($ownership, $name) !== FALSE) {
                            return $key;
                        }
                    break;
                case 'flow':
                    foreach ($this->flownames as $key => $flow)
                        if (stripos($flow, $name) !== FALSE) {
                            return $key;
                        }
                    break;
                case 'area':
                    foreach ($this->areanames as $key => $area) {
                        if (stripos($area, $name) !== FALSE) {
                            return $key;
                        }
                    }
                    break;
                case 'model':
                    foreach ($this->modelnames as $key => $model) {
                        if (stripos($model, $name) !== FALSE) {
                            return $key;
                        }
                    }
                    break;
                case 'am':
                    foreach ($this->amnames as $key => $am) {
                        if (stripos($am, $name) !== FALSE) {
                            return $key;
                        }
                    }
                    break;
                case 'cm':
                    foreach ($this->cmnames as $key => $cm) {
                        if (stripos($cm, $name) !== FALSE) {
                            return $key;
                        }
                    }
                    break;
                default:
                    return '';
            }
            return '';
        } catch (Exception $ex) {
            return '';
        }
    }

    public function updateStatusAction() {

        //recebe hash ou hashs de varias offers packs

        $hashs = $this->request->getPost('ids');
        $value = $this->request->getPost('valueStatus');

        //echo $hashs;        //hash,hash,hash
        //echo $value;        //3


        try {

            if (isset($hashs) && !empty($hashs) && isset($value) && is_numeric($value)) {
                $hashsarry = explode(',', $hashs);
                foreach ($hashsarry as $simplehash) {
                    echo $simplehash;
                    $packoffer = Offerpack::findFirst([
                                "hashMask = :hashMask: ",
                                "bind" => [
                                    "hashMask" => $simplehash,
                                ],
                    ]);
                    if (empty($packoffer)) {
                        continue;
                    }
                    $packoffer->status = $value;
                    $packoffer->statusreason = $this->request->getPost('reason') != null ? $this->request->getPost('reason') : new \Phalcon\Db\RawValue('default');


                    if ($packoffer->save() == false) {
                        $messages = $packoffer->getMessages();
                        foreach ($messages as $message) {
                            //echo $message, "\n";
                            break;
                        }
                        continue;
                    }
                }
                echo '1';
            }
        } catch (Exception $ex) {
            echo $ex->getMessage() . __LINE__;
        }
    }

    //only images
    public function addNewOrEditOffer($offerhash, $connection) {

        //if (!$editingoffer)
        //$this->checkforCloneImages();
        $baselocation = null;
        $this->editImages($offerhash, $baselocation, $connection, 'screenshot');
        $this->editImages($offerhash, $baselocation, $connection, 'banner');
        //when clone occurs (like new offer) check if there are images to copy to new "cloned" offer
        $this->checkforCloneImages($offerhash, $baselocation, $connection);
    }

    private function removeImages($offerhash, $connection, $bannerorscreenshot = 'banner') {
        try {
            $arrayfilter = array();
            $ids = $this->request->getPost($bannerorscreenshot . 'ids');
            $where = ' 1 = 1 ';
            if (!empty($ids)) {
                $oldids = explode(',', $ids);
                if (count($oldids) > 0) {
                    $where = ' id NOT IN (' . str_repeat('?,', count($oldids) - 1) . '?' . ') ';
                    $arrayfilter = $oldids;
                }
            }
            $sql = 'DELETE FROM offerpack__' . ($bannerorscreenshot == 'banner' ? 'offerbannerfiles' : 'offerscreenshotfiles') . ' WHERE ' . $where . ' and offerhash = ? ;';
            $statement = $connection->prepare($sql);
            $arrayfilter[] = $offerhash;
            $connection->executePrepared($statement, $arrayfilter, array());

            return;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            return null;
        }
    }

    private function checkforCloneImages($offerhash, &$baselocation, $connection) {
        try {
            if ($this->request->getPost('clonehash') != null && $this->request->getPost('clonehash') != 'undefined' && $this->request->getPost('clonehash') != '' && (
                    ($this->request->getPost('screenshotids') != null && $this->request->getPost('screenshotids') != 'undefined' && $this->request->getPost('screenshotids') != '') ||
                    ($this->request->getPost('bannerids') != null && $this->request->getPost('bannerids') != 'undefined' && $this->request->getPost('bannerids') != ''))) {
                //clone these screenshots
                $clonehash = $this->request->getPost('clonehash');

                $screenshotids = $this->request->getPost('screenshotids');
                $this->cloneImages($clonehash, $screenshotids, $offerhash, $baselocation, $connection, 'screenshot');

                $bannerids = $this->request->getPost('bannerids');
                $this->cloneImages($clonehash, $bannerids, $offerhash, $baselocation, $connection, 'banner');
                //}
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            return null;
        }
    }

    private function cloneImages($clonehash, $ids, $offerhash, &$baselocation, $connection, $bannerorscreenshot = 'banner') {
        try {
            //copy to new offer data @ database
            //copy images to correct offer folder
            if (empty($ids))
                return;

            $ids = explode(',', $ids);
            $wherevals = array();
            $location = $baselocation . '/' . $bannerorscreenshot . 's/';

            $where = ' 1 = 1 ';
            if (count($ids) > 0) {
                $where = ' id IN (' . str_repeat('?,', count($ids) - 1) . '?' . ') ';
                $wherevals = $ids;
            }
            //find all images selected

            $wherevals[] = $clonehash;
            $sql = 'SELECT id, location FROM offerpack__' . ($bannerorscreenshot == 'banner' ? 'offerbannerfiles' : 'offerscreenshotfiles') . ' WHERE ' . $where . ' AND offerhash LIKE ? ;';
            //echo $sql;
            //print_r($wherevals);
            $statement = $connection->prepare($sql);
            $prepared = $connection->executePrepared($statement, $wherevals, array());
            $array_ret = $prepared->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($array_ret) && !empty($array_ret[0])) {

                if (!file_exists($location)) {
                    mkdir($location, 0777, true);
                    //echo 'created:' . $location . ' finished';
                }
                foreach ($array_ret as $row) {
                    try {
                        //copy (string $source ,string $dest)
                        $file = $location . basename($row['location']);
                        if (copy($row['location'], $file)) {
                            $sql = 'INSERT INTO offerpack__' . ($bannerorscreenshot == 'banner' ? 'offerbannerfiles' : 'offerscreenshotfiles') . ' (offerhash,location,status) '
                                    . ' SELECT * FROM ( SELECT "' . $offerhash . '",:location as locatione,1) AS TMP '
                                    . ' WHERE NOT EXISTS ( SELECT offerhash FROM offerpack__' . ($bannerorscreenshot == 'banner' ? 'offerbannerfiles' : 'offerscreenshotfiles')
                                    . ' WHERE offerhash = "' . $offerhash . '" AND location LIKE :location ) LIMIT 1;';
                            $statement = $connection->prepare($sql);
                            $prepared = $connection->executePrepared($statement, array('location' => ($file)), array());
                        } else {
                            mail('pedro.leonardo@mobipium.com', 'could not save clone image to directory' . ' rowlocation:' . $row['location'] . ' file:' . $file . "\n");
                        }
                    } catch (Exception $ex) {
                        mail('pedro.leonardo@mobipium.com', 'could not cloneimage to directory', $ex->getMessage() . $sql . ' rowlocation:' . $row['location'] . ' file:' . $file . "\n" . $ex->getLine());
                        continue;
                    }
                }
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            return null;
        }
    }

    private function editImages($offerhash, &$baseLocation, $connection, $bannerorscreenshot = 'banner') {
        try {
            if (!isset($baseLocation))
                $baseLocation = $this->getPublicOfferBaseLocation($offerhash, $connection);
            $exitsatleastonefile = false;
            //should remove images from this offer?
            $this->removeimages($offerhash, $connection, $bannerorscreenshot);

            //check for new images
            $newimages = false;
            if ($this->request->hasFiles() == true) {

                foreach ($this->request->getUploadedFiles() as $file) {
                    if (strpos($file->getKey(), $bannerorscreenshot) > -1) {
                        $exitsatleastonefile = true;
                        //HelpingFunctions::writetolog("I\t" . __CLASS__, 'THERE ARE ' . $bannerorscreenshot);
                        break;
                    }
                }
                //there are files
                if ($exitsatleastonefile) {
                    //create dir
                    //HelpingFunctions::writetolog("I\t" . __CLASS__, 'DIRECTORY GONNA CREATING THIS: ' . $baseLocation . '/' . $bannerorscreenshot . 's');
                    if (!is_dir($baseLocation . '/' . $bannerorscreenshot . 's')) {
                        HelpingFunctions::writetolog("I\t", 'creating dirs', null, null, __METHOD__, __CLASS__);
                        mkdir($baseLocation . '/' . $bannerorscreenshot . 's', 0777, true);
                    }

                    foreach ($this->request->getUploadedFiles() as $file) {
                        if (strpos($file->getKey(), $bannerorscreenshot) > -1) {
                            //HelpingFunctions::writetolog("I\t", 'file found : ' . $file->getTempName() . ' - name: ' . $file->getName() . ' KEY : ' . $file->getKey());
                            $name = $file->getName();
                            $name = $this->clean($name);
                            $filefinallocation = $baseLocation . '/' . $bannerorscreenshot . 's/' . $name;
                            //HelpingFunctions::writetolog("I\t" . __CLASS__, ' FINAL FILE: ' . $filefinallocation);
                            //save file, overwrite if exists
                            move_uploaded_file($file->getTempName(), $filefinallocation);
                            $newimages = true;
                            //add to active files
                            $sql = 'INSERT INTO offerpack__' . ($bannerorscreenshot == 'banner' ? 'offerbannerfiles' : 'offerscreenshotfiles') . ' (offerhash,location,status) '
                                    . ' SELECT * FROM ( SELECT "' . $offerhash . '",:location,1) AS TMP '
                                    . ' WHERE NOT EXISTS ( SELECT offerhash FROM offerpack__' . ($bannerorscreenshot == 'banner' ? 'offerbannerfiles' : 'offerscreenshotfiles')
                                    . ' WHERE offerhash = "' . $offerhash . '" AND location LIKE :location ) LIMIT 1;';
                            $statement = $connection->prepare($sql);
                            $connection->executePrepared($statement, array('location' => $filefinallocation), array());
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            return null;
        }
    }

    private function clean($string) {
        try {
            $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

            return preg_replace('/[^A-Za-z0-9\-\.]/', '', $string); // Removes special chars.
        } catch (Exception $e) {
            return $string;
        }
    }

    public function getzipAction() {

        $offerhash = $this->request->get('offerhash');
        $db = $this->getDi()->getDb4();
        $ids = $this->request->get('ids');
        $ids = empty($ids) ? null : $ids;
        $type = $this->request->get('type') != null ? ($this->request->get('type') == 'banner' ? 'banner' : 'screenshot' ) : null;
        if (!isset($type)) {
            return;
        }
        $this->getOfferImages($offerhash, $ids, $db, $type);
    }

    public function getOfferImages($offerhash, $ids, $connection, $bannerorscreenshot) {
        try {

            $requestids = array();
            if (!empty($ids))
                $requestids = explode(',', $ids);
            $where = ' 1 = 1 ';
            $conds = array();
            if (count($requestids) > 0) {
                $where = ' id IN (' . str_repeat('?,', count($requestids) - 1) . '?' . ') ';
                $conds = $requestids;
            }
            $conds[] = $offerhash;

            $sql = 'SELECT location '
                    . ' FROM offerpack__' . ($bannerorscreenshot == 'banner' ? 'offerbannerfiles' : 'offerscreenshotfiles')
                    . ' WHERE ' . $where . ' AND offerhash = ? ';

            $statement = $connection->prepare($sql);

            $exe = $connection->executePrepared($statement, $conds, array());

            $res = $exe->fetchAll(PDO::FETCH_ASSOC);
            if (empty($res)) {
                echo "No content";
                return;
            }
            if (count($res) == 1) {

                header('Content-Type: application/' . pathinfo($res[0]['location'], PATHINFO_EXTENSION));
                header('Content-Length: ' . filesize($res[0]['location']));
                header('Content-Disposition: attachment; filename="' . basename($res[0]['location']) . '"');
                readfile($res[0]['location']);
                return;
            }
            $a = strpos($res[0]['location'], $offerhash);

            $dir = substr($res[0]['location'], 0, $a) . $offerhash . ($bannerorscreenshot == 'banner' ? '/banners' : '/screenshots');

            $zipitself = $dir . "/" . ($bannerorscreenshot == 'banner' ? 'banners.zip' : 'screenshots.zip');

            if (file_exists($zipitself)) {
                unlink($zipitself);
            }
            $zip = new ZipArchive();
            $zip->open($zipitself, ZipArchive::CREATE);
            $i = 0;
            foreach ($res as $row) {
                //echo $dir . "\n" . $row['location'] . "\n";
                $basename = str_replace($dir . "/", '', $row['location']);
                $zip->addFile($row['location'], $basename);
                $i++;
            }
            $zip->close();
            header('Content-Type: application/zip');
            header('Content-Length: ' . filesize($zipitself));
            header('Content-Disposition: attachment; filename="' . ($bannerorscreenshot == 'banner' ? 'banners.zip' : 'screenshots.zip'));

            readfile($zipitself);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            return null;
        }
    }

    //returns and creates if doesnt exist /home/whatsp/public/files/offers20/offerhash
    private function getPublicOfferBaseLocation($offerhash, $connection) {
        try {
            $path = $this->checkLocation($offerhash, $connection);
            if (isset($path)) {
                if (!is_dir($path)) {
                    HelpingFunctions::writetolog("I\t", 'creating dir from getlastzippath:' . $path, null, null, __METHOD__, __CLASS__);
                    mkdir($path, 0777, true);
                    mkdir($path . '/screenshots/', 0777, true);
                    mkdir($path . '/banners/', 0777, true);
                }
                return $path;
            }

            $folderroot = getcwd() . '/files/';
            $currentfolderroot = getcwd() . '/files/';
            $fi = new FilesystemIterator($folderroot, FilesystemIterator::SKIP_DOTS);
            //if more than 100 folder, create a new one, and this shall be the new baselocation for the banners
            if (iterator_count($fi) > 50) {
                //create new folder
                $i = 0;
                $newfolderroot = $folderroot . 'offers';
                $currentfolderroot = $newfolderroot . $i . '/';
                if (!is_dir($currentfolderroot)) {
                    HelpingFunctions::writetolog("I\t", 'creating dir:' . $currentfolderroot, null, null, __METHOD__, __CLASS__);
                    mkdir($currentfolderroot, 0777, true);
                    mkdir($currentfolderroot . $offerhash . '/', 0777, true);
                }
                $fi = new FilesystemIterator($currentfolderroot, FilesystemIterator::SKIP_DOTS);
                while (iterator_count($fi) > 50) {
                    $i++;
                    $currentfolderroot = $newfolderroot . $i . '/';
                    if (!is_dir($currentfolderroot)) {
                        HelpingFunctions::writetolog("I\t", 'creating dir:' . $currentfolderroot . $i, null, null, __METHOD__, __CLASS__);
                        mkdir($currentfolderroot, 0777, true);
                        mkdir($currentfolderroot . $offerhash . '/', 0777, true);
                    }
                    $fi = new FilesystemIterator($currentfolderroot, FilesystemIterator::SKIP_DOTS);
                }
            }
            return $currentfolderroot . $offerhash;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__, $ex->getMessage());
            echo $ex->getMessage() . __LINE__;
            return null;
        }
    }

    //returns /home/whatsp/public/files/offers20/offerhash
    private function checkLocation($offerhash, $connection) {
        try {

            $sql = 'SELECT location FROM offerpack__offerbannerfiles WHERE offerhash = :offerhash LIMIT 1';
            $statement = $connection->prepare($sql);
            $exe = $connection->executePrepared($statement, array('offerhash' => $offerhash), array());
            $location = $exe->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($location) && !empty($location[0]) && !empty($location[0]['location']))
                return dirname(dirname($location[0]['location']));
            //lets try to find it in screenshots
            $sql = 'SELECT location FROM offerpack__offerscreenshotfiles WHERE offerhash = :offerhash LIMIT 1';
            $statement = $connection->prepare($sql);
            $exe = $connection->executePrepared($statement, array('offerhash' => $offerhash), array());
            $location = $exe->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($location) && !empty($location[0]) && !empty($location[0]['location']))
                return dirname(dirname($location[0]['location']));
            return null;
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E' . __CLASS__ . $ex->getLine(), $ex->getMessage());
            return null;
        }
    }

}

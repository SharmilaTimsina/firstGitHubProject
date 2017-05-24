<?php

/**
 * ReportController
 *
 * Report Insertion and presentation
 */
class MainstreamController extends ControllerBase {

    private $reportObject;

    public function initialize() {
        $this->tag->setTitle('Mainstreamer');
        parent::initialize();
        $this->reportObject = new Report();
    }

    public function indexAction() {
        try {
            $logArray = $this->login_access();
            $op = new Operation();
            $slinks = new Slink();
            $sources = $op->getMainstreamSources($logArray[1]);
            $returnSources = $this->generateSelectOption($sources, 'id', 'sourceName');
            $njumps = $slinks->getNjumps($logArray[0], 2);
            $returnnjumps = $this->generateSelectOption($njumps, 'hashnjump', 'njump');
            
			$domains = $op->getDomains();
			
			//$domains = array('http://viver-e-crescer.com/', 'http://missao-felicidade.com/', 'http://signos-diarios.com/', 'http://caminante-camino.com/', 'http://nos-por-ca.com/', 'http://walking-alone.com/', 'http://nosotros-aqui.com/', 'http://superate-ya.com', 'http://el-pica-flor.com/', 'http://livinglavitaamore.com');
            $returndomains = $this->generateSelectOption($domains, null, null);
            $this->view->setVar("sourcelist", $returnSources);
            $this->view->setVar("njumplist", $returnnjumps);
            $this->view->setVar("domainlist", $returndomains);
        } catch (Exception $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    public function mainAction() {
        try {
            $logArray = $this->login_access();
            $op = new Operation();
            $slinks = new Slink();
            $sources = $op->getMainstreamSources($logArray[1]);
            $returnSources = $this->generateSelectOption($sources, 'id', 'sourceName');
            $njumps = $slinks->getNjumps($logArray[0], 2);
            
			$domains = $op->getDomains();
			
			//$domains = array('http://viver-e-crescer.com/', 'http://missao-felicidade.com/', 'http://signos-diarios.com/', 'http://caminante-camino.com/', 'http://nos-por-ca.com/', 'http://walking-alone.com/', 'http://nosotros-aqui.com/', 'http://superate-ya.com', 'http://el-pica-flor.com/','http://livinglavitaamore.com');
            $domains = Domains::find();
            $returndomains = array();
            $i = 0;
            foreach($domains as $domain){
                $returndomains[$i]['id'] = $domain->id;
                $returndomains[$i]['domain'] = $domain->domain;
                $returndomains[$i]['sources'] = $domain->sources;
                $i++;
            }
            //$returndomains = $this->generateSelectOptionFromModel($domains, 'id', 'domain');
            //$returndomains2 = $this->generateSelectOption($domains, null, null);
            $this->view->setVar("sourcelist", $returnSources);
            
            $this->view->setVar("domainlist", json_encode($returndomains));
        } catch (Exception $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    public function getCountriesWithDomainidAction() {
        try {
            $domainid = (null != $this->request->get('domainid') || '' != $this->request->get('domainid') ) ? $this->request->get('domainid') : null;
            if (!isset($domainid) || $domainid == '' || $domainid == 0) {
                echo 0;
                return;
            } else {
                $domains = Domains::findFirst(array(
                            "conditions" => "id = ?1",
                            "bind" => array(1 => $domainid)
                ));

                $countriessarr = array();
                if (!empty($domains)) {
                    $countriessarr = explode(',', $domains->countries);
                } else {
                    echo 0;
                    return;
                }
                $optioncountries = null;
                $optioncountries[0]['id'] = '';
                $optioncountries[0]['name'] = '';
                $i = 1;
                foreach ($countriessarr as $countryid) {
                    $country = Countries::findFirst(array(
                                "conditions" => "id LIKE ?1",
                                "bind" => array(1 => $countryid)
                    ));
                    if (empty($country))
                        continue;
                    $optioncountries[$i]['id'] = $country->id;
                    $optioncountries[$i]['name'] = $country->name;
                    $i++;
                }

                echo json_encode($optioncountries);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAllCategoriesAction() {
        try {
            $categories = Categories::find();
            $optioncats = null;
            $i = 1;
            $optioncats[0]['id'] = '';
            $optioncats[0]['name'] = '';
            foreach ($categories as $catid) {
                if (empty($catid))
                    continue;
                $optioncats[$i]['id'] = $catid->id;
                $optioncats[$i]['name'] = $catid->name;
                $i++;
            }

            echo json_encode($optioncats);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getNjumpsAction() {
        try {
            $country = ($this->request->get('country') != null && $this->request->get('country') != '') ? $this->request->get('country') : null;
            $category = ($this->request->get('category') != null && $this->request->get('category') != '') ? $this->request->get('category') : null;
            $source = ($this->request->get('source') != null && $this->request->get('source') != '') ? $this->request->get('source') : null;
            $slink = new Slink();
            $njumps = $slink->getNjumpsWithCountryCategory($country, $category);
            if (empty($njumps)) {
                echo '0';
                return;
            }
            if(isset($source)){
                foreach($njumps as $key=>$njump){
                    $namearr = explode('_',$njump['njump']);
                    if(isset($namearr) && $namearr[1] != $source){
                        unset($njumps[$key]);
                    }
                }
            }
            $returnnjumps = $this->generateSelectOption($njumps, 'hashnjump', 'njump');
            echo $returnnjumps;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getMainstreamHistoryAction() {
        try {
            $source = $this->request->get('source') != null || $this->request->get('source') != '' ? $this->request->get('source') : null;
            $domain = $this->request->get('domain') != null || $this->request->get('domain') != '' ? $this->request->get('domain') : null;
            $country = $this->request->get('country') != null || $this->request->get('country') != '' ? $this->request->get('country') : null;
            $category = $this->request->get('category') != null || $this->request->get('category') != '' ? $this->request->get('category') : null;
            $njump = $this->request->get('njump') != null || $this->request->get('njump') != '' ? $this->request->get('njump') : null;
            //id, source, domain, countries, category, njump, group, ad, banner, insertDate
            $whereclause = isset($source) ? ' source = ' . $source : ' 0=0 ';
            $whereclause .= isset($domain) ? ' AND domain = ' . $domain : '';
            $whereclause .= isset($category) ? ' AND category = ' . $category : '';
            $whereclause .= isset($njump) ? ' AND njump = "' . $njump . '"' : '';
            $whereclause .= isset($country) ? ' AND countries LIKE "%' . $country . '%"' : '';
            
            $history = MainstreamHistory::find(array($whereclause, "order" => "groupz DESC, ad DESC",
                        "limit" => 2));
            if (count($history) == 0) {
                $newhistory = MainstreamHistory::findFirst(array(' source = ' . $source, "order" => "groupz DESC"));
                if(empty($newhistory)){
                    echo 1;
                    return;
                }
                else{
                    echo ($newhistory->groupz +1 );
                }
                return;
            }
            $res = array();
            $i = 0;
            foreach ($history as $histrow) {
                $res[$i]['id'] = $histrow->id;
                $res[$i]['countries'] = $histrow->countries;
                $res[$i]['source'] = $histrow->source;
                $res[$i]['category'] = $histrow->category;
                $res[$i]['group'] = $histrow->groupz;
                $res[$i]['ad'] = $histrow->ad;
                $res[$i]['banner'] = $histrow->banner;
                $i++;
            }
            echo json_encode($res);
            return;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function saveNewRowAction() {
        try {
            $source = $this->request->getPost('source') != null || $this->request->getPost('source') != '' ? $this->request->getPost('source') : null;
            $domain = $this->request->getPost('domain') != null || $this->request->getPost('domain') != '' ? $this->request->getPost('domain') : null;
            $country = $this->request->getPost('country') != null || $this->request->getPost('country') != '' ? $this->request->getPost('country') : null;
            $category = $this->request->getPost('category') != null || $this->request->getPost('category') != '' ? $this->request->getPost('category') : null;
            $njump = $this->request->getPost('njump') != null || $this->request->getPost('njump') != '' ? $this->request->getPost('njump') : null;
            $group = $this->request->getPost('group') != null || $this->request->getPost('group') != '' ? $this->request->getPost('group') : null;
            $ad = $this->request->getPost('ad') != null || $this->request->getPost('ad') != '' ? $this->request->getPost('ad') : null;
			$sub_id = $this->request->getPost('sub_id') != null || $this->request->getPost('sub_id') != '' ? $this->request->getPost('sub_id') : null;
			$state = 0;
			
 		    $banner = null;
            //echo getcwd();
            foreach ($this->request->getUploadedFiles() as $file) {
                $imagefolder = "/img/";
                $baseLocation = '/home/whatsapp/public_html/mobisteinreport.com/public'.$imagefolder;
                if (!(($file->getKey() == 'file') ))
                    continue;
                $banner = $imagefolder.$njump .'_'.$group. '_'.$ad .'_'. $file->getName();
                $dir = $baseLocation . $njump .'_'.$group. '_'.$ad .'_'. $file->getName();
                $file->moveTo($dir);
                break;
            }
            $whereclause = isset($source) ? ' state="0" AND source = ' . $source : ' 0=0 ';
            $whereclause .= isset($domain) ? ' AND domain = ' . $domain : '';
            $whereclause .= isset($category) ? ' AND category = ' . $category : '';
            $whereclause .= isset($njump) ? ' AND njump = "' . $njump . '"' : '';
            $whereclause .= isset($country) ? ' AND countries LIKE "%' . $country . '%"' : '';
            $whereclause .= isset($group) ? ' AND groupz =' . $group : '';
            $whereclause .= isset($ad) ? ' AND ad = ' . $ad : '';
			$whereclause .= isset($sub_id) ? ' AND sub_id = "' . $sub_id . '"' : '';


            $newRow = MainstreamHistory::findFirst(array($whereclause));
            if(empty($newRow)){
                $newRow = new MainstreamHistory();
            }
            //id, source, domain, countries, category, njump, group, ad, banner, sub_id, insertDate
            //$newRow->id = new \Phalcon\Db\RawValue('default');
            $newRow->source = isset($source) ? $source : new \Phalcon\Db\RawValue('default');
            $newRow->domain = isset($domain) ? $domain : new \Phalcon\Db\RawValue('default');
            $newRow->countries = isset($country) ? $country : new \Phalcon\Db\RawValue('default');
            $newRow->category = isset($category) ? $category : new \Phalcon\Db\RawValue('default');
            $newRow->njump = isset($njump) ? $njump : new \Phalcon\Db\RawValue('default');
            $newRow->groupz = isset($group) ? $group : new \Phalcon\Db\RawValue('default');
            $newRow->ad = isset($ad) ? $ad : new \Phalcon\Db\RawValue('default');
			$newRow->sub_id = isset($sub_id) ? $sub_id : new \Phalcon\Db\RawValue('default');
            $newRow->banner = isset($banner) ? $banner : new \Phalcon\Db\RawValue('default');
			$newRow->state = '0';
            $newRow->insertdate = date('Y-m-d');

            if ($newRow->save() == false) {
                foreach ($newRow->getMessages() as $message) {
                    echo $message, "\n";
                }
                return;
            } else {
                $res = array();
                $res['id'] = $newRow->id;
                $res['source'] = $newRow->source;
                $res['domain'] = $newRow->domain;
                $res['countries'] = $newRow->countries;
                $res['njump'] = $newRow->njump;
                $res['group'] = $newRow->groupz;
                $res['ad'] = $newRow->ad;
				$res['sub_id'] = $newRow->sub_id;
                $res['banner'] = ((isset($newRow->banner) && $newRow->banner != 'default') ? ('..'.$newRow->banner ): '');
                echo json_encode($res);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function generateSelectOption($dbres, $id, $value) {
        if (empty($dbres)) {
            return null;
        }
        $option = '<option disabled selected>';
        if (isset($id)) {
            foreach ($dbres as $row) {
                $option .= '<option value="' . $row[$id] . '">' . $row[$id] . ' - ' . $row[$value] . '</option>';
            }
        } else {
            foreach ($dbres as $row) {
                $option .= '<option value="' . $row . '">' . $row . '</option>';
            }
        }
        return $option;
    }

    private function generateSelectOptionFromModel($modelres, $id, $value) {
        if (empty($modelres)) {
            return null;
        }
        $option = '<option disabled selected>';
        foreach ($modelres as $row) {
            $option .= '<option value="' . $row->$id . '">' . $row->$value . '</option>';
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

<?php

class MainstreambulkController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Mainstream');
        parent::initialize();
    }

    public function indexAction() {

        $report = new Invest();

        //domains filter
        $res = $report->getDomains();
        $domainsSelect = '';
        if (!empty($res)) {
            foreach ($res as $domain) {
                $domainsSelect .= "<option value='" . $domain['id'] . "'>" . $str = preg_replace('#^https?://#', '', $domain['domain']) . "</option>";
            }
            $this->view->setVar("domainsSelect", $domainsSelect);
        }

        $res = $report->getLanguagesAndCategories();
        $languagesSelect = '';
        $categoriesSelect = '';
        if (!empty($res)) {
            foreach ($res[0] as $language) {
                $languagesSelect .= "<option value='" . $language['id'] . "'>" . $language['name'] . "</option>";
            }

            foreach ($res[1] as $category) {
                $categoriesSelect .= "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
            }

            $this->view->setVar("selectboxLanguages", $languagesSelect);
            $this->view->setVar("selectboxCategories", $categoriesSelect);
        }

        $tableBulks = $this->tableBulk();
        $this->view->setVar("tableBulks", $tableBulks);
    }

    public function galleryAction() {
        $report = new Invest();

        $res = $report->getLanguagesAndCategories();

        $languagesSelect = '';
        $categoriesSelect = '';
        if (!empty($res)) {
            foreach ($res[0] as $language) {
                $languagesSelect .= "<option value='" . $language['id'] . "'>" . $language['name'] . "</option>";
            }

            foreach ($res[1] as $category) {
                $categoriesSelect .= "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
            }

            $this->view->setVar("selectboxLanguages", $languagesSelect);
            $this->view->setVar("selectboxCategories", $categoriesSelect);
        }
    }

    public function insertbannersAction() {
        try {

            $newbanners = array();

            if (isset($_POST)) {
                foreach ($_FILES['files']['name'] as $f => $name) {
                    $response = $this->cloudinary($_FILES["files"]["tmp_name"][$f]);
                    array_push($newbanners, array('hash' => $response['tags'][0], 'url' => $response['secure_url'], 'name' => strtoupper(preg_replace('/\\.[^.\\s]{3,4}$/', '', $name))));
                }
            }

            if (isset($newbanners) && !empty($newbanners)) {
                $mainstream = new MainstreamHistory;
                $result = $mainstream->insertBanners($newbanners);
            }

            if ($result == 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (Exception $e) {
            print_r($e);
        }
    }

    public function editbannerAction() {
        $array_data = array(
            'language' => $this->request->getPost('languageTagedit'),
            'category' => $this->request->getPost('categoryTagedit'),
            'hash' => $this->request->getPost('hash')
        );

        $mainstream = new MainstreamHistory;
        $mainstream->editBanner($array_data);
    }

    public function updateBannerIdAction() {
        $array_data = array(
            'hash' => $this->request->get('hash'),
            'newId' => $this->request->get('newId')
        );

        $mainstream = new MainstreamHistory;
        $res = $mainstream->editBannerId($array_data);
    }

    public function searchbannersAction() {
        $array_data = array(
            'category' => $this->request->get('category'),
            'language' => $this->request->get('language'),
            'page' => $this->request->get('page'),
            'inputID' => strtoupper($this->request->get('inputID'))
        );

        $mainstream = new MainstreamHistory;
        $res = $mainstream->searchBanners($array_data);

        $array_final = array();
        $id = 0;
        foreach ($res as $banner) {
            $newstr = substr_replace($banner['url_cloudinary'], 'c_scale,w_164/', 51, 0);

            array_push($array_final, array('id' => $id, 'attributes' => array('hash' => $banner['hash'], 'language' => $banner['language'], 'category' => $banner['category'], 'url_cloudinary' => $newstr, 'id_user' => $banner['id_user'])));
            $id++;
        }

        echo json_encode($array_final);
    }

    private function cloudinary($file) {

        try {

            $name = 'banner_main';

            Cloudinary::config(array(
                "cloud_name" => "precosmart",
                "api_key" => "349486951711411",
                "api_secret" => "5wIYG-YwgLGVcjnRZlhYHDKrdEk"
            ));
            $api = new \Cloudinary\Api();

            $hash = uniqid();
            $response = \Cloudinary\Uploader::upload($file, array("public_id" => "main_banners/" . $hash, "tags" => "m_" . $hash, 'folder' => "mainstream_banners"));

            return $response;
        } catch (Exception $e) {
            print_r($e);
        }
    }

    public function createBulkAction() {
        $array_data = array(
            'domain' => $this->request->getPost('domain'),
            'source' => $this->request->getPost('source'),
            'platform' => $this->request->getPost('platform'),
            'os' => $this->request->getPost('os'),
            'age' => $this->request->getPost('age'),
            'genre' => $this->request->getPost('genre'),
            'banners' => $this->request->getPost('banners'),
            'country' => $this->request->getPost('country'),
            'njump' => $this->request->getPost('njump'),
            'description' => $this->request->getPost('description'),
            'title' => $this->request->getPost('title'),
        );

        $mainstream = new MainstreamHistory;
        $mainstream->insert_bulk($array_data);

        echo $this->tableBulk();
    }

    public function tableBulk() {
        $mainstream = new MainstreamHistory;
        $res = $mainstream->getTableBulk($this->session->get('auth')['invest']);

        /*
          $njumpnames = '';
          $numerLinesId = '';
          $domainsLines = '';
          foreach ($res as $bulk) {
          $njumpnames .= "'" . $bulk['njump'] . "',";
          $numerLinesId .= "'" . $bulk['id'] . "',";
          $domainsLines .= "'" . $bulk['domain'] . "',";
          }
          $njumpnames = substr($njumpnames, 0, -1);
          $numerLinesId = substr($numerLinesId, 0, -1);
          $domainsLines = substr($domainsLines, 0, -1);

          $njumpsNames = $mainstream->getNJumpName($njumpnames);
          $numberLinesBulks = $mainstream->getNumberLines($numerLinesId);
          $domainsBulks = $mainstream->getDomainById($domainsLines);
         */

        $tableb = "";
        foreach ($res as $bulk) {

            $njump = $bulk['lpName'];
            $numberLines = $bulk['bulkLines'];
            $domain = $bulk['domain'];

            /*
              foreach ($njumpsNames as $line) {
              if($bulk['njump'] == $line['hash']) {
              $njump = $line['lpname'];

              break;
              }
              }

              foreach ($numberLinesBulks as $line) {
              if($bulk['id'] == $line['bulk']) {
              $numberLines = $line['line'];

              break;
              }
              }

              foreach ($domainsBulks as $line) {
              if($bulk['domain'] == $line['id']) {
              $domain = $line['domain'];

              break;
              }
              }
             */

            //$domain = $mainstream->getDomainById($bulk['domain']);
            $genre = ($bulk['genre'] == 0) ? "M" : "F";
            $max_age = ($bulk['MAX_age'] == 0) ? "+65" : $bulk['MAX_age'];

            $platform = $bulk['platform'];
            switch ($platform) {
                case 0:
                    $platform = "Desktop";
                    break;
                case 1:
                    $platform = "Mobile";
                    break;
                case 2:
                    $platform = "Instagram";
                    break;
            }


            $os = $bulk['os'];
            switch ($os) {
                case 1:
                    $os = "Android";
                    break;
                case 2:
                    $os = "IOS";
                    break;
            }

            //$njump = $mainstream->getNJumpName($bulk['njump']);
            //$numberLines = $mainstream->getNumberLines($bulk['id']);

            $date = $bulk['insertdate'];

            $tableb .= "<tr>
				<td>$bulk[id]</td>
				<td><a href='/mainstreambulk/downloadXlsx?bulk=$bulk[id]'><img data-toggle='modal' data-target='#modaldownloading' class='bulkImg' bulk='$bulk[id]' type='csv' src='/img/saving.svg'></a></td>
				<td>$njump</td>
				<td>$bulk[country]</td>
				<td>$domain</td>
				<td>$genre</td>
				<td>$platform</td>
				<td>$os</td>
				<td>$bulk[MIN_age]</td>
				<td>$max_age</td>
				<td>$numberLines</td>
				<td>$date</td>
				<td><img class='bulkImg' bulk='$bulk[id]' type='editclone' src='/img/iconEdit.svg'><img class='bulkImg' t='$bulk[title]' d='$bulk[description]' type='details' src='/img/details.png' data-toggle='modal' data-target='#modaldetails'>
				</td>
			</tr>";
        }

        return $tableb;
    }

    public function getCountryAction() {
        $domain = $this->request->getPost('domain');

        $mainstream = new MainstreamHistory;
        $res = $mainstream->getCountriesByDomain($domain);

        if (isset($res[0]['countries'])) {
            $res = explode(",", $res[0]['countries']);

            $countriesCombo = '';
            foreach ($res as $key => $country) {
                $countriesCombo .= "<option value='" . $country . "'>" . $country . "</option>";
            }

            echo $countriesCombo;
        }
    }

    public function getNjumpAction() {

        $country = $this->request->getPost('country');
        $source = $this->request->getPost('source');

        try {

            $slink = new Slink();
            $njumps = $slink->getNjumpsWithCountryCategory($country);
            if (empty($njumps)) {
                echo '0';
                return;
            }
            if (isset($source)) {
                foreach ($njumps as $key => $njump) {
                    $namearr = explode('_', $njump['njump']);
                    if (isset($namearr) && $namearr[1] != $source) {
                        unset($njumps[$key]);
                    }
                }
            }

            $campaignsCombo = '';
            foreach ($njumps as $campaign) {
                $campaignsCombo .= "<option value='" . $campaign['hashnjump'] . "'>" . $campaign['njump'] . "</option>";
            }

            echo $campaignsCombo;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSourcesAction() {
        $domain = $this->request->getPost('domain');

        $mainstream = new MainstreamHistory;
        $res = $mainstream->getSourcesByDomain($domain, $this->session->get('auth')['invest']);
        $domainsCombo = '';
        foreach ($res as $source) {
            $domainsCombo .= "<option value='" . $source['id'] . "'>" . $source['sourceName'] . "</option>";
        }

        echo $domainsCombo;
    }

    public function getBulkAction() {
        $bulk_id = $this->request->get('bulk_id');

        $mainstream = new MainstreamHistory;
        $res = $mainstream->getBulkInfo($bulk_id);

        echo json_encode($res);
    }

    public function getBannersAction() {
        $bulk_id = $this->request->get('bulk_id');

        $mainstream = new MainstreamHistory;
        $res = $mainstream->getBannersInfo($bulk_id);

        echo json_encode($res);
    }

    public function editBulkAction() {
        $array_data = array(
            'bulk_id' => $this->request->getPost('bulk_id'),
            'domain' => $this->request->getPost('domain'),
            'source' => $this->request->getPost('source'),
            'platform' => $this->request->getPost('platform'),
            'os' => $this->request->getPost('os'),
            'age' => $this->request->getPost('age'),
            'genre' => $this->request->getPost('genre'),
            'banners' => $this->request->getPost('banners'),
            'country' => $this->request->getPost('country'),
            'njump' => $this->request->getPost('njump'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description')
        );

        $mainstream = new MainstreamHistory;
        $mainstream->edit_bulk($array_data);

        echo $this->tableBulk();
    }

    public function downloadZipAction() {

        $bulk = $this->request->get('bulk');

        $mainstream = new MainstreamHistory;
        $res = $mainstream->getBannersInfo($bulk);

        Cloudinary::config(array(
            "cloud_name" => "precosmart",
            "api_key" => "349486951711411",
            "api_secret" => "5wIYG-YwgLGVcjnRZlhYHDKrdEk"
        ));
        $api = new \Cloudinary\Api();

        $public_ids = array();
        foreach ($res as $banner) {
            $div = explode("_", $banner['hash']);

            array_push($public_ids, "mainstream_banners/main_banners/$div[1]");
        }

        //add tags
        $result = \Cloudinary\Uploader::add_tag('bulk_' . $bulk, $public_ids);

        //download zip
        $urlarr = \Cloudinary\Uploader::multi('bulk_' . $bulk, array('format' => 'zip'));

        echo $urlarr['url'];

        //remove tags
        $result = \Cloudinary\Uploader::remove_tag('bulk_' . $bulk, $public_ids);
    }

    public function downloadXlsxAction() {


        $bulk_id = $this->request->get('bulk');

        ini_set("memory_limit", "3000M");

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("mobistein");
        $objPHPExcel->getProperties()->setLastModifiedBy('mobistein');
        $objPHPExcel->getProperties()->setTitle('MainstreamBulk_' . $bulk_id);
        $objPHPExcel->getProperties()->setSubject('MainstreamBulk_' . $bulk_id);
        $objPHPExcel->getProperties()->setDescription('Auto-generated');
        $alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU');
        $startRow = 1;

        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[0] . $startRow, 'Campaign ID');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, 'Campaign Name');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, 'Campaign Status');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, 'Campaign Objective');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[4] . $startRow, 'Buying Type');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[5] . $startRow, 'Campaign Spend Limit');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[6] . $startRow, 'Tags');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[7] . $startRow, 'Ad Set ID');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[8] . $startRow, 'Ad Set Run Status');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[9] . $startRow, 'Ad Set Name');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[10] . $startRow, 'Ad Set Time Start');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[11] . $startRow, 'Ad Set Time Stop');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[12] . $startRow, 'Ad Set Daily Budget');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[13] . $startRow, 'Ad Set Lifetime Budget');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[14] . $startRow, 'Countries');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[15] . $startRow, 'Cities');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[16] . $startRow, 'Regions');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[17] . $startRow, 'Zip');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[18] . $startRow, 'Gender');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[19] . $startRow, 'Age Min');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[20] . $startRow, 'Age Max');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[21] . $startRow, 'Education Status');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[22] . $startRow, 'College Start Year');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[23] . $startRow, 'College End Year');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[24] . $startRow, 'Interested In');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[25] . $startRow, 'Relationship');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[26] . $startRow, 'Connections');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[27] . $startRow, 'Excluded Connections');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[28] . $startRow, 'Friends of Connections');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[29] . $startRow, 'Locales');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[30] . $startRow, 'Broad Category Clusters');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[31] . $startRow, 'Custom Audiences');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[32] . $startRow, 'Excluded Custom Audiences');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[33] . $startRow, 'Automatically Set Bid');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[34] . $startRow, 'Use Average Bid');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[35] . $startRow, 'Optimization Goal');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[36] . $startRow, 'Billing Event');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[37] . $startRow, 'Bid Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[38] . $startRow, 'Page Types');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[39] . $startRow, 'Link Object ID');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[40] . $startRow, 'Link');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[41] . $startRow, 'Application ID');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[42] . $startRow, 'Ad ID');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[43] . $startRow, 'Ad Status');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[44] . $startRow, 'Ad Name');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[45] . $startRow, 'Title');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[46] . $startRow, 'Body');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[47] . $startRow, 'Link Description');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[48] . $startRow, 'Display Link');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[49] . $startRow, 'Image Hash');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[50] . $startRow, 'Creative Type');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[51] . $startRow, 'URL Tags');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[52] . $startRow, 'Image');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[53] . $startRow, 'Creative Optimization');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[54] . $startRow, 'Product 1 - Link');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[55] . $startRow, 'Product 1 - Name');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[56] . $startRow, 'Product 1 - Description');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[57] . $startRow, 'Product 1 - Image Hash');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[58] . $startRow, 'Product 2 - Link');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[59] . $startRow, 'Product 2 - Name');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[60] . $startRow, 'Product 2 - Description');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[61] . $startRow, 'Product 2 - Image Hash');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[62] . $startRow, 'Product 3 - Link');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[63] . $startRow, 'Product 3 - Name');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[64] . $startRow, 'Product 3 - Description');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[65] . $startRow, 'Product 3 - Image Hash');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[66] . $startRow, 'Call to Action');
        $objPHPExcel->getActiveSheet()->SetCellValue($alpha[67] . $startRow, 'Story ID');

        $mainstreamBulkInfo = new MainstreamHistory;
        $resultBulk = $mainstreamBulkInfo->getBulkPlatformAndDevice($bulk_id);

        if ($resultBulk[0]['platform'] == '1' || $resultBulk[0]['platform'] == '2') {  //Mobile e Instagram
            //android
            if ($resultBulk[0]['os'] == '1') {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[68] . $startRow, 'Publisher Platforms');
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[69] . $startRow, 'Facebook Positions');
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[70] . $startRow, 'Device Platforms');
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[71] . $startRow, 'User Device');
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[72] . $startRow, 'Flexible Inclusions');
            } else {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[68] . $startRow, 'Flexible Inclusions');
            }
        }

        $mainstream = new MainstreamHistory;
        $result = $mainstream->getMainHistoryRows($bulk_id);

        $array_domains_id = $mainstream->getDomainsFacebook();

        foreach ($result as $row) {

            $startRow++;

            if ($resultBulk[0]['platform'] == '1' || $resultBulk[0]['platform'] == '2') {  //Mobile e Instagram
                //android
                if ($resultBulk[0]['os'] == '1') {
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[68] . $startRow, 'facebook feed');
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[69] . $startRow, 'right_hand_column desktop');
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[70] . $startRow, 'mobile ');
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[71] . $startRow, 'Android_Smartphone');
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[72] . $startRow, '[{"behaviors":[{"id":"6017253511583","name":"Ligação 3G"},{"id":"6017253531383","name":"Ligação 4G"}]}]');
                } else {
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[68] . $startRow, '[{"behaviors":[{"id":"6017253511583","name":"Ligação 3G"},{"id":"6017253531383","name":"Ligação 4G"}]}]');
                }
            }

            //default
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[2] . $startRow, 'ACTIVE');

            if ($resultBulk[0]['source'] == '1117' || $resultBulk[0]['source'] == '1137') {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, 'Traffic');
            } else {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[3] . $startRow, 'Clicks to Website');
            }

            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[4] . $startRow, 'AUCTION');
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[12] . $startRow, '10');
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[35] . $startRow, 'LINK_CLICKS');
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[36] . $startRow, 'LINK_CLICKS');

            //dots = 1
            if ($resultBulk[0]['dots'] == 1) {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[37] . $startRow, '0.05');
                //dots = 2
            } else {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[37] . $startRow, '0,05');
            }


            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[43] . $startRow, 'ACTIVE');
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[66] . $startRow, 'LEARN_MORE');
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[40] . $startRow, 'Page Post Ad');
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[8] . $startRow, 'ACTIVE');
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[50] . $startRow, 'Page Post Ad');

            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[45] . $startRow, $row['title']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[46] . $startRow, $row['description']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[14] . $startRow, $row['country']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[18] . $startRow, ($row['genre'] == "0") ? "Male" : "Female");
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[19] . $startRow, $row['MIN_age']);
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[20] . $startRow, ($row['MAX_age'] == '0') ? NULL : $row['MAX_age']);

            if ($bulk_id == '2789') {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[10] . $startRow, date("d-m-Y H:i", strtotime('-1 hour')));
            } else {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[10] . $startRow, date("d-m-Y H:i"));
            }

            switch ($row['platform']) {
                case "0":
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[38] . $startRow, "desktopfeed");
                    break;
                case "1":
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[38] . $startRow, "mobilefeed");
                    break;
                case "2":
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[38] . $startRow, "instagramstream");
                    break;
            }

            //print_r($array_domains_id[$row['domain']]);

            if ($row['country'] == "TH" && $row['domain'] == '5') {
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($alpha[39] . $startRow, $array_domains_id['5-TH'][1], PHPExcel_Cell_DataType::TYPE_STRING);
            } else {
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($alpha[39] . $startRow, $array_domains_id[$row['domain']][1], PHPExcel_Cell_DataType::TYPE_STRING);
            }

            $url = $array_domains_id[$row['domain']][0] . "n/?p=" . $row['burl'];
            $objPHPExcel->getActiveSheet()->SetCellValue($alpha[40] . $startRow, $url);




            switch ($resultBulk[0]['type']) {
                //type 1
                case 1:
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[9] . $startRow, $row['groupz'] . "_" . $row['ad']);
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, $row['groupz'] . "_" . $row['ad']);
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[44] . $startRow, $row['burl']);
                    break;
                //type 2
                case 2:
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[9] . $startRow, $row['sub_id']);  //'Ad Set Name'
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, $row['sub_id']);   //'Campaign Name'
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[44] . $startRow, $row['sub_id']);  //'Ad Name'
                    break;
                //type 3
                case 3:
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[9] . $startRow, $row['sub_id']);
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, $row['sub_id']);
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[44] . $startRow, $url);
                    break;
                //type 4
                case 4:
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[9] . $startRow, $row['groupz'] . "_" . $row['ad']);
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[1] . $startRow, $row['groupz'] . "_" . $row['ad']);
                    $objPHPExcel->getActiveSheet()->SetCellValue($alpha[44] . $startRow, $url);
                    break;
            }

            $array_bulks_jpg = $mainstream->getBannersJpg();
            $banner_url = explode("_", $row['banner_cloudi']);

            if (in_array($banner_url[1], $array_bulks_jpg)) {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[52] . $startRow, $banner_url[1] . ".jpg");
            } else {
                $objPHPExcel->getActiveSheet()->SetCellValue($alpha[52] . $startRow, $banner_url[1] . ".png");
            }
        }


        $objPHPExcel->getActiveSheet()->freezePane('BP2');

        header('Content-Type: application/vnd.ms-excel');
        $myContentDispositionHeader = 'Content-Disposition: attachment;filename="MainStream_' . time() . '.xlsx"';
        //$myContentDispositionHeader = 'Content-Disposition: attachment;filename="MainStream_' . time() . '.csv"';
        header($myContentDispositionHeader);
        header('Cache-Control: max-age=0');

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

}

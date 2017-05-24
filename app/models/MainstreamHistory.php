<?php

use Phalcon\Mvc\Model;

class MainstreamHistory extends Model {

    //id, source, domain, countries, category, njump, group, ad, banner, insertDate
    public function initialize() {
        $this->setConnectionService('db');
        $this->setSource('MainstreamHistory');
    }

    public function getLanguagesAndCategories() {
        $statement = $this->getDi()->getDb()->prepare('SELECT id, value FROM Banners_categories');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result0 = $res->fetchAll(PDO::FETCH_ASSOC);

        $statement = $this->getDi()->getDb()->prepare('SELECT id, value FROM Banners_languages');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result1 = $res->fetchAll(PDO::FETCH_ASSOC);

        $languages = array();
        $categories = array();

        foreach ($result0 as $category) {
            $categories[$category['id']] = $category['value'];
        }

        foreach ($result1 as $language) {
            $languages[$language['id']] = $language['value'];
        }


        return [$languages, $categories];
    }

    public function insertBanners($newbanners) {

        $date = date("Y-m-d H:i:s");

        $languagesAndCategories = $this->getLanguagesAndCategories();

        $languages = $languagesAndCategories[0];
        $categories = $languagesAndCategories[1];

        $values = "";
        foreach ($newbanners as $key => $value) {

           
            $infoexploded = explode('_', $value['name']);

            if(!in_array($infoexploded[0], $languages)) {
                echo "Language not found";
                return;

                if(!in_array($infoexploded[1], $categories)) {
                    echo "Category not found";
                    return;
                }
            }



            $values .= '("' . $value['hash'] . '","' . array_search($infoexploded[0], $languages) . '","' . array_search($infoexploded[1], $categories) . '","' . $value['url'] . '","' . $value['name'] . '","' . $date . '"),';
        }
        $values = substr($values, 0, -1);

        $statement = $this->getDi()->getDb()->prepare('INSERT INTO Banners_mainstream (hash, language, category, url_cloudinary, id_user, insertdate) VALUES ' . $values);
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());

        return 0;
    }

    public function searchBanners($array_data) {

        /*
        $where = " WHERE ";
        if ($array_data['language'] != 0)
            $where .= 'language="' . $array_data['language'] . '"';

        if ($array_data['category'] != 0 && $array_data['language'] != 0 && $array_data['inputID'] == '')
            $where .= ' AND category="' . $array_data['category'] . '"';
        else if ($array_data['category'] != 0 && $array_data['language'] != 0 && $array_data['inputID'] != '')
            $where .= ' AND category="' . $array_data['category'] . '" AND id_user ="' . $array_data['inputID'] . '"';

        else if ($array_data['category'] != 0 && $array_data['language'] == 0)
            $where .= ' category="' . $array_data['category'] . '"';
        else {

        }

        $page = 32 * $array_data['page'];
        $array_ret = array();
        if ($array_data['category'] != 0 || $array_data['language'] != 0) {
            $statement = $this->getDi()->getDb()->prepare('SELECT * from Banners_mainstream ' . $where . ' ORDER BY id DESC LIMIT 32 OFFSET ' . $page);
            $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        }

        return $array_ret;
        */

        $where = " ";
        if ($array_data['language'] != 0)
            $where .= ' AND language="' . $array_data['language'] . '"';

        if ($array_data['category'] != 0)
            $where .= ' AND category="' . $array_data['category'] . '"';

        if ($array_data['inputID'] != '')
            $where .= ' AND id_user LIKE "%' . $array_data['inputID'] . '%"';

        $page = 32 * $array_data['page'];
        $array_ret = array();
        if ($array_data['category'] != 0 || $array_data['language'] != 0 || $array_data['inputID'] != '') {
            $statement = $this->getDi()->getDb()->prepare('SELECT * from Banners_mainstream WHERE 0=0 ' . $where . ' ORDER BY id DESC LIMIT 32 OFFSET ' . $page);
            $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        }

        return $array_ret;
    }

    public function editBannerId($array_data) {

        $statement = $this->getDi()->getDb()->prepare('UPDATE Banners_mainstream SET id_user="' . $array_data['newId'] . '" WHERE hash = "' . $array_data['hash'] . '"');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());

    }

    public function getSources($auth) {


        $sources = explode(",", $auth['invest']);

        $sources_in = '';
        foreach ($sources as $key => $value) {
            $sources_in .= '"' . $value . '",';
        }
        $sources_in = substr($sources_in, 0, -1);

        $statement = $this->getDi()->getDb()->prepare('SELECT id, sourceName FROM tinas__Sources WHERE id IN (' . $sources_in . ')');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function editBanner($array_data) {

        $statement = $this->getDi()->getDb()->prepare('UPDATE Banners_mainstream SET language="' . $array_data['language'] . '" , category="' . $array_data['category'] . '" WHERE hash="' . $array_data['hash'] . '"');
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
    }

    public function getCountriesByDomain($domain) {
        $statement = $this->getDi()->getDb()->prepare('SELECT countries FROM Domains WHERE id="' . $domain . '"');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_bulk($array_data) {

        $array_ages = array(
            '0' => array(13, 17),
            '1' => array(18, 24),
            '2' => array(25, 34),
            '3' => array(35, 44),
            '4' => array(45, 54),
            '5' => array(55, 64),
            '6' => array(65, '')
        );

        $min_age = 0;
        $max_age = 0;
        if (count($array_data['age']) > 1) {
            //more than one
            $first = reset($array_data['age']);
            $last = end($array_data['age']);

            $min_age = $array_ages[$first][0];
            $max_age = $array_ages[$last][1];
        } else {
            //one
            $first = reset($array_data['age']);

            $min_age = $array_ages[$first][0];
            $max_age = $array_ages[$first][1];
        }

        $insertdate = date("Y-m-d H:i:s");

        $statement = $this->getDi()->getDb()->prepare('INSERT INTO Bulks_mainstream(country, source, njump, domain, genre, platform, os, MIN_age, MAX_age, title, description, insertdate) VALUES ("' . $array_data['country'] . '" , "' . $array_data['source'] . '" , "' . $array_data['njump'] . '" , "' . $array_data['domain'] . '" , "' . $array_data['genre'] . '" , "' . $array_data['platform'] . '" , "' . $array_data['os'] . '" , "' . $min_age . '" , "' . $max_age . '" , "' . $array_data['title'] . '" , "' . $array_data['description'] . '" , "' . $insertdate . '")');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());

        $stmt = $this->getDi()->getDb()->query("SELECT MAX(id) AS lastId FROM Bulks_mainstream");
        $lastId = $stmt->fetch(PDO::FETCH_NUM);

        $this->insertMainHistory($array_data, $lastId['lastId']);
    }

    public function insertMainHistory($array_data, $lastId, $to_add = NULL, $edit = 0) {
        $domain = $array_data['domain'];
        $source = $array_data['source'];
        $country = $array_data['country'];
        $category = 0;
        $njump = $array_data['njump'];
        $groupz = $this->getHightestGroup($njump, $source);
        $banner = 'NULL';
        $bulk = $lastId;
        $insertDate = date("Y-m-d");
        //$new_groupz = ($edit == 1) ? $groupz : ($groupz + 1);
        $ad = $this->getHightestAd($njump, $groupz);

        $values = "";
        $arrayforuse = ($edit == 0) ? $array_data['banners'] : $to_add;
        foreach ($arrayforuse as $key => $value) {
            $ad++;

            $burl = $this->generateBurl($domain, $source, $groupz, $ad, $njump);
            $sub_id = $source . "_" . $groupz . "_" . $ad;
            $banner_cloudi = $value;
            $state = 0;  //0 -> active, 1 -> innactive

            $values .= "('" . $source . "','" . $domain . "','" . $country . "','" . $category . "','" . $njump . "','" . $groupz . "','" . $ad . "','" . $bulk . "','" . $banner_cloudi . "','" . $burl . "','" . $sub_id . "','" . $state . "','" . $insertDate . "') ,";
        }
        $values = substr($values, 0, -1);

        //echo 'INSERT INTO MainstreamHistory ( source, domain, countries, category, njump, groupz, ad, bulk, banner_cloudi, burl, sub_id, state, insertdate ) VALUES ' . $values;

        $statement2 = $this->getDi()->getDb()->prepare('INSERT INTO MainstreamHistory ( source, domain, countries, category, njump, groupz, ad, bulk, banner_cloudi, burl, sub_id, state, insertdate ) VALUES ' . $values);
        $res = $this->getDi()->getDb()->executePrepared($statement2, array(), array());
    }

    private function generateBurl($domain, $source, $new_groupz, $ad, $njump) {
        $toencode = $source . '_' . $new_groupz . '_' . $ad . '_' . $njump;

        $coded = base64_encode($toencode);

        return $coded;
    }

    public function getSourcesByDomain($domain, $invest) {
        $statement = $this->getDi()->getDb()->prepare('SELECT doma.sources FROM Domains doma WHERE doma.id="' . $domain . '"');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        $sources = explode(",", $result[0]['sources']);

        $sources_in = '';
        foreach ($sources as $key => $value) {
            $sources_in .= '"' . $value . '",';
        }
        $sources_in = substr($sources_in, 0, -1);

        $statement = $this->getDi()->getDb()->prepare('SELECT id, sourceName FROM tinas__Sources WHERE id IN (' . $sources_in . ') AND id IN ( ' . $this->filterToString($invest) . ' ) ');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    private function getHightestGroup($njump, $source = NULL) {
        $hightestGroup = 0;

        $statement = $this->getDi()->getDb()->prepare('SELECT MAX(groupz) as hightestGroup FROM MainstreamHistory WHERE njump="' . $njump . '" AND source="' . $source . '"');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        if ($result[0]['hightestGroup'] == "" || !isset($result[0]['hightestGroup'])) {
            $statement = $this->getDi()->getDb()->prepare('SELECT MAX(groupz) as hightestGroup FROM MainstreamHistory WHERE source="' . $source . '"');
            $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
            $result = $res->fetchAll(PDO::FETCH_ASSOC);

            $hightestGroup = $result[0]['hightestGroup'] + 1;
        } else {
            $hightestGroup = $result[0]['hightestGroup'];
        }

        return $hightestGroup;
    }

    private function getHightestAd($njump, $groupz) {
        $statement = $this->getDi()->getDb()->prepare('SELECT MAX(ad) as hightestAd FROM MainstreamHistory WHERE njump="' . $njump . '" AND groupz="' . $groupz . '"');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        return ($result[0]['hightestAd'] == "") ? 0 : $result[0]['hightestAd'];
    }

    public function getTableBulk($sources) {
        //$statement = $this->getDi()->getDb()->prepare('SELECT * FROM Bulks_mainstream ORDER BY insertdate ASC' );
        $statement = $this->getDi()->getDb()->prepare("SELECT bm.id, bm.country, bm.source, bm.njump, bm.domain, bm.genre, bm.platform, bm.os, bm.MIN_age, bm.MAX_age, bm.title, bm.description, bm.insertdate, dm.domain, mc.lpName, count(bulk) as bulkLines FROM Bulks_mainstream bm INNER JOIN Domains dm ON dm.id=bm.domain LEFT JOIN tinas__MultiClick mc ON mc.hashMask = bm.njump INNER JOIN MainstreamHistory mh ON bm.id=mh.bulk WHERE state='0' AND bm.source IN (" . $this->filterToString($sources) . ") GROUP BY bulk ORDER BY `bm`.`id`  DESC");

        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    private function filterToString($value) {
        $values = explode(',', $value);
        $valuesString = '';
        foreach ($values as $value) {
            $valuesString .= "'" . $value . "',";
        }
        $valuesString = rtrim($valuesString, ",");

        return $valuesString;
    }

    public function getDomainById($id) {
        $statement = $this->getDi()->getDb()->prepare('SELECT id, domain FROM Domains WHERE id IN (' . $id . ')');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        $array_result = array();
        foreach ($result as $line) {
            if (isset($line['domain'])) {
                array_push($array_result, array(
                    'id' => $line['id'],
                    'domain' => $line['domain']
                ));
            } else {
                array_push($array_result, array(
                    'id' => 0,
                    'domain' => $line['domain']
                ));
            }
        }

        return $array_result;
    }

    public function getNumberLines($bulk_id) {
        $statement = $this->getDi()->getDb()->prepare('SELECT bulk, COUNT(*) AS lin FROM MainstreamHistory WHERE state="0" AND bulk IN (' . $bulk_id . ') GROUP BY bulk');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        $array_result = array();
        foreach ($result as $line) {
            if (isset($line['lin'])) {
                array_push($array_result, array(
                    'line' => $line['lin'],
                    'bulk' => $line['bulk']
                ));
            } else {
                array_push($array_result, array(
                    'line' => 0,
                    'bulk' => $line['bulk']
                ));
            }
        }

        return $array_result;
    }

    public function getNJumpName($bulk_njump) {
        $statement = $this->getDi()->getDb4()->prepare('SELECT hashMask , lpName FROM MultiClick WHERE hashMask IN (' . $bulk_njump . ')');
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        $array_result = array();
        foreach ($result as $line) {
            if (isset($line['lpName']))
                array_push($array_result, array(
                    'lpname' => $line['lpName'],
                    'hash' => $line['hashMask']
                ));
        }

        return $array_result;
    }

    public function getBulkInfo($bulk_id) {
        $statement = $this->getDi()->getDb()->prepare('SELECT * FROM Bulks_mainstream WHERE id="' . $bulk_id . '"');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBannersInfo($bulk_id) {
        $statement = $this->getDi()->getDb()->prepare('SELECT bm.hash, bm.url_cloudinary, state FROM Banners_mainstream bm INNER JOIN MainstreamHistory ON bm.hash = banner_cloudi WHERE bulk="' . $bulk_id . '"');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());

        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function edit_bulk($array_data) {

        //update bulks_mainstream
        $values = '';
        $values .= 'domain="' . $array_data['domain'] . '",';
        $values .= 'source="' . $array_data['source'] . '",';
        $values .= 'platform="' . $array_data['platform'] . '",';
        $values .= 'os="' . $array_data['os'] . '",';
        $values .= 'genre="' . $array_data['genre'] . '",';
        $values .= 'country="' . $array_data['country'] . '",';
        $values .= 'njump="' . $array_data['njump'] . '",';
        $values .= 'title="' . $array_data['title'] . '",';
        $values .= 'description="' . $array_data['description'] . '",';


        $array_ages = array(
            '0' => array(13, 17),
            '1' => array(18, 24),
            '2' => array(25, 34),
            '3' => array(35, 44),
            '4' => array(45, 54),
            '5' => array(55, 64),
            '6' => array(65, '')
        );

        $min_age = 0;
        $max_age = 0;
        if (count($array_data['age']) > 1) {
            //more than one
            $first = reset($array_data['age']);
            $last = end($array_data['age']);

            $min_age = $array_ages[$first][0];
            $max_age = $array_ages[$last][1];
        } else {
            //one
            $first = reset($array_data['age']);

            $min_age = $array_ages[$first][0];
            $max_age = $array_ages[$first][1];
        }


        $values .= 'MIN_age="' . $min_age . '",';
        $values .= 'MAX_age="' . $max_age . '"';

        $statement = $this->getDi()->getDb()->prepare('UPDATE Bulks_mainstream SET ' . $values . ' WHERE id="' . $array_data['bulk_id'] . '"');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());


        //update mainstream_history
        $statement = $this->getDi()->getDb()->prepare('SELECT * FROM MainstreamHistory WHERE bulk="' . $array_data['bulk_id'] . '"');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        $values2 = '';
        foreach ($result as $key => $value) {
            $values2 .= 'domain="' . $array_data['domain'] . '",';
            $values2 .= 'source="' . $array_data['source'] . '",';
            $values2 .= 'countries="' . $array_data['country'] . '",';
            $values2 .= 'njump="' . $array_data['njump'] . '",';
            $values2 .= 'burl="' . $this->generateBurl($array_data['domain'], $array_data['source'], $value['groupz'], $value['ad'], $array_data['njump']) . '",';
            $values2 .= 'sub_id="' . $array_data['source'] . "_" . $value['groupz'] . "_" . $value['ad'] . '"';

            $statement = $this->getDi()->getDb()->prepare('UPDATE MainstreamHistory SET ' . $values2 . ' WHERE bulk="' . $array_data['bulk_id'] . '"');
            $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());

            $values2 = '';
        }

        //banners changed
        $oldLines = array();
        $to_add = array();
        $to_delete = array();
        foreach ($result as $key => $value) {
            array_push($oldLines, $value['banner_cloudi']);

            if (!in_array($value['banner_cloudi'], $array_data['banners'])) {
                array_push($to_delete, $value['banner_cloudi']);
            }
        }

        foreach ($array_data['banners'] as $key2 => $value2) {
            if (!in_array($value2, $oldLines)) {
                array_push($to_add, $value2);
            }
        }

        if (count($to_add) > 0) {
            $this->insertMainHistory($array_data, $array_data['bulk_id'], $to_add, 1);
        }

        if (count($to_delete) > 0) {
            $this->disableMainHistory($array_data, $array_data['bulk_id'], $to_delete);
        }
    }

    private function disableMainHistory($array_data, $bulk_id, $to_delete) {
        foreach ($to_delete as $key => $value) {
            $statement = $this->getDi()->getDb()->prepare('UPDATE MainstreamHistory SET state=1 WHERE bulk="' . $bulk_id . '" AND banner_cloudi="' . $value . '"');
            $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        }
    }

    public function getBannersJpg() {
        $statement = $this->getDi()->getDb()->prepare("SELECT * FROM Banners_mainstream WHERE  url_cloudinary LIKE  '%.jpg%'");
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        $array_images_jpg = array();
        foreach ($result as $image) {
            $banner_url = explode("_", $image['hash']);

            $array_images_jpg[] = $banner_url[1];
        }

        return $array_images_jpg;
    }

    public function getMainHistoryRows($bulk_id) {
        $statement = $this->getDi()->getDb()->prepare('SELECT * , bm.title, bm.description FROM MainstreamHistory INNER JOIN Bulks_mainstream bm ON bulk=bm.id WHERE state="0" AND bulk="' . $bulk_id . '"');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDomainsFacebook() {
        $statement = $this->getDi()->getDb()->prepare('SELECT * FROM Domains');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        /*
          $array_domains_id = array(
          '5-TH' => array('http://walking-alone.com/','1532998560326212'),
          );
         */

        $array_domains_id = array();
        foreach ($result as $domainFace) {
            $array_domains_id[$domainFace['id']] = array($domainFace['domain'], $domainFace['facebookPageId']);
        }

        return $array_domains_id;
    }

    public function getBulkPlatformAndDevice($bulk_id) {
        $statement = $this->getDi()->getDb()->prepare('SELECT os, platform, bm.source, type, dots FROM Bulks_mainstream bm INNER JOIN Bulks_exceltype be ON bm.source = be.source WHERE bm.id=' . $bulk_id );
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

}

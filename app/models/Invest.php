<?php

use Phalcon\Mvc\Model;

class Invest extends Model {

    public function initialize() {
        $this->setSource("I__InvestMetadata");
    }

    public function get_clicks_today($source, $metacountry) {

        $subid = 'CONCAT(fkSource,"_",format,"_",adNumber)';
        $group = 'fkSource,format,adNumber';
        if ($metacountry == 1) {
            $subid = 'CONCAT(countryCode,"_",fkSource,"_",format,"_",adNumber)';
            $group = 'countryCode,fkSource,format,adNumber';
        }
        $statement = $this->getDi()->getDb2()->prepare('SELECT ' . $subid . ' AS sub_id, COUNT(*) AS clicks_m FROM (SELECT * FROM ClicksDaily  WHERE fkSource=:source GROUP BY uniqid) AS t1 GROUP BY ' . $group);
        $res = $this->getDi()->getDb2()->executePrepared($statement, array('source' => $source), array('source' => \Phalcon\Db\Column::TYPE_INTEGER));
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSourcesUserInvest($auth) {

        $invest = $auth['invest'];

        if($invest == '' || $auth['id'] == 21) {
            $statement = $this->getDi()->getDb4()->prepare('SELECT id, sourceName, affiliate FROM Sources WHERE affiliate=2');
            $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            return $res->fetchAll(PDO::FETCH_ASSOC);
        }

        $investresult = '';
        $invest = explode(',', $invest);
        foreach ($invest as $eachinvest) {
            $investresult .= $eachinvest . ",";
        }
        $investresult = trim($investresult, ",");

        $statement = $this->getDi()->getDb4()->prepare('SELECT id, sourceName, affiliate FROM Sources WHERE id IN ( ' . $investresult . ' ) ');
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_conversions_today($source, $metacountry) {

        $subid = 'CONCAT(fkSource,"_",format,"_",adNumber)';
        $group = 'fkSource,format,adNumber';
        if ($metacountry == 1) {
            $subid = 'CONCAT(countryCode,"_",fkSource,"_",format,"_",adNumber)';
            $group = 'countryCode,fkSource,format,adNumber';
        }
        $statement = $this->getDi()->getDb2()->prepare('SELECT ' . $subid . ' AS sub_id, COUNT(*) AS conversions, SUM(ccpa) AS revenue FROM (SELECT * FROM ConversionsDaily WHERE fkSource=:source GROUP BY clickId) AS t1 GROUP BY ' . $group);
        $res = $this->getDi()->getDb2()->executePrepared($statement, array('source' => $source), array('source' => \Phalcon\Db\Column::TYPE_INTEGER));
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_clicks_date($source, $date, $metacountry) {
        $subid = 'sub_id';
        $group = 'sub_id,insert_date';
        if ($metacountry == 1) {
            $subid = 'CONCAT(campaign_country,"_",sub_id) AS sub_id';
            $group = 'campaign_country,sub_id,insert_date';
        }
        //echo 'SELECT '.$subid.',clicks AS clicks_m,conversions, SUM(revenue) AS revenue FROM  Agr__MainReport WHERE source=:source AND insert_date=:date GROUP BY '.$group;
        $statement = $this->getDi()->getDb()->prepare('SELECT ' . $subid . ',SUM(clicks) AS clicks_m,SUM(conversions) AS conversions, SUM(revenue) AS revenue FROM  Agr__MainReport WHERE source=:source AND insert_date=:date GROUP BY ' . $group);
        $res = $this->getDi()->getDb()->executePrepared($statement, array('source' => $source, 'date' => $date), array('source' => \Phalcon\Db\Column::TYPE_INTEGER, 'date' => \Phalcon\Db\Column::TYPE_VARCHAR));
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_clicks_dateNexus($source, $date, $metacountry) {
        $subid = 'subid AS sub_id';
        $group = 'subid,insertDate,country';
        if ($metacountry == 1) {
            $subid = 'subid AS sub_id';
            $group = 'campaign_country,subid,insertDate';
        }
        //echo 'SELECT '.$subid.',clicks AS clicks_m,conversions, SUM(revenue) AS revenue FROM  Agr__MainReport WHERE source=:source AND insert_date=:date GROUP BY '.$group;
        $statement = $this->getDi()->getDb4()->prepare('SELECT ' . $subid . ',SUM(clicks) AS clicks_m,campaign_country AS country , SUM(conversions) AS conversions, SUM(revenue) AS revenue FROM  MainReport WHERE source=:source AND insertDate=:date GROUP BY ' . $group);
        $res = $this->getDi()->getDb4()->executePrepared($statement, array('source' => $source, 'date' => $date), array('source' => \Phalcon\Db\Column::TYPE_INTEGER, 'date' => \Phalcon\Db\Column::TYPE_VARCHAR));
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_rate($currency) {

        $statement = $this->getDi()->getDb4()->prepare('SELECT rate FROM  CurrencyHistory WHERE currency=:cur ORDER BY insertDate DESC, id DESC LIMIT 1');
        $res = $this->getDi()->getDb4()->executePrepared($statement, array('cur' => $currency), array('cur' => \Phalcon\Db\Column::TYPE_VARCHAR));
        $rateres = $res->fetchAll(PDO::FETCH_ASSOC);
        return $rateres[0]['rate'];
    }

    public function insert_report($sql, $date, $source) {
        //echo $sql;

        $this->getDi()->getDb()->query('DELETE FROM I__investReport WHERE insert_date="' . $date . '" AND source="' . $source . '"');

        $this->getDi()->getDb()->query($sql);
    }

    public function getInfoMainstream($sub_ids) {

        $statement = $this->getDi()->getDb()->prepare('SELECT mh.bulk, mh.sub_id ,bm.title, bm.description , bm.platform, bm.os, bm.genre, bm.country, bannm.url_cloudinary FROM MainstreamHistory mh LEFT JOIN Bulks_mainstream bm ON mh.bulk = bm.id LEFT JOIN Banners_mainstream bannm ON mh.banner_cloudi = bannm.hash WHERE mh.sub_id IN ( ' . $sub_ids . ' )');

        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());

        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function get_report($sdate, $edate, $source) {

        $s = '';
        if ($source != 0) {

            $s = ' AND source IN (' . implode(',', $source) . ') ';
        }

        $statement = $this->getDi()->getDb()->prepare('SELECT subid,country, investment,impressions,clicks_source,clicks_mobistein,conversions,revenue,ctr,cpc,roi,cr,margin,source,insert_date FROM  I__investReport WHERE insert_date>=:sdate AND insert_date<=:edate ' . $s . ' ORDER BY insert_date,subid,source');
        $res = $this->getDi()->getDb()->executePrepared($statement, array('edate' => $edate, 'sdate' => $sdate), array('edate' => \Phalcon\Db\Column::TYPE_VARCHAR, 'sdate' => \Phalcon\Db\Column::TYPE_VARCHAR));
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountries() {
        $statement = $this->getDi()->getDb()->prepare('SELECT DISTINCT country FROM  I__investReport ORDER BY country ASC');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSources($auth) {
        $usersources = $auth['sources'];
        $userutype = $auth['utype'];

        if($auth['id'] == 21) {
            unset($usersources);
        }

        $statement = $this->getDi()->getDb4()->prepare('SELECT id, sourceName FROM  Sources WHERE affiliate="2" ORDER BY id ASC');
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        if ($userutype == 2 || $userutype == 0) {
            if (!isset($usersources)) {
                return $result;
            } else if (isset($usersources)) {

                $pieces = explode(",", $usersources);

                foreach ($result as $sourceKey => $sourceValue) {

                    if (!in_array($sourceValue['id'], $pieces)) {
                        unset($result[$sourceKey]);
                    }
                }

                return $result;
            }
        }
    }

    public function getDomains() {
        //$statement = $this->getDi()->getDb()->prepare( 'SELECT DISTINCT MainstreamHistory.domain AS id, Domains.domain FROM MainstreamHistory INNER JOIN Domains ON MainstreamHistory.domain = Domains.id ORDER BY id ASC' );

        $statement = $this->getDi()->getDb()->prepare('SELECT id, domain FROM Domains');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLanguagesAndCategories() {
        $statement = $this->getDi()->getDb()->prepare('SELECT id, name FROM Banners_categories');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result0 = $res->fetchAll(PDO::FETCH_ASSOC);

        $statement = $this->getDi()->getDb()->prepare('SELECT id, name FROM Banners_languages');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result1 = $res->fetchAll(PDO::FETCH_ASSOC);

        return [$result1, $result0];
    }

    public function getCampaigns() {
        $statement = $this->getDi()->getDb()->prepare('SELECT DISTINCT MainstreamHistory.njump, tinas__MultiClick.lpName FROM MainstreamHistory INNER JOIN tinas__MultiClick ON MainstreamHistory.njump = tinas__MultiClick.hashMask ORDER BY lpName ASC');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCampaignsByCountry($country) {
        $statement = $this->getDi()->getDb()->prepare('SELECT DISTINCT MainstreamHistory.njump, tinas__MultiClick.lpName FROM MainstreamHistory INNER JOIN tinas__MultiClick ON MainstreamHistory.njump = tinas__MultiClick.hashMask AND tinas__MultiClick.c_country = "' . $country . '" ORDER BY lpName ASC');
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilterContent($data_array) {

        //print_r($data_array);

        $limit = 30;
        $totalPages = "";

        if (!isset($data_array['excel'])) {
            $currentLimit = $data_array['currentLimit'];
        }

        $col = $data_array['col'];
        $order = $data_array['order'];
        $search = $data_array['search'];

        $groupBy = ' GROUP BY mainh.sub_id, mainh.countries';
        if ($data_array['agregation'] != '') {
            $groupBy = ' GROUP BY  ';
            $array_group = array(
                '1' => 'mainh.countries, invre.country' /* '' */,
                '2' => 'invre.source',
                '3' => 'mainh.domain',
                '4' => 'mainh.njump',
                '5' => 'invre.insert_date',
                '7' => 'invre.gender',
                '6' => 'invre.platform'
            );

            foreach ($data_array['agregation'] as $line) {
                $groupBy .= $array_group[$line] . " ,";
            }

            $groupBy = substr($groupBy, 0, -1);
        }


        $sql = array();
        if ($data_array['sdate'] != '' && $data_array['edate'] != '') {
            array_push($sql, " invre.insert_date BETWEEN '" . $data_array['sdate'] . "' AND '" . $data_array['edate'] . "' ");
            //array_push($sql, " mainh.insertdate BETWEEN '" . $data_array['sdate'] . "' AND '" . $data_array['edate'] . "' ");
        }

        if ($data_array['search'] != '') {
            //array_push($sql, ' mainh.groupz LIKE "%' . $data_array['search'] . '%" OR mainh.ad LIKE "%' . $data_array['search'] . '%" OR mainh.njump LIKE "%' . $data_array['search'] . '%" OR mainh.sub_id LIKE "%' . $data_array['search'] . '%" OR mainh.source LIKE "%' . $data_array['search'] . '%"');
            array_push($sql, ' bannersMain.user_id LIKE "%' . $data_array['search'] . '%"');
        }

        if (isset($data_array['source']) && $data_array['source'] != '' && $data_array['source'][0] != 'ALL') {
            $sources = '';
            foreach ($data_array['source'] as $line) {
                $sources .= "'" . $line . "',";
            }
            $sources = substr($sources, 0, -1);

            array_push($sql, ' mainh.source IN (' . $sources . ')');
        }

        if (isset($data_array['country']) && $data_array['country'] != '' && $data_array['country'][0] != 'ALL') {
            $countries = '';
            foreach ($data_array['country'] as $line) {
                $countries .= "'" . $line . "',";
            }
            $countries = substr($countries, 0, -1);

            array_push($sql, ' mainh.countries IN (' . $countries . ') AND invre.country IN (' . $countries . ')');
        }

        /*
          if($data_array['domain'] != '') {
          array_push($sql, ' mainh.domain="' . $data_array['domain'] . '"');
          }

          if($data_array['campaign'] != '') {
          array_push($sql, ' mainh.njump="' . $data_array['campaign'] . '"');
          }
         */

        if (isset($data_array['os']) && $data_array['os'] != '' && $data_array['os'][0] != 'ALL') {
            $os = '';
            foreach ($data_array['os'] as $line) {
                $os .= "'" . $line . "',";
            }
            $os = substr($os, 0, -1);

            array_push($sql, ' invre.os IN (' . $os . ')');
        }

        if (isset($data_array['platform']) && $data_array['platform'] != '' && $data_array['platform'][0] != 'ALL') {
            $platform = '';
            foreach ($data_array['platform'] as $line) {
                $platform .= "'" . $line . "',";
            }
            $platform = substr($platform, 0, -1);

            array_push($sql, ' invre.platform IN (' . $platform . ')');
        }

        if (isset($data_array['gender']) && $data_array['gender'] != '' && $data_array['gender'][0] != 'ALL') {
            $gender = '';
            foreach ($data_array['gender'] as $line) {
                $gender .= "'" . $line . "',";
            }
            $gender = substr($gender, 0, -1);

            array_push($sql, ' invre.gender IN (' . $gender . ')');
        }

        $sqlString = '';
        if (!empty($sql)) {
            $lastElement = end($sql);
            $sqlString = " WHERE ";
            foreach ($sql as $key => $value) {
                if ($value == $lastElement) {
                    $sqlString .= $value;
                } else {
                    $sqlString .= $value . ' AND';
                }
            }
        }


        $limit_sql = '';
        $fields = '';
        if (!isset($data_array['excel'])) {
            $limit_sql = ' LIMIT ' . $limit . ' OFFSET ' . $currentLimit;
            /*
              $fields = ' mainh.groupz, mainh.ad, mainh.banner, invre.investment, invre.revenue, invre.impressions, invre.cr, invre.ctr, invre.cpc, invre.clicks_source, invre.conversions, invre.country, mainh.domain, mainh.njump, mainh.insertdate, invre.source,invre.margin,invre.roi, invre.subid ';
             */
            $fields = 'mainh.groupz,mainh.ad,mainh.banner,SUM(invre.investment) as investment,SUM(invre.revenue) as revenue,SUM(invre.impressions) as impressions,(SUM(invre.conversions) / SUM(invre.clicks_source)) * 100  as cr, (SUM(invre.clicks_source) / SUM(invre.impressions)) as ctr, (SUM(invre.investment) / SUM(invre.clicks_source)) as cpc, SUM(invre.clicks_source) as clicks_source, SUM(invre.conversions) as conversions, invre.country, mainh.domain, mainh.njump, invre.insert_date as insertdate, invre.source, (SUM(invre.revenue) - SUM(invre.investment)) as margin, (((SUM(invre.revenue) - SUM(invre.investment))) / SUM(invre.investment)) * 100 as roi, invre.subid, invre.platform,invre.gender,invre.title,invre.description, invre.url_cloudinary, invre.os';
        } else {
            /*
              $fields = ' mainh.groupz, mainh.ad, invre.investment, invre.revenue, invre.impressions, invre.cr, invre.ctr, invre.cpc, invre.clicks_source,invre.country, mainh.domain, mainh.njump, mainh.insertdate, invre.source , invre.margin,invre.roi, invre.subid ';
             */
            $fields = 'mainh.groupz,mainh.ad,SUM(invre.investment) as investment,SUM(invre.revenue) as revenue,SUM(invre.impressions) as impressions,(SUM(invre.conversions) / SUM(invre.clicks_source)) * 100  as cr, (SUM(invre.clicks_source) / SUM(invre.impressions)) as ctr, (SUM(invre.investment) / SUM(invre.clicks_source)) as cpc, SUM(invre.clicks_source) as clicks_source, invre.country, mainh.domain, mainh.njump, invre.insert_date as insertdate, invre.source, (SUM(invre.revenue) - SUM(invre.investment)) as margin, (((SUM(invre.revenue) - SUM(invre.investment))) / SUM(invre.investment)) * 100  as roi, invre.subid, invre.platform,invre.gender,invre.title,invre.description, invre.os';
        }

        $statement = $this->getDi()->getDb()->prepare('SELECT ' . $fields . ' FROM I__investReport invre INNER JOIN MainstreamHistory mainh ON invre.subid = mainh.sub_id AND invre.country = mainh.countries LEFT JOIN Banners_mainstream bannersMain ON mainh.banner_cloudi = bannersMain.hash ' . $sqlString . ' ' . $groupBy . ' ORDER BY ' . $data_array['col'] . ' ' . $data_array['order'] . $limit_sql);
        $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        //mail('andre.vieira@mobipium.com', 'query', 'SELECT ' . $fields . ' FROM I__investReport invre INNER JOIN MainstreamHistory mainh ON invre.subid = mainh.sub_id AND invre.country = mainh.countries LEFT JOIN Banners_mainstream bannersMain ON mainh.banner_cloudi = bannersMain.hash ' . $sqlString . ' ' . $groupBy . ' ORDER BY ' . $data_array['col'] . ' ' . $data_array['order'] . $limit_sql);


        //echo 'SELECT ' . $fields . ' FROM I__investReport invre INNER JOIN MainstreamHistory mainh ON invre.subid = mainh.sub_id AND invre.country = mainh.countries' . $sqlString . ' ' . $groupBy . ' ORDER BY ' . $data_array['col'] . ' ' . $data_array['order'] . $limit_sql;

        $statement2 = $this->getDi()->getDb()->prepare('SELECT COUNT(*) as totalRows FROM I__investReport invre INNER JOIN MainstreamHistory mainh ON invre.subid = mainh.sub_id' . $sqlString . ' ' . $groupBy);
        $res2 = $this->getDi()->getDb()->executePrepared($statement2, array(), array());
        $result2 = $res2->fetchAll(PDO::FETCH_ASSOC);

        $totalPages = ceil(count($result2) / 30);

        return array($result, $totalPages);
    }

    public function getTotals($data_array) {
        $sql = array();
        if ($data_array['sdate'] != '' && $data_array['edate'] != '') {
            array_push($sql, " invre.insert_date BETWEEN '" . $data_array['sdate'] . "' AND '" . $data_array['edate'] . "' ");
            //array_push($sql, " mainh.insertdate BETWEEN '" . $data_array['sdate'] . "' AND '" . $data_array['edate'] . "' ");
        }

        if ($data_array['search'] != '') {
            //array_push($sql, ' mainh.groupz LIKE "%' . $data_array['search'] . '%" OR mainh.ad LIKE "%' . $data_array['search'] . '%" OR mainh.njump LIKE "%' . $data_array['search'] . '%" OR mainh.sub_id LIKE "%' . $data_array['search'] . '%" OR mainh.source LIKE "%' . $data_array['search'] . '%"');
            array_push($sql, ' bannersMain.user_id LIKE "%' . $data_array['search'] . '%"');
        }

        if (isset($data_array['source']) && $data_array['source'] != '' && $data_array['source'][0] != 'ALL') {
            $sources = '';
            foreach ($data_array['source'] as $line) {
                $sources .= "'" . $line . "',";
            }
            $sources = substr($sources, 0, -1);

            array_push($sql, ' mainh.source IN (' . $sources . ')');
        }

        if (isset($data_array['country']) && $data_array['country'] != '' && $data_array['country'][0] != 'ALL') {
            $countries = '';
            foreach ($data_array['country'] as $line) {
                $countries .= "'" . $line . "',";
            }
            $countries = substr($countries, 0, -1);

            array_push($sql, ' mainh.countries IN (' . $countries . ') AND invre.country IN (' . $countries . ')');
        }

        if (isset($data_array['gender']) && $data_array['gender'] != '' && $data_array['gender'][0] != 'ALL') {
            $genders = '';
            foreach ($data_array['gender'] as $line) {
                $genders .= "'" . $line . "',";
            }
            $genders = substr($genders, 0, -1);

            array_push($sql, ' invre.gender IN (' . $genders . ')');
            //array_push($sql, ' mainh.countries IN (' . $countries . ') AND invre.country IN (' . $countries . ')');
        }

        /*
          if($data_array['domain'] != '') {
          array_push($sql, ' mainh.domain="' . $data_array['domain'] . '"');
          }

          if($data_array['campaign'] != '') {
          array_push($sql, ' mainh.njump="' . $data_array['campaign'] . '"');
          }
         */

        $sqlString = '';
        if (!empty($sql)) {
            $lastElement = end($sql);
            $sqlString = " WHERE ";
            foreach ($sql as $key => $value) {
                if ($value == $lastElement) {
                    $sqlString .= $value;
                } else {
                    $sqlString .= $value . ' AND';
                }
            }
        }


        $limit_sql = '';
        $fields = '';
        if (!isset($data_array['excel'])) {

            $fields = 'SUM(invre.investment) as investment,SUM(invre.revenue) as revenue,SUM(invre.impressions) as impressions,(SUM(invre.conversions) / SUM(invre.clicks_source)) * 100  as cr, (SUM(invre.clicks_source) / SUM(invre.impressions)) as ctr, (SUM(invre.investment) / SUM(invre.clicks_source)) as cpc, SUM(invre.clicks_source) as clicks_source, SUM(invre.conversions) as conversions, (SUM(invre.revenue) - SUM(invre.investment)) as margin, (((SUM(invre.revenue) - SUM(invre.investment))) / SUM(invre.investment)) * 100 as roi';

            $statement = $this->getDi()->getDb()->prepare('SELECT ' . $fields . ' FROM I__investReport invre INNER JOIN MainstreamHistory mainh ON invre.subid = mainh.sub_id AND invre.country = mainh.countries LEFT JOIN Banners_mainstream bannersMain ON mainh.banner_cloudi = bannersMain.hash ' . $sqlString);
            $res = $this->getDi()->getDb()->executePrepared($statement, array(), array());
            $result = $res->fetchAll(PDO::FETCH_ASSOC);


            //echo 'SELECT ' . $fields . ' FROM I__investReport invre INNER JOIN MainstreamHistory mainh ON invre.subid = mainh.sub_id' . $sqlString;

            return $result;
        }
    }

}

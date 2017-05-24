<?php

use Phalcon\Mvc\Model;

class Dashboard extends Model {

    public function initialize() {
        error_reporting(E_ERROR | E_PARSE);
    }

    //638 642 43 44 50
    public function get_last7Days($type, $array_data, $auth) {
        $date = date('Y-m-d', strtotime('-7 days'));
        $today = date('Y-m-d');

        if ($type == 1) {

            $exception = '';
            $exception2 = '';
            if ($auth['id'] == '2') {
                $exception = ' AND affiliate="0"';
                //$exception2 = ' WHERE affiliate="0" AND insertDate="' . $today . '"';
            }

            //last 7 days
            $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM MainReport INNER JOIN Sources ON source=Sources.id WHERE insertDate >=  "' . $date . '"' . $exception . ' GROUP BY insertDate');

            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

            /*
              //today
              //clicks
              $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate AS date, SUM( clicks ) AS clicks FROM MainReport INNER JOIN Sources ON source=Sources.id ' . $exception2);
              $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
              $array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);

              //conversions
              $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate AS date, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM MainReport INNER JOIN Sources ON source=Sources.id ' . $exception2);
              $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
              $array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);


              /*
              //last 7 days
              $statement = $this->getDi()->getDb()->prepare('SELECT insert_date as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM Agr__MainReport INNER JOIN tinas__Sources ON source=tinas__Sources.id WHERE insert_date >=  "' . $date . '"' . $exception . ' GROUP BY insert_date');

              $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
              $array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);


              //today
              //clicks
              $statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS clicks FROM ClicksDaily INNER JOIN Sources ON fkSource=Sources.id ' . $exception2);
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);


              //conversions
              $statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS conversions, SUM( ccpa ) AS revenue FROM ConversionsDaily INNER JOIN Sources ON fkSource=Sources.id ' . $exception2);
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */


            foreach ($array_ret as $key => $value) {
                $cr = $value['conversions'] / $value['clicks'];

                $array_ret[$key]['cr'] = $cr;

                $array_ret[$key]['revenue'] = number_format($array_ret[$key]['revenue'], 2, '.', '');
                $array_ret[$key]['cr'] = number_format($array_ret[$key]['cr'] * 100, 3, '.', '');
            }

            //$cr = $array_ret_conversions[0]['conversions'] / $array_ret_clicks[0]['clicks'];
            //array_push($array_ret, array('date' => $array_ret_clicks[0]['date'] , 'clicks' => $array_ret_clicks[0]['clicks'], 'conversions' => $array_ret_conversions[0]['conversions'], 'revenue' => number_format($array_ret_conversions[0]['revenue'], 2, '.', ''), 'cr' => $cr * 100));

            return $array_ret;
        } else if ($type == 2) {

            $sql = '';

            if ($array_data[0]['sources'] != '') {
                $sources = str_replace(',', '","', $auth['sources']);
                $sources = ' AND source IN ("' . $sources . '")';
                $sql .= $sources;
            }

            if ($array_data[0]['countries'] != '') {
                $countries = str_replace(',', '","', $auth['countries']);
                $countries = ' AND campaign_country IN ("' . $countries . '")';
                $sql .= $countries;
            }

            if ($array_data[0]['aggregators'] != '') {
                $aggregators = str_replace(',', '","', $auth['aggregators']);
                $aggregators = ' AND agregator IN ("' . $aggregators . '")';
                $sql .= $aggregators;
            }

            /*
              //last 7 days

              $statement = $this->getDi()->getDb()->prepare('SELECT insert_date as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM Agr__MainReport INNER JOIN tinas__Sources ON source=tinas__Sources.id WHERE insert_date >=  "' . $date . '" ' . $sql . ' AND affiliate="0" GROUP BY insert_date');
              $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
              $array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);

             */

            //last 7 days
            $sqlstr = 'SELECT insertDate as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM MainReport INNER JOIN Sources ON source=Sources.id WHERE insertDate >=  "' . $date . '" ' . $sql . ' AND affiliate="0" GROUP BY insertDate';
            //mail('pedrorleonardo@gmail.com', 'str', $sqlstr);
            $statement = $this->getDi()->getDb4()->prepare($sqlstr);

            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

            /*
              //today
              //clicks
              $sql = '';
              if($array_data[0]['sources'] != '') {
              $sources = str_replace(',', '","', $auth['sources']);
              $sources = (($sql != "")) ? (' AND source IN ("' . $sources . '")') : (' WHERE source IN ("' . $sources . '")');
              $sql .= $sources;
              }

              if($array_data[0]['countries'] != '') {
              $countries = str_replace(',', '","', $auth['countries']);
              $countries = (($sql != "")) ? (' AND campaign_country IN ("' . $countries . '")') : (' WHERE campaign_country IN ("' . $countries . '")');
              $sql .= $countries;
              }

              if($array_data[0]['aggregators'] != '') {
              $aggregators = str_replace(',', '","', $auth['aggregators']);
              $aggregators = (($sql != "")) ? (' AND agregator IN ("' . $aggregators . '")') : (' WHERE agregator IN ("' . $aggregators . '")');
              $sql .= $aggregators;
              }

              $date = (($sql != "")) ? (' AND insertDate="' . $today . '"') : (' WHERE insertDate ="' . $today . '"');
              $sql .= $date;

              $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate AS date, COUNT( * ) AS clicks FROM MainReport ' . $sql);
              $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
              $array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);

              /*
              $statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS clicks FROM ClicksDaily ' . $sql);
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);

              //conversions
              $statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS conversions, SUM( ccpa ) AS revenue FROM ConversionsDaily ' . $sql);
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);


              //conversions
              $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate AS date, COUNT( * ) AS conversions, SUM( revenue ) AS revenue FROM MainReport ' . $sql);
              $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
              $array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            foreach ($array_ret as $key => $value) {
                $cr = $value['conversions'] / $value['clicks'];

                $array_ret[$key]['cr'] = $cr;

                $array_ret[$key]['revenue'] = number_format($array_ret[$key]['revenue'], 2, '.', '');
                $array_ret[$key]['cr'] = number_format($array_ret[$key]['cr'] * 100, 3, '.', '');
            }

            //$cr = ($array_ret_clicks[0]['clicks'] == 0) ? 0 : ($array_ret_conversions[0]['conversions'] / ($array_ret_clicks[0]['clicks']));
            //array_push($array_ret, array('date' => $array_ret_clicks[0]['date'] , 'clicks' => $array_ret_clicks[0]['clicks'], 'conversions' => $array_ret_conversions[0]['conversions'], 'revenue' => number_format($array_ret_conversions[0]['revenue'], 2, '.', ''), 'cr' => $cr * 100));

            return $array_ret;
        } else if ($type == 3) {
            //last 7 days
            /*
              $statement = $this->getDi()->getDb()->prepare('SELECT insert_date as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM Agr__MainReport INNER JOIN tinas__Sources ON source=tinas__Sources.id WHERE insert_date >=  "' . $date . '" AND affiliate="1" GROUP BY insert_date');
              $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
              $array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */
            $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM MainReport INNER JOIN Sources ON source=Sources.id WHERE insertDate >=  "' . $date . '" AND affiliate="1" GROUP BY insertDate');

            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

            /*
              //today
              //clicks
              /*
              $statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS clicks FROM ClicksDaily INNER JOIN Sources ON fkSource=Sources.id WHERE affiliate="1"');
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);

              $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate AS date, COUNT( * ) AS clicks FROM MainReport INNER JOIN Sources ON source=Sources.id WHERE affiliate="1" AND insertDate="' . $today . '"');
              $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
              $array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            //conversions
            /*
              $statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS conversions, SUM( ccpa ) AS revenue FROM ConversionsDaily INNER JOIN Sources ON fkSource=Sources.id WHERE affiliate="1"');
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);

              $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate AS date, COUNT( * ) AS conversions, SUM( revenue ) AS revenue FROM MainReport INNER JOIN Sources ON source=Sources.id WHERE affiliate="1" AND insertDate="' . $today . '"');
              $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
              $array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            foreach ($array_ret as $key => $value) {
                $cr = $value['conversions'] / (($value['clicks'] == 0) ? 1 : $value['clicks']);

                $array_ret[$key]['cr'] = $cr;

                $array_ret[$key]['revenue'] = number_format($array_ret[$key]['revenue'], 2, '.', '');
                $array_ret[$key]['cr'] = number_format($array_ret[$key]['cr'] * 100, 3, '.', '');
            }

            //$cr = ($array_ret_clicks[0]['clicks'] == 0) ? 0 : ($array_ret_conversions[0]['conversions'] / ($array_ret_clicks[0]['clicks']));
            //array_push($array_ret, array('date' => $array_ret_clicks[0]['date'] , 'clicks' => $array_ret_clicks[0]['clicks'], 'conversions' => $array_ret_conversions[0]['conversions'], 'revenue' => number_format($array_ret_conversions[0]['revenue'], 2, '.', ''), 'cr' => $cr * 100));

            return $array_ret;
        } else if ($type == 4) {


            $sqlDb = '';
            $sqlDb2 = '';
            if ($array_data[0]['sources'] != '') {
                $sources = str_replace(',', '","', $auth['sources']);
                $sqlDb .= ' AND source IN ("' . $sources . '") ';
                //$sqlDb2 .= ' AND source IN ("' . $sources . '") AND insertDate="' . $today . '"';
            }

            //last 7 days
            /*
              $statement = $this->getDi()->getDb()->prepare('SELECT insert_date as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM Agr__MainReport INNER JOIN tinas__Sources ON source=tinas__Sources.id WHERE insert_date >=  "' . $date . '" AND affiliate="2" ' . $sqlDb . ' GROUP BY insert_date');
              $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
              $array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM MainReport INNER JOIN Sources ON source=Sources.id WHERE insertDate >=  "' . $date . '" AND affiliate="2" ' . $sqlDb . ' GROUP BY insertDate');

            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);


            /*
              //today
              //clicks
              /*
              $statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS clicks FROM ClicksDaily INNER JOIN Sources ON fkSource=Sources.id WHERE affiliate="2" ' . $sqlDb2);
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);

              $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate AS date, COUNT( * ) AS clicks FROM MainReport INNER JOIN Sources ON source=Sources.id WHERE affiliate="2" ' . $sqlDb2);
              $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
              $array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */
            //conversions
            /*
              $statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS conversions, SUM( ccpa ) AS revenue FROM ConversionsDaily INNER JOIN Sources ON fkSource=Sources.id WHERE affiliate="2" ' . $sqlDb2);
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);

              $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate AS date, COUNT( * ) AS conversions, SUM( revenue ) AS revenue FROM MainReport INNER JOIN Sources ON source=Sources.id WHERE affiliate="2" ' . $sqlDb2);
              $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
              $array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            foreach ($array_ret as $key => $value) {
                $cr = $value['conversions'] / (($value['clicks'] == 0) ? 1 : $value['clicks']);

                $array_ret[$key]['cr'] = $cr;

                $array_ret[$key]['revenue'] = number_format($array_ret[$key]['revenue'], 2, '.', '');
                $array_ret[$key]['cr'] = number_format($array_ret[$key]['cr'] * 100, 3, '.', '');
            }

            //$cr = $array_ret_conversions[0]['conversions'] / $array_ret_clicks[0]['clicks'];
            //array_push($array_ret, array('date' => $array_ret_clicks[0]['date'] , 'clicks' => $array_ret_clicks[0]['clicks'], 'conversions' => $array_ret_conversions[0]['conversions'], 'revenue' => number_format($array_ret_conversions[0]['revenue'], 2, '.', ''), 'cr' => $cr * 100));

            return $array_ret;
        } else if ($type == 5) {

            $sqlDb = '';
            $sqlDb2 = '';
            if ($array_data[0]['aggregators'] != '') {
                $aggregators = str_replace(',', '","', $auth['aggregators']);
                $sqlDb .= ' AND agregator IN ("' . $aggregators . '") ';
                //$sqlDb2 .= ' WHERE agregator IN ("' . $aggregators . '") AND insertDate="' . $today . '"';
            }

            //last 7 days
            /*
              $statement = $this->getDi()->getDb()->prepare('SELECT insert_date as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM Agr__MainReport WHERE insert_date >=  "' . $date . '" ' . $sqlDb . ' GROUP BY insert_date');
              $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
              $array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            $sqlstr = 'SELECT insertDate as date, SUM( clicks ) AS clicks, SUM( conversions ) AS conversions, SUM( revenue ) AS revenue FROM MainReport INNER JOIN Sources ON source=Sources.id WHERE insertDate >=  "' . $date . '" ' . $sqlDb . ' GROUP BY insertDate';
            $statement = $this->getDi()->getDb4()->prepare($sqlstr);
            //mail('pedrorleonardo@gmail.com', 'sql', $sqlstr);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

            /*
              //today
              //clicks

              $statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS clicks FROM ClicksDaily INNER JOIN Sources ON fkSource=Sources.id ' . $sqlDb2);
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);

              $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate AS date, COUNT( * ) AS clicks FROM MainReport INNER JOIN Sources ON source=Sources.id ' . $sqlDb2);
              $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
              $array_ret_clicks =  $exe->fetchAll(PDO::FETCH_ASSOC);


              //conversions
              /*
              $statement = $this->getDi()->getDb2()->prepare('SELECT insertdate AS date, COUNT( * ) AS conversions, SUM( ccpa ) AS revenue FROM ConversionsDaily INNER JOIN Sources ON fkSource=Sources.id ' . $sqlDb2);
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);

              $statement = $this->getDi()->getDb4()->prepare('SELECT insertDate AS date, COUNT( * ) AS conversions, SUM( revenue ) AS revenue FROM MainReport INNER JOIN Sources ON source=Sources.id ' . $sqlDb2);
              $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
              $array_ret_conversions =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            foreach ($array_ret as $key => $value) {
                $cr = $value['conversions'] / (($value['clicks'] == 0) ? 1 : $value['clicks']);

                $array_ret[$key]['cr'] = $cr;

                $array_ret[$key]['revenue'] = number_format($array_ret[$key]['revenue'], 2, '.', '');
                $array_ret[$key]['cr'] = number_format($array_ret[$key]['cr'] * 100, 3, '.', '');
            }

            //$cr = $array_ret_conversions[0]['conversions'] / $array_ret_clicks[0]['clicks'];
            //array_push($array_ret, array('date' => $array_ret_clicks[0]['date'] , 'clicks' => $array_ret_clicks[0]['clicks'], 'conversions' => $array_ret_conversions[0]['conversions'], 'revenue' => number_format($array_ret_conversions[0]['revenue'], 2, '.', ''), 'cr' => $cr * 100));

            return $array_ret;
        }
    }

    public function percentShift($type, $array_data, $auth) {
        $hour = date('H', strtotime("-1 hour"));

        //dates
        if ($hour == '00') {
            $hour = '23';
            $dayYesterday = date('Y-m-d', strtotime("-2 days"));
            $day3days = date('Y-m-d', strtotime("-3 days"));
            $today = date('Y-m-d', strtotime('-1 days'));
        } else {
            $dayYesterday = date('Y-m-d', strtotime("-1 days"));
            $day3days = date('Y-m-d', strtotime("-3 days"));
            $today = date('Y-m-d');
        }

        if ($type == 1) {

            $exception = '';
            if ($auth['id'] == '2') {
                $exception = ' AND affiliate="0"';
            }

            /*
              $statement = $this->getDi()->getDb2()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN Sources ON sourceid=Sources.id  WHERE datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $exception . " group by datePeriod");
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            $statement = $this->getDi()->getDb4()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM R__HourlyCampaignSource INNER JOIN Sources ON sourceid=Sources.id  WHERE datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $exception . " group by datePeriod");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);


            //today
            /*
              $statement2 = $this->getDi()->getDb2()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN Sources ON sourceid=Sources.id  WHERE datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $exception . " group by datePeriod");
              $exe2 = $this->getDi()->getDb2()->executePrepared($statement2, array(), array());
              $array_ret2 =  $exe2->fetchAll(PDO::FETCH_ASSOC);
             */

            $statement2 = $this->getDi()->getDb4()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM R__HourlyCampaignSource INNER JOIN Sources ON sourceid=Sources.id  WHERE datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $exception . " group by datePeriod");
            $exe2 = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
            $array_ret2 = $exe2->fetchAll(PDO::FETCH_ASSOC);


            $days3clicks = 0;
            $days3conversions = 0;
            $days3revenue = 0;
            foreach ($array_ret as $element) {
                $days3clicks += $element['clicks'];
                $days3conversions += $element['conversions'];
                $days3revenue += $element['revenue'];
            }

            $days3clicks = $days3clicks / 3;
            $days3conversions = $days3conversions / 3;
            $days3revenue = $days3revenue / 3;
            $epc3days = number_format($days3revenue / $days3clicks, 3);

            if (!isset($array_ret2[0])) {
                $percentageRevenue = "0.00";
                $percentageClicks = "0.00";
                $percentageConversions = "0.00";
                $percentageepc = "0.000";
                $revenue = "0.00";
                $conversions = "0.00";
                $clicks = 0;
                $epc = "0.000";
            } else {
                $percentageRevenue = number_format(100 - (($days3revenue * 100) / $array_ret2[0]['revenue']), 2);
                $percentageClicks = number_format(100 - (($days3clicks * 100) / $array_ret2[0]['clicks']), 2);
                $percentageConversions = number_format(100 - (($days3conversions * 100) / $array_ret2[0]['conversions']), 2);
                $percentageepc = number_format(100 - (($epc3days * 100) / ($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'])), 2);
            }
            $array_result = array();
            array_push($array_result, array(
                'pshift' => array('clicks' => $percentageClicks, 'conversions' => $percentageConversions, 'revenue' => $percentageRevenue, 'epc' => $percentageepc),
                'last3days' => array('clicks' => number_format($days3clicks), 'conversions' => number_format($days3conversions, 2), 'revenue' => number_format($days3revenue, 2), 'epc' => $epc3days),
                'today' => array('clicks' => isset($clicks) ? $clicks : number_format($array_ret2[0]['clicks']), 'conversions' => isset($conversions) ? $conversions : number_format($array_ret2[0]['conversions'], 2), 'revenue' => isset($revenue) ? $revenue : number_format($array_ret2[0]['revenue'], 2), 'epc' => isset($epc) ? $epc : number_format($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'], 3))
            ));

            return $array_result;
        } else if ($type == 2) {
            //last 3 days
            $dayYesterday = date('Y-m-d', strtotime("-1 days"));
            $day3days = date('Y-m-d', strtotime("-3 days"));

            $sql = '';
            if ($array_data[0]['sources'] != '') {
                $sources = str_replace(',', '","', $auth['sources']);
                $sources = ' AND sourceid IN ("' . $sources . '")';
                $sql .= $sources;
            }

            if ($array_data[0]['countries'] != '') {
                $countries = str_replace(',', '","', $auth['countries']);
                $countries = ' AND Mask.country IN ("' . $countries . '")';
                $sql .= $countries;
            }

            if ($array_data[0]['aggregators'] != '') {
                $aggregators = str_replace(',', '","', $auth['aggregators']);
                $aggregators = ' AND agregator IN ("' . $aggregators . '")';
                $sql .= $aggregators;
            }

            /*
              $statement = $this->getDi()->getDb2()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN Mask ON hashMask=hash WHERE datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $sql ." group by datePeriod");
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            $statement = $this->getDi()->getDb4()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM R__HourlyCampaignSource INNER JOIN Mask ON Mask.hash=hashMask INNER JOIN Sources ON Sources.id=sourceid WHERE datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $sql . " AND affiliate='0' group by datePeriod");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

            //today
            /*
              $today = date('Y-m-d');
              $statement2 = $this->getDi()->getDb2()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN Mask ON hashMask=hash WHERE datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $sql . " group by datePeriod");
              $exe2 = $this->getDi()->getDb2()->executePrepared($statement2, array(), array());
              $array_ret2 =  $exe2->fetchAll(PDO::FETCH_ASSOC);
             */

            $sqlstring = "SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM R__HourlyCampaignSource INNER JOIN Mask ON Mask.hash=hashMask INNER JOIN Sources ON Sources.id=sourceid WHERE datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $sql . " AND affiliate='0' group by datePeriod";
            $statement2 = $this->getDi()->getDb4()->prepare($sqlstring);
            //mail('pedrorleonardo@gmail.com', 'sqlstr', $sqlstring);
            $exe2 = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
            $array_ret2 = $exe2->fetchAll(PDO::FETCH_ASSOC);


            $days3clicks = 0;
            $days3conversions = 0;
            $days3revenue = 0;
            foreach ($array_ret as $element) {
                $days3clicks += $element['clicks'];
                $days3conversions += $element['conversions'];
                $days3revenue += $element['revenue'];
            }


            $days3clicks = $days3clicks / 3;
            $days3conversions = $days3conversions / 3;
            $days3revenue = $days3revenue / 3;
            $epc3days = ($days3clicks != 0) ? number_format($days3revenue / $days3clicks, 3) : 0;

//print_r($array_ret2);

            if (isset($array_ret2) && !empty($array_ret2)) {
                $percentageRevenue = ($array_ret2[0]['revenue'] == 0) ? 0 : number_format(100 - (($days3revenue * 100) / $array_ret2[0]['revenue']), 2);
                $percentageClicks = empty($array_ret2[0]['clicks']) ? 0 : number_format(100 - (($days3clicks * 100) / $array_ret2[0]['clicks']), 2);
                $percentageConversions = empty($array_ret2[0]['conversions']) ? 0 : number_format(100 - (($days3conversions * 100) / $array_ret2[0]['conversions']), 2);
                $percentageepc = (empty($array_ret2[0]['clicks']) || empty($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'])) ? 0 : number_format(100 - (($epc3days * 100) / ($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'])), 2);

                $array_result = array();
                array_push($array_result, array(
                    'pshift' => array('clicks' => $percentageClicks, 'conversions' => $percentageConversions, 'revenue' => $percentageRevenue, 'epc' => $percentageepc),
                    'last3days' => array('clicks' => number_format($days3clicks), 'conversions' => number_format($days3conversions, 2), 'revenue' => number_format($days3revenue, 2), 'epc' => $epc3days),
                    'today' => array('clicks' => number_format($array_ret2[0]['clicks']), 'conversions' => number_format($array_ret2[0]['conversions'], 2), 'revenue' => number_format($array_ret2[0]['revenue'], 2), 'epc' => number_format($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'], 3))
                ));

                return $array_result;
            } else {
                $array_result = array();

                array_push($array_result, array(
                    'pshift' => array('clicks' => 0, 'conversions' => 0, 'revenue' => 0, 'epc' => 0),
                    'last3days' => array('clicks' => number_format($days3clicks), 'conversions' => number_format($days3conversions, 2), 'revenue' => number_format($days3revenue, 2), 'epc' => $epc3days),
                    'today' => array('clicks' => 0, 'conversions' => 0, 'revenue' => 0, 'epc' => 0)
                ));

                return $array_result;
            }
        } else if ($type == 3) {

            //last 3 days
            $dayYesterday = date('Y-m-d', strtotime("-1 days"));
            $day3days = date('Y-m-d', strtotime("-3 days"));

            /*
              $statement = $this->getDi()->getDb2()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN Sources ON sourceid=Sources.id WHERE datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' AND affiliate='1' group by datePeriod");
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            $statement = $this->getDi()->getDb4()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM R__HourlyCampaignSource INNER JOIN Sources ON sourceid=Sources.id  WHERE datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' AND affiliate='1' group by datePeriod");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

            //today
            /*
              $today = date('Y-m-d');
              $statement2 = $this->getDi()->getDb2()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN Sources ON sourceid=Sources.id WHERE datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' AND affiliate='1' group by datePeriod");
              $exe2 = $this->getDi()->getDb2()->executePrepared($statement2, array(), array());
              $array_ret2 =  $exe2->fetchAll(PDO::FETCH_ASSOC);
             */

            $statement2 = $this->getDi()->getDb4()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM R__HourlyCampaignSource INNER JOIN Sources ON sourceid=Sources.id WHERE datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' AND affiliate='1' group by datePeriod");
            $exe2 = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
            $array_ret2 = $exe2->fetchAll(PDO::FETCH_ASSOC);


            $days3clicks = 0;
            $days3conversions = 0;
            $days3revenue = 0;
            foreach ($array_ret as $element) {
                $days3clicks += $element['clicks'];
                $days3conversions += $element['conversions'];
                $days3revenue += $element['revenue'];
            }

            $days3clicks = $days3clicks / 3;
            $days3conversions = $days3conversions / 3;
            $days3revenue = $days3revenue / 3;
            $epc3days = ($days3clicks != 0) ? number_format($days3revenue / $days3clicks, 3) : 0;


            $percentageRevenue = number_format(100 - (($days3revenue * 100) / $array_ret2[0]['revenue']), 2);
            $percentageClicks = ($array_ret2[0]['clicks'] != 0) ? (number_format(100 - (($days3clicks * 100) / $array_ret2[0]['clicks']), 2)) : 0;
            $percentageConversions = number_format(100 - (($days3conversions * 100) / $array_ret2[0]['conversions']), 2);
            $percentageepc = ($array_ret2[0]['clicks'] != 0) ? (number_format(100 - (($epc3days * 100) / ($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'])), 2)) : 0;

            $array_result = array();
            array_push($array_result, array(
                'pshift' => array('clicks' => $percentageClicks, 'conversions' => $percentageConversions, 'revenue' => $percentageRevenue, 'epc' => $percentageepc),
                'last3days' => array('clicks' => number_format($days3clicks), 'conversions' => number_format($days3conversions, 2), 'revenue' => number_format($days3revenue, 2), 'epc' => $epc3days),
                'today' => array('clicks' => number_format($array_ret2[0]['clicks']), 'conversions' => number_format($array_ret2[0]['conversions'], 2), 'revenue' => number_format($array_ret2[0]['revenue'], 2), 'epc' => (($array_ret2[0]['clicks'] != 0) ? (number_format($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'], 3)) : 0))
            ));

            return $array_result;
        } else if ($type == 4) {


            $sql = '';
            if ($array_data[0]['sources'] != '') {
                $sources = str_replace(',', '","', $auth['sources']);
                $sources = ' AND sourceid IN ("' . $sources . '")';
                $sql .= $sources;
            }


            //last 3 days

            /*
              $statement = $this->getDi()->getDb2()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN Sources ON sourceid=Sources.id WHERE datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' AND affiliate='2' " . $sql . " group by datePeriod");
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */
            $statement = $this->getDi()->getDb4()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM R__HourlyCampaignSource INNER JOIN Sources ON sourceid=Sources.id  WHERE datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' AND affiliate='2' " . $sql . " group by datePeriod");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

            //today
            /*
              $today = date('Y-m-d');
              $statement2 = $this->getDi()->getDb2()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN Sources ON sourceid=Sources.id WHERE datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' AND affiliate='2' " . $sql . " group by datePeriod");
              $exe2 = $this->getDi()->getDb2()->executePrepared($statement2, array(), array());
              $array_ret2 =  $exe2->fetchAll(PDO::FETCH_ASSOC);
             */

            $statement2 = $this->getDi()->getDb4()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM R__HourlyCampaignSource INNER JOIN Sources ON sourceid=Sources.id WHERE datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' AND affiliate='2' " . $sql . " group by datePeriod");
            $exe2 = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
            $array_ret2 = $exe2->fetchAll(PDO::FETCH_ASSOC);


            $days3clicks = 0;
            $days3conversions = 0;
            $days3revenue = 0;
            foreach ($array_ret as $element) {
                $days3clicks += $element['clicks'];
                $days3conversions += $element['conversions'];
                $days3revenue += $element['revenue'];
            }

            $days3clicks = $days3clicks / 3;
            $days3conversions = $days3conversions / 3;
            $days3revenue = $days3revenue / 3;
            $epc3days = empty($days3clicks) ? 0 : number_format($days3revenue / ($days3clicks), 3);


            $percentageRevenue = empty($array_ret2[0]['revenue']) ? '0.00' : number_format(100 - (($days3revenue * 100) / $array_ret2[0]['revenue']), 2);
            $percentageClicks = empty($array_ret2[0]['clicks']) ? 0 : number_format(100 - (($days3clicks * 100) / $array_ret2[0]['clicks']), 2);
            $percentageConversions = empty($array_ret2[0]['conversions']) ? 0 : number_format(100 - (($days3conversions * 100) / $array_ret2[0]['conversions']), 2);
            $percentageepc = empty($array_ret2[0]['clicks']) ? 0 : number_format(100 - (($epc3days * 100) / ($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'])), 2);

            $array_result = array();
            array_push($array_result, array(
                'pshift' => array('clicks' => $percentageClicks, 'conversions' => $percentageConversions, 'revenue' => $percentageRevenue, 'epc' => $percentageepc),
                'last3days' => array('clicks' => number_format($days3clicks), 'conversions' => number_format($days3conversions, 2), 'revenue' => number_format($days3revenue, 2), 'epc' => $epc3days),
                'today' => array('clicks' => empty($array_ret2[0]) ? '0' : number_format($array_ret2[0]['clicks']), 'conversions' => empty($array_ret2[0]) ? '0' : number_format($array_ret2[0]['conversions'], 2), 'revenue' => empty($array_ret2[0]) ? '0.00' : number_format($array_ret2[0]['revenue'], 2), 'epc' => empty($array_ret2[0]['clicks']) ? '0.000' : number_format($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'], 3))
            ));

            return $array_result;
        } else if ($type == 5) {

            $sql = '';
            if ($array_data[0]['aggregators'] != '') {
                $aggregators = str_replace(',', '","', $auth['aggregators']);
                $aggregators = ' AND R__HourlyCampaignSource.agregator IN ("' . $aggregators . '") ';
                $sql .= $aggregators;
            }

            //last 3 days

            /*
              $statement = $this->getDi()->getDb2()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns INNER JOIN Mask ON SourceAndCampaigns.hashmask=Mask.hash  WHERE datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $sql . " group by datePeriod");
              $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
              $array_ret =  $exe->fetchAll(PDO::FETCH_ASSOC);
             */

            $statement = $this->getDi()->getDb4()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM R__HourlyCampaignSource INNER JOIN Mask ON hashmask=Mask.hash  WHERE datePeriod BETWEEN '" . $day3days . "' AND '" . $dayYesterday . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $sql . " group by datePeriod");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

            //today
            /*
              $today = date('Y-m-d');
              $statement2 = $this->getDi()->getDb2()->prepare("SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM SourceAndCampaigns  INNER JOIN Mask ON SourceAndCampaigns.hashmask=Mask.hash  WHERE datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $sql . " group by datePeriod");
              $exe2 = $this->getDi()->getDb2()->executePrepared($statement2, array(), array());
              $array_ret2 =  $exe2->fetchAll(PDO::FETCH_ASSOC);
             */
            $sqlstr = "SELECT datePeriod, SUM(clicks) as clicks , SUM(conversions) as conversions , SUM(revenues) as revenue FROM R__HourlyCampaignSource INNER JOIN Mask ON hashmask=Mask.hash  WHERE datePeriod = '" . $today . "' AND timePeriod BETWEEN '00:00:00' AND '" . $hour . ":00:00' " . $sql . " group by datePeriod";
            //mail('pedrorleonardo@gmail.com', 'sql', $sqlstr);

            $statement2 = $this->getDi()->getDb4()->prepare($sqlstr);
            $exe2 = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
            $array_ret2 = $exe2->fetchAll(PDO::FETCH_ASSOC);



            $days3clicks = 0;
            $days3conversions = 0;
            $days3revenue = 0;
            foreach ($array_ret as $element) {
                $days3clicks += $element['clicks'];
                $days3conversions += $element['conversions'];
                $days3revenue += $element['revenue'];
            }

            $days3clicks = $days3clicks / 3;
            $days3conversions = $days3conversions / 3;
            $days3revenue = $days3revenue / 3;
            $epc3days = number_format($days3revenue / $days3clicks, 3);


            $percentageRevenue = number_format(100 - (($days3revenue * 100) / $array_ret2[0]['revenue']), 2);
            $percentageClicks = number_format(100 - (($days3clicks * 100) / $array_ret2[0]['clicks']), 2);
            $percentageConversions = number_format(100 - (($days3conversions * 100) / $array_ret2[0]['conversions']), 2);
            $percentageepc = number_format(100 - (($epc3days * 100) / ($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'])), 2);

            $array_result = array();
            array_push($array_result, array(
                'pshift' => array('clicks' => $percentageClicks, 'conversions' => $percentageConversions, 'revenue' => $percentageRevenue, 'epc' => $percentageepc),
                'last3days' => array('clicks' => number_format($days3clicks), 'conversions' => number_format($days3conversions, 2), 'revenue' => number_format($days3revenue, 2), 'epc' => $epc3days),
                'today' => array('clicks' => number_format($array_ret2[0]['clicks']), 'conversions' => number_format($array_ret2[0]['conversions'], 2), 'revenue' => number_format($array_ret2[0]['revenue'], 2), 'epc' => number_format($array_ret2[0]['revenue'] / $array_ret2[0]['clicks'], 3))
            ));

            return $array_result;
        }
    }

}

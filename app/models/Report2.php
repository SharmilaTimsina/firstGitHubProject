<?php

use Phalcon\Mvc\Model;

class Report2 extends Model {

    /**
     * Initializes correct affiliate table
     */
    public function setReport($table) {
        $this->setSource($table);
    }

    public function getMainReportResult($sdate, $edate, $country, $countries, $sources, $aff = null, $aggs = null) {
        try {
            $affRes = $this->getSourcesByAff($aff, 0);
            $db = $this->getDi()->getDb4();
            $db->query('call explode_table(",");');
            $sql = 'SELECT m.source, m.c_hash, m.subid as sub_id, m.ad, m.clicks, m.conversions, m.revenue, '
                    . 'm.agregator, m.agregator_name, m.campaign, UPPER(m.campaign_country) as campaign_country, m.insertDate as insert_date, EPC, CR,cname as Entity, CASE WHEN affiliate = 0 THEN "Adult Sources" WHEN affiliate=1 THEN "Affiliates" WHEN affiliate=2 THEN "Mainstream" ELSE "testingsource" END as aff_flag, CASE WHEN u.initials IS NULL THEN "AT" ELSE u.initials END as account '
                    . 'FROM MainReport m LEFT JOIN Sources ON Sources.id = m.source LEFT JOIN Agregators a ON m.agregator=a.id  LEFT JOIN (SELECT MIN(id) as id, value,MIN(initials) as initials FROM table2 GROUP BY value) u ON  m.agregator = u.value  WHERE insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" '
                    . ($affRes != '' ? ' AND ' . $affRes : '' )
                    . (isset($country) ? ' AND m.campaign_country IN (' . $country . ') ' : '' )
                    . (isset($countries) ? ' AND m.campaign_country IN (' . $countries . ') ' : '' )
                    . (isset($sources) ? ' AND m.source IN (' . $sources . ') ' : '' )
                    . (isset($aggs) ? ' AND m.agregator IN (' . $aggs . ') ' : '' )
                    . ' order by insert_date';


            $res = $db->query($sql)->fetchAll();

            return $res;
        } catch (Exception $ex) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n" . $ex->getMessage());
            mail('martim.barone@mobipium.com', __METHOD__, $sql . "\n" . $ex->getMessage());
        }
    }

    public function getcountriestotals($sdate, $edate, $country, $countries, $sources, $aff = null, $aggs = null) {
        try {
            $affRes = $this->getSourcesByAff($aff, 0);
            $sql = 'SELECT SUM(m.clicks) as clicks, SUM(m.conversions) as conversions, SUM(m.revenue) as revenue, '
                    . 'UPPER(m.campaign_country) as campaign_country '
                    . 'FROM MainReport m LEFT JOIN Sources ON Sources.id = m.source WHERE insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" '
                    . ($affRes != '' ? ' AND ' . $affRes : '' )
                    . (isset($country) ? ' AND m.campaign_country IN (' . $country . ') ' : '' )
                    . (isset($countries) ? ' AND m.campaign_country IN (' . $countries . ') ' : '' )
                    . (isset($sources) ? ' AND m.source IN (' . $sources . ') ' : '' )
                    . (isset($aggs) ? ' AND m.agregator IN (' . $aggs . ') ' : '' )
                    . ' GROUP BY UPPER(campaign_country) order by campaign_country';

            $res = $this->getDi()->getDb4()->query($sql)->fetchAll();

            return $res;
        } catch (Exception $ex) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n" . $ex->getMessage());
        }
    }

    public function getNewMainReportResult($sdate, $edate, $country, $countries, $sources, $aff = null, $aggs = null) {
        try {
            $affRes = $this->getSourcesByAff($aff, 0);
            $sql = 'SELECT m.subid as sub_id, SUM(m.clicks) as clicks, SUM(m.conversions) as conversions, SUM(m.revenue) as revenue, '
                    . 'm.agregator, m.agregator_name, m.campaign, UPPER(m.campaign_country) as campaign_country, EPC, CR '
                    . 'FROM MainReport m LEFT JOIN Sources ON Sources.id = m.source WHERE insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" '
                    . ($affRes != '' ? ' AND ' . $affRes : '' )
                    . (isset($country) ? ' AND m.campaign_country IN (' . $country . ') ' : '' )
                    . (isset($countries) ? ' AND m.campaign_country IN (' . $countries . ') ' : '' )
                    . (isset($sources) ? ' AND m.source IN (' . $sources . ') ' : '' )
                    . (isset($aggs) ? ' AND m.agregator IN (' . $aggs . ') ' : '' )
                    . ' GROUP BY sub_id, agregator, agregator_name, campaign, UPPER(campaign_country)'
                    . ' order by campaign, campaign_country';

            $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
            return $res;
        } catch (Exception $ex) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n" . $ex->getMessage());
        }
    }

    public function getDaysTotal2($sdate, $edate, $country, $countries, $sources, $aggs, $aff = null) {
        try {
            $affRes = $this->getSourcesByAff($aff, 0);
            $sql = 'SELECT CAST(SUM(clicks) AS UNSIGNED) as clicks, CAST(SUM(conversions) AS UNSIGNED) as conversions, SUM(revenues) as revenue
                    FROM (SELECT SUM(clicks) as clicks, sum(conversions) as conversions, SUM(conversions)/SUM(clicks) as conversionRate, SUM(revenues) as revenues, SUM(revenues)/SUM(clicks) as EPC
                        FROM R__HourlyCampaignSourceRotation r inner join `Mask` m on r.hashmask = m.hash LEFT JOIN  Sources ON Sources.id = r.sourceid
                        WHERE r.datePeriod BETWEEN "' . $sdate . '" AND "' . $edate . '" '
                    . (isset($country) ? ' AND m.country IN (' . $country . ') ' : '') . (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : '') . (isset($aggs) ? ' AND m.agregator IN (' . $aggs . ') ' : '') . (isset($sources) ? ' AND sourceid IN (' . $sources . ')' : '')
                    . ' AND ' . ($affRes != '' ? $affRes : ' 0=0 ') . ' GROUP BY datePeriod ) as temp';

            $a = new DateTime();
            $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
            $clicks = (isset($res[0]['clicks']) ? $res[0]['clicks'] : 0 );
            $conversions = (isset($res[0]['conversions']) ? $res[0]['conversions'] : 0 );
            $revenue = (isset($res[0]['revenue']) ? $res[0]['revenue'] : 0 );
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com','getDaysTotal2 took'. $interval,$sql . '   rev:'.$revenue);
            return array($clicks, $conversions, $revenue);
        } catch (Exception $ex) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n" . $ex->getMessage());
        }
    }

    public function getDayTotal2($country, $countries, $sources, $aggs, $aff = null) {
        try {
            $affRes = $this->getSourcesByAff($aff, 0);
            $sql = 'SELECT CAST(AVG(clicks) AS UNSIGNED) as clicks, CAST(AVG(conversions) AS UNSIGNED) as conversions, AVG(revenues) as revenue
                    FROM (SELECT SUM(clicks) as clicks, sum(conversions) as conversions, SUM(conversions)/SUM(clicks) as conversionRate, SUM(revenues) as revenues, SUM(revenues)/SUM(clicks) as EPC
                        FROM R__HourlyCampaignSourceRotation r inner join `Mask` m on r.hashmask = m.hash LEFT JOIN  Sources ON Sources.id = r.sourceid
                        WHERE r.datePeriod = "' . date('Y-m-d') . '" AND r.timePeriod BETWEEN "' . "00:00:00" . '" AND "' . date("H:00:00", strtotime('-1 hour')) . '" '
                    . (isset($country) ? ' AND m.country IN (' . $country . ') ' : '') . (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : '') . (isset($aggs) ? ' AND m.agregator IN (' . $aggs . ') ' : '') . (isset($sources) ? ' AND sourceid IN (' . $sources . ')' : '')
                    . ' AND ' . ($affRes != '' ? $affRes : ' 0=0 ') . ' GROUP BY datePeriod ) as temp';

            $a = new DateTime();
            $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
            $clicks = (isset($res[0]['clicks']) ? $res[0]['clicks'] : 0 );
            $conversions = (isset($res[0]['conversions']) ? $res[0]['conversions'] : 0 );
            $revenue = (isset($res[0]['revenue']) ? $res[0]['revenue'] : 0 );
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com','getDayTotal2 took'. $interval,$sql . '   rev:'.$revenue);
            return array($clicks, $conversions, $revenue);
        } catch (Exception $ex) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n" . $ex->getMessage());
        }
    }

    public function getLastDaysAvg2($sdate, $country, $countries, $sources, $aff = null, $aggs = null) {
        try {

            $edate = date("Y-m-d", strtotime(date("Y-m-d")));
            $affRes = $this->getSourcesByAff($aff, 0);
            $e = date('Y-m-d', strtotime('-1 day'));
            $sql = 'SELECT AVG(clicks) as avgClicks, AVG(conversions) as avgCon, AVG(revenues) as avgRev, AVG(EPC) as avgEPC, AVG(conversionRate) as avgCR
                    FROM (SELECT SUM(clicks) as clicks, sum(conversions) as conversions, SUM(conversions)/SUM(clicks) as conversionRate, SUM(revenues) as revenues, SUM(revenues)/SUM(clicks) as EPC
                        FROM R__HourlyCampaignSourceRotation r inner join `Mask` m on r.hashmask = m.hash LEFT JOIN  Sources ON Sources.id = r.sourceid
                        WHERE r.datePeriod BETWEEN "' . $sdate . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . "00:00:00" . '" AND "' . date("H:00:00", strtotime('-1 hour')) . '" '
                    . (isset($country) ? ' AND m.country IN (' . $country . ') ' : '') . (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : '') . (isset($aggs) ? ' AND m.agregator IN (' . $aggs . ') ' : '') . (isset($sources) ? ' AND sourceid IN (' . $sources . ')' : '')
                    . ' AND ' . ($affRes != '' ? $affRes : ' 0=0 ') . ' GROUP BY datePeriod ) as temp';
            $a = new DateTime();
            $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getLastDaysAvg2 time ' . $interval, $sql);
            return $res;
        } catch (Exception $ex) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n" . $ex->getMessage());
        }
    }

    public function getOperatorReport2($sdate, $edate, $country, $countries, $sources, $aggs, $aff = null, $simple) {
        try {

            $affRes = $this->getSourcesByAff($aff, 0);
            $db = $this->getDi()->getDb4();
            $db->query('call explode_table(",");');
            $sql = 'SELECT insertDate as insert_date, subid as sub_id, c_hash, campaign, upper(campaign_country) as campaign_country, '
                    . ' upper(user_country) as user_country, m.agregator as agregator,a.agregator as agregatorName ,source, mobileBrand as operator, '
                    . ( $simple ? ' ' : ' ISP as isp, osType as os, browserName as browser, mobileTest as mobilet, ')
                    . ' ad , conversions, '
                    . ' revenue,clicks, CASE WHEN Sources.affiliate = 0 THEN "Adult Sources" WHEN Sources.affiliate=1 THEN "Affiliates" '
                    . ' WHEN Sources.affiliate=2 THEN "Mainstream" ELSE "testingsource" END as area, CASE WHEN u.initials IS NULL THEN "AT" ELSE u.initials END as account  '
                    . ' FROM ' . ( $simple ? ' OperatorReportSimple ' : ' OperatorReport ' ) . ' m FORCE INDEX (ix_insertDate, ix_agregator,ix_sourceid) LEFT JOIN Agregators a ON m.agregator = a.id LEFT JOIN Sources as Sources ON m.source = Sources.id LEFT JOIN (SELECT MIN(id) as id, value,MIN(initials) as initials FROM table2 GROUP BY value) u ON  m.agregator = u.value '
                    . ' WHERE insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" '
                    . ($affRes != '' ? ' AND ' . $affRes : '' )
                    . (isset($country) ? ' AND m.campaign_country IN (' . $country . ') ' : '' )
                    . (isset($countries) ? ' AND m.campaign_country IN (' . $countries . ') ' : '' )
                    . (isset($sources) ? ' AND m.source IN (' . $sources . ') ' : '' )
                    . (isset($aggs) ? ' AND m.agregator IN (' . $aggs . ') ' : '' )
                    . ' ORDER BY insert_date ASC LIMIT 1000000';
            $a = new DateTime();
            $res = $db->query($sql)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'OperatorReport2 ' . $interval, $sql);
            return $res;
        } catch (Exception $e) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n" . $e->getMessage());
        }
    }

    public function getMjumpResults($auth, $sdate, $edate, $sources, $country) {

        try {
            $sql = 'SELECT insertDate,lpName,page,ad,subid,userCountry,mobileBrand as carrier,browserName as browser,mobileTest as OS,subid, fkSource as source, SUM(lpclicks) as lpClicks, SUM(clicks) AS clicks, SUM(conversions) AS conversions,SUM(revenue) AS revenue, SUM(revenue)/SUM(lpclicks) as eplpc, SUM(revenue)/SUM(clicks) as epc  '
                    . ' FROM MjumpReport r ' . ((isset($sources) || $auth['utype'] == 2 ) ? ' INNER JOIN Sources s ON s.id = r.fkSource ' . (($auth['utype'] == 2) ? ' AND s.affiliate = 2 ' : '') : ' ' )
                    . ' WHERE insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" ' . (isset($sources) ? ' AND s.id IN (' . $sources . ')' : '') . (isset($country) ? ' AND r.userCountry IN (' . $country . ')' : '') . ' GROUP BY fkSource,insertDate,lpname,page,subid,ad,userCountry,mobileBrand,browserName,mobileTest';
            //'fkSource,lpname,page,subid,ad,userCountry,mobileBrand,browserName,mobileTest,mjumpid,mjumpkey,lpclicks,clicks,conversions,revenue,insertDate'

            $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
            //mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n");
            return $res;
        } catch (Exception $e) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n" . $e->getMessage());
        }
    }

    public function getGeneralStatisticsCountryAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null, $operator = null) {
        try {
            $affRes = $this->getSourcesByAff($aff, 0);
            $sdateintime = strtotime(date('Y-m-d', strtotime($s)));
            $edateintime = strtotime(date('Y-m-d', strtotime($e)));
            $timeDiff = abs($edateintime - $sdateintime);

            $numberofdays = round($timeDiff / 86400);

            $numberofdays = ($numberofdays == 0 ? 1 : ($numberofdays + 1));
            if ($numberofdays < 0) {
                return null;
            }
            $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
            $numberofdays = ($aggFunction == 'SUM') ? 1 : $numberofdays;

            //hashmask,sourceid,agregator,operator,datePeriod,timePeriod,clicks,conversions,revenues,insertTimestamp

            $statement = 'SELECT id, name, CAST(REPLACE(FORMAT((SUM(clicks)/' . $numberofdays . '),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT((SUM(conversions)/' . $numberofdays . '),0),",","") as conversions, REPLACE(FORMAT((SUM((Conversions)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '))*100,0),",","") as conversionrate, CAST((SUM(revenues)/' . $numberofdays . ') AS UNSIGNED) as revenues, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '),4),",","") as epc FROM ('
                    . 'SELECT m.country as id, UPPER(m.country) as name, SUM(clicks) as clicks, sum(conversions) as conversions, SUM(revenues) as revenues ';
            $fromStatement = ' FROM `R__HourlyCampaignSource` r FORCE INDEX ( ix_date_time_mask_source ) inner join `Mask` m ON m.hash = r.hashmask AND r.datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" ';
            $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
            $whereStatement = ' WHERE 0=0 ';
            $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN (' . $country . ') ' : ' ');
            $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask IN (' . $campaign . ') ' : ' ');
            $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid IN (' . $source . ') ' : ' ');
            $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
            $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
            $whereStatement .= (($operator != 'allops' && isset($operator)) ? ' AND r.operator LIKE "' . $operator . '" ' : ' ');
            $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
            $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
            $whereStatement .= (isset($aggregators) ? ' AND m.agregator IN (' . $aggregators . ') ' : ' ');
            if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
                //$fromStatement .= ' inner join `Agregators` a on m.agregator = a.id ';
                $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND r.agregator IN (' . $agg . ') ' : ' ');
                $whereStatement .= (isset($aggregators) ? ' AND r.agregator IN (' . $aggregators . ') ' : ' ');
            }
            $statement .= $fromStatement . $whereStatement . ' GROUP BY r.datePeriod, id , name) B GROUP BY id ORDER BY revenues DESC ';
            //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement);
            $a = new DateTime();
            $res = $this->getDi()->getDb4()->query($statement)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getGeneralStatisticsCountryAvg time ' . $interval, $statement);
            return $res;
        } catch (Exception $e) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $statement . "\n" . $e->getMessage());
        }
    }

    public function getGeneralStatisticsSourcesAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null, $operator = null) {

        try {
            $sdateintime = strtotime(date('Y-m-d', strtotime($s)));
            $edateintime = strtotime(date('Y-m-d', strtotime($e)));
            $timeDiff = abs($edateintime - $sdateintime);

            $numberofdays = round($timeDiff / 86400);

            $numberofdays = ($numberofdays == 0 ? 1 : ($numberofdays + 1));
            if ($numberofdays < 0) {
                return null;
            }
            $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
            $numberofdays = ($aggFunction == 'SUM') ? 1 : $numberofdays;

            $affRes = $this->getSourcesByAff($aff, 0);

            $statement = 'SELECT id, name, CAST(REPLACE(FORMAT((SUM(clicks)/' . $numberofdays . '),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT((SUM(conversions)/' . $numberofdays . '),0),",","") as conversions, REPLACE(FORMAT(((SUM(Conversions)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '))*100,0),",","") as conversionrate, CAST((SUM(revenues)/' . $numberofdays . ') AS UNSIGNED) as revenues, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '),4),",","") as epc FROM ('
                    . 'SELECT r.sourceID as id, LOWER(s.sourceName) as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
            $fromStatement = ' FROM `R__HourlyCampaignSource` r  FORCE INDEX ( ix_date_time_mask_source ) inner join `Mask` m ON m.hash = r.hashmask AND r.datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" LEFT join `Sources` s on r.sourceid = s.id ';
            $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
            $whereStatement = ' WHERE 0=0 ';
            $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN (' . $country . ') ' : ' ');
            $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask IN (' . $campaign . ') ' : ' ');
            $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid IN (' . $source . ') ' : ' ');
            $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
            $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND m.agregator IN (' . $aggregators . ') ' : ' ');
            $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
            $whereStatement .= (($operator != 'allops' && isset($operator)) ? ' AND r.operator LIKE "' . $operator . '" ' : ' ');
            $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
            $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
            if ($agg != 'allaggs' && $agg != '') {
                //$fromStatement .= ' inner join `Agregators` a on m.agregator = a.id ';
                $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND r.agregator IN (' . $agg . ') ' : ' ');
                $whereStatement .= (isset($aggregators) ? ' AND r.agregator IN (' . $aggregators . ') ' : ' ');
            }
            $statement .= $fromStatement . $whereStatement . '  GROUP BY r.datePeriod , id, name) B GROUP BY id ORDER BY revenues desc LIMIT 150';
            //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement);

            $a = new DateTime();
            $res = $this->getDi()->getDb4()->query($statement)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getGeneralStatisticsSourcesAvg time ' . $interval, $statement);
            return $res;
        } catch (Exception $e) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $statement . "\n" . $e->getMessage());
        }
    }

    public function getGeneralStatisticsAggregatorsAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null, $operator = null) {
        try {
            $sdateintime = strtotime(date('Y-m-d', strtotime($s)));
            $edateintime = strtotime(date('Y-m-d', strtotime($e)));
            $timeDiff = abs($edateintime - $sdateintime);

            $numberofdays = round($timeDiff / 86400);

            $numberofdays = ($numberofdays == 0 ? 1 : ($numberofdays + 1));
            if ($numberofdays < 0) {
                return null;
            }
            $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';

            $numberofdays = ($aggFunction == 'SUM') ? 1 : $numberofdays;

            $affRes = $this->getSourcesByAff($aff, 0);

            $statement = 'SELECT id, name, CAST(REPLACE(FORMAT((SUM(clicks)/' . $numberofdays . '),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT((SUM(conversions)/' . $numberofdays . '),0),",","") as conversions, REPLACE(FORMAT(((SUM(Conversions)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '))*100,0),",","") as conversionrate, CAST((SUM(revenues)/' . $numberofdays . ') AS UNSIGNED) as revenues, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '),4),",","") as epc FROM ('
                    . 'SELECT a.id as id, LOWER(a.agregator) as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
            $fromStatement = ' FROM `R__HourlyCampaignSource` r  FORCE INDEX ( ix_date_time_mask_source ) inner join `Mask` m ON m.hash = r.hashmask AND r.datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" inner join `Agregators` a on m.agregator = a.id ';
            $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
            $whereStatement = ' WHERE 0=0 ';
            $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN (' . $country . ') ' : ' ');
            $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask IN (' . $campaign . ') ' : ' ');
            $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid IN (' . $source . ') ' : ' ');
            $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
            $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
            $whereStatement .= (($operator != 'allops' && isset($operator)) ? ' AND r.operator LIKE "' . $operator . '" ' : ' ');
            $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
            $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
            $whereStatement .= (isset($aggregators) ? ' AND r.agregator IN (' . $aggregators . ') ' : ' ');
            if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
                $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND r.agregator IN (' . $agg . ') ' : ' ');
                $whereStatement .= (isset($aggregators) ? ' AND r.agregator IN (' . $aggregators . ') ' : ' ');
            }
            $statement .= $fromStatement . $whereStatement . '  GROUP BY r.datePeriod, id, name) B GROUP BY id ORDER BY revenues desc ';
            //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement . "\n");
            $a = new DateTime();
            $res = $this->getDi()->getDb4()->query($statement)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getGeneralStatisticsAggregatorsAvg time ' . $interval, $statement);
            return $res;
        } catch (Exception $e) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $statement . "\n" . $e->getMessage());
        }
    }

    public function getGeneralStatisticsBySourcesCategory($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null, $operator = null) {
        try {
            $sdateintime = strtotime(date('Y-m-d', strtotime($s)));
            $edateintime = strtotime(date('Y-m-d', strtotime($e)));
            $timeDiff = abs($edateintime - $sdateintime);

            $numberofdays = round($timeDiff / 86400);

            $numberofdays = ($numberofdays == 0 ? 1 : ($numberofdays + 1));
            if ($numberofdays < 0) {
                return null;
            }
            $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
            $numberofdays = ($aggFunction == 'SUM') ? 1 : $numberofdays;

            $affRes = $this->getSourcesByAff($aff, 0);
            $statement = 'SELECT id, CASE WHEN name = 0 THEN "Adult Sources" WHEN name=1 THEN "Affiliates" '
                    . ' WHEN name=2 THEN "Mainstream" ELSE "testingsource" END as name, CAST(REPLACE(FORMAT((SUM(clicks)/' . $numberofdays . '),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT((SUM(conversions)/' . $numberofdays . '),0),",","") as conversions, REPLACE(FORMAT(((SUM(Conversions)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '))*100,0),",","") as conversionrate, CAST((SUM(revenues)/' . $numberofdays . ') AS UNSIGNED) as revenues, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '),4),",","") as epc FROM ('
                    . 'SELECT r.datePeriod as dateperiod, s.affiliate as id,s.affiliate as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
            $fromStatement = ' FROM `R__HourlyCampaignSource` r  FORCE INDEX ( ix_date_time_mask_source ) inner join `Mask` m ON m.hash = r.hashmask AND r.datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" inner join `Sources` s on r.sourceid = s.id ';
            $fromStatement .= ' LEFT JOIN  Sources ON Sources.id = r.sourceid ';
            $whereStatement = ' WHERE 0=0 ';
            $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN (' . $country . ') ' : ' ');
            $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask IN (' . $campaign . ') ' : ' ');
            $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid IN (' . $source . ') ' : ' ');
            $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
            $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
            $whereStatement .= (($operator != 'allops' && isset($operator)) ? ' AND r.operator LIKE "' . $operator . '" ' : ' ');
            $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
            $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
            $whereStatement .= (isset($aggregators) ? ' AND m.agregator IN (' . $aggregators . ') ' : ' ');

            if ($agg != 'allaggs' && $agg != '') {
                //$fromStatement .= ' inner join `Agregators` a on m.agregator = a.id ';
                $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND r.agregator IN (' . $agg . ') ' : ' ');
                $whereStatement .= (isset($aggregators) ? ' AND r.agregator IN (' . $aggregators . ') ' : ' ');
            }
            $statement .= $fromStatement . $whereStatement . '  GROUP BY r.datePeriod, name) B GROUP BY id ORDER BY revenues desc LIMIT 25';
            //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement);
            $a = new DateTime();
            $res = $this->getDi()->getDb4()->query($statement)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getGeneralStatisticsSourcesAvg time ' . $interval, $statement);
            return $res;
        } catch (Exception $e) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $statement . "\n" . $e->getMessage());
        }
    }

    public function getGeneralStatisticsAggCampaignsAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null, $operator = null) {
        try {
            $sdateintime = strtotime(date('Y-m-d', strtotime($s)));
            $edateintime = strtotime(date('Y-m-d', strtotime($e)));
            $timeDiff = abs($edateintime - $sdateintime);

            $numberofdays = round($timeDiff / 86400);

            $numberofdays = ($numberofdays == 0 ? 1 : ($numberofdays + 1));
            if ($numberofdays < 0) {
                return null;
            }
            $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
            $numberofdays = ($aggFunction == 'SUM') ? 1 : $numberofdays;

            $affRes = $this->getSourcesByAff($aff, 0);

            $statement = 'SELECT id, name, CAST(REPLACE(FORMAT((SUM(clicks)/' . $numberofdays . '),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT((SUM(conversions)/' . $numberofdays . '),0),",","") as conversions, REPLACE(FORMAT(((SUM(Conversions)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '))*100,0),",","") as conversionrate, CAST((SUM(revenues)/' . $numberofdays . ') AS UNSIGNED) as revenues, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '),4),",","") as epc FROM ('
                    . 'SELECT m.hash as id, m.campaign as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
            $fromStatement = ' FROM `R__HourlyCampaignSource` r  FORCE INDEX ( ix_date_time_mask_source ) inner join `Mask` m ON m.hash = r.hashmask AND r.datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '"  inner join `Agregators` a on m.agregator = a.id ';
            $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
            $whereStatement = ' WHERE 0=0 ';
            $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN (' . $country . ') ' : ' ');
            $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask IN (' . $campaign . ') ' : ' ');
            $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid IN (' . $source . ') ' : ' ');
            $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
            $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
            $whereStatement .= (($operator != 'allops' && isset($operator)) ? ' AND r.operator LIKE "' . $operator . '" ' : ' ');
            $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
            $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
            $whereStatement .= (isset($aggregators) ? ' AND m.agregator IN (' . $aggregators . ') ' : ' ');
            if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
                $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND r.agregator IN (' . $agg . ') ' : ' ');
                $whereStatement .= (isset($aggregators) ? ' AND r.agregator IN (' . $aggregators . ') ' : ' ');
            }
            $statement .= $fromStatement . $whereStatement . '  GROUP BY r.datePeriod, id, name) B GROUP BY id ORDER BY revenues desc';
            //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement . "\n");
            $a = new DateTime();
            $res = $this->getDi()->getDb4()->query($statement)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getGeneralStatisticsAggCampaignsAvg time ' . $interval, $statement);
            return $res;
        } catch (Exception $e) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $statement . "\n" . $e->getMessage());
        }
    }

    public function getGeneralStatisticsOperatorsAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $operator = null, $sourcetype = null) {
        try {
            $sdateintime = strtotime(date('Y-m-d', strtotime($s)));
            $edateintime = strtotime(date('Y-m-d', strtotime($e)));
            $timeDiff = abs($edateintime - $sdateintime);

            $numberofdays = round($timeDiff / 86400);

            $numberofdays = ($numberofdays == 0 ? 1 : ($numberofdays + 1));
            if ($numberofdays < 0) {
                return null;
            }
            $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
            $numberofdays = ($aggFunction == 'SUM') ? 1 : $numberofdays;

            $affRes = $this->getSourcesByAff($aff, 1);

            $statement = 'SELECT CONCAT(UPPER(COALESCE(country,"-")),"_",COALESCE(name,"-")) as name, CAST(REPLACE(FORMAT((SUM(clicks)/' . $numberofdays . '),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT((SUM(conversions)/' . $numberofdays . '),0),",","") as conversions, REPLACE(FORMAT(((SUM(Conversions)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '))*100,0),",","") as conversionrate, CAST((SUM(revenues)/' . $numberofdays . ') AS UNSIGNED) as revenues, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '),4),",","") as epc FROM ('
                    . 'SELECT UPPER(m.country) as country, LOWER(r.operator) as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
            $fromStatement = ' FROM `R__HourlyCampaignSource` r  FORCE INDEX ( ix_date_time_mask_source ) inner join `Mask` m ON m.hash = r.hashmask AND r.datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" ';
            $fromStatement .= (isset($aff) ? ' LEFT JOIN Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
            $whereStatement = ' WHERE 0=0 ';
            $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN (' . $country . ') ' : ' ');
            $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask IN (' . $campaign . ') ' : ' ');
            $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid IN (' . $source . ') ' : ' ');
            $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
            $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
            $whereStatement .= (($operator != 'allops' && isset($operator)) ? ' AND r.operator LIKE "' . $operator . '" ' : ' ');
            $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
            $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
            if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
                $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND r.agregator IN (' . $agg . ') ' : ' ');
                $whereStatement .= (isset($aggregators) ? ' AND r.agregator IN (' . $aggregators . ') ' : ' ');
            }
            $statement .= $fromStatement . $whereStatement . '  GROUP BY datePeriod, UPPER(country), LOWER(name)) B GROUP BY country,name ORDER BY revenues desc LIMIT 100';
            $a = new DateTime();
            $res = $this->getDi()->getDb4()->query($statement)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getGeneralStatisticsOperatorsAvg time ' . $interval, $statement);
            return $res;
        } catch (Exception $e) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $statement . "\n" . $e->getMessage());
        }
    }

    public function getGeneralStatisticsHoursAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null, $operator = null) {

        try {
            $sdateintime = strtotime(date('Y-m-d', strtotime($s)));
            $edateintime = strtotime(date('Y-m-d', strtotime($e)));
            $timeDiff = abs($edateintime - $sdateintime);

            $numberofdays = round($timeDiff / 86400);

            $numberofdays = ($numberofdays == 0 ? 1 : ($numberofdays + 1));
            if ($numberofdays < 0) {
                return null;
            }
            $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
            $numberofdays = ($aggFunction == 'SUM') ? 1 : $numberofdays;

            $affRes = $this->getSourcesByAff($aff, 0);

            $statement = 'SELECT id, name, CAST(REPLACE(FORMAT((SUM(clicks)/' . $numberofdays . '),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT((SUM(conversions)/' . $numberofdays . '),0),",","") as conversions, REPLACE(FORMAT(((SUM(Conversions)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '))*100,0),",","") as conversionrate, CAST((SUM(revenues)/' . $numberofdays . ') AS UNSIGNED) as revenues, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '),4),",","") as epc FROM ('
                    . 'SELECT DATE_FORMAT(timePeriod,"%H:%59") as id, DATE_FORMAT(timePeriod,"%H:%i") as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
            $fromStatement = ' FROM `R__HourlyCampaignSource` r  FORCE INDEX ( ix_date_time_mask_source ) inner join `Mask` m ON m.hash = r.hashmask AND r.datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" inner join `Sources` s on r.sourceid = s.id ';
            $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
            $whereStatement = ' WHERE 0=0 ';
            $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN (' . $country . ') ' : ' ');
            $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask IN (' . $campaign . ') ' : ' ');
            $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid IN (' . $source . ') ' : ' ');
            $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
            $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND m.agregator IN (' . $aggregators . ') ' : ' ');
            $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
            $whereStatement .= (($operator != 'allops' && isset($operator)) ? ' AND r.operator LIKE "' . $operator . '" ' : ' ');
            $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
            $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
            if ($agg != 'allaggs' && $agg != '') {
                //$fromStatement .= ' inner join `Agregators` a on m.agregator = a.id ';
                $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND r.agregator IN (' . $agg . ') ' : ' ');
                $whereStatement .= (isset($aggregators) ? ' AND r.agregator IN (' . $aggregators . ') ' : ' ');
            }
            $statement .= $fromStatement . $whereStatement . '  GROUP BY r.datePeriod , id, name) B GROUP BY id ORDER BY id LIMIT 150';
            //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement);

            $a = new DateTime();
            $res = $this->getDi()->getDb4()->query($statement)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getGeneralStatisticsSourcesAvg time ' . $interval, $statement);
            return $res;
        } catch (Exception $e) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $statement . "\n" . $e->getMessage());
        }
    }

    public function reportbyhour($sdate, $edate, $country, $source, $countries, $sources, $aggs, $aff = null) {
        $affRes = $this->getSourcesByAff($aff);
        //'hashmask,sourceid,subid,ad,datetimePeriod,clicks,conversions,revenues';
        $begindate = date('Y-m-d', strtotime($sdate));
        $begintime = date('H:00:00', strtotime($sdate));
        $enddate = date('Y-m-d', strtotime($edate));
        $endtime = date('H:00:00', strtotime($edate));
        $betweendatesarray = $this->createDateRangeArray($begindate, $enddate);
        $between = ' AND  1=1';
        if (!empty($betweendatesarray)) {
            $between = array_map(function($v) {
                return ' OR r.datePeriod = "' . $v . '" ';
            }, $betweendatesarray);
            $between = implode('', $between);
        }

        //hashmask,sourceid,fkAgregator,ad,datePeriod,timePeriod,clicks,conversions,revenues
        $sql = 'SELECT B.* FROM (SELECT datePeriod as Date, timePeriod as Hour, m.campaign as Campaign,hashmask as Campaign_Hash,m.country as Country,r.sourceid as Source,m.agregator as Agregator,r.subid as Subid,r.clicks as Clicks,r.conversions as Conversions'
                . ',r.revenues as Revenue,r.revenues/r.clicks as EPC,r.conversions/r.clicks as CR,ad as Ad FROM R__HourlyAggCampaignSourceOperator r FORCE INDEX (ix_date_time_mask_source) inner join Mask m on r.hashmask = m.hash '
                . 'WHERE ( ( r.datePeriod = "' . $begindate . '" AND r.timePeriod >= "' . $begintime . '" ) ' . $between . '  OR (r.datePeriod = "' . $enddate . '" AND r.timePeriod <= "' . $endtime . '") ) ' . (isset($country) ? (' and m.country IN (' . $country . ') ') : '') . (isset($source) ? (' and r.sourceid IN (' . $source . ') ') : '') . (isset($aggs) ? ' and m.agregator IN (' . $aggs . ')' : '') . (isset($countries) ? ' and m.country IN (' . $countries . ')' : '') . (isset($sources) ? ' and r.sourceid IN (' . $sources . ')' : '') . ') B inner join Sources ON B.Source = Sources.id WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . ' ORDER BY B.Date ASC, B.Hour ASC';
        //mail('pedrorleonardo@gmail.com', __METHOD__, $sql);
        //exit();
        return $this->getDi()->getDb4()->query($sql)->fetchAll();
    }

    private function createDateRangeArray($strDateFrom, $strDateTo) {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = array();

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
        $iDateFrom += 86400;
        if ($iDateTo >= $iDateFrom) {
            //array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                array_push($aryRange, date('Y-m-d', $iDateFrom));
                $iDateFrom += 86400; // add 24 hours
            }
        }
        return $aryRange;
    }

    public function getNetworkResult($startDate, $endDate, $convTableMonth, $source, $country, $countries, $netArray, $aff = null) {
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));
        $countryParam = '';
        $countryDailyParam = '';
        $selectDailyCountry = ',t1.countryCode as country';
        $groupByCountry = ', country';

        if (isset($country) && $country != 'ALL') {
            $selectDailyCountry = '';
            $countryParam = ' AND country IN (' . $country . ') ';
            $countryDailyParam = 'AND t1.countryCode IN (' . $country . ') ';
        }
        $res = array();
        $res2 = array();
        $relativeFields = explode(',', $netArray[$source][0]);
        array_walk($relativeFields, array($this, 'addtableprefix'));
        //is it for today?
        if ($convTableMonth != '') {
            $sql = 'SELECT t1.insertDate, SUM(a) as TotalClicks, SUM(c) as TotalConv, IFNULL(SUM(b),0) as Rev, IFNULL(SUM(b)/SUM(a),0) AS cpa, countryCode as countryCode,' .
                    $netArray[$source][0] . '
		FROM (SELECT t1.insertDate, COUNT(distinct t1.clickid) as a, 0 as b, 0 as c, t1.countryCode as countryCode, ' . $netArray[$source][0] . '
		FROM ' . $netArray[$source][1] . ' t1
		WHERE t1.insertDate = "' . $endDate . '" ' . $countryDailyParam . (isset($countries) ? (' AND countryCode IN (' . $countries . ') ') : ' ') . '
		GROUP BY t1.insertDate, t1.countryCode, ' . $netArray[$source][0] . '
		UNION ALL
		SELECT t1.insertDate, 0 a, MAX(ccpa) b, COUNT(distinct t2.clickId) as c, t1.countryCode as countryCode, ' . implode(',', $relativeFields) . '
		FROM Conversions' . $convTableMonth . ' t2 inner join ' . $netArray[$source][1] . ' t1 on t2.clickId = t1.clickId
		WHERE t2.insertDate = "' . $endDate . '" ' . $countryDailyParam . (isset($countries) ? (' AND t1.countryCode IN (' . $countries . ') ') : ' ') . '
		GROUP BY t1.countryCode, t2.clickid, ' . $netArray[$source][0] .
                    ' ) t1 GROUP BY countryCode, ' . $netArray[$source][0] . ' ORDER BY `insertDate` ASC ,`TotalConv` DESC';
            //echo 'today network report'; echo '<br>'; echo $sql; echo '<br>';//exit();
            //mail('pedrorleonardo@gmail.com', 'today network report', $sql);
            $res = $this->getDi()->getDb2()->query($sql)->fetchAll();
        }
        if (date("Y-m-d") > date("Y-m-d", strtotime($startDate))) {

            $sql = 'SELECT datePeriod as insertDate, SUM( clicks ) AS TotalClicks, SUM( conversions) AS TotalConv, IFNULL(SUM(rev),0) AS Rev, IFNULL(SUM(rev)/SUM(conversions),0) AS cpa' . $groupByCountry . ' as countryCode, '
                    . $netArray[$source][0] . ' FROM Src__' . $netArray[$source][1]
                    . ' WHERE datePeriod BETWEEN "' . $startDate . '" AND "' . $endDate . '" ' . $countryParam . (isset($countries) ? (' AND country IN (' . $countries . ') ') : ' ')
                    . ' GROUP BY insertDate, ' . $netArray[$source][0] . $groupByCountry . ' ORDER BY `insertDate` ASC,`TotalConv` DESC';
            //mail('pedrorleonardo@gmail.com', 'previous days network report', $sql);
            $res2 = $this->getDi()->getDb()->query($sql)->fetchAll();
        }

        return array_merge($res2, $res);
    }

    public function getNetworkReport($startDate, $endDate, $source, $country, $countries, $sources) {
        try {
            $sourcesparams = "SELECT tableColumns FROM sourcesMetadata WHERE fkID = $source LIMIT 1";
            $res2 = $this->getDi()->getDb4()->query($sourcesparams)->fetchAll();
            if (empty($res2)) {
                return null;
            }
            $params = $res2[0]['tableColumns'];
            $arr = explode(',', $params);
            $i = 0;
            $column = '';
            $columna = '';
            $excelcolumns = '';
            foreach ($arr as $columndb) {
                $i++;
                if ($columndb == '')
                    continue;
                $column .= ',cl_sourcep' . $i . " as $columndb";
                $columna .= ',cl_sourcep' . $i;
                $excelcolumns .= ',' . $columndb;
            }
            $sql = "SELECT insertDate as Date, fkAgregator as clientid, hashMask as offerid,campaignName as offer, countryCode as offerCountry, fkSource as sourceid, "
                    . "format as format, adNumber as adNumber,subid,mobileBrand as carrier, SUM(clicks) as clicks, SUM(conversions) as conversions, SUM(ccpa) as revenue$column "
                    . ' FROM MainReport2 WHERE insertDate BETWEEN "' . $startDate . '" and "' . $endDate . '" AND fkSource = ' . $source . (!empty($country) ? ' and countryCode IN (' . $country . ') ' : ' ')
                    . " " . (isset($sources) ? "AND fksource IN (' . $sources . ') " : '' ) . (isset($countries) ? "AND countryCode IN ($countries) " : '' ) . " GROUP BY insertDate, fkAgregator, hashMask,countryCode, fkSource,MainReport2.format,adNumber,subid,mobileBrand$columna ";
            //mail('pedrorleonardo@gmail.com', 'sql', $sql);
            $totalres = $this->getDi()->getDb4()->query($sql)->fetchAll();
            return array($totalres, $excelcolumns);
        } catch (Exception $ex) {
            mail('pedrorleonardo@gmail.com', 'error getaggdata2', $ex->getMessage() . ' SQL : ' . $sql);
        }
    }

    private function addtableprefix(&$item1) {
        $item1 = 't1.' . $item1;
    }

    public function getAggData2($sdate, $edate, $selectCountry, $selectSourceids ,$selectUsercountry, $selectAgg, $selectCampaign, $selectRurl, $selectOperator, $orderby, $countries, $sources, $aggregators, $aggsid = null, $campaignsid = null, $operators = null, $aff = null, $ccs = null) {
        try {
            $selectStatement = ', t1.insertDate as `Date`';
            $groupBy = ' t1.insertDate, Sources.affiliate ';
            if (isset($selectCountry)) {
                $selectStatement .= ', UPPER(t1.campaign_country) as Country ';
                $groupBy .= ', UPPER(t1.campaign_country) ';
            }
            if (isset($selectSourceids)) {
                $selectStatement .= ', CASE WHEN affiliate = 1 THEN Sources.id ELSE "" END as SourceID ';
                $groupBy .= ', Sources.id ';
            }
            if (isset($selectAgg)) {
                $selectStatement .= ', t1.agregator as ClientID, t1.agregator_name as Client, CASE WHEN u.initials IS NULL THEN "AT" ELSE u.initials END as account, a.cname as Entity ';
                $groupBy .= ', t1.agregator,t1.agregator_name ';
            }
            if (isset($selectCampaign)) {
                $selectStatement .= ', t1.campaign as Offer ';
                $groupBy .= ', t1.campaign ';
            }
            if (isset($selectUsercountry)) {
                $selectStatement .= ', t1.user_country as UserCountry';
                $groupBy .= ', t1.user_country';
            }
            if (isset($selectRurl)) {
                $selectStatement .= ', m1.rurl as OfferUrl';
                $groupBy .= ',  m1.rurl';
            }
            if (isset($selectOperator)) {
                $selectStatement .= ', t1.mobileBrand as Carrier';
                $groupBy .= ',  t1.mobileBrand ';
            }

            $affRes = $this->getSourcesByAff($aff);
            $db = $this->getDi()->getDb4();
            $db->query('call explode_table(",");');
            $statement = 'SELECT SUM(clicks) as clicks, sum(conversions) as conversions, TRUNCATE(IFNULL(SUM(revenue), 0), 2) as revenues, CASE WHEN affiliate = 0 THEN "Adult Sources" WHEN affiliate = 1 THEN "Affiliates" WHEN affiliate = 2 THEN "Mainstream" ELSE "testingsource" END as Area ';
            $whereStatement = ' FROM `OperatorReportSimple` t1 FORCE INDEX (ix_insertDate, c_hash, ix_agregator,ix_sourceid)  left join `Mask` m1 ON m1.hash = t1.c_hash '
                    . ' LEFT JOIN Agregators a ON t1.agregator = a.id '
                    . ' LEFT JOIN Sources ON Sources.id = t1.source '
                    . (isset($selectAgg) ? ' LEFT JOIN (SELECT MIN(id) as id, value,MIN(initials) as initials FROM table2 GROUP BY value) u ON  t1.agregator = u.value  ' : '')
                    . ' WHERE insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" '
                    . ((isset($aggsid) && $aggsid != 'allaggs') ? ' AND t1.agregator IN ( ' . $aggsid . ') ' : '') . ((isset($campaignsid) && $campaignsid != 'allcampaigns') ? ' AND c_hash IN ( ' . $campaignsid . ') ' : '')
                    . ((isset($ccs) && $ccs != 'ALL') ? ' AND campaign_country IN ( ' . $ccs . ') ' : '')
                    . ((isset($operators) && $operators != 'alloperators') ? ' AND mobileBrand IN (' . $operators . ') ' : ' ')
                    . (isset($countries) ? ' AND campaign_country IN (' . $countries . ') ' : ' ')
                    . (isset($sources) ? ' AND t1.source IN (' . $sources . ') ' : ' ')
                    . (isset($aggregators) ? ' AND t1.agregator IN (' . $aggregators . ') ' : ' ')
                    . ($affRes != '' ? ' AND ' . $affRes : '');
            $sql = $statement . $selectStatement . $whereStatement . ' GROUP BY ' . $groupBy . ' ORDER BY date, ' . $orderby . ' DESC';
            //mail('pedrorleonardo@gmail.com', 'getaggdata2', $sql);
            //exit();
            $a = new DateTime();
            $result = $db->query($sql)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'OperatorReport2 ' . $interval, $sql);
            return $result;
        } catch (Exception $ex) {
            mail('pedrorleonardo@gmail.com', 'error getaggdata2', $ex->getMessage() . ' SQL : ' . $sql);
        }
    }

    public function getGeneralAggCampaignsAvgNewDB($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null) {
        $sdateintime = strtotime(date('Y-m-d', strtotime($s)));
        $edateintime = strtotime(date('Y-m-d', strtotime($e)));
        $timeDiff = abs($edateintime - $sdateintime);

        $numberofdays = round($timeDiff / 86400);

        $numberofdays = ($numberofdays == 0 ? 1 : ($numberofdays + 1));
        if ($numberofdays < 0) {
            return null;
        }
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $numberofdays = ($aggFunction == 'SUM') ? 1 : $numberofdays;

        $affRes = $this->getSourcesByAff($aff, 0);
        $statement = 'SELECT id, name, CAST(REPLACE(FORMAT((SUM(clicks)/' . $numberofdays . '), 0), ",", "") AS UNSIGNED) as clicks, REPLACE(FORMAT((SUM(conversions)/' . $numberofdays . '), 0), ",", "") as conversions, REPLACE(FORMAT(((SUM(Conversions)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '))*100, 0), ",", "") as conversionrate, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . '), 0), ",", "") as revenues, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '), 4), ",", "") as epc FROM ('
                . 'SELECT m.hash as id, m.campaign as name, SUM(clicks) as clicks, sum(conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `R__HourlyAggCampaignSourceOperator` r inner join `Mask` m ON m.hash = r.hashmask inner join `Agregators` a on m.agregator = a.id ';
        $fromStatement .= (isset($aff) ? ' LEFT JOIN Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN Sources ON Sources.id = r.sourceid ' );
        $whereStatement = ' WHERE datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask = "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
        $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
        $whereStatement .= (isset($aggregators) ? ' AND m.agregator IN (' . $aggregators . ') ' : ' ');
        if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . ' GROUP BY datePeriod, id, name) B GROUP BY id ORDER BY clicks desc';
        //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country = ' . $country . '$agg = ' . $agg . ', $campaign = ' . $campaign . '$source = ' . $source . "\n" . ' statement = ' . $statement . "\n");

        $result = $this->getDi()->getDb4()->query($statement)->fetchAll();
        return $result;
    }

    public function getGeneralOperatorsAvgNewDB($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $operator = null) {
        $sdateintime = strtotime(date('Y-m-d', strtotime($s)));
        $edateintime = strtotime(date('Y-m-d', strtotime($e)));
        $timeDiff = abs($edateintime - $sdateintime);

        $numberofdays = round($timeDiff / 86400);

        $numberofdays = ($numberofdays == 0 ? 1 : ($numberofdays + 1));
        if ($numberofdays < 0) {
            return null;
        }
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $numberofdays = ($aggFunction == 'SUM') ? 1 : $numberofdays;

        $affRes = $this->getSourcesByAff($aff, 0);
        $statement = 'SELECT CONCAT(UPPER(COALESCE(country, "-")), "_", COALESCE(name, "-")) as name, CAST(REPLACE(FORMAT((SUM(clicks)/' . $numberofdays . '), 0), ",", "") AS UNSIGNED) as clicks, REPLACE(FORMAT((SUM(conversions)/' . $numberofdays . '), 0), ",", "") as conversions, REPLACE(FORMAT(((SUM(Conversions)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '))*100, 0), ",", "") as conversionrate, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . '), 0), ",", "") as revenues, REPLACE(FORMAT((SUM(revenues)/' . $numberofdays . ')/(SUM(Clicks)/' . $numberofdays . '), 4), ",", "") as epc FROM ('
                . 'SELECT m.country as country, r.operator as name, SUM(clicks) as clicks, sum(conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `R__HourlyAggCampaignSourceOperator` r inner join `Mask` m ON m.hash = r.hashmask inner join `Agregators` a on m.agregator = a.id ';
        $fromStatement .= (isset($aff) ? ' LEFT JOIN Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN Sources ON Sources.id = r.sourceid ' );
        $whereStatement = ' WHERE datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask = "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
        $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
        $whereStatement .= (($operator != 'allops' && isset($operator)) ? ' AND r.operator LIKE "' . $operator . '" ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
        if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . ' GROUP BY datePeriod, country, name) B GROUP BY country, name ORDER BY clicks desc';
        //mail('pedrorleonardo@gmail.com', 'getGeneralOperatorsAvg', '$country = ' . $country . '$agg = ' . $agg . ', $campaign = ' . $campaign . '$source = ' . $source . "\n" . ' statement = ' . $statement . "\n");

        $result = $this->getDi()->getDb4()->query($statement)->fetchAll();
        return $result;
    }

    public function lpreport($sdate, $edate, $country, $source, $countries, $sources, $aff) {
        try {
            //Date,OfferCountry,Subid,Source,ClientID,UserCountry,Offer,Carrier,ISP,Os,Browser,MobileType,Jumpline,LP,lpclicks,clicks,conversions,revenue,ad
            $affRes = $this->getSourcesByAff($aff, 0);
            $sql = 'SELECT fkAgregator as ClientID, hashMask as campaignhash, '
                    . 'campaignName as Offer, countryCode as OfferCountry, '
                    . 'fkSource as Source, CONCAT(lpid,"-",lpname) as LP, Subid, ad, '
                    . 'userCountry as UserCountry, ISP, mobileBrand as Carrier, osType as Os, '
                    . 'browserName as Browser, mobileTest as MobileType, '
                    . 'njumpkey as njumpcode, njumpname as Njumpname, njumpid as Jumpline, lpclicks, '
                    . 'clicks, conversions, revenue, insertDate as `Date` '
                    . 'FROM lpreport inner join Sources ON Sources.id = lpreport.fkSource '
                    . ' WHERE insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" '
                    . ($affRes != '' ? ' AND ' . $affRes : '' )
                    . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '' )
                    . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '' )
                    . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : '' )
                    . (isset($source) ? ' AND fkSource IN (' . $source . ') ' : '' )
                    . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '' )
                    . ' order by insertDate';
            //mail('pedrorleonardo@gmail.com', 'lpreport', $sql);
            $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
            return $res;
        } catch (Exception $ex) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n" . $ex->getMessage());
        }
    }

    public function lpreportsimple($sdate, $edate, $country, $source, $countries, $sources, $aff) {
        try {
            //Date,OfferCountry,Subid,Source,ClientID,UserCountry,Offer,Carrier,ISP,Os,Browser,MobileType,Jumpline,LP,lpclicks,clicks,conversions,revenue,ad
            $affRes = $this->getSourcesByAff($aff, 0);
            $sql = 'SELECT fkAgregator as ClientID, hashMask as campaignhash, '
                    . 'campaignName as Offer, countryCode as OfferCountry, '
                    . 'fkSource as Source, CONCAT(lpid,"-",lpname) as LP, Subid, ad, '
                    . 'ISP, mobileBrand as Carrier, '
                    . 'njumpkey as njumpcode, njumpname as Njumpname, lpclicks, '
                    . 'clicks, conversions, revenue, insertDate as `Date` '
                    . 'FROM lpreportsimple inner join Sources ON Sources.id = lpreportsimple.fkSource '
                    . ' WHERE insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" '
                    . ($affRes != '' ? ' AND ' . $affRes : '' )
                    . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '' )
                    . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '' )
                    . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : '' )
                    . (isset($source) ? ' AND fkSource IN (' . $source . ') ' : '' )
                    . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '' )
                    . ' order by insertDate';
            //mail('pedrorleonardo@gmail.com', 'lpreport', $sql);
            $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
            return $res;
        } catch (Exception $ex) {
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n" . $ex->getMessage());
        }
    }

    private function getSourcesByAff($aff) {
        try {
            if (!isset($aff))
                return '';
            $table = 'Sources';

            if ($aff === 0) {
                return '(' . $table . '.affiliate = 0)';
            } else if ($aff === 1) {
                return '(' . $table . '.affiliate = 1)';
            } else if ($aff === 2) {
                return '(' . $table . '.affiliate = 2)';
            } else if ($aff === 3) {
                return '(' . $table . '.affiliate = 3 OR ' . $table . '.affiliate IS NULL OR ' . $table . '.affiliate = 0 OR ' . $table . '.affiliate = 2)';
            } else if ($aff === 4) {
                return '(' . $table . '.affiliate = 3 OR ' . $table . '.affiliate IS NULL OR ' . $table . '.affiliate = 0)';
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}

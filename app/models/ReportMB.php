<?php

use Phalcon\Mvc\Model;

class ReportMB extends Model {

    /**
     * Initializes correct affiliate table
     */
    public function setReport($table) {
        $this->setSource($table);
    }

    public function getDailyResult($join_type, $permission, $aff = null, $aggs = null) {
        try {
            $affRes = $this->getSourcesByAff($aff, 0);
            $sql = 'SELECT  cc.fkSource as source, cc.hashMask as c_hash, CONCAT(cc.fkSource,"_",cc.format,"_",cc.adNumber) AS sub_id
				, c.clicksCount as clicks,
				CASE WHEN cc.convCount IS NULL THEN 0
					ELSE cc.convCount END as conversions,
				CASE WHEN cc.convCpa IS NULL THEN 0
					ELSE cc.convCpa END as revenue,
				cc.fkAgregator as agregator, cc.campaignName as campaign, cc.countryCode as campaign_country, cc.insertDate as insert_date
				from
				(
					SELECT SUM(ccpa) as convCpa, fkSource, hashMask,format,adNumber, COUNT(clickId) as convCount, fkAgregator, campaignName, countryCode,insertDate
					FROM (
						SELECT MAX(ccpa) as ccpa,clickId,fkSource,hashMask,format,adNumber, fkAgregator, campaignName, UPPER(countryCode) as countryCode,insertDate
						FROM ConversionsDaily
						WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission . '
						GROUP BY clickId,fkSource,hashMask,format,adNumber, fkAgregator, campaignName, UPPER(countryCode),insertDate
					) uu
					GROUP BY fkSource, hashMask,format,adNumber,insertDate) cc


				LEFT JOIN
				(
				SELECT fkSource, hashMask,SUM(clicks) as clicksCount,format,adNumber, fkAgregator, campaignName, UPPER(countryCode) as countryCode
				FROM ClicksDailyAgg
				WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission . '
				GROUP BY fkSource, hashMask,format,adNumber, fkAgregator, campaignName, UPPER(countryCode)) c
				ON c.hashMask = cc.hashMask and c.fkSource = cc.fkSource and c.format = cc.format and c.adNumber = cc.adNumber and c.fkAgregator = cc.fkAgregator and c.campaignName = cc.campaignName and c.countryCode = cc.countryCode left join Sources ON Sources.id = cc.fkSource ' . ($affRes != '' ? ' WHERE ' . $affRes : '') . ($join_type == 'INNER' ? ' ORDER BY campaign,campaign_country,agregator,sub_id;' : '

				UNION ALL

				SELECT c.fkSource as source,  c.hashMask as c_hash, CONCAT(c.fkSource,"_",c.format,"_",c.adNumber) AS sub_id
						, c.clicksCount as clicks, 0 as conversions, 0 as revenue,c.fkAgregator as agregator, c.campaignName as campaign, c.countryCode as campaign_country, c.insertDate as insert_date
				from (SELECT fkSource, hashMask,format,adNumber, COUNT(*) as convCount, SUM(ccpa) as convCpa, fkAgregator, campaignName, UPPER(countryCode) as countryCode
				FROM ConversionsDaily
				WHERE 0 = 0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission . '
				GROUP BY fkSource, hashMask,format,adNumber, fkAgregator, campaignName, UPPER(countryCode)) cc RIGHT JOIN (SELECT fkSource, hashMask,SUM(clicks) as clicksCount,format,adNumber, fkAgregator, campaignName, UPPER(countryCode) as countryCode,insertDate
				FROM ClicksDailyAgg
				WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission . '
				GROUP BY fkSource, hashMask,format,adNumber,fkAgregator, campaignName, UPPER(countryCode)) c ON c.hashMask = cc.hashMask and c.fkSource = cc.fkSource and c.format = cc.format and c.adNumber = cc.adNumber and c.fkAgregator = cc.fkAgregator and c.campaignName = cc.campaignName and c.countryCode = cc.countryCode left join Sources ON Sources.id = c.fkSource WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . ' AND cc.convCount is null
			ORDER BY campaign,campaign_country,agregator,sub_id;');

            $a = new DateTime();
            $res = $this->getDi()->getDb2()->query($sql)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getDailyResult time:' . $interval, $sql);
            return $res;
        } catch (Exception $ex) {
            mail('martim.barone@mobipium.com', __METHOD__, $sql . "\n" . $ex->getMessage());
        }
    }

    public function getDailyResult_rbb($join_type, $country, $permission, $aff = null) {
        try {
            $affRes = $this->getSourcesByAff($aff, 0);

            $sql = 'SELECT  cc.fkSource as source, cc.hashMask as c_hash, CONCAT(cc.fkSource,"_",cc.format,"_",cc.adNumber) AS sub_id,cc.ad as ad
				, c.clicksCount as clicks,
				CASE WHEN cc.convCount IS NULL THEN 0
					ELSE cc.convCount END as conversions,
				CASE WHEN cc.convCpa IS NULL THEN 0
					ELSE cc.convCpa END as revenue,
				cc.fkAgregator as agregator, agg.agregator as agregator_name, cc.campaignName as campaign, cc.countryCode as campaign_country, cc.insertDate as insert_date,
				CASE WHEN c.clicksCount IS NULL THEN 0
					WHEN c.clicksCount = 0 THEN 0
					ELSE cc.convCpa/c.clicksCount END as EPC,
				CASE WHEN c.clicksCount IS NULL THEN 0
					WHEN c.clicksCount = 0 THEN 0
					ELSE cc.convCount/c.clicksCount END as CR
				from
				(
					SELECT SUM(ccpa) as convCpa, fkSource, hashMask,format,adNumber, ad, COUNT(clickId) as convCount, fkAgregator, campaignName, countryCode,insertDate
					FROM (
						SELECT MAX(ccpa) as ccpa,clickId,fkSource,hashMask,format,adNumber, ad, fkAgregator, campaignName, UPPER(countryCode) as countryCode,insertDate
						FROM ConversionsDaily
						WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission . '
						GROUP BY clickId,fkSource,hashMask,format,adNumber,ad, fkAgregator, campaignName, UPPER(countryCode),insertDate
					) uu
					GROUP BY fkSource, hashMask,format,adNumber, ad,insertDate) cc


				LEFT JOIN
				(
				SELECT fkSource, hashMask,SUM(clicks) as clicksCount,format,adNumber, ad, fkAgregator, campaignName, UPPER(countryCode) AS countryCode
				FROM ClicksDailyAgg
				WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission . '
				GROUP BY fkSource, hashMask,format,adNumber, ad, fkAgregator, campaignName, UPPER(countryCode)) c
				ON c.hashMask = cc.hashMask and c.fkSource = cc.fkSource and c.ad = cc.ad and c.format = cc.format and c.adNumber = cc.adNumber and c.fkAgregator = cc.fkAgregator and c.campaignName = cc.campaignName and c.countryCode = cc.countryCode LEFT JOIN Agregators agg ON cc.fkAgregator = agg.id left join Sources ON Sources.id = cc.fkSource WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . ($join_type == 'INNER' ? ' ORDER BY campaign,campaign_country,agregator,sub_id;' : '

				UNION ALL

				SELECT c.fkSource as source,  c.hashMask as c_hash, CONCAT(c.fkSource,"_",c.format,"_",c.adNumber) AS sub_id,c.ad as ad
						, c.clicksCount as clicks, 0 as conversions, 0 as revenue,c.fkAgregator as agregator, agg2.agregator as agregator_name, c.campaignName as campaign, c.countryCode as campaign_country, c.insertDate as insert_date,
						0.00 as EPC,
						0.00 as CR
				from (SELECT fkSource, hashMask,format,adNumber,ad, COUNT(*) as convCount, SUM(ccpa) as convCpa, fkAgregator, campaignName, countryCode
				FROM ConversionsDaily
				WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission . '
				GROUP BY fkSource, hashMask,format,adNumber,ad, fkAgregator, campaignName, countryCode) cc RIGHT JOIN (SELECT fkSource, hashMask,SUM(clicks) as clicksCount,format,adNumber, ad, fkAgregator, campaignName, UPPER(countryCode) as countryCode,insertDate
				FROM ClicksDailyAgg
				WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission . '
				GROUP BY fkSource, hashMask,format,adNumber,ad,fkAgregator, campaignName, UPPER(countryCode)) c ON c.hashMask = cc.hashMask and c.fkSource = cc.fkSource and c.ad = cc.ad and c.format = cc.format and c.adNumber = cc.adNumber and c.fkAgregator = cc.fkAgregator and c.campaignName = cc.campaignName and c.countryCode = cc.countryCode left join Sources ON Sources.id = c.fkSource LEFT JOIN Agregators agg2 ON cc.fkAgregator = agg2.id WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . '
				AND cc.convCount is null
			ORDER BY campaign,campaign_country,agregator,sub_id;');
            $a = new DateTime();
            $res = $this->getDi()->getDb2()->query($sql)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getDailyResult_rbb time:' . $interval, $sql);

            return $res;
        } catch (Exception $ex) {
            mail('martim.barone@mobipium.com', __METHOD__, $sql . "\n" . $ex->getMessage());
        }
    }

    public function getMainResult($sdate, $edate, $filter, $country, $permission, $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 1);
        $sql = 'SELECT B.*, agg.agregator as agregator_name FROM '
                . ' (SELECT insert_date, campaign, c_hash,UPPER(campaign_country) as campaign_country, agregator, source, sub_id, ad, clicks, conversions as conversions, revenue as revenue, revenue/clicks as EPC, conversions/clicks as CR FROM Agr__MainReport '
                . 'WHERE insert_date BETWEEN "' . $sdate . '" AND "' . $edate . '" ' . $filter . (isset($country) ? ' AND campaign_country IN (' . $country . ') ' : ' ') . $permission . ')
                   B LEFT JOIN tinas__Agregators agg ON B.agregator = agg.id LEFT JOIN tinas__Sources ON tinas__Sources.id = B.source WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . ' ORDER BY insert_date, campaign_country';

        $a = new DateTime();
        $res = $this->getDi()->getDb()->query($sql)->fetchAll();
        $b = new DateTime();
        $interval = $a->diff($b)->format("%h:%i:%s");
        //mail('pedrorleonardo@gmail.com', 'getMainResult time '.$interval, $sql);

        return $res;
    }

    public function getHistoricalPerCountryResult($sdate, $edate, $filter, $country, $permission, $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 1);
        $sql = 'SELECT UPPER(campaign_country) as Country, SUM(clicks) as Clicks, SUM(conversions) as Conversions, SUM(revenue) as Revenues
                    FROM Agr__MainReport left join tinas__Sources ON Sources.id = Agr__MainReport.source WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . '
                      AND insert_date BETWEEN "' . $sdate . '" AND "' . $edate . '"' . $filter
                . (isset($country) ? ' AND campaign_country IN (' . $country . ') ' : ' ') . $permission
                . '  GROUP BY UPPER(campaign_country)';
        $res = $this->getDi()->getDb()->query($sql)->fetchAll();
        //mail('pedrorleonardo@gmail.com', 'getHistoricalPerCountryResult', $sql);
        return $res;
    }

    public function getOpDailyResult($join_type, $country, $permission, $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $sdate = date('Y-m-d');
        $thisMonthYear = date('mY', strtotime($sdate));
        $sql = 'SELECT "' . $sdate . '"  as insert_date, sub_id, c_hash, campaign, campaign_country, user_country, agregator, source, operator, isp, os, browser, mobilet, ad , sum(conversions) as conversions, sum(revenue) as revenue,SUM(clicks) as clicks
FROM(

	/*clicks de hoje sem conversao*/
	select CONCAT( c1.fkSource,  "_", c1.format,  "_", c1.adNumber ) AS sub_id, c1.hashMask AS c_hash,c1.campaignName AS campaign, c1.countryCode AS campaign_country,c1.userCountry AS user_country, c1.fkAgregator AS agregator, c1.fkSource AS source,c1.mobileBrand AS operator,c1.ISP AS isp,c1.osType AS os, c1.browserName AS browser,c1.mobileTest AS mobilet,c1.ad AS ad, 0 as conversions, 0 as revenue, count(distinct c1.uniqid) as clicks
	FROM Clicks' . $thisMonthYear . ' c1
	WHERE c1.insertDate = "' . $sdate . '" ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : ' ') . $permission . '
	AND NOT EXISTS(SELECT id
		FROM Conversions' . $thisMonthYear . ' cc1
		WHERE c1.uniqid = cc1.clickId )
	GROUP BY sub_id, c_hash,campaign,campaign_country,user_country,agregator, source, operator, isp, os, browser, mobilet, ad

	UNION ALL

	/* Conversoes de hoje com Click */

	SELECT sub_id, c_hash,campaign,campaign_country,user_country,agregator, source, operator, isp, os, browser, mobilet, ad, COUNT(conversions) as conversions, SUM(revenue) as revenue, COUNT(clicks) as clicks
	FROM (
		select CONCAT( c1.fkSource,  "_", c1.format,  "_", c1.adNumber ) AS sub_id, c1.hashMask AS c_hash,c1.campaignName AS campaign, c1.countryCode AS campaign_country,
		c1.userCountry AS user_country, c1.fkAgregator AS agregator, c1.fkSource AS source,c1.mobileBrand AS operator,c1.ISP AS isp,c1.osType AS os, c1.browserName AS browser,c1.mobileTest AS mobilet,c1.ad AS ad, 1 as conversions, MAX(cc1.ccpa) as revenue, 1 as clicks
		FROM ConversionsDaily cc1, Clicks' . $thisMonthYear . ' c1
		WHERE cc1.insertDate = "' . $sdate . '" AND cc1.clickId = c1.uniqid ' . (isset($country) ? ' AND cc1.countryCode IN (' . $country . ') ' : ' ') . $permission . '
		GROUP BY sub_id, c_hash,campaign,campaign_country,user_country,agregator, source, operator, isp, os, browser, mobilet, ad, cc1.clickId
	) C GROUP by sub_id, c_hash,campaign,campaign_country,user_country,agregator, source, operator, isp, os, browser, mobilet, ad
) Z left join Sources ON Sources.id = Z.source
WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . '
GROUP BY sub_id, c_hash,campaign,campaign_country,user_country,agregator, source, operator, isp, os, browser, mobilet, ad

ORDER BY campaign,campaign_country,agregator,sub_id';
//WHERE ' . ($affRes != '' ? $affRes : ' 0=0 '). ($join_type == 'INNER' ? ' AND conversions > 0 ' : '').'
        //mail('pedrorleonardo@gmail.com', 'getOpDailyResult', $sql);
        return $this->getDi()->getDb2()->query($sql)->fetchAll();
    }

    public function getOpMainResult($sdate, $edate, $filter, $country, $permission, $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 1);
        $edate2 = $edate;
        $res = array();
        $res2 = array();

        $sql = 'SELECT B.* FROM (SELECT insert_date, sub_id,c_hash,campaign, campaign_country, user_country,agregator, source, operator, isp, os, browser, mobilet, clicks, conversions, revenue,ad FROM Agr__OperatorReport2 WHERE insert_date BETWEEN "' . $sdate . '" AND "' . $edate . '"'
                . $filter . (isset($country) ? ' AND campaign_country IN (' . $country . ') ' : '') . $permission . ' )B left join tinas__Sources ON tinas__Sources.id = B.source WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ');
        //mail('pedrorleonardo@gmail.com', 'getOpMainResult2', $sql);
        $res2 = $this->getDi()->getDb()->query($sql)->fetchAll();

        return array_merge($res, $res2);
    }

    public function getMjResult($auth, $sdate, $edate, $sources, $country) {
        $src = '';
        if ($auth['utype'] == 2 and $auth['sources'] != '') {

            $src = ' AND sourceid IN (' . $auth['sources'] . ')';
        }



        $sqlCountry = '';
        $sqlCountry2 = '';
        if (isset($country)) {
            $sqlCountry = ' AND mjumps.userCountry IN (' . $country . ') ';
            $sqlCountry2 = ' AND mjumps.country IN (' . $country . ') ';
        }

        $current = date('mY');
        $sql = 'SELECT insertDate,lpName,page,ad,SUM(lpClick) as lpClicks, SUM(conversion) AS conversions, SUM(click) AS clicks,SUM(amount) AS revenue,subid
		FROM Agr__MjumpReport mjumps ' . ($auth['utype'] == 2 ? ' INNER JOIN tinas__Sources s ON mjumps.insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" AND mjumps.sourceid = s.id AND s.affiliate = 2 ' : ' WHERE insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" ' ) . (isset($sources) ? ' AND sourceid IN (' . $sources . ')' : '') . $sqlCountry2 . ' GROUP BY insertDate,page,lpName,ad,subid ORDER BY insertDate,conversions';

        //mail('pedrorleonardo@gmail.com','entrei 1',$sql);
        $res = $this->getDi()->getDb()->query($sql)->fetchAll();
        if ($edate == date('Y-m-d')) {
            $sql = 'SELECT insertDate,lpName,page,ad,COUNT(*) AS lpClicks, SUM(conversion) AS conversions, SUM(click) AS clicks, SUM(amount) AS revenue,subid
		    FROM LPclick mjumps ' . ($auth['utype'] == 2 ? ' INNER JOIN Sources s ON mjumps.insertDate = "' . $edate . '" AND mjumps.fkSource = s.id AND s.affiliate = 2 ' : ' WHERE insertDate = "' . $edate . '" ' ) . (isset($sources) ? ' AND fkSource IN (' . $sources . ')' : '') . ' ' . $sqlCountry . ' GROUP BY lpName,page,ad,subid ORDER BY insertDate,conversions';
            //mail('pedrorleonardo@gmail.com','entrei 2',$sql);

            $res2 = $this->getDi()->getDb2()->query($sql)->fetchAll();
            $res = array_merge($res, $res2);
        }
        return $res;
    }

    public function getDailyResAggPerCountry($join_type, $country, $permission, $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $sql2 = 'SELECT T1.countryCode as Country, SUM(T1.idCount) AS Clicks, SUM(T2.Conversions) as Conversions,  SUM(T2.ccpa + 0) as Revenues
                    FROM    (   SELECT fkAgregator,UPPER(countryCode) as countryCode,fkClient,campaignName,fkSource,format,adNumber,insertDate, COUNT(id) AS idCount
                                FROM ClicksDailyAgg WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission . '
                                GROUP BY insertDate, fkAgregator, UPPER(countryCode), fkClient, campaignName, fkSource, format, adNumber
                    ) AS T1 ' .
                $join_type . ' JOIN (
                            SELECT SUM(ccpa + 0) as ccpa,fkAgregator,countryCode,fkClient,campaignName,fkSource,format,adNumber,insertDate,COUNT(DISTINCT clickId) AS Conversions
                            FROM ConversionsDaily
                            GROUP BY insertDate, fkAgregator, countryCode, fkClient, campaignName, fkSource, format, adNumber
                    ) AS T2 ON T1.fkAgregator=T2.fkAgregator AND T1.countryCode=T2.countryCode AND T1.fkClient=T2.fkClient AND T1.campaignName = T2.campaignName AND T1.fkSource=T2.fkSource AND T1.format=T2.format AND T1.adNumber=T2.adNumber AND T1.insertDate=T2.insertDate
                    LEFT JOIN Sources ON T1.fkSource = Sources.id
                    WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . '
                    GROUP BY T1.countryCode';
        //mail('pedrorleonardo@gmail.com', 'getDailyResAggPerCountry', $sql2);
        return $this->getDi()->getDb2()->query($sql2)->fetchAll();
    }

    public function getLastDaysAvg($sdate, $country, $permission, $aff = null) {
        date_default_timezone_set('Europe/Lisbon');

        $edate = date("Y-m-d", strtotime(date("Y-m-d")));
        $affRes = $this->getSourcesByAff($aff, 0);
        $e = date('Y-m-d', strtotime('-1 day'));
        $sql = 'SELECT AVG(clicks) as avgClicks, AVG(conversions) as avgCon, AVG(revenues) as avgRev, AVG(EPC) as avgEPC, AVG(conversionRate) as avgCR
                        FROM (SELECT SUM(clicks) as clicks, sum(conversions) as conversions, SUM(conversions)/SUM(clicks) as conversionRate, SUM(revenues) as revenues, SUM(revenues)/SUM(clicks) as EPC '
                . 'FROM `SourceXCampaign` r  inner join `Mask` m on CAST(datetimePeriod AS DATE) BETWEEN "' . $sdate . '" AND "' . $e . '" AND CAST(datetimePeriod AS TIME) BETWEEN "00:00:00" AND  "' . date("H:i:s") . '" AND r.hashmask = m.hash LEFT JOIN  Sources ON Sources.id = r.sourceid '
                . 'WHERE 0=0 '
                . (isset($country) ? ' AND m.country IN (' . $country . ') ' : '') . $permission
                . ' AND ' . ($affRes != '' ? $affRes : ' 0=0 ') . ' GROUP BY CAST(datetimePeriod AS DATE) ) as temp';
        $a = new DateTime();
        $res = $this->getDi()->getDb2()->query($sql)->fetchAll();
        $b = new DateTime();
        $interval = $a->diff($b)->format("%h:%i:%s");
        //mail('pedrorleonardo@gmail.com', 'getLastDaysAvg2 time ' . $interval, $sql);
        return $res;
        //}
    }

    private function addtableprefix(&$item1) {
        $item1 = 't1.' . $item1;
    }

    public function getDayTotal($country, $permission, $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $query1 = 'SELECT SUM(clicks) AS clicks '
                . ' FROM ClicksDailyAgg LEFT JOIN Sources ON ClicksDailyAgg.fkSource = Sources.id '
                . ' WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . ' ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission;
        $query2 = 'SELECT COUNT( DISTINCT clickId) AS conversions, SUM(ccpa) AS revenue '
                . ' FROM ConversionsDaily LEFT JOIN Sources ON Sources.id = ConversionsDaily.fkSource '
                . ' WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') .
                (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . $permission;
        $a = new DateTime();
        $res1 = $this->getDi()->getDb2()->query($query1)->fetchAll();
        $b = new DateTime();
        $interval = $a->diff($b)->format("%h:%i:%s");

        //mail('pedrorleonardo@gmail.com', 'query1 getDayTotal time:' . $interval, $query1);
        $a = new DateTime();
        $res2 = $this->getDi()->getDb2()->query($query2)->fetchAll();
        $b = new DateTime();
        $interval = $a->diff($b)->format("%h:%i:%s");
        //mail('pedrorleonardo@gmail.com', 'query2 getDayTotal time:' . $interval, $query2);



        return array($res1[0]['clicks'], $res2[0]['conversions'], $res2[0]['revenue']);
    }

    public function getDaysTotal($sdate, $edate, $country, $permission, $aff = null) {

        if ($sdate < date('Y-m-d')) {
            $affRes = $this->getSourcesByAff($aff, 1);
            $query1 = ' SELECT SUM(clicks) as clicks, SUM(revenue) as revenue, SUM(conversions) as conversions FROM ( '
                    . ' SELECT source, clicks, revenue, conversions '
                    . ' FROM  `Agr__MainReport` '
                    . ' WHERE insert_date BETWEEN "' . $sdate . '" AND "' . $edate . '" ' . (isset($country) ? ' AND campaign_country IN (' . $country . ') ' : '')
                    . $permission . ') B LEFT JOIN `tinas__Sources` ON B.source = `tinas__Sources`.id '
                    . ' WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ');
            $a = new DateTime();
            $res1 = $this->getDi()->getDb()->query($query1)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'old clicks time:' . $interval, $query1);
        }
        if ($edate >= date('Y-m-d')) {
            $affRes = $this->getSourcesByAff($aff, 0);
            $query2 = 'SELECT SUM(uniqid) as clicks FROM (SELECT SUM(clicks) as uniqid, fkSource as source '
                    . ' FROM ClicksDailyAgg '
                    . ' WHERE 0 = 0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '')
                    . $permission
                    . ' GROUP BY fkSource) B LEFT JOIN `Sources` ON B.source = `Sources`.id '
                    . ' WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ');
            $query3 = 'SELECT COUNT(clickId) as conversions,SUM(ccpa) as revenue FROM (SELECT MAX(clickId) as clickId, MAX(ccpa) as ccpa,fkSource as source '
                    . ' FROM ConversionsDaily '
                    . ' WHERE 0 = 0' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '')
                    . $permission
                    . ' GROUP BY clickId, fkSource) B LEFT JOIN `Sources` ON B.source = `Sources`.id '
                    . ' WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ');
            $a = new DateTime();
            $dailyClicks = $this->getDi()->getDb2()->query($query2)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'daily clicks time:' . $interval, $query2);
            $a = new DateTime();
            $dailyConversions = $this->getDi()->getDb2()->query($query3)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'daily rev and conversions' . $interval, $query3);
        }

        $clicks = (isset($res1[0]['clicks']) ? $res1[0]['clicks'] : 0 ) + (isset($dailyClicks[0]['clicks']) ? $dailyClicks[0]['clicks'] : 0 );
        $conversions = (isset($res1[0]['conversions']) ? $res1[0]['conversions'] : 0 ) + (isset($dailyConversions[0]['conversions']) ? $dailyConversions[0]['conversions'] : 0 );
        $revenue = (isset($res1[0]['revenue']) ? $res1[0]['revenue'] : 0 ) + (isset($dailyConversions[0]['revenue']) ? $dailyConversions[0]['revenue'] : 0 );

        return array($clicks, $conversions, $revenue);
    }

    public function getGeneralCountryAvg($s, $e, $hs, $he, $permission, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $statement = 'SELECT id, name, CAST(REPLACE(FORMAT(' . $aggFunction . '(clicks),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT(' . $aggFunction . '(conversions),0),",","") as conversions, REPLACE(FORMAT((' . $aggFunction . '(Conversions)/' . $aggFunction . '(Clicks))*100,0),",","") as conversionrate, REPLACE(FORMAT(' . $aggFunction . '(revenues),0),",","") as revenues, REPLACE(FORMAT(' . $aggFunction . '(revenues)/' . $aggFunction . '(Clicks),4),",","") as epc FROM ('
                . 'SELECT  m.country as id, m.country as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `SourceXCampaign` r inner join `Mask` m ON m.hash = r.hashmask ';
        $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
        $whereStatement = ' WHERE CAST(r.datetimePeriod as DATE) BETWEEN "' . $s . '" AND "' . $e . '" AND CAST(r.datetimePeriod as TIME) BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask= "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= $permission;
        $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
        if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
            $fromStatement .= ' inner join `Agregators` a on m.agregator = a.id ';
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . ' GROUP BY CAST(r.datetimePeriod as DATE), id , name) B GROUP BY id ORDER BY name';
        //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement);
        return $this->getDi()->getDb2()->query($statement)->fetchAll();
    }

    public function getGeneralSourcesAvg($s, $e, $hs, $he, $permission, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $statement = 'SELECT id, name, CAST(REPLACE(FORMAT(' . $aggFunction . '(clicks),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT(' . $aggFunction . '(conversions),0),",","") as conversions, REPLACE(FORMAT((' . $aggFunction . '(Conversions)/' . $aggFunction . '(Clicks))*100,0),",","") as conversionrate, REPLACE(FORMAT(' . $aggFunction . '(revenues),0),",","") as revenues, REPLACE(FORMAT(' . $aggFunction . '(revenues)/' . $aggFunction . '(Clicks),4),",","") as epc FROM ('
                . 'SELECT r.sourceID as id, s.sourceName as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `SourceXCampaign` r inner join `Mask` m ON m.hash = r.hashmask inner join `Sources` s on r.sourceid = s.id ';
        $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
        $whereStatement = ' WHERE CAST(r.datetimePeriod AS DATE) BETWEEN "' . $s . '" AND "' . $e . '" AND CAST(r.datetimePeriod as TIME) BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask= "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= $permission;
        $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
        if ($agg != 'allaggs' && $agg != '') {
            $fromStatement .= ' inner join `Agregators` a on m.agregator = a.id ';
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . '  GROUP BY CAST(r.datetimePeriod as DATE), id, name) B GROUP BY id ORDER BY clicks desc LIMIT 150';
        //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement);
        return $this->getDi()->getDb2()->query($statement)->fetchAll();
    }

    public function getGeneralAggregatorsAvg($s, $e, $hs, $he, $permission, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $statement = 'SELECT id, name, CAST(REPLACE(FORMAT(' . $aggFunction . '(clicks),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT(' . $aggFunction . '(conversions),0),",","") as conversions, REPLACE(FORMAT((' . $aggFunction . '(Conversions)/' . $aggFunction . '(Clicks))*100,0),",","") as conversionrate, REPLACE(FORMAT(' . $aggFunction . '(revenues),0),",","") as revenues, REPLACE(FORMAT(' . $aggFunction . '(revenues)/' . $aggFunction . '(Clicks),4),",","") as epc FROM ('
                . 'SELECT a.id as id, a.agregator as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `SourceXCampaign` r inner join `Mask` m ON m.hash = r.hashmask inner join `Agregators` a on m.agregator = a.id ';
        $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
        $whereStatement = ' WHERE CAST(r.datetimePeriod AS DATE) BETWEEN "' . $s . '" AND "' . $e . '" AND CAST(r.datetimePeriod as TIME) BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask= "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= $permission;
        $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
        if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . '  GROUP BY CAST(r.datetimePeriod as DATE), id, name) B GROUP BY id ORDER BY clicks desc';
        //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement . "\n");

        $result = $this->getDi()->getDb2()->query($statement)->fetchAll();
        return $result;
    }

    public function getGeneralBySourcesCategory($s, $e, $hs, $he, $permission, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $statement = 'SELECT id, CASE WHEN name = 0 THEN "adult media buying" WHEN name=1 THEN "affiliate" '
                . ' WHEN name=2 THEN "mainstream" ELSE "old affiliates" END as name, CAST(REPLACE(FORMAT(' . $aggFunction . '(clicks),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT(' . $aggFunction . '(conversions),0),",","") as conversions, REPLACE(FORMAT((' . $aggFunction . '(Conversions)/' . $aggFunction . '(Clicks))*100,0),",","") as conversionrate, REPLACE(FORMAT(' . $aggFunction . '(revenues),0),",","") as revenues, REPLACE(FORMAT(' . $aggFunction . '(revenues)/' . $aggFunction . '(Clicks),4),",","") as epc FROM ('
                . 'SELECT CAST(r.datetimePeriod as DATE) as dateperiod, s.affiliate as id,s.affiliate as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `SourceXCampaign` r inner join `Mask` m ON m.hash = r.hashmask inner join `Sources` s on r.sourceid = s.id ';
        $fromStatement .= ' LEFT JOIN  Sources ON Sources.id = r.sourceid ';
        $whereStatement = ' WHERE CAST(r.datetimePeriod AS DATE) BETWEEN "' . $s . '" AND "' . $e . '" AND CAST(r.datetimePeriod as TIME) BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask= "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= $permission;
        $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
        if ($agg != 'allaggs' && $agg != '') {
            $fromStatement .= ' inner join `Agregators` a on m.agregator = a.id ';
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . '  GROUP BY CAST(r.datetimePeriod as DATE), name) B GROUP BY id ORDER BY clicks desc LIMIT 25';
        //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement);
        return $this->getDi()->getDb2()->query($statement)->fetchAll();
    }

    public function getGeneralAggCampaignsAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $statement = 'SELECT id, name, CAST(REPLACE(FORMAT(' . $aggFunction . '(clicks),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT(' . $aggFunction . '(conversions),0),",","") as conversions, REPLACE(FORMAT((' . $aggFunction . '(Conversions)/' . $aggFunction . '(Clicks))*100,0),",","") as conversionrate, REPLACE(FORMAT(' . $aggFunction . '(revenues),0),",","") as revenues, REPLACE(FORMAT(' . $aggFunction . '(revenues)/' . $aggFunction . '(Clicks),4),",","") as epc FROM ('
                . 'SELECT m.hash as id, m.campaign as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `SourceXCampaign` r inner join `Mask` m ON m.hash = r.hashmask inner join `Agregators` a on m.agregator = a.id ';
        $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
        $whereStatement = ' WHERE CAST(r.datetimePeriod AS DATE) BETWEEN "' . $s . '" AND "' . $e . '" AND CAST(r.datetimePeriod as TIME) BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask= "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
        $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
        $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
        $whereStatement .= (isset($aggregators) ? ' AND m.agregator IN (' . $aggregators . ') ' : ' ');
        if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . '  GROUP BY CAST(r.datetimePeriod as DATE), id, name) B GROUP BY id ORDER BY clicks desc';
        //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement . "\n");

        $result = $this->getDi()->getDb2()->query($statement)->fetchAll();
        return $result;
    }

    public function getGeneralAggCampaignsAvgNewDB($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $statement = 'SELECT id, name, CAST(REPLACE(FORMAT(' . $aggFunction . '(clicks),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT(' . $aggFunction . '(conversions),0),",","") as conversions, REPLACE(FORMAT((' . $aggFunction . '(Conversions)/' . $aggFunction . '(Clicks))*100,0),",","") as conversionrate, REPLACE(FORMAT(' . $aggFunction . '(revenues),0),",","") as revenues, REPLACE(FORMAT(' . $aggFunction . '(revenues)/' . $aggFunction . '(Clicks),4),",","") as epc FROM ('
                . 'SELECT m.hash as id, m.campaign as name, SUM(clicks) as clicks, sum(conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `R__HourlyAggCampaignSourceOperator` r inner join `Mask` m ON m.hash = r.hashmask inner join `Agregators` a on m.agregator = a.id ';
        $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
        $whereStatement = ' WHERE datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask= "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
        $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
        $whereStatement .= (isset($aggregators) ? ' AND m.agregator IN (' . $aggregators . ') ' : ' ');
        if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . '  GROUP BY datePeriod, id, name) B GROUP BY id ORDER BY clicks desc';
        //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement . "\n");

        $result = $this->getDi()->getDb4()->query($statement)->fetchAll();
        return $result;
    }

    private function getSourcesByAff($aff, $location) {
        try {
            if (!isset($aff))
                return '';
            $table = 'Sources';
            if ($location === 1)
                $table = 'tinas__Sources';

            if ($aff === 0) {
                return '(' . $table . '.affiliate = 0 OR ' . $table . '.affiliate IS NULL)';
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

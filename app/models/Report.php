<?php

use Phalcon\Mvc\Model;

class Report extends Model {

    /**
     * Initializes correct affiliate table
     */
    public function setReport($table) {
        $this->setSource($table);
    }

    public function getDailyResult($join_type, $country, $countries, $sources, $aff = null, $aggs = null)
    {
    try{
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
						WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '') . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : '') . '
						GROUP BY clickId,fkSource,hashMask,format,adNumber, fkAgregator, campaignName, UPPER(countryCode),insertDate 
					) uu
					GROUP BY fkSource, hashMask,format,adNumber,insertDate) cc


				LEFT JOIN 
				(
				SELECT fkSource, hashMask,SUM(clicks) as clicksCount,format,adNumber, fkAgregator, campaignName, UPPER(countryCode) as countryCode
				FROM ClicksDailyAgg
				WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '') . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : '') . '
				GROUP BY fkSource, hashMask,format,adNumber, fkAgregator, campaignName, UPPER(countryCode)) c
				ON c.hashMask = cc.hashMask and c.fkSource = cc.fkSource and c.format = cc.format and c.adNumber = cc.adNumber and c.fkAgregator = cc.fkAgregator and c.campaignName = cc.campaignName and c.countryCode = cc.countryCode left join Sources ON Sources.id = cc.fkSource ' . ($affRes != '' ? ' WHERE ' . $affRes : '') . ($join_type == 'INNER' ? ' ORDER BY campaign,campaign_country,agregator,sub_id;' : '

				UNION ALL

				SELECT c.fkSource as source,  c.hashMask as c_hash, CONCAT(c.fkSource,"_",c.format,"_",c.adNumber) AS sub_id
						, c.clicksCount as clicks, 0 as conversions, 0 as revenue,c.fkAgregator as agregator, c.campaignName as campaign, c.countryCode as campaign_country, c.insertDate as insert_date
				from (SELECT fkSource, hashMask,format,adNumber, COUNT(*) as convCount, SUM(ccpa) as convCpa, fkAgregator, campaignName, UPPER(countryCode) as countryCode
				FROM ConversionsDaily
				WHERE 0 = 0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '') . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : '') . '
				GROUP BY fkSource, hashMask,format,adNumber, fkAgregator, campaignName, UPPER(countryCode)) cc RIGHT JOIN (SELECT fkSource, hashMask,SUM(clicks) as clicksCount,format,adNumber, fkAgregator, campaignName, UPPER(countryCode) as countryCode,insertDate
				FROM ClicksDailyAgg
				WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '') . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : '') . '
				GROUP BY fkSource, hashMask,format,adNumber,fkAgregator, campaignName, UPPER(countryCode)) c ON c.hashMask = cc.hashMask and c.fkSource = cc.fkSource and c.format = cc.format and c.adNumber = cc.adNumber and c.fkAgregator = cc.fkAgregator and c.campaignName = cc.campaignName and c.countryCode = cc.countryCode left join Sources ON Sources.id = c.fkSource WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . ' AND cc.convCount is null
			ORDER BY campaign,campaign_country,agregator,sub_id;');

        $a = new DateTime();
        $res = $this->getDi()->getDb2()->query($sql)->fetchAll();
        $b = new DateTime();
        $interval = $a->diff($b)->format("%h:%i:%s");
        //mail('pedrorleonardo@gmail.com', 'getDailyResult time:' . $interval, $sql);
        return $res;
    }catch(Exception $ex){
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n". $ex->getMessage());
        }
    }

    public function getDailyResult_rbb($join_type, $country, $countries, $sources, $aff = null, $aggs = null) {
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
						WHERE 0=0 ' . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '') . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : '') . '
						GROUP BY clickId,fkSource,hashMask,format,adNumber,ad, fkAgregator, campaignName, UPPER(countryCode),insertDate 
					) uu
					GROUP BY fkSource, hashMask,format,adNumber, ad,insertDate) cc


				LEFT JOIN 
				(
				SELECT fkSource, hashMask,SUM(clicks) as clicksCount,format,adNumber, ad, fkAgregator, campaignName, UPPER(countryCode) AS countryCode
				FROM ClicksDailyAgg
				WHERE 0=0 ' . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '') . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : '') . '
				GROUP BY fkSource, hashMask,format,adNumber, ad, fkAgregator, campaignName, UPPER(countryCode)) c
				ON c.hashMask = cc.hashMask and c.fkSource = cc.fkSource and c.ad = cc.ad and c.format = cc.format and c.adNumber = cc.adNumber and c.fkAgregator = cc.fkAgregator and c.campaignName = cc.campaignName and c.countryCode = cc.countryCode LEFT JOIN Agregators agg ON cc.fkAgregator = agg.id left join Sources ON Sources.id = cc.fkSource WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . ($join_type == 'INNER' ? ' ORDER BY campaign,campaign_country,agregator,sub_id;' : '

				UNION ALL

				SELECT c.fkSource as source,  c.hashMask as c_hash, CONCAT(c.fkSource,"_",c.format,"_",c.adNumber) AS sub_id,c.ad as ad
						, c.clicksCount as clicks, 0 as conversions, 0 as revenue,c.fkAgregator as agregator, agg2.agregator as agregator_name, c.campaignName as campaign, c.countryCode as campaign_country, c.insertDate as insert_date,
						0.00 as EPC, 
						0.00 as CR
				from (SELECT fkSource, hashMask,format,adNumber,ad, COUNT(*) as convCount, SUM(ccpa) as convCpa, fkAgregator, campaignName, countryCode
				FROM ConversionsDaily
				WHERE 0=0 ' . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '') . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : '') . '
				GROUP BY fkSource, hashMask,format,adNumber,ad, fkAgregator, campaignName, countryCode) cc RIGHT JOIN (SELECT fkSource, hashMask,SUM(clicks) as clicksCount,format,adNumber, ad, fkAgregator, campaignName, UPPER(countryCode) as countryCode,insertDate
				FROM ClicksDailyAgg 
				WHERE 0=0 ' . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '') . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : '') . '
				GROUP BY fkSource, hashMask,format,adNumber,ad,fkAgregator, campaignName, UPPER(countryCode)) c ON c.hashMask = cc.hashMask and c.fkSource = cc.fkSource and c.ad = cc.ad and c.format = cc.format and c.adNumber = cc.adNumber and c.fkAgregator = cc.fkAgregator and c.campaignName = cc.campaignName and c.countryCode = cc.countryCode left join Sources ON Sources.id = c.fkSource LEFT JOIN Agregators agg2 ON cc.fkAgregator = agg2.id WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') . '
				AND cc.convCount is null
			ORDER BY campaign,campaign_country,agregator,sub_id;');
            $a = new DateTime();
            $res = $this->getDi()->getDb2()->query($sql)->fetchAll();
            $b = new DateTime();
            $interval = $a->diff($b)->format("%h:%i:%s");
            //mail('pedrorleonardo@gmail.com', 'getDailyResult_rbb time:' . $interval, $sql);
            
			return $res;
        }
        catch(Exception $ex){
            mail('pedrorleonardo@gmail.com', __METHOD__, $sql . "\n". $ex->getMessage());
        }
    }

    public function getMainResult($sdate, $edate, $filter, $country, $countries, $sources, $aff = null, $aggs = null) {
        $affRes = $this->getSourcesByAff($aff, 1);
        $sql = 'SELECT B.*, agg.agregator as agregator_name FROM '
                . ' (SELECT insert_date, campaign, c_hash,UPPER(campaign_country) as campaign_country, agregator, source, sub_id, ad, clicks, conversions as conversions, revenue as revenue, revenue/clicks as EPC, conversions/clicks as CR FROM Agr__MainReport '
                . 'WHERE insert_date BETWEEN "' . $sdate . '" AND "' . $edate . '" ' . $filter . (isset($country) ? ' AND campaign_country IN (' . $country . ') ' : ' ') . (isset($aggs) ? ' AND agregator IN (' . $aggs . ') ' : '')  .(isset($countries) ? ' AND campaign_country IN (' . $countries . ') ' : ' ') . (isset($sources) ? ' AND source IN (' . $sources . ') ' : ' ') . ') 
                   B LEFT JOIN tinas__Agregators agg ON B.agregator = agg.id LEFT JOIN tinas__Sources ON tinas__Sources.id = B.source WHERE ' . ($affRes != '' ? $affRes : ' 0=0 '). ' ORDER BY insert_date, campaign_country';

        $a = new DateTime();
        $res = $this->getDi()->getDb()->query($sql)->fetchAll();
        $b = new DateTime();
        $interval = $a->diff($b)->format("%h:%i:%s");
        //mail('pedrorleonardo@gmail.com', 'getMainResult time '.$interval, $sql);

        return $res;
    }

    public function getHistoricalPerCountryResult($sdate, $edate, $filter, $country, $countries, $sources, $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 1);
        $sql = 'SELECT UPPER(campaign_country) as Country, SUM(clicks) as Clicks, SUM(conversions) as Conversions, SUM(revenue) as Revenues
                    FROM Agr__MainReport left join tinas__Sources ON Sources.id = Agr__MainReport.source WHERE ' . ($affRes != '' ? $affRes : ' 0=0 '). '
                      AND insert_date BETWEEN "' . $sdate . '" AND "' . $edate . '"' . $filter
                . (isset($country) ? ' AND campaign_country IN (' . $country . ') ' : ' ') . (isset($countries) ? ' AND campaign_country IN (' . $countries . ') ' : ' ') . (isset($sources) ? ' AND source IN (' . $sources . ') ' : ' ')
                . '  GROUP BY UPPER(campaign_country)';
        $res = $this->getDi()->getDb()->query($sql)->fetchAll();
        //mail('pedrorleonardo@gmail.com', 'getHistoricalPerCountryResult', $sql);
        return $res;
    }

    public function getOpDailyResult($join_type, $country, $countries, $sources, $aff = null, $aggs = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $sdate = date('Y-m-d');
        $thisMonthYear = date('mY', strtotime($sdate));
        $sql = 'SELECT "' . $sdate . '"  as insert_date, sub_id, c_hash, campaign, campaign_country, user_country, Z.agregator as agregator, a.agregator as agregatorName, source, operator, isp, os, browser, mobilet, ad , sum(conversions) as conversions, sum(revenue) as revenue,SUM(clicks) as clicks
FROM(

	/*clicks de hoje sem conversao*/
	select CONCAT( c1.fkSource,  "_", c1.format,  "_", c1.adNumber ) AS sub_id, c1.hashMask AS c_hash,c1.campaignName AS campaign, c1.countryCode AS campaign_country,c1.userCountry AS user_country, c1.fkAgregator AS agregator, c1.fkSource AS source,c1.mobileBrand AS operator,c1.ISP AS isp,c1.osType AS os, c1.browserName AS browser,c1.mobileTest AS mobilet,c1.ad AS ad, 0 as conversions, 0 as revenue, count(distinct c1.uniqid) as clicks
	FROM Clicks' . $thisMonthYear . ' c1 
	WHERE c1.insertDate = "'.$sdate.'" '. (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '')  .(isset($country) ? ' AND countryCode IN (' . $country . ') ' : ' ') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : ' ') . (isset($sources) ? ' AND fkSource IN (' . $sources . ') ' : ' ') . '
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
		WHERE cc1.insertDate = "' . $sdate . '" AND cc1.clickId = c1.uniqid '. (isset($aggs) ? ' AND cc1.fkAgregator IN (' . $aggs . ') ' : '')  . (isset($country) ? ' AND cc1.countryCode IN (' . $country . ') ' : ' ') . (isset($countries) ? ' AND cc1.countryCode IN (' . $countries . ') ' : ' ') . (isset($sources) ? ' AND cc1.fkSource IN (' . $sources . ') ' : ' ') . '
		GROUP BY sub_id, c_hash,campaign,campaign_country,user_country,agregator, source, operator, isp, os, browser, mobilet, ad, cc1.clickId
	) C GROUP by sub_id, c_hash,campaign,campaign_country,user_country,agregator, source, operator, isp, os, browser, mobilet, ad
) Z left join Sources ON Sources.id = Z.source INNER JOIN Agregators a ON Z.agregator = a.id
WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ') .'
GROUP BY sub_id, c_hash,campaign,campaign_country,user_country,agregator, source, operator, isp, os, browser, mobilet, ad

ORDER BY campaign,campaign_country,agregator,sub_id';
//WHERE ' . ($affRes != '' ? $affRes : ' 0=0 '). ($join_type == 'INNER' ? ' AND conversions > 0 ' : '').'
        //mail('pedrorleonardo@gmail.com', 'getOpDailyResult', $sql);
        return $this->getDi()->getDb2()->query($sql)->fetchAll();
    }

    public function getOpMainResult($sdate, $edate, $filter, $country, $countries, $sources, $aff = null,$aggs = null) {
        $affRes = $this->getSourcesByAff($aff, 1);
        $edate2 = $edate;
        $res = array();
        $res2 = array();

        $sql = 'SELECT B.* FROM (SELECT insert_date, sub_id,c_hash,campaign, campaign_country, user_country,Agr__OperatorReport2.agregator as agregator,a.agregator as agregatorName, source, operator, isp, os, browser, mobilet, clicks, conversions, revenue,ad FROM Agr__OperatorReport2 INNER JOIN tinas__Agregators a ON a.id = Agr__OperatorReport2.agregator WHERE insert_date BETWEEN "' . $sdate . '" AND "' . $edate . '"'
                . $filter . (isset($country) ? ' AND campaign_country IN (' . $country . ') ' : '') . (isset($aggs) ? ' AND agregator IN (' . $aggs . ') ' : '')  .(isset($countries) ? ' AND campaign_country IN (' . $countries . ') ' : '') . (isset($sources) ? ' AND source IN (' . $sources . ')' : '') . ' )B left join tinas__Sources ON tinas__Sources.id = B.source WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ');
        //mail('pedrorleonardo@gmail.com', 'getOpMainResult2', $sql);
        $res2 = $this->getDi()->getDb()->query($sql)->fetchAll();

        return array_merge($res, $res2);
    }

    public function getMjResult($auth, $sdate, $edate,$sources, $country) {
        $src = '';
        if ($auth['utype'] == 2 and $auth['sources'] != '') {

            $src = ' AND sourceid IN (' . $auth['sources'] . ')';
        }
		
		

		$sqlCountry = '';
		$sqlCountry2 = '';
		if(isset($country)) {
			$sqlCountry = ' AND mjumps.userCountry IN (' . $country . ') ';
			$sqlCountry2 = ' AND mjumps.country IN (' . $country . ') ';
		}
		
		$current = date('mY');
        $sql = 'SELECT insertDate,lpName,page,ad,SUM(lpClick) as lpClicks, SUM(conversion) AS conversions, SUM(click) AS clicks,SUM(amount) AS revenue,subid 
		FROM Agr__MjumpReport mjumps ' .($auth['utype']==2 ? ' INNER JOIN tinas__Sources s ON mjumps.insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" AND mjumps.sourceid = s.id AND s.affiliate = 2 ' : ' WHERE insertDate BETWEEN "' . $sdate . '" AND "' . $edate . '" ' ).(isset($sources) ? ' AND sourceid IN (' . $sources . ')' : ''). $sqlCountry2 . ' GROUP BY insertDate,page,lpName,ad,subid ORDER BY insertDate,conversions';

        //mail('pedrorleonardo@gmail.com','entrei 1',$sql);
        $res = $this->getDi()->getDb()->query($sql)->fetchAll();
        if($edate == date('Y-m-d')){		
			$sql = 'SELECT insertDate,lpName,page,ad,COUNT(*) AS lpClicks, SUM(conversion) AS conversions, SUM(click) AS clicks, SUM(amount) AS revenue,subid
		    FROM LPclick mjumps '. ($auth['utype']==2 ? ' INNER JOIN Sources s ON mjumps.insertDate = "' . $edate . '" AND mjumps.fkSource = s.id AND s.affiliate = 2 ' : ' WHERE insertDate = "' . $edate . '" ' ).(isset($sources) ? ' AND fkSource IN (' . $sources . ')' : ''). ' ' . $sqlCountry . ' GROUP BY lpName,page,ad,subid ORDER BY insertDate,conversions';
            //mail('pedrorleonardo@gmail.com','entrei 2',$sql);

            $res2 = $this->getDi()->getDb2()->query($sql)->fetchAll();
            $res = array_merge($res,$res2);
        }
        return $res;


    }

    public function getPayoutResult($sdate, $edate, $source, $countries, $sources, $aff = null) {

        $sql = 'SELECT p.hash,p.campaign,p.source,p.value,p.insertTimestamp FROM MobisteinPayoutRec p inner join Mask m on p.hash = m.hash '
                . 'WHERE p.insertTimestamp BETWEEN "' . $sdate . ' 00:00:00" AND "' . $edate . ' 23:59:59"' . (isset($source) ? ' and p.source=' . $source : '') . (isset($countries) ? ' and m.country IN (' . $countries . ')' : '') . (isset($sources) ? ' and p.source IN (' . $sources . ')' : '');
        //mail('pedrorleonardo@gmail.com','getPayoutResult', $sql);
        return $this->getDi()->getDb2()->query($sql)->fetchAll();
    }

    public function reportbyhour($sdate, $edate, $country, $countries, $sources, $aggs, $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        //'hashmask,sourceid,subid,ad,datetimePeriod,clicks,conversions,revenues';
        $begindate = date('Y-m-d', strtotime($sdate));
        $begintime = date('H:00:00', strtotime($sdate));
        $enddate = date('Y-m-d', strtotime($edate));
        $endtime = date('H:00:00', strtotime($edate));
        $sql = 'SELECT B.* FROM (SELECT datePeriod as Date, timePeriod as Hour, m.campaign as Campaign,hashmask as Campaign_Hash,m.country as Country,r.sourceid as Source,m.agregator as Agregator,r.subid as Subid,r.clicks as Clicks,r.conversions as Conversions'
                . ',r.revenues as Revenue,r.revenues/r.clicks as EPC,r.conversions/r.clicks as CR,ad as Ad FROM SourceAndCampaigns r inner join Mask m on r.hashmask = m.hash '
                . 'WHERE r.datePeriod BETWEEN "' . $begindate . '" AND "' . $enddate . '" AND r.timePeriod BETWEEN "' . $begintime . '" AND "' . $endtime . '" ' . (isset($country) ? (' and m.country IN (' . $country . ')') : '') . (isset($aggs) ? ' and m.agregator IN (' . $aggs . ')' : '') . (isset($countries) ? ' and m.country IN (' . $countries . ')' : '') . (isset($sources) ? ' and r.sourceid IN (' . $sources . ')' : '') . ') B left join Sources ON Sources.id = B.Source WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ');
        //mail('pedrorleonardo@gmail.com',__METHOD__, $sql);
        return $this->getDi()->getDb2()->query($sql)->fetchAll();
    }

    public function getDailyResAggPerCountry($join_type, $country, $countries, $sources, $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $sql2 = 'SELECT T1.countryCode as Country, SUM(T1.idCount) AS Clicks, SUM(T2.Conversions) as Conversions,  SUM(T2.ccpa + 0) as Revenues
                    FROM    (   SELECT fkAgregator,UPPER(countryCode) as countryCode,fkClient,campaignName,fkSource,format,adNumber,insertDate, COUNT(id) AS idCount
                                FROM ClicksDailyAgg WHERE 0=0 ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') . (isset($sources) ? ' AND fkSource IN (' . $sources . ')' : '') . ' 
                                GROUP BY insertDate, fkAgregator, UPPER(countryCode), fkClient, campaignName, fkSource, format, adNumber
                    ) AS T1 ' .
                $join_type . ' JOIN (
                            SELECT SUM(ccpa + 0) as ccpa,fkAgregator,countryCode,fkClient,campaignName,fkSource,format,adNumber,insertDate,COUNT(DISTINCT clickId) AS Conversions
                            FROM ConversionsDaily
                            GROUP BY insertDate, fkAgregator, countryCode, fkClient, campaignName, fkSource, format, adNumber
                    ) AS T2 ON T1.fkAgregator=T2.fkAgregator AND T1.countryCode=T2.countryCode AND T1.fkClient=T2.fkClient AND T1.campaignName = T2.campaignName AND T1.fkSource=T2.fkSource AND T1.format=T2.format AND T1.adNumber=T2.adNumber AND T1.insertDate=T2.insertDate
                    LEFT JOIN Sources ON T1.fkSource = Sources.id 
                    WHERE ' . ($affRes != '' ? $affRes : ' 0=0 '). '
                    GROUP BY T1.countryCode';
        //mail('pedrorleonardo@gmail.com', 'getDailyResAggPerCountry', $sql2);
        return $this->getDi()->getDb2()->query($sql2)->fetchAll();
    }

    public function getLastDaysAvg($sdate, $country, $countries, $sources, $aff = null, $aggs = null) {
        date_default_timezone_set('Europe/Lisbon');

        $edate = date("Y-m-d", strtotime(date("Y-m-d")));
        $affRes = $this->getSourcesByAff($aff, 0);
        $e = date('Y-m-d', strtotime('-1 day'));
        $sql = 'SELECT AVG(clicks) as avgClicks, AVG(conversions) as avgCon, AVG(revenues) as avgRev, AVG(EPC) as avgEPC, AVG(conversionRate) as avgCR
                        FROM (SELECT SUM(clicks) as clicks, sum(conversions) as conversions, SUM(conversions)/SUM(clicks) as conversionRate, SUM(revenues) as revenues, SUM(revenues)/SUM(clicks) as EPC '
                . 'FROM `SourceXCampaign` r  inner join `Mask` m on CAST(datetimePeriod AS DATE) BETWEEN "' . $sdate . '" AND "' . $e . '" AND CAST(datetimePeriod AS TIME) BETWEEN "00:00:00" AND  "' . date("H:i:s") . '" AND r.hashmask = m.hash LEFT JOIN  Sources ON Sources.id = r.sourceid '
                . 'WHERE 0=0 '
                . (isset($country) ? ' AND m.country IN (' . $country . ') ' : '') . (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : '') . (isset($aggs) ? ' AND m.agregator IN (' . $aggs . ') ' : '')  . (isset($sources) ? ' AND sourceid IN (' . $sources . ')' : '')
                . ' AND ' . ($affRes != '' ? $affRes : ' 0=0 ') . ' GROUP BY CAST(datetimePeriod AS DATE) ) as temp';
        $a = new DateTime();
        $res = $this->getDi()->getDb2()->query($sql)->fetchAll();
        $b = new DateTime();
        $interval = $a->diff($b)->format("%h:%i:%s");
        //mail('pedrorleonardo@gmail.com', 'getLastDaysAvg2 time ' . $interval, $sql);
        return $res;
        //}
    }

    public function getNetworkResult($startDate, $endDate, $convTableMonth, $source, $country, $countries, $netArray, $aff = null) {
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));
        $countryParam = '';
        $countryDailyParam = '';
        $selectDailyCountry = ',t1.countryCode as country';
        $groupByCountry = ', country';

        if ($country != 'ALL') {
            $selectDailyCountry = '';
            $countryParam = ' AND country = "' . $country . '" ';
            $countryDailyParam = 'AND t1.countryCode ="' . $country . '"';
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

        return array_merge($res2,$res);
    }

    private function addtableprefix(&$item1) {
        $item1 = 't1.' . $item1;
    }

    public function getDayTotal($country, $countries, $sources, $aggs, $aff = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $query1 = 'SELECT SUM(clicks) AS clicks '
                . ' FROM ClicksDailyAgg LEFT JOIN Sources ON ClicksDailyAgg.fkSource = Sources.id '
                . ' WHERE ' . ($affRes != '' ? $affRes : ' 0=0 '). ' ' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : ''). (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') . (isset($sources) ? ' AND fkSource IN (' . $sources . ')' : '');
        $query2 = 'SELECT COUNT( DISTINCT clickId) AS conversions, SUM(ccpa) AS revenue '
                . ' FROM ConversionsDaily LEFT JOIN Sources ON Sources.id = ConversionsDaily.fkSource '
                . ' WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ').
                (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '') . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '') .
                (isset($sources) ? ' AND fkSource IN (' . $sources . ')' : '')
                . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ') ' : '');
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

    public function getDaysTotal($sdate, $edate, $country, $countries, $sources, $aggs, $aff = null) {

        if ($sdate < date('Y-m-d')) {
            $affRes = $this->getSourcesByAff($aff, 1);
            $query1 = ' SELECT SUM(clicks) as clicks, SUM(revenue) as revenue, SUM(conversions) as conversions FROM ( '
                    . ' SELECT source, clicks, revenue, conversions '
                    . ' FROM  `Agr__MainReport` '
                    . ' WHERE insert_date BETWEEN "' . $sdate . '" AND "' . $edate . '" ' . (isset($country) ? ' AND campaign_country IN (' . $country . ') ' : '')
                    . (isset($aggs) ? ' AND agregator IN( ' . $aggs . ') ' : '')
                    . (isset($countries) ? ' AND campaign_country IN (' . $countries . ') ' : '')
                    . (isset($sources) ? ' AND source IN (' . $sources . ')' : '') . ') B LEFT JOIN `tinas__Sources` ON B.source = `tinas__Sources`.id '
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
                    . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '')
                    . (isset($sources) ? ' AND fkSource IN (' . $sources . ')' : '')
                    . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ')' : '')
                    . ' GROUP BY fkSource) B LEFT JOIN `Sources` ON B.source = `Sources`.id '
                    . ' WHERE ' . ($affRes != '' ? $affRes : ' 0=0 ');
            $query3 = 'SELECT COUNT(clickId) as conversions,SUM(ccpa) as revenue FROM (SELECT MAX(clickId) as clickId, MAX(ccpa) as ccpa,fkSource as source '
                    . ' FROM ConversionsDaily '
                    . ' WHERE 0 = 0' . (isset($country) ? ' AND countryCode IN (' . $country . ') ' : '')
                    . (isset($countries) ? ' AND countryCode IN (' . $countries . ') ' : '')
                    . (isset($aggs) ? ' AND fkAgregator IN (' . $aggs . ')' : '')    
                    . (isset($sources) ? ' AND fkSource IN (' . $sources . ')' : '')
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

    public function getGeneralCountryAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null) {
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
        $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
        $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
        $whereStatement .= (isset($sourcetype) ? ' AND Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
		$whereStatement .= (isset($aggregators) ? ' AND m.agregator IN (' . $aggregators . ') ' : ' ');
        if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
            $fromStatement .= ' inner join `Agregators` a on m.agregator = a.id ';
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . ' GROUP BY CAST(r.datetimePeriod as DATE), id , name) B GROUP BY id ORDER BY name';
        //mail('pedrorleonardo@gmail.com', 'getGeneralCountryAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement);
        return $this->getDi()->getDb2()->query($statement)->fetchAll();
    }

    public function getGeneralSourcesAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null) {
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
        $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
        $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
        $whereStatement .= (isset($aggregators) ? ' AND m.agregator IN (' . $aggregators . ') ' : ' ');
        $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
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

    public function getGeneralAggregatorsAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype) {
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

    public function getGeneralBySourcesCategory($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $sourcetype = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $statement = 'SELECT id, CASE WHEN name = 0 THEN "adult media buying" WHEN name=1 THEN "affiliate" '
            .' WHEN name=2 THEN "mainstream" ELSE "old affiliates" END as name, CAST(REPLACE(FORMAT(' . $aggFunction . '(clicks),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT(' . $aggFunction . '(conversions),0),",","") as conversions, REPLACE(FORMAT((' . $aggFunction . '(Conversions)/' . $aggFunction . '(Clicks))*100,0),",","") as conversionrate, REPLACE(FORMAT(' . $aggFunction . '(revenues),0),",","") as revenues, REPLACE(FORMAT(' . $aggFunction . '(revenues)/' . $aggFunction . '(Clicks),4),",","") as epc FROM ('
                . 'SELECT CAST(r.datetimePeriod as DATE) as dateperiod, s.affiliate as id,s.affiliate as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `SourceXCampaign` r inner join `Mask` m ON m.hash = r.hashmask inner join `Sources` s on r.sourceid = s.id ';
        $fromStatement .= ' LEFT JOIN  Sources ON Sources.id = r.sourceid ';
        $whereStatement = ' WHERE CAST(r.datetimePeriod AS DATE) BETWEEN "' . $s . '" AND "' . $e . '" AND CAST(r.datetimePeriod as TIME) BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask= "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
        $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
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

    public function getGeneralOperatorsAvg($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $operator = null, $sourcetype = null) {
        $affRes = $this->getSourcesByAff($aff, 1);
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $statement = 'SELECT CONCAT(UPPER(COALESCE(country,"-")),"_",COALESCE(name,"-")) as name, CAST(REPLACE(FORMAT(' . $aggFunction . '(clicks),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT(' . $aggFunction . '(conversions),0),",","") as conversions, REPLACE(FORMAT((' . $aggFunction . '(Conversions)/' . $aggFunction . '(Clicks))*100,0),",","") as conversionrate, REPLACE(FORMAT(' . $aggFunction . '(revenues),0),",","") as revenues, REPLACE(FORMAT(' . $aggFunction . '(revenues)/' . $aggFunction . '(Clicks),4),",","") as epc FROM ('
                . 'SELECT r.country as country, r.operator as name, SUM(Clicks) as clicks, sum(Conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `OperatorMaskSourceByHour` r inner join `tinas__Mask` m ON m.hash = r.hashmask inner join `tinas__Agregators` a on m.agregator = a.id ';
        $fromStatement .= (isset($aff) ? ' LEFT JOIN  tinas__Sources ON tinas__Sources.id = r.sourceid ' : ' LEFT JOIN  tinas__Sources ON tinas__Sources.id = r.sourceid ' );
        $whereStatement = ' WHERE dateInsert BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask= "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
        $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
        $whereStatement .= (($operator != 'allops' && isset($operator)) ? ' AND r.operator LIKE "' . $operator . '" ' : ' ');
        $whereStatement .= (isset($sourcetype) ? ' AND tinas__Sources.affiliate IN (' . $sourcetype . ') ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
        if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . '  GROUP BY dateInsert, country, name) B GROUP BY country,name ORDER BY clicks desc';
        //mail('pedrorleonardo@gmail.com', 'getGeneralOperatorsAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement . "\n");

        $result = $this->getDi()->getDb()->query($statement)->fetchAll();
        return $result;
    }

    public function getAggData($sdate, $edate, $selectCountry, $selectAgg, $selectCampaign, $selectOperator, $orderby, $countries, $sources, $aggregators, $aggsid = null, $campaignsid = null, $operators = null, $aff = null, $ccs = null) {

        $selectStatement = ',insert_date as `Date`';
        $selectStatement2 = ',t1.insertDate as `Date`';
        $selectStatement3 = ', `Date`';
        $selectStatement4 = ',t1.insertDate as `Date`';
        $groupBy = ' `Date`';
        if (isset($selectCountry)) {
            $selectStatement .= ',campaign_country as Country';
            $selectStatement2 .= ',t1.countryCode as Country ';
            $selectStatement3 .= ',Country';
            $selectStatement4 .= ',t1.countryCode as Country ';
            $groupBy .= ',Country';
        }
        if (isset($selectAgg)) {
            $selectStatement .= ',t1.agregator as Aggregator, t2.agregator as AggregatorName';
            $selectStatement2 .= ',t1.fkAgregator as Aggregator, t3.agregator as AggregatorName';
            $selectStatement3 .= ',Aggregator, AggregatorName';
            $selectStatement4 .= ',t1.fkAgregator as Aggregator, t3.agregator as AggregatorName';
            $groupBy .= ',Aggregator';
        }
        if (isset($selectCampaign)) {
            $selectStatement .= ',campaign as Campaign';
            $selectStatement2 .= ',t1.campaignName as Campaign';
            $selectStatement3 .= ',Campaign';
            $selectStatement4 .= ',t1.campaignName as Campaign';
            $groupBy .= ',Campaign';
        }
        if (isset($selectOperator)) {
            $selectStatement .= ', operator as Operator';
            $selectStatement2 .= ', t1.mobileBrand as Operator';
            $selectStatement3 .= ',Operator';
            $selectStatement4 .= ', "" as Operator';
            $groupBy .= ',Operator';
        }
        if ($sdate < date('Y-m-d')) {
            $affRes = $this->getSourcesByAff($aff, 1);
            $statement = 'SELECT SUM(clicks) as clicks, sum(conversions) as conversions, TRUNCATE(IFNULL(SUM(revenue),0),2) as revenues ';
            $whereStatement = ' FROM `Agr__OperatorReport2` t1 left join `tinas__Agregators` t2 ON t1.agregator = t2.id '
                    . ((isset($aff)) ? ' LEFT JOIN  tinas__Sources ON tinas__Sources.id = t1.source ' : '' )
                    . ' WHERE insert_date BETWEEN "' . $sdate . '" AND "' . $edate . '" '
                    . ((isset($aggsid) && $aggsid != 'allaggs') ? ' AND t1.agregator IN ( ' . $aggsid . ') ' : '') . ((isset($campaignsid) && $campaignsid != 'allcampaigns') ? ' AND c_hash IN ( ' . $campaignsid . ') ' : '')
                    . ((isset($ccs) && $ccs != 'ALL') ? ' AND campaign_country IN ( ' . $ccs . ') ' : '')
                    . ((isset($operators) && $operators != 'alloperators') ? ' AND operator IN (' . $operators . ') ' : ' ')
                    . (isset($countries) ? ' AND campaign_country IN (' . $countries . ') ' : ' ')
                    . (isset($sources) ? ' AND source IN (' . $sources . ') ' : ' ')
                    . (isset($aggregators) ? ' AND t1.agregator IN (' . $aggregators . ') ' : ' ')
                    . ($affRes != '' ? ' AND ' . $affRes : '');
            $sql = $statement . $selectStatement . $whereStatement . ' GROUP BY ' . $groupBy . ' ORDER BY date,' . $orderby . ' DESC';
            //mail('pedrorleonardo@gmail.com', 'aggregator', $sql);
            $result = $this->getDi()->getDb()->query($sql)->fetchAll();
        }
        if ($edate == date('Y-m-d')) {
            $affRes = $this->getSourcesByAff($aff, 0);
            $statement2 = 'SELECT count(t1.id) as clicks, TRUNCATE(IFNULL(MAX(ccpa),0)*COUNT(DISTINCT t2.clickId),2) AS revenues, COUNT(DISTINCT t2.clickId) as conversions ';
            $whereStatement2 = ' FROM Clicks' . date('mY') . ' t1 LEFT JOIN Conversions' . date('mY')
                    . ' t2 on t1.uniqid = t2.clickid left join Agregators t3 on t3.id=t1.fkAgregator '
                    . ((isset($aff)) ? ' LEFT JOIN  Sources ON Sources.id = t1.fkSource ' : '' )
                    . ' WHERE t1.insertDate = "' . date('Y-m-d') . '" '
                    . ((isset($aggsid) && $aggsid != 'allaggs') ? ' AND t1.fkAgregator IN ( ' . $aggsid . ') ' : '') . ((isset($campaignsid) && $campaignsid != 'allcampaigns') ? ' AND t1.hashMask IN ( ' . $campaignsid . ') ' : '')
                    . ((isset($operators) && $operators != 'alloperators') ? ' AND t1.mobileBrand IN (' . $operators . ') ' : ' ')
                    . (isset($countries) ? ' AND t1.countryCode IN (' . $countries . ') ' : ' ')
                    . ((isset($ccs) && $ccs != 'ALL' ) ? ' AND t1.countryCode IN (' . $ccs . ') ' : ' ')
                    . (isset($sources) ? ' AND t1.fkSource IN (' . $sources . ') ' : ' ')
                    . (isset($aggregators) ? ' AND t1.fkAgregator IN (' . $aggregators . ') ' : ' ')
                    . ($affRes != '' ? ' AND ' . $affRes : '');
            $sql2 = $statement2 . $selectStatement2 . $whereStatement2 . ' GROUP BY ' . $groupBy; // . ' ORDER BY date,'. $orderby .' DESC';

            $sql2 = 'SELECT SUM(clicks) as clicks, SUM(revenues) AS revenues, SUM(conversions) as conversions ' . $selectStatement3 . ' FROM (' . $sql2
                    . ' UNION ALL '
                    . 'SELECT 0 as clicks, TRUNCATE(IFNULL(MAX(ccpa),0),2)*COUNT(DISTINCT t1.clickId) AS revenues, COUNT(DISTINCT t1.clickId) as conversions' . $selectStatement4
                    . ' FROM Conversions' . date('mY') . ' t1 LEFT JOIN Clicks' . date('mY') . ' t2 ON t1.clickId = t2.uniqid and t1.insertDate = "' . date('Y-m-d') . '" and ( t2.insertDate = "' . date('Y-m-d') . '" OR t2.insertDate IS NULL) left join Agregators t3 on t3.id=t1.fkAgregator '
                    . ((isset($aff)) ? ' LEFT JOIN  Sources ON Sources.id = t1.fkSource ' : '' )
                    . ' WHERE t2.uniqid IS NULL AND t1.insertDate =  "' . date('Y-m-d') . '" '
                    . ((isset($aggsid) && $aggsid != 'allaggs') ? ' AND t1.fkAgregator IN ( ' . $aggsid . ') ' : '') . ((isset($campaignsid) && $campaignsid != 'allcampaigns') ? ' AND t1.hashMask IN ( ' . $campaignsid . ') ' : '')
                    . ((isset($operators) && $operators != 'alloperators') ? ' AND t2.mobileBrand IN (' . $operators . ') ' : ' ')
                    . (isset($countries) ? ' AND t1.countryCode IN (' . $countries . ') ' : ' ')
                    . ((isset($ccs) && $ccs != 'ALL' ) ? ' AND t1.countryCode IN (' . $ccs . ') ' : ' ')
                    . (isset($sources) ? ' AND t1.fkSource IN (' . $sources . ') ' : ' ')
                    . (isset($aggregators) ? ' AND t1.fkAgregator IN (' . $aggregators . ') ' : ' ')
                    . ($affRes != '' ? ' AND ' . $affRes : '')
                    . ' GROUP BY ' . $groupBy . ' ) B '
                    . 'GROUP BY ' . $groupBy . ' ORDER BY date,' . $orderby . ' DESC';

            //mail('pedrorleonardo@gmail.com','sql2',$sql2);
            $result2 = $this->getDi()->getDb2()->query($sql2)->fetchAll();

            if (!isset($result))
                return $result2;
            return array_merge($result, $result2);
        }
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
    
    public function getGeneralOperatorsAvgNewDB($s, $e, $hs, $he, $countries, $sources, $aggregators, $totalsOrAvg, $country = 'ALL', $agg = 'allaggs', $campaign = 'allcampaigns', $source = 'allsrc', $aff = null, $operator = null) {
        $affRes = $this->getSourcesByAff($aff, 0);
        $aggFunction = ($totalsOrAvg == 'SUM') ? 'SUM' : 'AVG';
        $statement = 'SELECT CONCAT(UPPER(COALESCE(country,"-")),"_",COALESCE(name,"-")) as name, CAST(REPLACE(FORMAT(' . $aggFunction . '(clicks),0),",","") AS UNSIGNED) as clicks, REPLACE(FORMAT(' . $aggFunction . '(conversions),0),",","") as conversions, REPLACE(FORMAT((' . $aggFunction . '(Conversions)/' . $aggFunction . '(Clicks))*100,0),",","") as conversionrate, REPLACE(FORMAT(' . $aggFunction . '(revenues),0),",","") as revenues, REPLACE(FORMAT(' . $aggFunction . '(revenues)/' . $aggFunction . '(Clicks),4),",","") as epc FROM ('
                . 'SELECT m.country as country, r.operator as name, SUM(clicks) as clicks, sum(conversions) as conversions, SUM(revenues) as revenues ';
        $fromStatement = ' FROM `R__HourlyAggCampaignSourceOperator` r inner join `Mask` m ON m.hash = r.hashmask inner join `Agregators` a on m.agregator = a.id ';
        $fromStatement .= (isset($aff) ? ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' : ' LEFT JOIN  Sources ON Sources.id = r.sourceid ' );
        $whereStatement = ' WHERE datePeriod BETWEEN "' . $s . '" AND "' . $e . '" AND r.timePeriod BETWEEN "' . $hs . '" AND "' . $he . '" ';
        $whereStatement .= (($country != 'ALL' && isset($country)) ? ' AND m.country IN ("' . $country . '") ' : ' ');
        $whereStatement .= (($campaign != 'allcampaigns' && $campaign != '') ? ' AND r.hashmask= "' . $campaign . '" ' : ' ');
        $whereStatement .= (($source != 'allsrc' && $source != '' ) ? ' AND r.sourceid = ' . $source . ' ' : ' ');
        $whereStatement .= (isset($countries) ? ' AND m.country IN (' . $countries . ') ' : ' ');
        $whereStatement .= (isset($sources) ? ' AND r.sourceid IN (' . $sources . ') ' : ' ');
        $whereStatement .= (($operator != 'allops' && isset($operator)) ? ' AND r.operator LIKE "' . $operator . '" ' : ' ');
        $whereStatement .= $affRes != '' ? ' AND ' . $affRes : '';
        if (($agg != 'allaggs' && $agg != '') || isset($aggregators)) {
            $whereStatement .= (($agg != 'allaggs' && isset($agg)) ? ' AND a.id IN (' . $agg . ') ' : ' ');
            $whereStatement .= (isset($aggregators) ? ' AND a.id IN (' . $aggregators . ') ' : ' ');
        }
        $statement .= $fromStatement . $whereStatement . '  GROUP BY datePeriod, country, name) B GROUP BY country,name ORDER BY clicks desc';
        //mail('pedrorleonardo@gmail.com', 'getGeneralOperatorsAvg', '$country =' . $country . '$agg=' . $agg . ', $campaign=' . $campaign . '$source=' . $source . "\n" . '  statement =' . $statement . "\n");

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
                return '('.$table . '.affiliate = 0 OR ' . $table . '.affiliate IS NULL)';
            } else if ($aff === 1) {
                return '('.$table . '.affiliate = 1)';
            } else if ($aff === 2) {
                return '('.$table . '.affiliate = 2)';
            } else if ($aff === 3) {
                return '('.$table . '.affiliate = 3 OR ' . $table . '.affiliate IS NULL OR ' . $table . '.affiliate = 0 OR ' . $table . '.affiliate = 2)';
            } else if ($aff === 4) {
                return '('.$table . '.affiliate = 3 OR ' . $table . '.affiliate IS NULL OR ' . $table . '.affiliate = 0)';
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
	
	public function getData($countries,$carriers,$date,$type=0){
		$sqlstring='';
		$sql1=array();
		$sql2=array();
		if(!empty($countries)){   //countries need to be selected
			$sqlstring.='countryCode=:countryCode';
			$sql1[':countryCode']=$countries;
			$sql2[':countryCode']=\Phalcon\Db\Column::TYPE_VARCHAR;
			//if(!empty($carriers)){
				
				
			if($type==1)
			{	
			//this is to check for the excel option
				if(!empty($carriers) && isset($carriers)){
					
					$_in_params = array();
					foreach ($carriers as $idx_in => $value_in)
						{
							$_in_params[] = ':param_in_'.$idx_in;
							$sql1[':param_in_'.$idx_in] = $value_in;
							$sql2[':param_in_'.$idx_in]=\Phalcon\Db\Column::TYPE_VARCHAR;
						}
					$sqlstring.=' AND mobileBrand IN ('.join(",",$_in_params).')' ;
				}
				
			}
			else
			{
				//if nothing is selected multiselect send 'null' string so for the search click option it need to be check like this
				if($carriers!='null'){
				
					$new_carriers=explode(',',$carriers);
					$_in_params = array();
					foreach ($new_carriers as $idx_in => $value_in)
						{
							$_in_params[] = ':param_in_'.$idx_in;
							$sql1[':param_in_'.$idx_in] = $value_in;
							$sql2[':param_in_'.$idx_in]=\Phalcon\Db\Column::TYPE_VARCHAR;
						}
					$sqlstring.=' AND mobileBrand IN ('.join(",",$_in_params).')' ;
				}
			}
			if(!empty($date)){
				
				//explode date 
				
				$date = explode('-', $date);
				if (count($date) === 1) {
					$sqlstring.=' AND insertDate=:date';
					$date=$date[0];
					$date=implode('-',explode('/',$date));
					$sql1[':date']=$date;
					$sql2[':date']=\Phalcon\Db\Column::TYPE_DATE;	
				}
				else{
					
					$sqlstring.=' AND insertDate BETWEEN :date1 AND :date2';
					$date1=$date[0];
					$date1=implode('-',explode('/',$date1));
					$date2=$date[1];
					$date2=implode('-',explode('/',$date2));
					$sql1[':date1']=$date1;
					$sql2[':date1']=\Phalcon\Db\Column::TYPE_DATE;
					$sql1[':date2']=$date2;
					$sql2[':date2']=\Phalcon\Db\Column::TYPE_DATE;
						
				}
					
				//for add
				//$sqlstring.=" AND  (ad IS NULL OR ad='') ";	
			}
			
			try {
				$sql = 'SELECT affiliate,sum(clicks) as clicks,sum(conversions)as conversions,ROUND((sum(ccpa)/sum(conversions)),4) as CPA,ROUND((sum(conversions)/sum(clicks))*100,4) as CR,ROUND(sum(ccpa)/sum(clicks),4) as EPC, sum(ccpa) as revenue FROM MainReport2 INNER JOIN Sources as s ON s.id=fkSource'.(!empty($sqlstring)? " WHERE " . $sqlstring :'').' GROUP BY affiliate';
				//echo $sql;
				//print_r($sql1);
				$statement = $this->getDi()->getDb4()->prepare($sql);
				$exe = $this->getDi()->getDb4()->executePrepared($statement,$sql1,$sql2);
				return $exe->fetchAll(PDO::FETCH_ASSOC);
            
			} catch (PDOException $e) {
				return $e->getMessage();
			} 
		}	
		else{
			
			return false;
			
		}
	}
	
	
	
	//for total
	
	public function getTotal($countries,$carriers,$date,$type=0){
		$sqlstring='';
		$sql1=array();
		$sql2=array();
		if(!empty($countries)){   //countries need to be selected
			$sqlstring.='countryCode=:countryCode';
			$sql1[':countryCode']=$countries;
			$sql2[':countryCode']=\Phalcon\Db\Column::TYPE_VARCHAR;
			if($type==1)
			{	
				if(!empty($carriers) && isset($carriers)){
					
					$_in_params = array();
					foreach ($carriers as $idx_in => $value_in)
						{
							$_in_params[] = ':param_in_'.$idx_in;
							$sql1[':param_in_'.$idx_in] = $value_in;
							$sql2[':param_in_'.$idx_in]=\Phalcon\Db\Column::TYPE_VARCHAR;
						}
					$sqlstring.=' AND mobileBrand IN ('.join(",",$_in_params).')' ;
				}
				
			}
			else
			{
				if($carriers!='null'){
				
					$new_carriers=explode(',',$carriers);
					$_in_params = array();
					foreach ($new_carriers as $idx_in => $value_in)
						{
							$_in_params[] = ':param_in_'.$idx_in;
							$sql1[':param_in_'.$idx_in] = $value_in;
							$sql2[':param_in_'.$idx_in]=\Phalcon\Db\Column::TYPE_VARCHAR;
						}
					$sqlstring.=' AND mobileBrand IN ('.join(",",$_in_params).')' ;
				}
			}
			if(!empty($date)){	
				$date = explode('-', $date);
				
				//this may not be needed for the current daterange picker. only use the else section
					if (count($date) === 1) {
					$sqlstring.=' AND insertDate=:date';
					$date=$date[0];
					$date=implode('-',explode('/',$date));
					$sql1[':date']=$date;
					$sql2[':date']=\Phalcon\Db\Column::TYPE_DATE;	
				}
				else{
					
					$sqlstring.=' AND insertDate BETWEEN :date1 AND :date2';
					$date1=$date[0];
					$date1=implode('-',explode('/',$date1));
					$date2=$date[1];
					$date2=implode('-',explode('/',$date2));
					$sql1[':date1']=$date1;
					$sql2[':date1']=\Phalcon\Db\Column::TYPE_DATE;
					$sql1[':date2']=$date2;
					$sql2[':date2']=\Phalcon\Db\Column::TYPE_DATE;
						
				}
					
				//for add
				//$sqlstring.=" AND  (ad IS NULL OR ad='') ";	
					
			}
			
			try {
				//$sql = 'SELECT sum(clicks) as clicks,sum(conversions)as conversions,ROUND((sum(ccpa)/sum(conversions)),4) as CPA,ROUND((sum(conversions)/sum(clicks))*100,4) as CR,ROUND(sum(ccpa)/sum(clicks),4) as EPC, sum(ccpa) as revenue FROM MainReport2 as m INNER JOIN Sources as s ON s.id=fkSource'.(!empty($sqlstring)? " WHERE " . $sqlstring :'');
				$sql = 'SELECT sum(clicks) as clicks,sum(conversions)as conversions,ROUND((sum(ccpa)/sum(conversions)),4) as CPA,ROUND((sum(conversions)/sum(clicks))*100,4) as CR,ROUND(sum(ccpa)/sum(clicks),4) as EPC, sum(ccpa) as revenue FROM MainReport2 '.(!empty($sqlstring)? " WHERE " . $sqlstring :'');
				$statement = $this->getDi()->getDb4()->prepare($sql);
				$exe = $this->getDi()->getDb4()->executePrepared($statement,$sql1,$sql2);
				return $exe->fetchAll(PDO::FETCH_ASSOC);
            
			} catch (PDOException $e) {
				return $e->getMessage();
			} 
		}		
	}

}

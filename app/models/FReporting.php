<?php

use Phalcon\Mvc\Model;

class FReporting extends Model {

    public function initialize() {
        $this->setConnectionService('db');
    }

    public function getSource() {
        return "FinancialReport";
    }

    public function get_agregators($type, $auth) {

        $array_ret = array();

        if ($type == 1) {
            $statement = $this->getDi()->getDb4()->prepare("SELECT a.id , agregator, trackingParam, sinfo, custom_url, c.currency as currency, currencyRequestKey, payoutRequestKey FROM Agregators a LEFT JOIN C__aggmetadata c ON a.id = c.agg");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        } else if ($type == 2) {
            $statement = $this->getDi()->getDb4()->prepare("SELECT * FROM users WHERE id='" . $auth['id'] . "'");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

            if (isset($array_ret[0]['aggregators']) && $array_ret[0]['aggregators'] != '' && $array_ret[0]['aggregators'] != NULL) {

                $aggByUser = '';
                foreach (explode(',', $array_ret[0]['aggregators']) as $agg) {
                    $aggByUser .= "'" . $agg . "',";
                }
                $aggByUser = substr($aggByUser, 0, -1);

                $statement = $this->getDi()->getDb4()->prepare("SELECT a.id , agregator, trackingParam, sinfo, custom_url, c.currency as currency, currencyRequestKey, payoutRequestKey FROM Agregators a LEFT JOIN C__aggmetadata c ON a.id = c.agg WHERE a.id IN (" . $aggByUser . ")");
                $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
                $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return $this->get_agregators(1, $auth);
            }
        }

        return $array_ret;
    }

    public function get_infoAffs($data_array, $countriesSql, $aggregatorsSql, $groupbySql, $sdate, $edate) {

        if ($groupbySql != '') {
            $groupbySql = ' group by ' . $groupbySql;
        } else {
            $groupbySql = ' group by advertiser_id ';
        }

        $statement = $this->getDi()->getDb5()->prepare("SELECT DATE_FORMAT(insertDate,'%M %Y') as month, affiliate_id, advertiser_id, adv.name as aggName, CONCAT(advertiser_id, ' - ' ,adv.name) as aggInfo, CONCAT(affiliate_id, ' - ' ,usr.company_name) as affInfo, SUM(steincpa) AS revenue, SUM(cpa) as investment, (SUM(steincpa) - SUM(cpa)) as margin, campaign_country FROM ClicksAgg LEFT JOIN Advertisers adv ON advertiser_id = adv.advid LEFT JOIN users usr ON usr.affid = affiliate_id WHERE 0=0 " . $countriesSql . $aggregatorsSql . " AND insertDate BETWEEN '" . $sdate . "' AND '" . $edate . "' " . $groupbySql);

        $exe = $this->getDi()->getDb5()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function getAffiliates() {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id , sourceName FROM Sources WHERE affiliate='1'");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function getInformationAgregators($tdate) {
        $date = explode('-', $tdate);

        $statement = $this->getDi()->getDb2()->prepare(
                "SELECT agta.agregator as agg_name, b.fkAgregator as agg_id, b.countryCode, SUM(revenue) AS revenue, SUM(conv) AS conversions, country, SUM(duplicated) as duplicated FROM (
			SELECT fkAgregator ,countryCode, MAX(ccpa) AS revenue , MAX(countryCode) AS country , 1 AS conv, COUNT(clickId)-1 as duplicated FROM ConversionsYeti WHERE insertDate BETWEEN '" . $date[0] . "-" . $date[1] . "-01' AND '" . $tdate . "' GROUP BY clickId
		) b INNER JOIN Agregators agta ON b.fkAgregator=agta.id GROUP BY country, b.fkAgregator, b.countryCode");

        $exe = $this->getDi()->getDb2()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function insertIntoWhat($final_info) {
        $informationToInsert = '';
        foreach ($final_info as $agregator) {
            $informationToInsert .= "( '$agregator[agg_id]', '$agregator[agg_name]','$agregator[accounts_ids]', '$agregator[accounts_names]', '$agregator[country]', '$agregator[mobistein_leads]', '$agregator[duplicated]', '$agregator[client_leads]', '$agregator[mobistein_amount]', '$agregator[client_amount]', '$agregator[client_currency]', '$agregator[client_amount_dollar]', '$agregator[difRevenue]', '$agregator[difLeads]', '$agregator[difPercentRevenue]' , '$agregator[difPercentLeads]', '$agregator[total_amount_invoiced]', '$agregator[type_info]' , '$agregator[comments]', '$agregator[service_date]' ),";
        }
        $informationToInsert = substr($informationToInsert, 0, -1);


        $statement = $this->getDi()->getDb()->prepare("INSERT INTO FinancialReport(aggregatorId, aggregatorName, accountId, accountName, country, mobisteinLeads, duplicateds, clientLeads, mobisteinAmount, clientAmount, clientCurrency, clientAmountDollar, difRevenue, difLeads, difPercentRevenue, difPercentLeads, totalAmountInvoiced, typeInfo, comments, serviceDate) VALUES " . $informationToInsert);

        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
    }

    public function get_accountsMulti($aggregators) {
        $array_ret = array();

        $statement = $this->getDi()->getDb4()->prepare("SELECT id, username,  aggregators FROM users WHERE userlevel='3'");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        $user_info = array();
        foreach ($aggregators AS $agg) {
            $array_users_id = array();
            $array_users_name = array();
            foreach ($array_ret AS $user) {
                $aggreg = explode(',', $user['aggregators']);

                if (in_array($agg, $aggreg)) {
                    array_push($array_users_id, $user['id']);
                    array_push($array_users_name, $user['username']);
                }
            }
            $user_info[$agg] = array(
                'ids' => implode(',', $array_users_id),
                'usernames' => implode(',', $array_users_name)
            );
        }

        return $user_info;
    }

    public function convertAmountToDollar($client_amount, $total_amount_invoiced, $currency, $tdate) {

        $today = $tdate;
        $date = explode('-', $tdate);
        $firstday = $date[0] . '-' . $date[1] . '-01';

        $statement = $this->getDi()->getDb4()->prepare("SELECT currency, avg(cast(rate as decimal(10,4))) as rate FROM CurrencyHistory WHERE currency LIKE '" . $currency . "' AND insertDate BETWEEN '" . $firstday . "'  AND  '" . $today . "' GROUP BY currency");

        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        $convertedAmount = $client_amount * $array_ret[0]['rate'];
        $convertedAmountInvoiced = $total_amount_invoiced * $array_ret[0]['rate'];

        return [$convertedAmount, $convertedAmountInvoiced];
    }

    public function getAggregators() {


        $statement = $this->getDi()->getDb4()->prepare("SELECT id, agregator FROM Agregators");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        $array_info_agregators = array();
        foreach ($array_ret as $agregatorMobistein) {
            $array_info_agregators[$agregatorMobistein['id']] = $agregatorMobistein['agregator'];
        }

        return $array_info_agregators;
    }

    public function getNameAgregators($aggsIds) {
        $aggImploded = '';
        foreach ($aggsIds as $agg) {
            $aggImploded .= "'" . $agg . "',";
        }
        $aggImploded = substr($aggImploded, 0, -1);

        $statement = $this->getDi()->getDb4()->prepare("SELECT id, agregator FROM Agregators WHERE id IN (" . $aggImploded . ")");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function get_accounts($agregator) {

        $array_ret = array();

        $statement = $this->getDi()->getDb4()->prepare("SELECT id, username,  aggregators FROM users WHERE userlevel='3'");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        $array_users_id = array();
        $array_users_name = array();
        foreach ($array_ret AS $user) {
            $aggreg = explode(',', $user['aggregators']);

            if (in_array($agregator, $aggreg)) {
                array_push($array_users_id, $user['id']);
                array_push($array_users_name, $user['username']);
            }
        }

        return array(
            'ids' => implode(',', $array_users_id),
            'usernames' => implode(',', $array_users_name)
        );
    }





    public function get_mobisteininfo($country, $agregator, $tdate) {
        $date = explode('-', $tdate);

        $statement = $this->getDi()->getDb4()->prepare(
                "SELECT SUM(revenue) AS rev, SUM(conv) AS conv, country, SUM(duplicated) as duplicated FROM (
			SELECT MAX(ccpa) AS revenue , MAX(countryCode) AS country , 1 AS conv, COUNT(clickId)-1 as duplicated FROM ConversionsYeti WHERE fkAgregator='" . $agregator . "' AND insertDate BETWEEN '" . $date[0] . "-" . $date[1] . "-01' AND '" . $tdate . "' AND countryCode='" . $country . "' GROUP BY clickId
		) b GROUP BY country ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        $typeInfo = '';
        if ($date[2] == '15')
            $typeInfo = '1';
        else
            $typeInfo = '2';


        if (isset($array_ret) && !empty($array_ret)) {
            return array(
                'typeInfo' => $typeInfo,
                'revenue' => $array_ret[0]['rev'],
                'leads' => $array_ret[0]['conv'],
                'duplicated' => $array_ret[0]['duplicated']
            );
        } else {
            return array(
                'typeInfo' => $typeInfo,
                'revenue' => '0',
                'leads' => '0',
                'duplicated' => 0
            );
        }
    }

    public function get_mobisteininfoMulti($countries, $agregator, $tdate) {
        $date = explode('-', $tdate);

        $countriesImploded = '';
        foreach ($countries as $country) {
            $countriesImploded .= "'" . $country . "',";
        }
        $countriesImploded = substr($countriesImploded, 0, -1);

        $statement = $this->getDi()->getDb4()->prepare(
                "SELECT SUM(revenue) AS rev, SUM(conv) AS conv, country, SUM(duplicated) as duplicated FROM (
			SELECT MAX(ccpa) AS revenue , MAX(countryCode) AS country , 1 AS conv, COUNT(clickId)-1 as duplicated FROM ConversionsYeti WHERE fkAgregator='" . $agregator . "' AND insertDate BETWEEN '" . $date[0] . "-" . $date[1] . "-01' AND '" . $tdate . "' AND countryCode IN (" . $countriesImploded . ") GROUP BY clickId
		) b GROUP BY country ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function get_mobisteininfoAggsNotInserted($agregatorsIds, $tdate) {
        $date = explode('-', $tdate);

        $aggsImploded = '';
        foreach ($agregatorsIds as $agg) {
            $aggsImploded .= "'" . $agg . "',";
        }
        $aggsImploded = substr($aggsImploded, 0, -1);

        $statement = $this->getDi()->getDb4()->prepare(
                "SELECT b.fkAgregator as agg, b.country, SUM(revenue) AS rev, SUM(conv) AS conv, country, SUM(duplicated) as duplicated FROM (
			SELECT fkAgregator, MAX(ccpa) AS revenue , MAX(countryCode) AS country , 1 AS conv, COUNT(clickId)-1 as duplicated FROM ConversionsYeti WHERE fkAgregator NOT IN (" . $aggsImploded . ") AND insertDate BETWEEN '" . $date[0] . "-" . $date[1] . "-01' AND '" . $tdate . "' GROUP BY clickId
		) b GROUP BY country ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function convertAmount($clientAmount, $clientCurrency) {
        $firstday = date('Y-m-01');
        $today = date('Y-m-d');

        $statement = $this->getDi()->getDb4()->prepare("SELECT currency, cast(rate as decimal(10,4)) as rate FROM CurrencyHistory WHERE currency LIKE '" . $clientCurrency . "' AND insertDate BETWEEN '" . $firstday . "'  AND  '" . $today . "' GROUP BY currency");

        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        $convertedamount = $clientAmount * $array_ret[0]['rate'];

        return $convertedamount;
    }

    public function deleteBeforeInsert($agregator, $tdate) {
        $date = explode('-', $tdate);

        $typeInfo = '';
        if ($date[2] == '15')
            $typeInfo = '1';
        else
            $typeInfo = '2';

        /*
          $countriesImploded = '';
          foreach($countries as $country) {
          $countriesImploded .= "'" . $country . "',";
          }
          $countriesImploded = substr($countriesImploded, 0, -1);
         */

        $statement = $this->getDi()->getDb()->prepare("DELETE FROM FinancialReport WHERE aggregatorId='" . $agregator . "' AND serviceDate='" . $tdate . "' AND typeInfo='" . $typeInfo . "'");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
    }

    public function deleteBeforeInsertMulti($aggs, $tdate) {
        $date = explode('-', $tdate);

        $typeInfo = '';
        if ($date[2] == '15')
            $typeInfo = '1';
        else
            $typeInfo = '2';

        $agreImploded = '';
        foreach ($aggs as $agregator) {
            $agreImploded .= "'" . $agregator . "',";
        }
        $agreImploded = substr($agreImploded, 0, -1);

        $statement = $this->getDi()->getDb()->prepare("DELETE FROM FinancialReport WHERE aggregatorId IN (" . $agreImploded . ") AND serviceDate='" . $tdate . "' AND typeInfo='" . $typeInfo . "'");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
    }

    public function get_accountsWithPermissions($auth) {

        if ($auth['userlevel'] == 3) {
            return array(array(
                    'username' => $auth['name'],
                    'id' => $auth['id']
            ));
        } else {
            $statement = $this->getDi()->getDb4()->prepare("SELECT id, username,  aggregators FROM users WHERE userlevel='3'");
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
            return $array_ret;
        }
    }

    public function get_info($data_array, $accountsSql, $countriesSql, $groupbySql, $aggregatorsSql, $sdate, $edate) {

        $statement = $this->getDi()->getDb()->prepare("SELECT aggregatorId , CONCAT(aggregatorId, ' - ', aggregatorName) as aggregator, country, accountName, comments, serviceDate, typeInfo, SUM(mobisteinLeads) as mobisteinConversions, SUM(clientLeads) as clientConversions, SUM(mobisteinAmount) as mobisteinRevenue, SUM(clientAmountDollar) as clientRevenue, (SUM(clientAmountDollar) - SUM(mobisteinAmount)) as difRevenue, (SUM(clientLeads) - SUM(mobisteinLeads)) as difLeads, CASE WHEN SUM(mobisteinLeads) = 0 THEN 0 ELSE (SUM(clientLeads) - SUM(mobisteinLeads))/SUM(mobisteinLeads) END as difPercentLeads, CASE WHEN SUM(mobisteinAmount) = 0 THEN 0 ELSE (SUM(clientAmountDollar) - SUM(mobisteinAmount))/SUM(mobisteinAmount) END as difPercentRevenue, CASE WHEN SUM(totalAmountInvoiced) > 0 THEN 'TRUE' ELSE 'FALSE' END as invoicedState, SUM(totalAmountInvoiced) as totalAmountInvoiced , SUM(duplicateds) as duplicateds, CASE WHEN SUM(mobisteinLeads) = 0 THEN 0 ELSE SUM(duplicateds)/SUM(mobisteinLeads) END as difduplicateds FROM FinancialReport WHERE 0=0 " . $accountsSql . $countriesSql . $aggregatorsSql . " AND typeInfo='" . $data_array['typeInfo'] . "' AND serviceDate BETWEEN '" . $sdate . "' AND '" . $edate . "' GROUP BY accountName, aggregatorId, serviceDate " . $groupbySql . " ORDER BY serviceDate ASC");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function updateCom($data_array) {

        $country = '';
        if ($data_array['coun'] != '') {
            $country = " AND country='" . $data_array['coun'] . "'";
        }

        $statement = $this->getDi()->getDb()->prepare("UPDATE FinancialReport SET comments='" . $data_array['comment'] . "' WHERE aggregatorId=" . $data_array['agg'] . "" . $country . " AND serviceDate='" . $data_array['date'] . "' ");

        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
    }

    public function get_infoByType($data_array) {

        $agre = explode(',', $data_array['aggreg']);

        $agreImploded = '';
        foreach ($agre as $agregator) {
            $agreImploded .= "'" . $agregator . "',";
        }
        $agreImploded = substr($agreImploded, 0, -1);


        $first = strtotime('first day this month');
        $monthsNames = array();

        $day = '';
        if ($data_array['typeInfo'] == 2) {
            $day = 't';
        } else if ($data_array['typeInfo'] == 1) {
            $day = '15';
        }

        for ($i = 12; $i >= 0; $i--) {
            array_push($monthsNames, array(
                'monthName' => date('M', strtotime("-$i month", $first)),
                'monthNum' => date("Y-m-$day", strtotime("-$i month", $first)),
            ));
        }

        $statement = $this->getDi()->getDb()->prepare("SELECT aggregatorId, sum(totalAmountInvoiced) as totalAmountInvoiced, CASE WHEN SUM(mobisteinLeads) = 0 THEN 0 ELSE ((SUM(clientLeads) - SUM(mobisteinLeads))/SUM(mobisteinLeads)) * 100 END as difPercentLeads ,serviceDate FROM FinancialReport where aggregatorId IN(" . $agreImploded . ") and serviceDate BETWEEN '" . $monthsNames[0]['monthNum'] . "' AND '" . $monthsNames[12]['monthNum'] . "' AND typeInfo='" . $data_array['typeInfo'] . "' group by serviceDate, aggregatorId order by serviceDate ASC");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function get_othermobisteininfo($countries, $agregator, $tdate) {
        $date = explode('-', $tdate);

        $countImploded = '';
        foreach ($countries as $count) {
            $countImploded .= "'" . $count . "',";
        }
        $countImploded = substr($countImploded, 0, -1);

        $statement = $this->getDi()->getDb4()->prepare(
                "SELECT SUM(revenue) AS rev, SUM(conv) AS conv, country, SUM(duplicated) as duplicated FROM (
			SELECT MAX(ccpa) AS revenue , MAX(countryCode) AS country , 1 AS conv, COUNT(clickId)-1 as duplicated FROM ConversionsYeti WHERE fkAgregator='" . $agregator . "' AND insertDate BETWEEN '" . $date[0] . "-" . $date[1] . "-01' AND '" . $tdate . "' AND countryCode NOT IN(" . $countImploded . ") GROUP BY clickId
		) b GROUP BY country ");
        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        $typeInfo = '';
        if ($date[2] == '15')
            $typeInfo = '1';
        else
            $typeInfo = '2';


        $final_array = array();
        foreach ($array_ret as $line) {
            array_push($final_array, array(
                'typeInfo' => $typeInfo,
                'revenue' => $line['rev'],
                'leads' => $line['conv'],
                'duplicated' => $line['duplicated'],
                'country' => $line['country']
            ));
        }

        return $final_array;
    }

}

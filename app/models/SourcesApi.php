<?php

use Phalcon\Mvc\Model;

class SourcesApi extends Model {

    public function getSourcesByUser($invest) {
    
        if($invest == null && $invest == '') {
           $invest = '47,57,148,43,170,366,83,70,167,98,154,364,174,166,91,386,7,363,59,168,288'; 
        }

        $investIn = '';
        $invest = explode(',', $invest);
        foreach ($invest as $inv) {
            $investIn .= "'" . $inv . "',";
        }
        $investIn = substr($investIn, 0, -1);

        $statement = $this->getDi()->getDb()->prepare("SELECT id,sourceName FROM tinas__Sources WHERE id IN (" . $investIn . ")");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function getCountriesByUser($user_countries) {
        $countries = explode(',', $user_countries);

        if ($user_countries != '') {
            $crt = '';
            foreach ($countries as $country) {
                $crt .= "'" . strtoupper($country) . "',";
            }
            $crt = substr($crt, 0, -1);

            $countriesF = "WHERE id IN ($crt)";
        } else {
            $countriesF = '';
        }

        $statement = $this->getDi()->getDb()->prepare("SELECT id, name FROM  Countries $countriesF");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function downloadReport($data, $userC, $userS) {
        $array_sources = array();
        
        $others = false;
       	$continue = true;
        $countriesMainReport = '';

        if ($data['sources'] == 'ALL') {
            $sources = explode(',', $userS);

            $others = true;

            $srcS = '';
            $crt = '';
            foreach ($sources as $sor) {
                $srcS .= "'" . $sor . "',";
                array_push($array_sources, $sor);
            }
            $srcS = substr($srcS, 0, -1);
            $sourcesF = " sourceId IN ($srcS) AND ";

            if ($data['countries'] == 'ALL' || $data['countries'] == '') {
                $count = explode(',', $userC);
                foreach ($count as $country) {
                    $crt .= "'" . $country . "',";
                }
                $crt = substr($crt, 0, -1);

                $countriesF = " country IN ($crt) AND ";
                $countriesMainReport = " AND campaign_country IN ($crt) ";
            } else {
                $count = explode(',', $data['countries']);
                foreach ($count as $country) {
                    $crt .= "'" . $country . "',";
                }
                $crt = substr($crt, 0, -1);

                $countriesF = " country IN ($crt) AND ";
                $countriesMainReport = " AND campaign_country IN ($crt) ";
            }
        } else {
            $sources = explode(',', $data['sources']);

            $srcS = '';
            $crt = '';
            foreach ($sources as $sor) {
                if($sor == 'OTHERS' && sizeof($sources) == 1) {
                    $others = true;
                    $continue = false;
                } else if($sor == 'OTHERS') {
                	$others = true;
                } else {
                    $srcS .= "'" . $sor . "',";
                    array_push($array_sources, $sor);
                }
            }
            $srcS = substr($srcS, 0, -1);

            $sourcesF = " sourceId IN ($srcS) AND ";

            if ($data['countries'] == 'ALL' || $data['countries'] == '') {
                $count = explode(',', $userC);
                foreach ($count as $country) {
                    $crt .= "'" . $country . "',";
                }
                $crt = substr($crt, 0, -1);

                $countriesF = " country IN ($crt) AND ";
                $countriesMainReport = " AND campaign_country IN ($crt) ";
            } else {
                $count = explode(',', $data['countries']);
                foreach ($count as $country) {
                    $crt .= "'" . $country . "',";
                }
                $crt = substr($crt, 0, -1);

                $countriesMainReport = " AND campaign_country IN ($crt) ";
                $countriesF = " country IN ($crt) AND ";
            }
        }

        if ($userC == '' && ($data['countries'] == '' || $data['countries'] == 'ALL')) {
            $countriesF = "";
            $countriesMainReport = "";
        }

        $date = explode(' to ', $data['date']);

        /*
          $keys = array('7','363','91', '166', '362');
          if(array_fill_keys($keys, $array_sources)) {
          $statement = $this->getDi()->getDb()->prepare("SELECT dateInsert, campaignId, campaignName, country, sourceId, sourceName, sub_id, SUM(clicksMobistein) as clicksMobistein, clicksSource, impressionsSource, conversionsMobistein, investment, SUM(revenue) as revenue, bannerType, status FROM SourcesInvestment WHERE $sourcesF $countriesF dateInsert BETWEEN '$date[0]' AND '$date[1]' GROUP BY sub_id, campaignId, country");
          } else {

          }
         */

        $array_ret = array(); 
        if($continue) {
        	$blanks = '';
	        if($data['blanks'] == 'true') {
	            $blanks = 'AND ((investment != 0 AND revenue != 0) OR (investment = 0 AND revenue != 0) OR (investment != 0 AND revenue = 0)) ';
	        }

	        if ($data['minified'] == 'true') {
	            $statement = $this->getDi()->getDb()->prepare("SELECT date(dateInsert) as dateInsert, country, sourceId, sub_id, investment, revenue as revenue, `timeStamp` as timeDate FROM SourcesInvestment WHERE $sourcesF $countriesF dateInsert BETWEEN '$date[0]' AND '$date[1]' $blanks ORDER BY sourceId ASC , dateInsert ASC ");
	        } else {
	            $statement = $this->getDi()->getDb()->prepare("SELECT date(dateInsert) as dateInsert, campaignId, campaignName, country, sourceId, sourceName, sub_id, clicksMobistein as clicksMobistein, clicksSource, impressionsSource, conversionsMobistein, investment, revenue as revenue, bannerType, status, spot as spotname, `timeStamp` as timeDate FROM SourcesInvestment WHERE $sourcesF $countriesF dateInsert BETWEEN '$date[0]' AND '$date[1]' $blanks ORDER BY sourceId ASC , dateInsert ASC ");
	        }

	        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
	        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        }
       
        $array_others = array();
        if($others) {
            $blanks2 = '';
            if($data['blanks'] == 'true') {
                $blanks2 = 'AND revenue != 0 ';
            }
            $array_others = $this->getOthers( $countriesMainReport, $date[0], $date[1], $data['minified'], $blanks2);
        }
    
        return array_merge($array_ret, $array_others);
        //return $array_ret;
    }

    private function getOthers($countriesF, $dateInicial, $dateFinal, $minified, $blanks) {
        $sources = '"57","43","170","366","83","70","167","98","154","364","174","166","91","386","7","363","59","168","288","929","47","944","928","289","61"'; 
        
        if($minified == 'true') {
          	$statement = $this->getDi()->getDb4()->prepare("SELECT insertDate as dateInsert, campaign_country as country, source as sourceId, subid as sub_id, 0 as investment, SUM(revenue) as revenue, '' as timeDate FROM MainReport INNER JOIN Sources src ON src.id = source WHERE 0=0 $countriesF $blanks AND source NOT IN ('57','43','170','366','70','167','98','154','364','174','166','91','386','7','363','59','168','288','929','47','944','928','289','61') AND insertDate BETWEEN '$dateInicial' AND '$dateFinal' AND src.affiliate=0 GROUP BY insertDate, subid , campaign_country ORDER BY sourceId ASC , dateInsert ASC");

        } else {
        	$statement = $this->getDi()->getDb4()->prepare("SELECT insertDate as dateInsert, '' as campaignId, '' as campaignName, campaign_country as country, source as sourceId, src.sourcename sourceName, subid as sub_id, SUM(clicks) as clicksMobistein, 0 as clicksSource, 0 as impressionsSource , SUM(conversions) as conversionsMobistein, 0 as investment, SUM(revenue) as revenue, '' as bannerType, '' as status, '' as spotname, '' as timeDate FROM MainReport INNER JOIN Sources src ON src.id = source WHERE 0=0 $countriesF $blanks AND source NOT IN ('57','148','43','170','366','70','167','98','154','364','174','166','91','386','7','363','59','168','288','929','47','944','928','289','61') AND insertDate BETWEEN '$dateInicial' AND '$dateFinal' AND src.affiliate=0 GROUP BY insertDate, subid , campaign_country ORDER BY sourceId ASC , dateInsert ASC");
        }

        //mail('andre.vieira@mobipium.com', 'teste', "SELECT insertDate as dateInsert, '' as campaignId, '' as campaignName, campaign_country as country, source as sourceId, src.sourcename sourceName, subid as sub_id, SUM(clicks) as clicksMobistein, 0 as clicksSource, 0 as impressionsSource , SUM(conversions) as conversionsMobistein, 0 as investment, SUM(revenue) as revenue, '' as bannerType, '' as status, '' as spotname, '' as timeDate FROM MainReport INNER JOIN Sources src ON src.id = source WHERE 0=0 $countriesF $blanks AND source NOT IN ('57','148','43','170','366','70','167','98','154','364','174','166','91','386','7','363','59','168','288','929','47','944','928','289','61') AND insertDate BETWEEN '$dateInicial' AND '$dateFinal' AND src.affiliate=0 GROUP BY insertDate, subid , campaign_country ORDER BY sourceId ASC , dateInsert ASC");

        $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        
        return $array_ret;        
    }

    public function getLastEdits($sources) {

        if($sources == null && $sources == '') {
           $sources = '57,148,43,170,366,83,70,167,98,154,364,174,166,91,386,7,363,59,168,288'; 
        }

        $sourcesString = $this->filterToString($sources);

        $statement = $this->getDi()->getDb()->prepare("SELECT sourceId , MAX(sourceName) as sourceName, MAX(timeStamp) as lastresult FROM SourcesInvestment WHERE sourceId IN (" . $sourcesString . ") GROUP BY sourceId ORDER BY lastresult");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        $table = '';
        foreach ($array_ret as $row) {
            $table .= '	<tr>
						<td>' . $row['sourceId'] . ' - ' . $row['sourceName'] . '</td>
						<td>' . $row['lastresult'] . '</td>
						</tr> ';
        }

        return $table;
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

}

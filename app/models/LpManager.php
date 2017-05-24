<?php

use Phalcon\Mvc\Model;

class LpManager extends Model {

    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('dim__landingpages');
    }

    public function getFilters() {
        try {
            $statement = $this->getDi()->getDb4()->prepare("SELECT id, domain as name FROM dim__domains");
            $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            $arrayDomains = array('domains' => $res->fetchAll(PDO::FETCH_ASSOC));

            $statement2 = $this->getDi()->getDb4()->prepare("SELECT id, name FROM dim__languages");
            $res2 = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
            $arrayLanguages = array('languages' => $res2->fetchAll(PDO::FETCH_ASSOC));

            $statement3 = $this->getDi()->getDb4()->prepare("SELECT id, name FROM dim__servers");
            $res3 = $this->getDi()->getDb4()->executePrepared($statement3, array(), array());
            $arrayServers = array('servername' => $res3->fetchAll(PDO::FETCH_ASSOC));

            $statement4 = $this->getDi()->getDb4()->prepare("SELECT id, name FROM offerpack__vertical");
            $res4 = $this->getDi()->getDb4()->executePrepared($statement4, array(), array());
            $arrayVeticals = array('verticals' => $res4->fetchAll(PDO::FETCH_ASSOC));

            return array($arrayDomains, $arrayLanguages, $arrayServers, $arrayVeticals);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getFiltersLpManager() {
        //Vertical
        $statement4 = $this->getDi()->getDb4()->prepare("SELECT id, name FROM offerpack__vertical");
        $res4 = $this->getDi()->getDb4()->executePrepared($statement4, array(), array());
        $arrayVeticals = array('verticals' => $res4->fetchAll(PDO::FETCH_ASSOC));

        //Language
        $statement2 = $this->getDi()->getDb4()->prepare("SELECT id, name FROM dim__languages");
        $res2 = $this->getDi()->getDb4()->executePrepared($statement2, array(), array());
        $arrayLanguages = array('languages' => $res2->fetchAll(PDO::FETCH_ASSOC));

        //Domain
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, domain as name FROM dim__domains");
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        $arrayDomains = array('domains' => $res->fetchAll(PDO::FETCH_ASSOC));

        //Geo
        $statement6 = $this->getDi()->getDb4()->prepare("SELECT ct.id, name FROM Countries ct INNER JOIN Mask ma ON ct.id = ma.country GROUP BY ct.id, ma.country");
        $res6 = $this->getDi()->getDb4()->executePrepared($statement6, array(), array());
        $arrayCountry = array('geo' => $res6->fetchAll(PDO::FETCH_ASSOC));

        //Ethinicity
        $statement6 = $this->getDi()->getDb4()->prepare("SELECT id, name FROM dim__ethnicity");
        $res6 = $this->getDi()->getDb4()->executePrepared($statement6, array(), array());
        $arrayEthinicity = array('ethnicity' => $res6->fetchAll(PDO::FETCH_ASSOC));


        //Client
        $statement5 = $this->getDi()->getDb4()->prepare("SELECT id, agregator FROM Agregators");
        $res5 = $this->getDi()->getDb4()->executePrepared($statement5, array(), array());
        $arrayClients = array('clients' => $res5->fetchAll(PDO::FETCH_ASSOC));

        //Offer
        //depende do country e client
        //Server
        $statement3 = $this->getDi()->getDb4()->prepare("SELECT id, name FROM dim__servers");
        $res3 = $this->getDi()->getDb4()->executePrepared($statement3, array(), array());
        $arrayServers = array('servername' => $res3->fetchAll(PDO::FETCH_ASSOC));

        return array($arrayVeticals, $arrayLanguages, $arrayDomains, $arrayCountry, $arrayEthinicity, $arrayClients, $arrayServers);
    }

    public function getOffers($geo, $client) {

        $geos = '';
        if ($geo != '' && $geo != 'null') {
            $geos = ' AND country IN (';
            $geos .= $this->filterToString($geo) . ')';
        }

        $clients = '';
        if ($client != '' && $client != 'null') {
            $clients = ' AND agregator IN (';
            $clients .= $this->filterToString($client) . ')';
        }

        $statement = $this->getDi()->getDb4()->prepare("SELECT hash, campaign FROM Mask WHERE 0=0 " . $geos . $clients);
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());

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

    public function getFilterRows($array_data) {

        try {
            $where = array();
            foreach ($array_data as $key => $value) {
                if ($value != '' && $value != 'null') {
                    if ($key != 'name' && $key != 'id') {
                        $value = explode(',', $value);
                        $str = ' (';
                        foreach ($value as $val) {
                            $str .= "find_in_set('$val',d." . $key . ') OR ';
                        }
                        $str = rtrim($str, ' OR ') . ' )';
                        array_push($where, $str);
                    } else {
                        array_push($where, 'd.' . $key . " = '" . $value . "'");
                    }
                }
            }

            $where = (sizeof($where) > 0) ? 'AND ' . implode(' AND ', $where) : '';
            //echo $where;
            $statement = $this->getDi()->getDb4()->prepare("SELECT d.id, DATE_FORMAT(d.insertTimestamp,'%Y-%m-%d') as insertTimestamp, COALESCE(GROUP_CONCAT(DISTINCT o.name),'') as verticals, COALESCE(GROUP_CONCAT(DISTINCT lng.name),'') as languages, COALESCE(GROUP_CONCAT(DISTINCT eth.name),'') as ethnicity, UPPER(d.countries) as countries, d.name, COALESCE(GROUP_CONCAT(DISTINCT m.campaign),'') as offers, d.url FROM dim__landingpages d left join offerpack__vertical o ON find_in_set(o.id,d.verticals) left join dim__languages lng ON find_in_set(lng.id,d.languages) left join dim__ethnicity eth ON find_in_set(eth.id,d.ethnicity) left join Mask m ON find_in_set(m.hash,d.offers)  WHERE d.status != '5' " . $where . ' GROUP BY id LIMIT 200 ');
            $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());

            return $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E', $ex->getMessage());
            return null;
        }
    }

    public function insertLp($data, $auth) {

        $date = date('Y-m-d H:i:s');

        if ($data['saveType'] == 'edit') {
            //update
            $statement = $this->getDi()->getDb4()->prepare("UPDATE dim__landingpages SET name='$data[name]',url='$data[url]',domain='$data[domains]',verticals='$data[verticals]',languages='$data[languages]',ethnicity='$data[ethnicity]',offers='$data[offers]',clients='$data[clients]',countries='$data[countries]',comments='$data[comments]', editTimestamp='$date' WHERE id='$data[lpId]'");
            $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        } else {
            //insert
            $statement = $this->getDi()->getDb4()->prepare("INSERT INTO dim__landingpages (name, url, domain, verticals, languages, ethnicity, offers, clients, countries, comments, createdBy, editTimestamp, insertTimestamp) VALUES ('$data[name]','$data[url]','$data[domains]','$data[verticals]','$data[languages]','$data[ethnicity]','$data[offers]','$data[clients]','$data[countries]','$data[comments]','$auth[id]','$date','$date')");
            $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
        }

        return 1;
    }

    public function getLp($lpid) {
        $statement = $this->getDi()->getDb4()->prepare("SELECT id, name, url, domain, verticals, languages, ethnicity, offers, clients, countries, comments FROM dim__landingpages WHERE id='$lpid'");
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());

        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteLpbyid($lpid) {
        $statement = $this->getDi()->getDb4()->prepare("UPDATE dim__landingpages SET status='5' WHERE id='$lpid'");
        $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
    }

    public function getLpView($lpId) {
        try {
            $statement = $this->getDi()->getDb4()->prepare("SELECT d.id, d.comments as comments, DATE_FORMAT(d.insertTimestamp,'%Y-%m-%d') as insertTimestamp, COALESCE(dd.domain,'') as domain, COALESCE(GROUP_CONCAT(DISTINCT o.name),'') as verticals, COALESCE(GROUP_CONCAT(DISTINCT lng.name),'') as languages, COALESCE(GROUP_CONCAT(DISTINCT eth.name),'') as ethnicity, UPPER(d.countries) as countries, d.name, COALESCE(GROUP_CONCAT(DISTINCT m.campaign),'') as offers, d.url FROM dim__landingpages d left join offerpack__vertical o ON find_in_set(o.id,d.verticals) left join dim__languages lng ON find_in_set(lng.id,d.languages) left join dim__ethnicity eth ON find_in_set(eth.id,d.ethnicity) left join Mask m ON find_in_set(m.hash,d.offers) left join dim__domains dd ON dd.id = d.domain WHERE d.id=$lpId GROUP BY d.id");
            $res = $this->getDi()->getDb4()->executePrepared($statement, array(), array());

            return $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E', $ex->getMessage());
            return null;
        }
    }

}

<?php

use Phalcon\Mvc\Model;

class Operation extends Model {

    public function updateOldCPA($start, $end, $campaign, $newCPA) {
        return true;
    }

    public function getAggregators($allowed = null) {
        $sql = 'SELECT id,agregator FROM Agregators WHERE 0=0 ' . (isset($allowed) ? ' AND id IN (' . $allowed . ') ' : ' ' ) . ' ORDER by agregator';
        return $this->getDi()->getDb4()->query($sql)->fetchAll();
    }

    public function getDomains() {
        $sql = 'SELECT domain FROM Domains';
        $res = $this->getDi()->getDb()->query($sql)->fetchAll();

        $domains = array();
        foreach ($res as $domain) {
            array_push($domains, $domain['domain']);
        }

        return $domains;
    }

    public function updateCPA($s, $e, $c, $val) {

        $callSP = 'CALL updateCPA("' . $s . '","' . $e . '","' . $c . '","' . $val . '",@res);';

        $res = $this->getDi()->getDb()->execute($callSP);

        return $res;
    }

    public function getRate($cur) {
        $sql = 'SELECT rate as currate FROM CurrencyHistory WHERE currency = "' . $cur . '" ORDER BY ID DESC LIMIT 1';
        $res = $this->getDi()->getDb4()->query($sql)->fetchAll();
        return $res;
    }

    function getClients() {
        $query = 'SELECT id,clientName FROM Clients ORDER by clientName';
        $res = $this->getDi()->getDb2()->query($query)->fetchAll();
        return $res;
    }

    function getSources($auth = null) {
        if (isset($auth)) {
            if ($auth['userlevel'] == 2 && $auth['utype'] == 0) {//CM's Ivo Pedro
                $aff = 0;
            } else if ($auth['utype'] == 1) { // Affiliate manager
                $aff = 1;
            } else if ($auth['userlevel'] == 2 && $auth['utype'] == 2) { // mainstream CM's  like Arrojado
                $aff = 2;
                //} else if ($auth['userlevel'] == 1 && ($auth['id'] == 3)) { // hello I'm Guille and Facha and I'm different
                //$aff = 3;
            } else
                $aff = '0,1,2,3,4,5';
            $query = "SELECT id,sourceName FROM Sources WHERE affiliate IN ($aff) ORDER by sourceName";
        } else
            $query = 'SELECT id,sourceName FROM Sources ORDER by sourceName';
        $res = $this->getDi()->getDb4()->query($query)->fetchAll();
        return $res;
    }

    public function insertSource($newSource, $sourcetype, $auth) {

        $affiliate = $sourcetype;


        $res = $this->getDi()->getDb4()->execute('INSERT INTO Sources (sourceName, affiliate) VALUES(?, ?)', array($newSource, $affiliate));
        $this->getDi()->get("viewCache")->delete('sources');
        $statement2 = $this->getDi()->getDb4()->prepare('SELECT id FROM Sources WHERE sourceName = :newSource');
        $result = $this->getDi()->getDb4()->executePrepared($statement2, array('newSource' => $newSource), array(\Phalcon\Db\Column::TYPE_VARCHAR))->fetchAll();

        $statement = $this->getDi()->getDb()->prepare('INSERT INTO tinas__Sources (id,sourceName, affiliate) VALUES(:id,:sourceName,:affiliate)');
        $this->getDi()->getDb()->executePrepared($statement, array('id' => $result[0]['id'], 'sourceName' => $newSource, 'affiliate' => $affiliate), array(\Phalcon\Db\Column::TYPE_INTEGER, \Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_INTEGER));

        $this->insertIntoUser($result[0]['id'], $auth);

        return $result[0]['id'];
    }

    public function insertIntoUser($id, $auth) {
        $idUser = $auth['id'];

        $res = $this->getDi()->getDb4()->execute("UPDATE users SET sources=CONCAT(sources, ',' , $id ) , investaccess=CONCAT(investaccess, ',', $id ) WHERE id=$idUser", array());
        $res = $this->getDi()->getDb()->execute("UPDATE users SET sources=CONCAT(sources, ',' , $id ) , investaccess=CONCAT(investaccess, ',', $id ) WHERE id=$idUser", array());
    }

    public function insertCategory($newCategory) {

        $res = $this->getDi()->getDb4()->execute('INSERT INTO Categories (name) VALUES(?)', array($newCategory));
        $this->getDi()->get("viewCache")->delete('categories');
        $statement2 = $this->getDi()->getDb4()->prepare('SELECT id FROM Categories WHERE name = :newCategory');
        $result = $this->getDi()->getDb4()->executePrepared($statement2, array('newCategory' => $newCategory), array(\Phalcon\Db\Column::TYPE_VARCHAR))->fetchAll();

        //$statement = $this->getDi()->getDb()->prepare('INSERT INTO tinas__Sources (id,sourceName) VALUES(:id,:sourceName)');
        //$this->getDi()->getDb()->executePrepared($statement, array('id' => $result[0]['id'],'sourceName' => $newSource),array(\Phalcon\Db\Column::TYPE_INTEGER,\Phalcon\Db\Column::TYPE_VARCHAR));

        return $result[0]['id'];
    }

    public function insertAggregator($newAgg, $newTracking, $auth, $company) {
        /*
          $statement = $this->getDi()->getDb2()->prepare('INSERT INTO Agregators (agregator, trackingParam) VALUES(:agg, :newtrack)');
          $this->getDi()->getDb2()->executePrepared($statement, array('agg' => $newAgg,'newtrack'=> $newTracking),array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_VARCHAR));
          $this->getDi()->get("viewCache")->delete('aggregators');
          $statement2 = $this->getDi()->getDb2()->prepare('SELECT id FROM Agregators WHERE agregator = :agg AND trackingParam = :newtrack ');
          $result = $this->getDi()->getDb2()->executePrepared($statement2, array('agg' => $newAgg,'newtrack'=> $newTracking),array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_VARCHAR))->fetchAll();
         */

        $statement = $this->getDi()->getDb4()->prepare('INSERT INTO Agregators (agregator, trackingParam, cname) VALUES(:agg, :newtrack, :cname)');
        $this->getDi()->getDb4()->executePrepared($statement, array('agg' => $newAgg, 'newtrack' => $newTracking, 'cname' => $company), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_VARCHAR));
        $this->getDi()->get("viewCache")->delete('aggregators');
        $statement2 = $this->getDi()->getDb4()->prepare('SELECT id FROM Agregators WHERE agregator = :agg AND trackingParam = :newtrack AND cname= :cname ');
        $result = $this->getDi()->getDb4()->executePrepared($statement2, array('agg' => $newAgg, 'newtrack' => $newTracking, 'cname' => $company), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_VARCHAR))->fetchAll();


        $statement = $this->getDi()->getDb()->prepare('INSERT INTO tinas__Agregators (id,agregator, trackingParam) VALUES(:id,:agg, :newtrack)');
        $this->getDi()->getDb()->executePrepared($statement, array('id' => $result[0]['id'], 'agg' => $newAgg, 'newtrack' => $newTracking), array(\Phalcon\Db\Column::TYPE_INTEGER, \Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_VARCHAR));

        //get user level and its clients

        $userInfoStatement = $this->getDi()->getDb()->prepare('SELECT userlevel, aggregators FROM users WHERE id=:id');
        $user = $this->getDi()->getDb()->executePrepared($userInfoStatement, array('id' => $auth['id']), array(\Phalcon\Db\Column::TYPE_INTEGER))->fetchAll();
        $user = $user[0];
        $userInfoStatement = $this->getDi()->getDb4()->prepare('SELECT userlevel, aggregators FROM users WHERE id=:id');
        $user = $this->getDi()->getDb4()->executePrepared($userInfoStatement, array('id' => $auth['id']), array(\Phalcon\Db\Column::TYPE_INTEGER))->fetchAll();
        $user = $user[0];
        if (!empty($user) && $user['userlevel'] > 2 && $auth['id'] != 25) {
            $str = '';
            if (!empty($user['aggregators'])) {
                $str = $user['aggregators'] . ',';
            }
            $str .= $result[0]['id'];
            $updateUser = $this->getDi()->getDb4()->prepare('UPDATE users SET aggregators = :aggregators where id=:id');
            $this->getDi()->getDb4()->executePrepared($updateUser, array('aggregators' => $str, 'id' => $auth['id']), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_INTEGER))->execute();
            $updateUser = $this->getDi()->getDb()->prepare('UPDATE users SET aggregators = :aggregators where id=:id');
            $this->getDi()->getDb()->executePrepared($updateUser, array('aggregators' => $str, 'id' => $auth['id']), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_INTEGER))->execute();
        }
        //ANA'S SPECIAL LEADER (adds all new agregators)
        /* $userLeaderInfoStatement = $this->getDi()->getDb4()->prepare('SELECT userlevel, aggregators FROM users WHERE id=:id');
          $userLeader = $this->getDi()->getDb4()->executePrepared($userLeaderInfoStatement, array('id' => 25), array(\Phalcon\Db\Column::TYPE_INTEGER))->fetchAll();
          $userLeader = $userLeader[0];
          $str = '';
          if (!empty($userLeader['aggregators'])) {
          $str = $userLeader['aggregators'] . ',';
          }
          $str .= $result[0]['id'];
          /*$updateUser = $this->getDi()->getDb4()->prepare('UPDATE users SET aggregators = :aggregators where id=:id');
          $this->getDi()->getDb4()->executePrepared($updateUser, array('aggregators' => $str, 'id' => 25), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_INTEGER))->execute();

          $updateUser = $this->getDi()->getDb()->prepare('UPDATE users SET aggregators = :aggregators where id=:id');
          $this->getDi()->getDb()->executePrepared($updateUser, array('aggregators' => $str, 'id' => 25), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_INTEGER))->execute();
         */
        //END ANA'S SPECIAL USER

        return $result[0]['id'];
    }

    public function getOperators() {
        $sql = 'SELECT operator_name FROM Operators';
        $result = $this->getDi()->getDb()->query($sql)->fetchAll();
        return $result;
    }

    public function getOperators2($country) {

        $country = (substr($country, 0, 1) == '"' ? $country : '"' . $country . '"');
        //mail('pedrorleonardo@gmail.com','countries','SELECT name as operator FROM Carriers where country IN ('.$country.')');

        $ops = $this->getDi()->getDb()->query('SELECT name as operator FROM Carriers where country IN (' . $country . ')')->fetchAll();

        return $ops;
    }

    public function getMainstreamSources($sources = null) {
        $srcs = $this->getDi()->getDb4()->query('SELECT id, sourceName FROM Sources where affiliate = 2' . (isset($sources) ? ' AND id IN (' . $this->filterToString($sources) . ')' : '' ))->fetchAll();

        return $srcs;
    }

    public function getAgregatorParams($aggid) {
        $statement = $this->getDi()->getDb()->prepare('SELECT trackingParam, connector, sinfo FROM tinas__Agregators WHERE id = :agg');
        $result = $this->getDi()->getDb()->executePrepared($statement, array('agg' => $aggid), array(\Phalcon\Db\Column::TYPE_INTEGER))->fetchAll();
        return $result[0];
    }

    public function getCampaignsByUrl($url) {
        $statement = $this->getDi()->getDb4()->prepare('SELECT campaign FROM Mask WHERE rurl LIKE :agg');
        $result = $this->getDi()->getDb4()->executePrepared($statement, array('agg' => '%' . $url . '%'), array(\Phalcon\Db\Column::TYPE_VARCHAR))->fetchAll();
        return $result;
    }

    public function getCategories() {
        $srcs = $this->getDi()->getDb4()->query('SELECT id, name FROM Categories LIMIT 250')->fetchAll();

        return $srcs;
    }

    public function insertCampaignCategory($campaignid, $categoryid) {
        $statement1 = $this->getDi()->getDb4()->prepare('SELECT campaignhash FROM CategoriesXCampaigns WHERE campaignhash LIKE :campaignid');
        $result = $this->getDi()->getDb4()->executePrepared($statement1, array('campaignid' => $campaignid), array(\Phalcon\Db\Column::TYPE_VARCHAR))->fetchAll();
        if (!empty($result[0])) {
            $statement2 = $this->getDi()->getDb42()->prepare('UPDATE CategoriesXCampaigns SET subjectid = :categoryid WHERE campaignhash = :campaignid LIMIT 1');
            return $this->getDi()->getDb4()->executePrepared($statement2, array('campaignid' => $campaignid, 'categoryid' => $categoryid), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_INTEGER));
        }
        $statement3 = $this->getDi()->getDb4()->prepare('INSERT INTO CategoriesXCampaigns (campaignhash, subjectid) VALUES (:campaignid, :categoryid)');
        $this->getDi()->getDb4()->executePrepared($statement3, array('campaignid' => $campaignid, 'categoryid' => $categoryid), array(\Phalcon\Db\Column::TYPE_VARCHAR, \Phalcon\Db\Column::TYPE_INTEGER));
    }

    public function removeCampaignCategory($campaignid) {
        $statement1 = $this->getDi()->getDb4()->prepare('DELETE FROM CategoriesXCampaigns WHERE campaignhash LIKE :campaignid');
        return $this->getDi()->getDb4()->executePrepared($statement1, array('campaignid' => $campaignid), array(\Phalcon\Db\Column::TYPE_VARCHAR));
    }

    public function getSourcesInv($id, $invest) {

        if($id== 21){
            $srcs = $this->getDi()->getDb()->query('SELECT tinas__Sources.id, sourceName FROM tinas__Sources LEFT JOIN I__InvestMetadata i ON i.source = tinas__Sources.id WHERE affiliate = 2 and i.id is not null ')->fetchAll();
        }
        else 
            $srcs = $this->getDi()->getDb4()->query('SELECT id, sourceName FROM Sources WHERE id IN (' . $this->filterToString($invest) . ')')->fetchAll();

        $options = '<option value="0"></option>';
        foreach ($srcs as $key => $value) {
            $options = $options . '<option value="' . $value['id'] . '">' . $value['id'] . ' - ' . $value['sourceName'] . '</option>';
        }

        return $options;
    }
    public function getSourcesInv2($id, $invest) {

        if($id== 21){
            $srcs = $this->getDi()->getDb()->query('SELECT tinas__Sources.id, sourceName FROM tinas__Sources LEFT JOIN I__InvestMetadata i ON i.source = tinas__Sources.id WHERE affiliate = 2 and i.id is null ')->fetchAll();
        }
        else 
            $srcs = $this->getDi()->getDb4()->query('SELECT id, sourceName FROM Sources WHERE id IN (' . $this->filterToString($invest) . ')')->fetchAll();

        $options = '<option value="0"></option>';
        foreach ($srcs as $key => $value) {
            $options = $options . '<option value="' . $value['id'] . '">' . $value['id'] . ' - ' . $value['sourceName'] . '</option>';
        }

        return $options;
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

    public function insertIntoMetadata($src, $id, $bulks) {

        if ($id != '' && $id != 0) {
            $srcs = $this->getDi()->getDb()->query("SELECT * FROM I__InvestMetadata WHERE source = '$src'")->fetchAll();

            if (sizeof($srcs) != 0) {

                $srcs = $srcs[0];

                $srcs = $this->getDi()->getDb()->query("INSERT INTO I__InvestMetadata(`source`, `currency`, `filetype`, `field_count`, `line_skip`, `col_skip`, `encoded`, `sep1`, `sep2`, `subid_pos`, `click_pos`, `imp_pos`, `inv_pos`, `explode_start`, `country`, `country_position`, `control_size`, `insertTimestamp`) VALUES ('$id','$srcs[currency]','$srcs[filetype]','$srcs[field_count]','$srcs[line_skip]','$srcs[col_skip]','$srcs[encoded]','$srcs[sep1]','$srcs[sep2]','$srcs[subid_pos]','$srcs[click_pos]','$srcs[imp_pos]','$srcs[inv_pos]','$srcs[explode_start]','$srcs[country]','$srcs[country_position]','$srcs[control_size]','$srcs[insertTimestamp]')");
            }
        }

        if ($id != '' && $id != 0) {
            if ($bulks) {
                $srcs = $this->getDi()->getDb()->query("SELECT * FROM Bulks_exceltype WHERE source = '$src'")->fetchAll();

                if (sizeof($srcs) != 0) {

                    $srcs = $srcs[0];

                    $srcs = $this->getDi()->getDb()->query("INSERT INTO Bulks_exceltype(`source`, `type`, `dots`) VALUES ('$id','$srcs[type]','$srcs[dots]')");
                }
            }
        }
    }

}

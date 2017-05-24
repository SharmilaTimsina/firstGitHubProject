<?php

use Phalcon\Mvc\Model;

class Permission extends Model {

    public function get_users() {
        $statement = $this->getDi()->getDb()->prepare("SELECT id, username, utype FROM users");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function get_agregators() {
        $statement = $this->getDi()->getDb()->prepare("SELECT id, agregator FROM tinas__Agregators");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function get_sources() {
        $statement = $this->getDi()->getDb()->prepare("SELECT id, sourceName FROM tinas__Sources");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array(), array());
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function get_user_countries($uid) {

        $statement = $this->getDi()->getDb()->prepare("SELECT c.id,c.name,
CASE WHEN country IS NULL
THEN 0
ELSE 1
END AS stat
FROM Countries c
LEFT JOIN (
SELECT *
FROM UsersCountrySource
WHERE uid =:uid
) AS ucs ON c.id = ucs.country");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array('uid' => $uid), array('uid' => \Phalcon\Db\Column::TYPE_INTEGER));
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function get_user_access($uid, $country) {

        $statement = $this->getDi()->getDb()->prepare("SELECT agregators,sources FROM UsersCountrySource WHERE  uid=:uid AND country=:country");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array('uid' => $uid, 'country' => $country), array('uid' => \Phalcon\Db\Column::TYPE_INTEGER, 'country' => \Phalcon\Db\Column::TYPE_VARCHAR));
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        return $array_ret;
    }

    public function updateCountry($uid, $country) {

        $statement = $this->getDi()->getDb()->prepare("SELECT * FROM UsersCountrySource WHERE country=:country AND uid=:uid");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array('uid' => $uid, 'country' => $country), array('uid' => \Phalcon\Db\Column::TYPE_INTEGER, 'country' => \Phalcon\Db\Column::TYPE_VARCHAR));
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);
        if (empty($array_ret)) {
            $statement = $this->getDi()->getDb()->prepare('INSERT INTO UsersCountrySource (uid,country,updateTime) VALUES (:uid,:country,"' . date('Y-m-d H:i:s') . '")');
            $this->getDi()->getDb2()->executePrepared($statement, array('uid' => $uid, 'country' => $country), array('uid' => \Phalcon\Db\Column::TYPE_INTEGER, 'country' => \Phalcon\Db\Column::TYPE_VARCHAR));
        } else {
            $statement = $this->getDi()->getDb()->prepare('DELETE FROM UsersCountrySource WHERE uid=:uid AND country=:country');
            $this->getDi()->getDb2()->executePrepared($statement, array('uid' => $uid, 'country' => $country), array('uid' => \Phalcon\Db\Column::TYPE_INTEGER, 'country' => \Phalcon\Db\Column::TYPE_VARCHAR));
        }
    }

    public function updateAgregators($uid, $country, $agregators) {
        $statement = $this->getDi()->getDb()->prepare('UPDATE UsersCountrySource SET agregators= :agr WHERE uid= :uid and country= :country ');
        $this->getDi()->getDb2()->executePrepared($statement, array('uid' => $uid, 'country' => $country, 'agr' => $agregators), array('uid' => \Phalcon\Db\Column::TYPE_INTEGER, 'country' => \Phalcon\Db\Column::TYPE_VARCHAR, 'agr' => \Phalcon\Db\Column::TYPE_VARCHAR));
    }

    public function updateSources($uid, $country, $sources) {

        $statement = $this->getDi()->getDb()->prepare('UPDATE UsersCountrySource SET sources= :src WHERE uid= :uid and country= :country ');
        $this->getDi()->getDb2()->executePrepared($statement, array('uid' => $uid, 'country' => $country, 'src' => $agregators), array('uid' => \Phalcon\Db\Column::TYPE_INTEGER, 'src' => \Phalcon\Db\Column::TYPE_VARCHAR, 'agr' => \Phalcon\Db\Column::TYPE_VARCHAR));
    }

    public function get_permissions($auth) {

        $statement = $this->getDi()->getDb()->prepare("SELECT country,agregators,sources FROM UsersCountrySource WHERE  uid=:uid");
        $exe = $this->getDi()->getDb()->executePrepared($statement, array('uid' => $auth['id']), array('uid' => \Phalcon\Db\Column::TYPE_INTEGER));
        $array_ret = $exe->fetchAll(PDO::FETCH_ASSOC);

        return $array_ret;
    }

    public function permission_string($array_ret, $auth, $countryfield, $sourcefield, $agregatorfield) {
        $retstring = "";
        $countrylist = array();
        $agrlist = '';
        $srclist = '';

        if ($auth['userlevel'] == 2 || $auth['userlevel'] == 3) {

            foreach ($array_ret as $row) {
                $agregator = ($row['agregators'] != 'ALL') ? ' AND ' . $agregatorfield . ' IN (' . $row['agregators'] . ') ' : '';
                $source = ($row['sources'] != 'ALL') ? ' AND ' . $sourcefield . ' IN (' . $row['sources'] . ') ' : '';
                $retstring.= ' (' . $countryfield . '="' . $row['country'] . '" ' . $agregator . ' ' . $source . ') OR';
                $countrylist[] = $row['country'];
                $agrlist.=$row['agregators'] . ',';
                $srclist.=$row['sources'] . ',';
            }
            $agrlist = (empty($agrlist)) ? NULL : array_unique(explode(',', $agrlist));
            $srclist = (empty($srclist)) ? NULL : array_unique(explode(',', $srclist));
            $retstring = ($retstring == '') ? ' AND 1=2 ' : ' AND (' . rtrim($retstring, 'OR') . ')';
        } else {
            $agrlist = NULL;
            $srclist = NULL;
            $retstring = '';
        }
        return array($retstring, $countrylist, $agrlist, $srclist);
    }

}

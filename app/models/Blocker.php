<?php

use Phalcon\Mvc\Model;

class Blocker extends Model {

    public function initialize() {
        $this->setConnectionService('db4');
        $this->setSource('Blocker__History');
    }

    public function checkAffectedRows($hashmask) {
        try {
            $hashmask .= '&';
            $sql = 'SELECT COUNT(*) as totalAffected 
                    FROM MultiClick m
                    WHERE lpUrl LIKE :lpUrl and percent > 0 ;';
            //echo $sql;
            $sql2 = 'SELECT COUNT(*) as totalAffected 
                    FROM LandingPage m
                    WHERE linkref LIKE :linkref and percent > 0 ;';
            //HelpingFunctions::writetolog('I', $sql2);
            $sql3 = 'SELECT lpName as njumpName 
                    FROM MultiClick m
                    WHERE lpUrl LIKE :lpUrl and percent > 0
                    GROUP BY lpName ';
            //echo $sql3;
            $sql4 = 'SELECT lpName as mjumpName
                    FROM LandingPage m
                    WHERE linkref LIKE :linkref and percent > 0
                    GROUP BY lpName ';
            //echo $sql4;
            $return1 = $this->getDi()->getDb4()->fetchAll($sql, Phalcon\Db::FETCH_ASSOC, array('lpUrl' => '%' . $hashmask . '%'));
            $return2 = $this->getDi()->getDb4()->fetchAll($sql2, Phalcon\Db::FETCH_ASSOC, array('linkref' => '%' . $hashmask . '%'));
            $return3 = $this->getDi()->getDb4()->fetchAll($sql3, Phalcon\Db::FETCH_ASSOC, array('lpUrl' => '%' . $hashmask . '%'));
            $return4 = $this->getDi()->getDb4()->fetchAll($sql4, Phalcon\Db::FETCH_ASSOC, array('linkref' => '%' . $hashmask . '%'));
            //HelpingFunctions::writetolog('I', $return2[0]['totalAffected']);
            return array($return1, $return2, $return3, $return4);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E'.__CLASS__, $ex->getMessage());
            return null;
        }
    }

    public function executeBackup($hashmask, $backupname, $backupdescription, $backuphash, $campaign,$userid) {
        try {
            $hashmask .= '&';
            $historysql = 'INSERT INTO Blocker__History (backuphash,campaign,userid,'
                    . 'backupname,backupdescription,status,insertTimestamp) '
                    . 'VALUES ("' . $backuphash . '","' . $campaign
                    . '",' . $userid
                    . ',"' . $backupname . '","' . $backupdescription . '",'
                    . '0,"' . date('Y-m-d H:i:s') . '")';
            $this->getDi()->getDb4()->execute($historysql);

            $sql1 = 'INSERT INTO Blocker__MultiClick
                SELECT m.*, "' . $backuphash . '","' . date('Y-m-d H:i:s') . '" 
                FROM MultiClick m
                WHERE lpUrl LIKE "%' . $hashmask . '%" and percent > 0;';
            $this->getDi()->getDb4()->execute($sql1);

            $sql2 = 'INSERT INTO Blocker__LandingPage
                SELECT m.*, "' . $backuphash . '","' . date('Y-m-d H:i:s') . '" 
                FROM LandingPage m
                WHERE linkref LIKE "%' . $hashmask . '%" and percent > 0;';
            $this->getDi()->getDb4()->execute($sql2);

            $sql3 = 'UPDATE MultiClick 
                SET percent = 0
                WHERE lpUrl LIKE "%' . $hashmask . '%" and percent > 0;';
            $this->getDi()->getDb4()->execute($sql3);

            $sql4 = 'UPDATE LandingPage 
                SET percent = 0
                WHERE linkref LIKE "%' . $hashmask . '%" and percent > 0;';
            $this->getDi()->getDb4()->execute($sql4);


            $sql5 = 'SELECT lpName 
                FROM MultiClick 
                WHERE hashmask IN 
                    ( SELECT hashmask as hashmask 
                        FROM MultiClick 
                        WHERE lpUrl LIKE :hashmask )  
                GROUP BY hashmask,lpname HAVING(SUM(percent) = 0);';
            $return1 = $this->getDi()->getDb4()->fetchAll($sql5, Phalcon\Db::FETCH_ASSOC, array('hashmask' => '%' . $hashmask . '%'));

            $sql6 = 'SELECT lpName 
                FROM LandingPage 
                WHERE hashmask IN 
                    ( SELECT hashmask as hashmask 
                        FROM LandingPage m
                    WHERE linkref LIKE :linkref )  
                GROUP BY hashmask,lpname HAVING(SUM(percent) = 0);';
            $return2 = $this->getDi()->getDb4()->fetchAll($sql6, Phalcon\Db::FETCH_ASSOC, array('linkref' => '%' . $hashmask . '%'));

            $sql7 = 'SELECT backuphash,campaign,userid,backupname,backupdescription,status,insertTimestamp '
                    . ' FROM Blocker__History WHERE status = 0 and userid = ' . $userid;
            $return3 = $this->getDi()->getDb4()->fetchAll($sql7, Phalcon\Db::FETCH_ASSOC, array());

            return array($return1, $return2, $return3);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E'.__CLASS__, $ex->getMessage());
            return null;
        }
    }

    public function executeRestore($backuphash,$userid,$userlevel) {
        try {
            $sql1 = 'UPDATE MultiClick m '
                    . ' inner join Blocker__MultiClick mm ON m.id = mm.id '
                    . ' SET m.percent = mm.percent '
                    . ' WHERE mm.backuphash = "' . $backuphash . '"';
            $this->getDi()->getDb4()->execute($sql1);

            $sql2 = 'UPDATE LandingPage lp '
                    . ' inner join Blocker__LandingPage lplp ON lp.id = lplp.id '
                    . ' SET lp.percent = lplp.percent '
                    . ' WHERE lplp.backuphash = "' . $backuphash . '"';
            $this->getDi()->getDb4()->execute($sql2);

            $sql3 = 'UPDATE Blocker__History '
                    . ' SET status = 1 '
                    . ' WHERE backuphash = "' . $backuphash . '"; ';
            $this->getDi()->getDb4()->execute($sql3);
            $sql4 = 'SELECT backuphash,campaign,userid,backupname,'
                    . ' backupdescription,status,insertTimestamp '
                    . ' FROM Blocker__History '
                    . ' WHERE (userid = '.$userid .' OR '.$userlevel.'<2 ) AND status = 0 ;';
            $return = $this->getDi()->getDb4()->fetchAll($sql4, Phalcon\Db::FETCH_ASSOC, array());

            return array(1,$return);
        } catch (Exception $ex) {
            HelpingFunctions::writetolog('E'.__CLASS__, $ex->getMessage());
            return null;
        }
    }

}

<?php

/**
 * ReportController
 *
 * Report Insertion and presentation
 */
class AffiliateController extends ControllerBase
{   
    private $reportObject;
    public function initialize()
    {
        $this->tag->setTitle('Affiliates');
        parent::initialize();
        $this->reportObject = new Report();
    }
    
    public function indexAction(){
        $auth = $this->session->get('auth');
        $revshare = $this->getRevshare(false);
        $op = new OperationController();
        $campaigns = $op->getCampaigns();
        $sources = $op->getSources();
        //select one source
        //format table for that specific source
        $tables = $this->generateTables($revshare);
        
        $this->view->setVar("revShare", $tables[0]);
        $this->view->setVar("sourcesList", $tables[1]);
        $this->view->setVar("campaignSelectList", $campaigns);
        $this->view->setVar("sourcesVar", $sources);
    }
    
    public function removeAffiliationAction(){
        if( null != $this->request->get('source') && null != $this->request->get('hash')){
            $aff = new Affiliate();
            $res = $aff->removeAff($this->request->get('source'),$this->request->get('hash'));
            $this->getRevshare(true);
            echo $res->rowCount();
        }
        
    }
    
    public function updatePayoutAction(){
        

        if( (null != $this->request->get('source')) && (null != $this->request->get('hash')) && (null != $this->request->get('payout'))){
            if(0 === preg_match('/^[0-9]+(\.[0-9]+)?$/', $this->request->get('payout')))
            {
                echo '-2';
                return;
            }
            $aff = new Affiliate();
            $res = $aff->updatepayout($this->request->get('source'),$this->request->get('hash'),$this->request->get('payout'));
            $this->getRevshare(true);
            echo $res->rowCount();
        }
        
    }
    
    public function newAffiliationAction(){
        if( (null != $this->request->get('source')) && (null != $this->request->get('hash')) && (null != $this->request->get('payout'))){
            if(0 === preg_match('/^[0-9]+(\.[0-9]+)?$/', $this->request->get('payout')))
            {
                echo '-2';
                return;
            }
            $aff = new Affiliate();
            $res = $aff->newAffialite($this->request->get('source'),$this->request->get('hash'),$this->request->get('payout'));
            $this->getRevshare(true);
            echo $res;
        }
    }
    private function generateTables($revshare){
        $table = '<div id="sourcetables">';
        $sources = '';
        $lastsource = 0;
        $uniqueness = 0;
        foreach($revshare as $item){
            $uniqueness++;
            if($lastsource == $item['source']){
                $table.= '<tr id="'.$item['hash'].'S-'.$item['source'].'"><td>'.$item['campaign'].'</td><td>'.$item['source'].'</td><td class="cell" contenteditable="">'.$item['value'].'</td><td><a class="deleteAffiliation">Delete</a></td></tr>';
            }
            else{
               if(strlen($table)> 6){
                   $table.='</tbody></table>';
                   $sources .= '<option value="source'.$item['source'].'">'.$item['sourceName'].'</option>';
               } 
               $lastsource = $item['source'];    
               $table .= '<table class="table table-striped table-condensed" id="source'.$item['source'].'">'
                       . '<thead><tr><th class="col-md-5">Campaign</th><th class="col-md-3">Affiliate - '.$item['sourceName'].'</th><th class="col-md-2">Payout</th><th class="col-md-2">Action</th></tr></thead>'
                       . '<tbody>       
                            <tr id="'.$item['hash'].'S-'.$item['source'].'"><td>'.$item['campaign'].'</td><td>'.$item['source'].'</td><td class="cell" contenteditable="">'.$item['value'].'</td><td><a class="deleteAffiliation" >Delete</a></td></tr>';
            }
        }
        $res = array();
        $res[0]=$table.'</tbody></table></div>';
        $res[1]=$sources;
        return $res;
    }
    
    private function getRevshare($renew){
        $cache = $this->di->get('viewCache');
        if($renew)
            $revshare = null;
        else
            $revshare = $cache->get('revshare');

        if($revshare == null){
            $aff = new Affiliate();
            $revshare = $aff->getCurrentCampaigns();
            $cache->save('revshare', $revshare);
        }
        return $revshare;
     }
}
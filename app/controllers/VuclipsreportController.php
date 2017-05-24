<?php

/**
 * ReportController
 *
 * Report Insertion and presentation
 */
class VuclipsreportController extends ControllerBase {

    private $reportObject;

    public function initialize() {
        $this->tag->setTitle('Vuclips Management');
        parent::initialize();
        $this->reportObject = new Report();
    }

    public function indexAction() {
        try {
            //$logArray = $this->login_access();
            $vuclips = VuclipsMetadata::find();
            $arr = array();
            foreach ($vuclips as $vuclipsoffer) {
                //id country vuclipsoffer carrierTag url masks campaignnames
                $res['id'] = $vuclipsoffer->id;
                $res['country'] = $vuclipsoffer->country;
                $res['vuclipsoffer'] = $vuclipsoffer->vuclipsoffer;
                $res['carrierTag'] = $vuclipsoffer->carrierTag;
                $res['url'] = $vuclipsoffer->url;
                $res['masks'] = $vuclipsoffer->masks;
                $res['campaignnames'] = $vuclipsoffer->campaignnames;
                $arr[] = $res;
            }
            $this->view->setVar("currentoffers", json_encode($arr));
        } catch (Exception $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    public function addOneAction() {
        try {
            //id country vuclipsoffer carrierTag url masks campaignnames
            //$id = $this->request->get('id');
            $country = $this->request->get('country');
            $vuclipsoffer = $this->request->get('vuclipsoffer');
            $carrierTag= $this->request->get('carrierTag');
            $url = urldecode($this->request->get('url'));
            //$this->request->get('masks');
            $campaignnames = $this->request->get('campaignnames');
            $masks = $this->findCampaigns($campaignnames);

            if (!isset($masks)) {
                echo '99';
                return;
            }
            $newvuclip = $this->createOffer($country,$vuclipsoffer,$carrierTag,$url,$campaignnames,$masks,1);
            if($newvuclip === null){
                return;
            }
            else {
                $res = array();
                $res['id'] = $newvuclip->id;
                $res['country'] = $newvuclip->country;
                $res['vuclipsoffer'] = $newvuclip->vuclipsoffer;
                $res['carrierTag'] = $newvuclip->carrierTag;
                $res['url'] = $newvuclip->url;
                $res['campaignnames'] = $newvuclip->campaignnames;
                $res['masks'] = $newvuclip->masks;
                echo json_encode($res);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return 0;
        }
    }

    public function removeOneAction() {
        try {
            $id = $this->request->get('id');
            $vuclip = VuclipsMetadata::findFirst("id=" . $id);
            if ($vuclip->delete() == false)
                echo '0';
            else
                echo '1';
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }

    public function editOneAction() {
        try {
            //id country vuclipsoffer carrierTag url campaignnames
            $id = $this->request->get('id');
            $country = $this->request->get('country');
            $country = strtoupper($country);
            $vuclipsoffer = $this->request->get('vuclipsoffer');
            $carrierTag= $this->request->get('carrierTag');
            $url = urldecode($this->request->get('url'));
            $campaignnames = $this->request->get('campaignnames');
            $masks = $this->findCampaigns($campaignnames);
            if(!isset($masks)){
                echo '99';
                return;
            }
            else{
                $currentoffer = VuclipsMetadata::findFirst("id=".$id);
                $currentoffer->status = 0;
                if($currentoffer->campaignnames != $campaignnames)
                    $currentoffer->status = 1;
                    
                $currentoffer->country = $country;
                $currentoffer->vuclipsoffer = $vuclipsoffer;
                $currentoffer->carrierTag = $carrierTag;
                $currentoffer->url = $url;
                $currentoffer->campaignnames = $campaignnames;
                $currentoffer->masks = $masks;
                if($currentoffer->save() == false){
                    foreach ($currentoffer->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    return null;
                }
                else{
                   $res = array();
                    $res['id'] = $currentoffer->id;
                    $res['country'] = $currentoffer->country;
                    $res['vuclipsoffer'] = $currentoffer->vuclipsoffer;
                    $res['carrierTag'] = $currentoffer->carrierTag;
                    $res['url'] = $currentoffer->url;
                    $res['campaignnames'] = $currentoffer->campaignnames;
                    $res['masks'] = $currentoffer->masks;
                    echo json_encode($res);
                }
            }
        } catch (Exception $ex) {
            echo 'something happened';
            return null;
        }
    }

    private function searchAtAffiliatesPlatform($campaign) {
        try {
            $sql = 'SELECT hash FROM Offers WHERE name LIKE "' . $campaign . '" LIMIT 1';
            $res = $this->getDi()->getDb3()->query($sql)->fetchAll();
            if (empty($res))
                return null;
            else if (empty($res['hash'])) {
                return null;
            } else
                return $res['hash'];
        } catch (Exception $ex) {
            return null;
        }
    }

    private function findCampaigns($campaignnames) {
        try {
            $campaignnamesarr = explode(',', $campaignnames);
            $masks = null;
            foreach ($campaignnamesarr as $campaign) {
                $mask = null;
                if (is_numeric($campaign)) {
                    $mask = $this->searchAtAffiliatesPlatform($campaign);
                } else {//mobistein search campaign
                    $campaignMask = new Mask();
                    $mask = $campaignMask->getMaskHash($campaign);
                }
                if (!isset($mask)) {
                    $masks = null;
                    break;
                }
                if ($masks === null)
                    $masks = $mask;
                else {
                    $masks .= ',' . $mask;
                }
            }
            return $masks;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }
    
    private function createOffer($country,$vuclipsoffer,$carrierTag,$url,$campaignnames,$masks,$status,$id = null){
        try{
            $newvuclip = new VuclipsMetadata();
            $newvuclip->country = $country;
            $newvuclip->vuclipsoffer = $vuclipsoffer;
            $newvuclip->carrierTag = $carrierTag;
            $newvuclip->url = $url;
            $newvuclip->campaignnames = $campaignnames;
            $newvuclip->masks = $masks;
            $newvuclip->status = $status;
            $newvuclip->insertTimestamp = date('Y-m-d H:i:s');
            if(isset($id)){
                $newvuclip->id = $id;
            }
            if ($newvuclip->create() == false) {
                foreach ($newvuclip->getMessages() as $message) {
                    echo $message, "\n";
                }
                return null;
            }
            else {
                return $newvuclip;
            }
        }
        catch(Exception $ex){
            echo $ex->getMessage();
        }
    }

}

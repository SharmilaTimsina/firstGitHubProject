<?php

/**
 * Permission
 *
 * Report Insertion and presentation
 */
class PermissionController extends ControllerBase {

    public function indexAction() {
        //$auth = $this->session->get('auth');
        //select one source
        //format table for that specific source
        //$tables = $this->generateTables($revshare);

        $this->view->setVar("users", $this->getUsers());
        $this->view->setVar("sources", $this->getSources());
        $this->view->setVar("agregators", $this->getAgregators());
    }

    private function getUsers() {

        //add managed users option
        $users = new Permission();
        $users = $users->get_users();
        $selectstr = '';
        foreach ($users as $user) {
            $selectstr.='<option value="' . $user['id'] . '">' . $user['username'] . '</option>';
        }

        return $selectstr;
    }

    private function getAgregators() {
        $permission = new Permission();
        $agregators = $permission->get_agregators();
        $selectstr = '';
        foreach ($agregators as $agregator) {
            $selectstr.='<option value="' . $agregator['id'] . '">' . $agregator['id'] . ' - ' . $agregator['agregator'] . '</option>';
        }
        return $selectstr;
    }

    private function getSources() {
        $permission = new Permission();
        $sources = $permission->get_sources();
        $selectstr = '';
        foreach ($sources as $source) {
            $selectstr.='<option value="' . $source['id'] . '">' . $source['id'] . ' - ' . $source['sourceName'] . '</option>';
        }
        return $selectstr;
    }

    public function countriesAction() {
        $uid = $this->request->getPost('u');
        $permission = new Permission();
        $clist = $permission->get_user_countries($uid);
        $multiselect = '';
        foreach ($clist as $sel) {
            $selv = ($sel['stat'] == 1) ? 'selected="selected"' : '';
            $multiselect.='<option ' . $selv . ' name="' . $sel['name'] . '" value="' . $sel['id'] . '">' . $sel['name'] . '</option>';
        }
        echo $multiselect;
    }

    public function srcagrAction() {
        $uid = $this->request->getPost('u');
        $cid = $this->request->getPost('c');
        $permission = new Permission();
        $salist = $permission->get_user_access($uid, $cid);
        if (empty($salist)) {
            echo '[{"agregators":"","sources":""}]';
        } else {

            echo json_encode($salist);
        }
    }

    public function updatecountryAction() {
        $uid = $this->request->get('u');
        $country = $this->request->get('c');
        $permission = new Permission();
        $permission->updateCountry($uid, $country);
        echo '{"status":"ok"}';
    }

    public function updateAgregatorsAction() {
        $uid = $this->request->getPost('u');
        $country = $this->request->getPost('c');
        $agregators = $this->request->getPost('a');
        $permission = new Permission();
        $permission->updateAgregators($uid, $country, $agregators);
        echo '{"status":"ok"}';
    }

    public function updateSourcesAction() {
        $uid = $this->request->getPost('u');
        $country = $this->request->getPost('c');
        $sources = $this->request->getPost('s');
        $permission = new Permission();
        $permission->updateAgregators($uid, $country, $sources);
        echo '{"status":"ok"}';
    }

}

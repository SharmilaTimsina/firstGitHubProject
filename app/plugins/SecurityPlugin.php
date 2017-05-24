<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin {

    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    public function getAcl() {

        //throw new \Exception("something");



        $acl = new AclList();

        $acl->setDefaultAction(Acl::DENY);

        //Register roles
        $roles = array(
            'users' => new Role('Users'),
            'guests' => new Role('Guests'),
            'operators' => new Role('Operators')
        );
        foreach ($roles as $role) {
            $acl->addRole($role);
        }

        //Private area resources
        $privateResources = array(
            'report' => array('index', 'main', 'operator', 'mjump', 'network', 'statistics', 'getavgdata', 'payoutreport', 'aggSummary', 'reportbyhour', 'notworking', 'getCarrier', 'getCarrier2', 'statisticsNew', 'index2', 'statistics2', 'lpreport'),
            'reportmb' => array('index', 'main', 'operator', 'mjump', 'network', 'statistics', 'getavgdata', 'payoutreport', 'aggSummary', 'reportbyhour', 'getCarrier', 'getCarrier2', 'statisticsNew', 'index2', 'statistics2'),
            'operation' => array('index', 'mainstreamsourcenewname', 'getClientInfo', 'jump', 'updateCampaign', 'getCampaignLink', 'createSource', 'createAggregator', 'getAggInfo', 'link_checker', 'campaign_url', 'createCategory', 'findLatestConv', 'copySource'),
            'slink' => array('index', 'ajaxtable', 'ajaxinsertgroup', 'ajaxgroupcombo', 'ajaxname', 'ajaxclone', 'ajaxdelete', 'ajaxupdatecell', 'ajaxdeleterow', 'ajaxclonerow', 'ajaxinsertmgroup', 'set_country', 'unset_country'),
            'smlink' => array('index', 'ajaxtable', 'ajaxinsertgroup', 'ajaxgroupcombo', 'ajaxname', 'ajaxclone', 'ajaxdelete', 'ajaxupdatecell', 'ajaxdeleterow', 'ajaxclonerow', 'ajaxinsertmgroup'),
            'affiliate' => array('index', 'removeAffiliation', 'updatePayout', 'newAffiliation'),
            'isp' => array('index', 'get_groups', 'get_group', 'create_group', 'create_isp', 'delete_isp'),
            'mainstream' => array('index', 'main', 'getCountriesWithDomainid', 'getAllCategories', 'getNjumps', 'getMainstreamHistory', 'saveNewRow'),
            'mainstreambulk' => array('index', 'gallery', 'updateBannerId', 'insertbanners', 'editbanner', 'searchbanners', 'createBulk', 'editBulk', 'cloneBulk', 'downloadZip', 'tableBulk', 'getNjump', 'getCountry', 'getSources', 'getBulk', 'getBanners', 'downloadXlsx'),
            'alert' => array('index', 'getSourcesAlerts', 'getClientsAlerts', 'getCountriesAlerts', 'getTablesAlerts', 'newAlert', 'editAlert', 'editTables', 'removeAlert', 'newCountryAlert', 'newAggAlert', 'newSrcAlert', 'getSourceTypeAlerts', 'newSourceTypeAlert'),
            'invest' => array('index', 'upload', 'report', 'setfilter', 'campaigns', 'getReportExcel', 'totalsRow'),
            'security' => array('index', 'deletei'),
            'google' => array('index', 'downloadreport', 'addgooglesource'),
            'vuclipsreport' => array('index', 'addOne', 'removeOne', 'editOne'),
            'integration' => array('index', 'save_agregator', 'save_source', 'get_excel', 'report_conversion', 'save_user', 'save_domain', 'delete_domain', 'new_domain', 'get_clicksInfo', 'setEditClicks', 'reverse_multiclick', 'save_risqiq', 'delete_risqiq'),
            'dashboard' => array('index', 'last7Days', 'percentageShift'),
            'ticketing' => array('index', 'createTicket', 'editTicket'),
            'jump' => array('index', 'uploadCsv'),
            'crm' => array('index', 'getClient', 'createClient', 'editClient', 'getClients', 'last7Days'),
            'autobid' => array('index', 'addcampaign', 'editcampaign', 'deletecampaign', 'refreshTable', 'downloadReport', 'addAccount', 'resetpass'),
            'ip' => array('index', 'getIps', 'getCustomIPs', 'getcarriers', 'getclientips', 'testphalconshit', 'addNewCarrier', 'addNewCountry'),
            'freporting' => array('index', 'upload', 'setFilter', 'updateComment', 'downloadCsv', 'getChart', 'uploadMultiAgg', 'getAffs', 'getAggs'),
            'sourcesapi' => array('index', 'downloadReport'),
            'permission' => array('index', 'countries', 'srcagr', 'updatecountry', 'updateAgregators', 'updateSources'),
            'customreport' => array('index', 'getAttributes', 'savecustomreport', 'createcustom', 'getMyCustomReports', 'echoReport', 'deleteSavedReport', 'downloadReport'),
            'campaignblocker' => array('index', 'getAffected', 'executeblock', 'executerestore', 'getcampaigns', 'executetemprestore', 'blockbyname'),
            'rates' => array('index', 'getRateThreeMonths'),
            'offerpack' => array('index', 'getDims', 'getCarriers', 'newOfferpack', 'newOfferpack2', 'offerpackedit', 'getCarriersMultiple', 'getCampaignJumpName', 'setFilter', 'updateCpa', 'addOffersExcel', 'disableOffer', 'getScreenshot', 'getBanners', 'getCM', 'getAccount', 'deletebanner', 'deletescreenshot', 'getofferinfo', 'updateStatus', 'index2', 'offerpackedit2', 'setFilter2'),
            'ticketsystem' => array('index', 'editTicket', 'createTicket', 'getUsersMultiple', 'create_ticket', 'sendMessage', 'refreshChat', 'downloadFile', 'uploadFile', 'edit_ticket', 'pickTicket', 'closeTicket', 'reopenTicket', 'setFilter', 'downloadExcel'),
            'ticketsystem2' => array('index', 'editTicket', 'createTicket', 'getUsersMultiple', 'create_ticket', 'sendMessage', 'refreshChat', 'downloadFile', 'uploadFile', 'edit_ticket', 'pickTicket', 'closeTicket', 'reopenTicket', 'setFilter', 'downloadExcel', 'editticket_itsdes', 'editTicket', 'sendToValidation', 'putOnHold', 'putInProgress', 'refuse', 'request', 'assign', 'ticketok', 'ticketnotok', 'closeticket', 'infosent', 'reopenticket', 'changeDeadline'),
            'njump' => array('index', 'changeGlobalName', 'getStatistics', 'favoriteNjump', 'getNjumps', 'getnjump', 'newnjump', 'njumpedit', 'njumpclone', 'njumpdelete', 'njumpdeleterow', 'njumpnewrow', 'updatecell', 'njumpsortby', 'indexm', 'njumpeditm', 'mreset', 'getnjumpsbycountry', 'njumpclonemultiplelines', 'njumpdeletemultiplerow'),
            'landingmanager' => array('index', 'createeditlp', 'getDims', 'setFilter', 'getlpinfo', 'newEditLp', 'getFilters', 'getOfferByGeo', 'insertNewLp', 'newlp', 'getLpInformation', 'saveLp', 'deleteLp', 'viewlp', 'getLpInformationView'),
            'admin' => array('index', 'displayAgregators', 'removeAgregators', 'removeCountries', 'addAgregators', 'getAllAgregatorsUsers', 'displayCountries', 'getAllCountriesUsers', 'addCountries'),
            'client' => array('index', 'searchAll', 'getData', 'searchData', 'createClient', 'saveClient', 'updateClient', 'getClientUpdated', 'getTotalRows', 'hideClient', 'getGraphData', 'chatSave', 'getHistoryInfo', 'getIssueInfo'),
            'sales' => array('index', 'getData', 'createExcel', 'getCarriers')
        );
        foreach ($privateResources as $resource => $actions) {
            $acl->addResource(new Resource($resource), $actions);
        }
        //operation area resources
        $operationResources = array(
            'operation' => array('index', 'jump', 'updateCampaign', 'getCampaignLink', 'createSource', 'createAggregator', 'getAggInfo', 'link_checker', 'campaign_url', 'createCategory', 'findLatestConv', 'copySource'),
            'report' => array('index', 'statistics', 'getavgdata', 'aggSummary', 'getCarrier', 'index2', 'statistics2', 'getCarrier2'),
            'reportmb' => array('index', 'statistics', 'getavgdata', 'aggSummary', 'getCarrier', 'index2', 'statistics2', 'getCarrier2'),
            'security' => array('index', 'deletei'),
            'mainstream' => array('index', 'main', 'getCountriesWithDomainid', 'getAllCategories', 'getNjumps', 'getMainstreamHistory', 'saveNewRow'),
            'mainstreambulk' => array('index'),
            'vuclipsreport' => array('index', 'addOne', 'removeOne', 'editOne'),
            'integration' => array('index', 'save_agregator', 'save_source', 'get_excel', 'report_conversion', 'save_user', 'save_domain', 'delete_domain', 'new_domain', 'get_clicksInfo', 'setEditClicks', 'reverse_multiclick', 'save_risqiq', 'delete_risqiq'),
            'dashboard' => array('index', 'chartinfo', 'reportbyhour', 'percentageShift', 'last7Days', 'getRateThreeMonths'),
            'ticketing' => array('index', 'createTicket', 'editTicket'),
            'jump' => array('index', 'uploadCsv'),
            'crm' => array('index', 'getClient', 'createClient', 'editClient', 'getClients', 'last7Days'),
            'autobid' => array('index', 'addcampaign', 'editcampaign', 'deletecampaign', 'refreshTable', 'downloadReport', 'addAccount', 'resetpass'),
            'ip' => array('index', 'getIps', 'getCustomIPs', 'getcarriers', 'getclientips', 'addNewCarrier', 'addNewCountry'),
            'freporting' => array('index', 'upload', 'setFilter', 'updateComment', 'downloadCsv', 'getChart', 'uploadMultiAgg', 'getAffs', 'getAggs'),
            'sourcesapi' => array('index', 'downloadReport'),
            'rates' => array('index', 'getRateThreeMonths'),
            'offerpack' => array('index', 'getDims', 'getCarriers', 'newOfferpack', 'offerpackedit', 'getCarriersMultiple', 'getCampaignJumpName', 'setFilter', 'updateCpa', 'addOffersExcel', 'disableOffer', 'getScreenshot', 'getBanners', 'getCM', 'getAccount', 'deletebanner', 'deletescreenshot', 'getofferinfo', 'updateStatus', 'index2', 'offerpackedit2', 'setFilter2'),
            'ticketsystem' => array('index', 'editTicket', 'createTicket', 'getUsersMultiple', 'create_ticket', 'sendMessage', 'refreshChat', 'downloadFile', 'uploadFile', 'edit_ticket', 'pickTicket', 'closeTicket', 'reopenTicket', 'setFilter', 'downloadExcel'),
            'ticketsystem2' => array('index', 'editTicket', 'createTicket', 'getUsersMultiple', 'create_ticket', 'sendMessage', 'refreshChat', 'downloadFile', 'uploadFile', 'edit_ticket', 'pickTicket', 'closeTicket', 'reopenTicket', 'setFilter', 'downloadExcel', 'editticket_itsdes', 'editTicket', 'sendToValidation', 'putOnHold', 'putInProgress', 'refuse', 'request', 'assign', 'ticketok', 'ticketnotok', 'closeticket', 'infosent', 'reopenticket', 'changeDeadline'),
            'njump' => array('index'),
            'campaignblocker' => array('index', 'getAffected', 'executeblock', 'executerestore', 'getcampaigns'),
            'landingmanager' => array('index', 'createeditlp', 'getDims', 'setFilter', 'getlpinfo', 'newEditLp', 'getFilters', 'getOfferByGeo', 'insertNewLp', 'newlp', 'getLpInformation', 'saveLp', 'deleteLp', 'viewlp', 'getLpInformationView'),
            'admin' => array('index', 'displayAgregators', 'removeAgregators', 'removeCountries', 'addAgregators', 'getAllAgregatorsUsers', 'displayCountries', 'getAllCountriesUsers', 'addCountries'),
            'client' => array('index', 'searchAll', 'getData', 'searchData', 'createClient', 'saveClient', 'updateClient', 'getClientUpdated', 'getTotalRows', 'hideClient', 'getGraphData', 'chatSave', 'getHistoryInfo', 'getIssueInfo'),
            'sales' => array('index', 'getData', 'createExcel', 'getCarriers')
        );
        foreach ($operationResources as $resource => $actions) {
            $acl->addResource(new Resource($resource), $actions);
        }

        //Public area resources
        $publicResources = array(
            'index' => array('index'),
            'errors' => array('show404', 'show500'),
            'session' => array('index', 'register', 'start', 'end'),
            'offerpack' => array('getzip')
        );
        foreach ($publicResources as $resource => $actions) {
            $acl->addResource(new Resource($resource), $actions);
        }

        //Grant access to public areas to both users and guests
        foreach ($roles as $role) {
            foreach ($publicResources as $resource => $actions) {
                $acl->allow($role->getName(), $resource, '*');
            }
        }

        //Grant acess to private area to role Users
        foreach ($privateResources as $resource => $actions) {
            foreach ($actions as $action) {
                $acl->allow('Users', $resource, $action);
            }
        }
        //Grant acess to operations area to role Operations
        foreach ($operationResources as $resource => $actions) {
            foreach ($actions as $action) {
                $acl->allow('Operators', $resource, $action);
            }
        }

        //The acl is stored in session, APC would be useful here too
        $this->persistent->acl = $acl;


        return $this->persistent->acl;
    }

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher) {

        $auth = $this->session->get('auth');
        if (!$auth) {
            if ($this->getDi()->getCookies()->has("remember-me")) {

                // Get the cookie
                $rememberMeCookie = $this->getDi()->getCookies()->get("remember-me");
                $this->getDi()->getCookies()->send();
                //mail('pedrorleonardo@gmail.com','something','yea');
                // Get the cookie's value
                $value = $rememberMeCookie->getValue();
                $db = $this->getDi()->getDb();
                $sql = 'SELECT userid as id FROM lotofsessions where rememberme LIKE :rememberme';
                $statement2 = $db->prepare($sql);
                $resprep = $db->executePrepared($statement2, array('rememberme' => $rememberMeCookie), array());
                $res = $resprep->fetchAll(PDO::FETCH_ASSOC);
                if ($res != null && !empty($res[0]) && !empty($res[0]['id'])) {
                    $user = Users::findFirst(array(
                                'id = :id',
                                'bind' => array('id' => $res[0]['id'])
                    ));

                    if ($user != false) {
                        $this->_registerSession($user);
                        $auth = $this->session->get('auth');
                        if ($auth['userlevel'] == 3 || $auth['userlevel'] == 4) {
                            $role = 'Operators';
                        } else {
                            $role = 'Users';
                        }
                    }
                } else
                    $role = 'Guests';
            } else {
                $role = 'Guests';
            }
        } elseif ($auth['userlevel'] == 3 || $auth['userlevel'] == 4) {
            $role = 'Operators';
        } else {
            $role = 'Users';
        }

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $acl = $this->getAcl();

        $allowed = $acl->isAllowed($role, $controller, $action);
        if ($allowed != Acl::ALLOW) {
            $dispatcher->forward(array(
                'controller' => 'errors',
                'action' => 'show401'
            ));
            return false;
        }
    }

    private function _registerSession(Users $user) {

        $this->session->set('auth', array(
            'id' => $user->id,
            'name' => $user->username,
            'email' => $user->email,
            'userarea' => $user->userarea,
            'userlevel' => $user->userlevel,
            'countries' => $user->countries,
            'sources' => $user->sources,
            'decimalchar' => $user->decimalchar,
            'aggregators' => $user->aggregators,
            'utype' => (($user->userlevel > 1 ) ? $user->utype : null),
            'affiliates' => $user->affiliates,
            'invest' => $user->investaccess,
            'navtype' => $user->navtype,
            'dailyControl' => $user->dailyControl
        ));
    }

}

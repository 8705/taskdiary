<?php

/**
 * AppController.
 *
 * @author 8705
 */
class AppController extends Controller
{
    public $login_user;

    public function beforeFilter()
    {
        $this->autoLogin();
        if($this->session->isAuthenticated()){
            $this->login_user = $this->session->get('user');

            //coreに組み込んでもいい:session->getUser()
            unset($this->login_user['user_password']);
        }
    }

    public function autoLogin()
    {
        //ログイン時/セッション切れでない
        if($this->session->isAuthenticated()) {
            return true;
        }
        $token = $token = @$_COOKIE['token'];
        $autologin_repo = $this->db_manager->get('Autologin');
        $user_id = $autologin_repo->checkAuthToken($token);
        if($user_id !== false) {
            $user = $this->db_manager->get('User')->fetchById($user_id);
            $this->session->setAuthenticated(true);
            $this->session->set('user', $user);
            $autologin_repo->deleteByToken($token);
            $autologin_repo->setAuthToken($user_id, 30);

            return true;
        }

        return false;
    }
}

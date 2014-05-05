<?php
require_once(dirname(__FILE__).'/../lib/twitteroauth/twitteroauth.php');
define('CONSUMER_KEY', 's3kykJtPGjfQYgNDNSnhNW7yn');
define('CONSUMER_SECRET', '3AXTSuk1I0TGazyZqUoXNjSOu7D48O0ReaxKRrYYbjpczdo9m1');
/**
 * OauthController.
 *
 * @author 8705
 */
class TwitterController extends AppController
{
    protected $auth_actions = array();

    public function loginAction()
    {
        // api keys

        define('CALLBACK_URL', 'http://dev.taskdiary.8705.co/twitter/callback/');

        // request token取得
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

        $token = $connection->getRequestToken(CALLBACK_URL);
        if(! isset($token['oauth_token'])){
            //例外処理投げたい
            $this->redirect('/');
        }

        // callbackで使うのでsessionに突っ込む
        $_SESSION['oauth_token']        = $token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];
        if($connection->http_code === 200){
            // 認証用URL取得してredirect
            $url = $connection->getAuthorizeURL($_SESSION['oauth_token']);
            header("Location: " . $url);
            exit;
        }

        //twitterに接続できなかった。例外処理投げたい
        $this->redirect('/');
    }

    public function callbackAction()
    {
        if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
            $_SESSION['oauth_status'] = 'oldtoken';
            //セッションクリア
            header('Location: ./clearsessions.php');
        }

        $connection = new TwitterOAuth(
            CONSUMER_KEY,
            CONSUMER_SECRET,
            $_SESSION['oauth_token'],
            $_SESSION['oauth_token_secret']
        );

        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

        //DBに保存
        $_SESSION['access_token'] = $access_token;

        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);

        $twitter_id             = $access_token['user_id'];
        $twitteroauth_repo      = $this->db_manager->get('TwitterOauth');
        $tw_oauth_status        = $twitteroauth_repo->fetchByTwitterId($twitter_id);
        if($tw_oauth_status) {
            $twitteroauth_repo->updateAccessToken(
                $twitter_id,
                $access_token['oauth_token'],
                $access_token['oauth_token_secret']
            );

            //ログイン
            $user = $this->db_manager->get('User')->fetchById($tw_aouth_status['user_id']);
            $this->session->setAuthenticated(true);
            $this->session->set('user', $user);

            return $this->redirect('/');
        }

        $screen_name        = $access_token['screen_name'];
        // $oauth_token        = $access_token['oauth_token'];
        // $oauth_token_secret = $access_token['oauth_token_secret'];

        return $this->render(array('twitter_id'      => $twitter_id,
                                   'screen_name'     => $screen_name,
                                   '_token' => $this->generateCsrfToken('/twitter/callback')
                            ));
    }

    public function registerAction()
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/');
        }

        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $post = $this->request->getPost();
        if (!$this->checkCsrfToken('/twitter/callback', $post['_token'])) {
            return $this->redirect('/twitter/callback');
        }

        $errors = $this->db_manager->get('User')->validateTwitterRegister($post);
        if (count($errors) === 0) {
            $this->db_manager->get('User')->insertByTwitter($post);
            $this->session->setAuthenticated(true);
            $user = $this->db_manager->get('User')->fetchByName($post['user_name']);
            $this->session->set('user', $user);

            $this->db_manager->get('TwitterOauth')->insert(
                $user['user_id'],
                $_SESSION['access_token']['user_id'],
                $_SESSION['access_token']['oauth_token'],
                $_SESSION['access_token']['oauth_token_secret']
            );

            //自動ログイン受理
            if($post['is_autologin'] === 'on') {
                $this->db_manager->get('Autologin')->setAuthToken($user['user_id'], 30);
            }

            //認証メール送信処理
            if(!is_null($user['user_mail'])) {
                $authenticate_token = sha1($post['user_name'] . microtime() . '987yhjg02jfhsfsflfp0');
                $user = $this->db_manager->get('User')->fetchByName($post['user_name']);
                $this->db_manager->get('Activation')->insert($user['user_id'], $authenticate_token);
                $this->sendAuthenticateMail(
                    $post['user_mail'],
                    'メールアドレスのご確認',
                    array(
                        'user_name' => $post['user_name'],
                        'authenticate_token'     => $authenticate_token
                    )
                );
            }

            return $this->redirect('/');
        }

        return $this->render(
            array('user_name'       => $post['user_name'],
                  'user_mail'       => $post['user_mail'],
                  'errors'          => $errors,
                  '_token'          => $this->generateCsrfToken('/twitter/callback')
                  ),
                  'callback'
            );
    }
}
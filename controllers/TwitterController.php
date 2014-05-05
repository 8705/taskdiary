<?php
require_once(dirname(__FILE__).'/../lib/twitteroauth/twitteroauth.php');
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
        define('CONSUMER_KEY', 's3kykJtPGjfQYgNDNSnhNW7yn');
        define('CONSUMER_SECRET', '3AXTSuk1I0TGazyZqUoXNjSOu7D48O0ReaxKRrYYbjpczdo9m1');
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
        }

        //twitterに接続できなかった。例外処理投げたい
        $this->redirect('/');
    }
}
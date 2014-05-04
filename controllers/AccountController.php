<?php
require_once (dirname(__FILE__).'/../utility/SendMail.php');

/**
 * AccountController.
 *
 * @author 8705
 */
class AccountController extends AppController
{
    protected $auth_actions = array('signout');

    public function indexAction()
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/');
        }

        return $this->render(array('_token' => $this->generateCsrfToken('/account/index')));
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
        if (!$this->checkCsrfToken('/account/index', $post['_token'])) {
            return $this->redirect('/account/index');
        }

        $errors = $this->db_manager->get('User')->validateRegister($post);

        if (count($errors) === 0) {
            $this->db_manager->get('User')->insert($post);
            $this->session->setAuthenticated(true);
            $user = $this->db_manager->get('User')->fetchByName($post['user_name']);
            $this->session->set('user', $user);

            //認証メール送信処理
            $authenticate_token = sha1($post['user_name'] . $post['user_password'] . microtime());
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

            return $this->redirect('/');
        }

        return $this->render(
            array('user_name'       => $post['user_name'],
                  'user_mail'       => $post['user_mail'],
                  'user_password'   => $post['user_password'],
                  'errors'          => $errors,
                  '_token'          => $this->generateCsrfToken('/account/index')
                  ),
                  'index'
            );
    }

    public function activationAction($param)
    {
        $user = $this->db_manager->get('User')->fetchByName($param['property']);
        if(!$user) {
            return $this->redirect('/');
        }
        if($user['user_authority'] !== "2") {
            return $this->redirect('/');
        }

        $activate_status = $this->db_manager->get('Activation')->fetchByUserId($user['user_id']);

        //$property2のサニタイズいるよな〜。utility待ちで
        if($activate_status['activation_token'] === $param['property2']) {
            $this->db_manager->get('User')->doneActivateById($user['user_id']);

            $this->session->setAuthenticated(true);
            $this->session->set('user', $user);

            //認証完了通知メール
            $this->sendDoneAuthenticateMail(
                $user['user_mail'],
                'メールアドレスの認証が完了しました - Task Diary',
                array(
                    'user_name'     => $user['user_name']
                )
            );
        }

        return $this->redirect('/');

    }

    /*
        メソッド化する必要ないかもしれない
    */
    private function sendAuthenticateMail($to, $subject, $vars = array())
    {
        $mail = new SendMail();
        $mail->setTo($to);
        $mail->setSubject($subject);
        $mail->setTemplate('authenticate');
        $mail->setVars($vars);
        $mail->send();

    }

    private function sendDoneAuthenticateMail($to, $subject, $vars = array())
    {
        $mail = new SendMail();
        $mail->setTo($to);
        $mail->setSubject($subject);
        $mail->setTemplate('done_authenticate');
        $mail->setVars($vars);
        $mail->send();

    }

    public function loginAction()
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/');
        }

        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $post = $this->request->getPost();
        if (!$this->checkCsrfToken('/account/index', $post['_token'])) {
            return $this->redirect('/account/index');
        }

        $errors = $this->db_manager->get('User')->validateLogin($post);

        if (count($errors) === 0) {
            $user = $this->db_manager->get('User')->fetchByName($post['user_name']);
            $hashed_password = $this->db_manager->get('User')->hashPassword($post['user_password']);

            if (!$user || ($user['user_password'] !== $hashed_password)) {
                $errors[] = 'ユーザIDかパスワードが不正です';
            } else {
                $this->session->setAuthenticated(true);
                $this->session->set('user', $user);

                return $this->redirect('/');
            }
        }

        return $this->render(array('user_name'       => '',
                                   'user_password'   => '',
                                   '_token'          => $this->generateCsrfToken('/account/login')
                          )
                );
    }

    public function logoutAction()
    {
        $this->session->clear();
        $this->session->setAuthenticated(false);

        return $this->redirect('/account/index');
    }

}

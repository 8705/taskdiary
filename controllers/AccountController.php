<?php

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

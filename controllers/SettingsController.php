<?php

/**
 * SettingsController.
 *
 * @author 8705
 */
class SettingsController extends AppController
{
    protected $auth_actions = array('index');

    public function indexAction()
    {
        return $this->render(array(
            'login_user' => $this->login_user,
        ));
    }

    public function imageAction()
    {
        if (!$this->request->isPost()) {
            return $this->render(array('user_id' => $this->login_user['user_id'], 'errors' => null));
        }

        $file   = $_FILES["image"];
        $errors = $this->db_manager->get('User')->validateImage($file);

        if (count($errors) === 0) {
            $fopen = fopen($file["tmp_name"],'rb');
            $image = fread($fopen, $file['size']);
            fclose($fopen);

            $this->db_manager->get('User')->insertImage($this->login_user['user_id'], $image);
        }

        return $this->render(array('user_id' => $this->login_user['user_id'], 'errors' => $errors));

    }

    public function uploadImageAction()
    {
        
    }

}

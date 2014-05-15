<?php

/**
 * AccountController.
 *
 * @author 8705
 */
class ImageController extends AppController
{
    public function outputAction($params)
    {
        $user_id = $params['property'];
        if ($user_id != $this->login_user['user_id']) {
            echo "不正なアクセスです";
            exit;
        }

        // ブラウザのキャッシュを利用する
        // if ($_SERVER['HTTP_IF_MODIFIED_SINCE']) {
        //     header('Content-type: image/jpeg');
        //     header('Last-Modified: Fri Jan 01 2010 00:00:00 GMT');
        //     header('HTTP/1.1 304 Not Modified');
        //     exit;
        // }

        $image = $this->db_manager->get('User')->fetchImage($user_id);

        header('Content-type: image/jpeg');
        echo $image;
        exit;
    }

}

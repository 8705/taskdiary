<?php

/**
 * AccountController.
 *
 * @author 8705
 */
class TaskController extends AppController
{
    protected $auth_actions = array('index', 'add', 'delete', 'add_task');

    public function addAction()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $user     = $this->session->get('user');
        $post     = $this->request->getPost();

        $errors = $this->db_manager->get('Task')->validateAdd($post);

        if (count($errors) === 0) {
            $this->_add($user, $post);
        }
    }

    public function _add($user, $post)
    {
        $res = $this->db_manager->get('Task')->insert($user['user_id'], $post);
        $last_insert_id = $res;
        if (isset($post['category_name']) && $post['category_name']) {
            $category = $this->db_manager->get('Category')->fetchByName($post['category_name'], $user);
            if(!$category) {
                $this->_add_category($post['category_name']);
                $category = $this->db_manager->get('Category')->fetchLastInsertId($user);
            }

            $this->db_manager->get('TaskCategory')->insert($last_insert_id, $category['category_id']);
        }

            return $this->redirect('/');
    }
    public function _add_category($category_name)
    {
        $user = $this->session->get('user');

        $errors = $this->db_manager->get('Category')->validateAdd($category_name);

        if (count($errors) === 0) {
            $this->db_manager->get('Category')->insert($user['user_id'],
                                                      $category_name
                                                      );
        }
    }

    public function add_taskAction() {

        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $user     = $this->session->get('user');
        $posts     = $this->request->getPost();
        foreach($posts['task_name'] as $key => $task_name) {
            if(strlen($task_name)) {
                $this->_add($user['user_id'], array(
                    'task_name'     =>$task_name,
                    'task_limit'    =>$posts['task_limit'][$key],
                    'category_name'   =>$posts['category_name'][$key]
                ));
            }
        }
        return $this->redirect('/');
    }

    public function updateIsDoneAction()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }
        $post = $this->request->getPost();
        foreach ($post as $task_id => $task_is_done) {
            $this->db_manager->get('Task')->updateIsDone($task_id, $task_is_done);
        }

        return $this->redirect('/');
    }

    public function doneAction($params) {
        $task_id = $params['property'];
        $task = $this->db_manager->get('Task')->fetchById($task_id);
        if(!$task) {
            $this->forward404('そんなタスクはないです');
        }
        $task_id_done = $this->db_manager->get('Task')->toggleIsDoneById($task_id);
        if($task_id_done !== false) {
            $res = array(
                "error"         => "false",
                "task_id"       => $task_id,
                "task_is_done"  => $task_id_done,
            );
        } else {
            $res = array(
                "error"         => "true",
            );
        }
        header('Content-Type: application/json');
        echo json_encode($res);
        exit;
    }

    public function deleteAction($params)
    {
        $task_id = $params['property'];
        $task = $this->db_manager->get('Task')->fetchById($task_id);
        if(!$task) {
            $this->forward404('そんなタスクはないです');
        }

        $this->db_manager->get('TaskCategory')->deleteByTaskId($task_id);
        $this->db_manager->get('Task')->delete($task_id);

        return $this->redirect('/');
    }

    public function sortAction() {
        $post = $this->request->getPost();
        $sequence = $post['sequence'];
        parse_str($sequence); //$taskに配列が入る
        $result_array = array();
        foreach ($task as $sequence => $task_id) {
            $result_array[] = $this->db_manager->get('Task')->updateSequence($sequence,$task_id);
        }

        if(!in_array(false, $result_array)) {
            $res = array(
                "error" => "false"
            );
        } else {
            $res = array(
                "error" => "true"
            );
        }

        header('Content-Type: application/json');
        echo json_encode($res);
        exit;
    }

    public function add_commentAction($params) {
        $task_id = $params['property'];
        $task = $this->db_manager->get('Task')->fetchById($task_id);
        if(!$task) {
            $this->forward404('そんなタスクはないです');
        }
        $post = $this->request->getPost();
        $result = $this->db_manager->get('Task')->updateComment($task_id, $post['task_text']);
        if($result) {
            $res = array(
                "error"         => "false",
                "task_id"       => $task_id,
                "task_text"     => $post['task_text'],
            );
        } else {
            $res = array(
                "error"         => "true",
            );
        }
        header('Content-Type: application/json');
        echo json_encode($res);
        exit;
    }

    public function get_commentAction($params) {
        $task_id = $params['property'];
        $task = $this->db_manager->get('Task')->fetchById($task_id);
        if(!$task) {
            $this->forward404('そんなタスクはないです');
        }
        $task = $this->db_manager->get('Task')->fetchComment($task_id);
        if($task) {
            $res = array(
                "error"         => "false",
                "task_id"       => $task_id,
                "task_text"     => $task['task_text'],
            );
        } else {
            $res = array(
                "error"         => "true",
            );
        }
        header('Content-Type: application/json');
        echo json_encode($res);
        exit;
    }
}

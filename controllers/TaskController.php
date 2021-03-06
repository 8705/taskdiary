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

        $user_id  = $this->session->get('user');
        $post     = $this->request->getPost();

        $errors = $this->db_manager->get('Task')->validateAdd($post);

        if (count($errors) === 0) {
            $this->_add($user_id, $post);
        }
    }

    public function _add($user_id, $post)
    {
        $res = $this->db_manager->get('Task')->insert($user_id, $post);
        $last_insert_id = $res;

        // カテゴリー登録
        // if (isset($post['category_name']) && $post['category_name']) {
        //     $category = $this->db_manager->get('Category')->fetchByName($post['category_name'], $user);
        //     if(!$category) {
        //         $this->_add_category($post['category_name']);
        //         $category = $this->db_manager->get('Category')->fetchLastInsertId($user);
        //     }
        //
        //     $this->db_manager->get('TaskCategory')->insert($last_insert_id, $category['category_id']);
        // }

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
        // var_dump($posts);exit;
        if(strlen($posts['task_name'])) {
            $this->_add($user['user_id'], $posts);
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

    public function sortAction()
    {
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

    public function changeDivisionAction()
    {
        $post = $this->request->getPost();
        $division           = $post['division'];
        $changed_task_id    = $post['task_id'];

        $result_array = array();
        if($division === 'todays') {
            $result_array[] = $this->db_manager->get('Task')->setTaskToday($changed_task_id);
        } elseif($division === 'futures') {
            $result_array[] = $this->db_manager->get('Task')->setTaskFuture($changed_task_id);
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
                "task_text"     => nl2br($task['task_text']),
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

    public function task_updateAction()
    {
      $post     = $this->request->getPost();
      $id     = $post['id'];
      $task_name = $post['task_name'];
      $res = $this->db_manager->get('Task')->updateTask($id, $task_name);
      if($res) {
          $res = array(
              "error"         => "false",
              "task_name"       => $task_name,
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

    public function time_updateAction(){
        $post     = $this->request->getPost();
        $number = $post['number'];
        $id     = $post['id'];
        $res = $this->db_manager->get('Task')->updateTime($id, $number);
        if($res) {
            $res = array(
                "error"         => "false",
                "number"       => $number,
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

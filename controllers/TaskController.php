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

        if (isset($post['category_id']) && $post['category_id']) {
            $category = $this->db_manager->get('Category')->fetchById($post['category_id']);
            if(!$category) {
                $this->forward404('そんなカテゴリーねえよｗｗ');
            }
            $this->db_manager->get('TaskCategory')->insert($last_insert_id, $post['category_id']);
        }

            return $this->redirect('/');
    }

    public function add_taskAction() {

        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $user     = $this->session->get('user');
        $posts     = $this->request->getPost();
        // var_dump($post);exit;
        foreach($posts['task_name'] as $key => $task_name) {
            $this->_add($user['user_id'], array(
                'task_name'=>$task_name,
                'task_limit'=>$posts['task_limit'][$key]
            ));
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

}

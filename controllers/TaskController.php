<?php

/**
 * AccountController.
 *
 * @author 8705
 */
class TaskController extends AppController
{
    protected $auth_actions = array('index', 'add', 'delete');

    public function addAction()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $user = $this->session->get('user');
        if(!$user) {
            $this->forward404();
        }

        $post    = $this->request->getPost();
        $project = $this->db_manager->get('Project')->fetchById($post['project_id']);

        if(!$project) {
            $this->forward404('そんなプロジェクトありません');
        }

        $errors = $this->db_manager->get('Task')->validateAdd($post);

        if (count($errors) === 0) {
            $this->db_manager->get('Task')->insert($post);

            return $this->redirect('/');
        }
    }

    public function indexAction()
    {
        $user = $this->session->get('user');

        if(!$user) {
            $this->forward404();
        }

        $tasks      = $this->db_manager->get('Task')->fetchAllByUserId($user['user_id']);
        $projects   = $this->db_manager->get('Project')->fetchAllByUserId($user['user_id']);
        return $this->render(array('user'       => $user,
                                   'tasks'      => $tasks,
                                   'projects'   => $projects
                             ));
    }

    public function deleteAction($params)
    {
        $task_id = $params['property'];
        $task = $this->db_manager->get('Task')->fetchById($task_id);
        if(!$task || $task['task_del_flg'] === '1') {
            $this->forward404('そのタスクはないです');
        }

        $this->db_manager->get('Task')->delete($task['task_id']);

        return $this->redirect('/');

    }

}

<?php

/**
 * AccountController.
 *
 * @author 8705
 */
class ProjectController extends AppController
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

        $post = $this->request->getPost();

        $errors = $this->db_manager->get('Project')->validateAdd($post);

        if (count($errors) === 0) {
            $this->db_manager->get('Project')->insert($user['user_id'],
                                                      $post
                                                      );

            return $this->redirect('/');
        }
    }

    public function indexAction()
    {
        $user = $this->session->get('user');

        if(!$user) {
            $this->forward404();
        }

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            foreach ($post as $task_id => $task_is_done) {
                $this->db_manager->get('Task')->updateIsDone($task_id, $task_is_done);
            }
        }

        $tasks      = $this->db_manager->get('Task')->fetchAllAndProjectNameByUserId($user['user_id']);
        $projects   = $this->db_manager->get('Project')->fetchAllByUserId($user['user_id']);

        return $this->render(array('user'      => $user,
                                   'tasks'     => $tasks,
                                   'projects'  => $projects,
                            ));
    }

    public function deleteAction($params)
    {
        $project_id = $params['property'];
        $project = $this->db_manager->get('Project')->fetchById($project_id);

        if(!$project || $project['project_del_flg'] === '1') {
            $this->forward404('そのタスクはないです');
        }

        $this->db_manager->get('Project')->delete($project['project_id']);

        return $this->redirect('/');

    }

    public function viewAction($params)
    {

        if (!$this->session->isAuthenticated()) {
            return $this->redirect('/account/index');
        }

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            foreach ($post as $task_id => $task_is_done) {
                $this->db_manager->get('Task')->updateIsDone($task_id, $task_is_done);
            }
        }

        $project_id = $params['property'];
        $user = $this->session->get('user');

        $project_name   = $this->db_manager->get('Project')->fetchNameById($project_id);
        $tasks          = $this->db_manager->get('Task')->fetchAllByProjectId($project_id);
        $projects       = $this->db_manager->get('Project')->fetchAllByUserId($user['user_id']);

        return $this->render(array('user'          => $user,
                                   'project_id'    => $project_id,
                                   'project_name'  => $project_name,
                                   'tasks'         => $tasks,
                                   'projects'      => $projects,
                             ));
    }

}

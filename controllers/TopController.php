<?php

/**
 * AccountController.
 *
 * @author 8705
 */
class TopController extends AppController
{
    protected $auth_actions = array('index');

    public function indexAction()
    {
        $user = $this->session->get('user');

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            foreach ($post as $task_id => $task_is_done) {
                $this->db_manager->get('Task')->updateIsDone($task_id, $task_is_done);
            }
        }

        $tasks      = $this->db_manager->get('Task')->fetchTopIndex($user['user_id']);
        $categories = $this->db_manager->get('Category')->fetchTopIndex($user['user_id']);
        $projects   = $this->db_manager->get('Project')->fetchTopIndex($user['user_id']);

        return $this->render(array('user'       => $user,
                                   'tasks'      => $tasks,
                                   'categories' => $categories,
                                   'projects'   => $projects,
                            ));
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

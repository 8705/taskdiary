<?php

class CategoryController extends AppController
{
    protected $auth_actions = array('add', 'delete');

    public function addAction()
    {

        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $user = $this->session->get('user');
        $post = $this->request->getPost();

        $errors = $this->db_manager->get('Category')->validateAdd($post);

        if (count($errors) === 0) {
            $this->db_manager->get('Category')->insert($user['user_id'],
                                                      $post
                                                      );

            return $this->redirect('/');
        }
    }

    public function indexAction()
    {
        $user = $this->session->get('user');

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            foreach ($post as $task_id => $task_is_done) {
                $this->db_manager->get('Task')->updateIsDone($task_id, $task_is_done);
            }
        }

        // $tasks    = $this->db_manager->get('Task')->fetchAllAndcategoryNameByUserId($user['user_id']);
        // $categorys = $this->db_manager->get('Category')->fetchAllByUserId($user['user_id']);

        return $this->render(array('user'      => $user,
                                   'tasks'     => $tasks,
                                   'categorys'  => $categorys,
                            ));
    }

    public function deleteAction($params)
    {
        $category_id = $params['property'];
        $category = $this->db_manager->get('Category')->fetchById($category_id);

        if(!$category || $category['category_del_flg'] === '1') {
            $this->forward404('そのタスクはないです');
        }

        $this->db_manager->get('Category')->delete($category['category_id']);

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

        $category_id = $params['property'];
        $user = $this->session->get('user');

        $category_name   = $this->db_manager->get('Category')->fetchNameById($category_id);
        $tasks          = $this->db_manager->get('Task')->fetchAllBycategoryId($category_id);
        $categorys       = $this->db_manager->get('Category')->fetchAllByUserId($user['user_id']);

        return $this->render(array('user'          => $user,
                                   'category_id'    => $category_id,
                                   'category_name'  => $category_name,
                                   'tasks'         => $tasks,
                                   'categorys'      => $categorys,
                             ));
    }

}

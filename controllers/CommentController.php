<?php

class CommentController extends AppController
{
    protected $auth_actions = array('add', 'delete');

    public function addAction()
    {
        $task_id = $params['property'];
        $task = $this->db_manager->get('Task')->fetchById($task_id);
        if(!$task) {
            $this->forward404('そんなタスクはないです');
        }

        $user = $this->session->get('user');
        $post = $this->request->getPost();

        $errors = $this->db_manager->get('Comment')->validateAdd($post);

        if (count($errors) === 0) {
            $this->db_manager->get('Comment')->insert($user['user_id'], $task_id, $post);

            return $this->redirect('/');
        }
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
}

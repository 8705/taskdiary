<?php

/**
 * AccountController.
 *
 * @author 8705
 */
class TopController extends AppController
{
    protected $auth_actions = array('index');
    protected $layout = 'top';

    public function indexAction()
    {
        $tasks      = $this->db_manager->get('Task')->fetchTodays$this->login_user['user_id']);
        $categories = $this->db_manager->get('Category')->fetchSideColum($this->login_user['user_id']);

        $now   = new DateTime();

        return $this->render(array('user'       => $this->login_user,
                                   'tasks'      => $tasks,
                                   'categories' => $categories,
                                   'year'       => $now->format('Y'),
                                   'month'      => $now->format('m')
                            ));
    }

    public function pastAction()
    {
        $get = $this->request->getGet();

        $year  = date('Y', strtotime($get['nav']. ' month'));
        $month = date('m', strtotime($get['nav']. ' month'));

        $tasks      = $this->db_manager->get('Task')->fetchTopList($this->login_user['user_id'],
                                                                   $year,
                                                                   $month
                                                                   );
        $categories = $this->db_manager->get('Category')->fetchSideColum($this->login_user['user_id']);

        return $this->render(array('user'       => $this->login_user,
                                   'year'       => $year,
                                   'month'      => $month,
                                   'prev'       => $get['nav'] - 1,
                                   'next'       => $get['nav'] + 1,
                                   'tasks'      => $tasks,
                                   'categories' => $categories,
                            ));
    }

    public function futureAction()
    {
        $tasks      = $this->db_manager->get('Task')->fetchTopFuture($this->login_user['user_id']);
        // $categories = $this->db_manager->get('Category')->fetchSideColum($this->login_user['user_id']);

        return $this->render(array('user'       => $this->login_user,
                                   'tasks'      => $tasks,
                                   // 'categories' => $categories,
                            ));
    }

}

<?php

/**
 * UserRepository.
 *
 * @author 8705
 */
class TaskRepository extends DbRepository
{
    public function fetchAllByUserId($user_id)
    {
        $sql = "SELECT t.*
                    FROM tasks t
                        LEFT JOIN projects p ON p.project_id = t.project_id
                        LEFT JOIN users u ON p.user_id = u.user_id
                    WHERE p.user_id = ? and t.task_del_flg = '0'
                    ORDER BY t.task_created DESC";

        return $this->fetchAll($sql, array($user_id));
    }

    public function fetchAllAndProjectNameByUserId($user_id)
    {
        $sql = "SELECT t.*, p.project_name
                    FROM tasks t
                        LEFT JOIN projects p ON p.project_id = t.project_id
                        LEFT JOIN users u ON p.user_id = u.user_id
                    WHERE p.user_id = ? and t.task_del_flg = '0'
                    ORDER BY t.task_created DESC";

        return $this->fetchAll($sql, array($user_id));
    }

    public function fetchAllByProjectId($project_id) {
        $sql = "SELECT *
                    FROM tasks
                    WHERE project_id = ? and task_del_flg = '0'
                    ORDER BY task_created DESC
                ";

        return $this->fetchAll($sql, array($project_id));
    }

    public function insert($post)
    {
        $now = new DateTime();

        $sql = "INSERT INTO tasks (project_id,
                                   task_name,
                                   task_text,
                                   task_size,
                                   task_created,
                                   task_modified
                                   )
                    VALUES(?,?,?,?,?,?)";

        $stmt = $this->execute($sql, array(
            $post['project_id'],
            $post['task_name'],
            $post['task_text'],
            $post['task_size'],
            $now->format('Y-m-d H:i:s'),
            $now->format('Y-m-d H:i:s'),
        ));
    }


    public function fetchById($task_id)
    {
        $sql = "SELECT * FROM tasks WHERE task_id = ?";

        return $this->fetch($sql, array($task_id));
    }

    public function delete($task_id) {
        $now = new DateTime();
        $sql = "UPDATE tasks SET task_del_flg = '1', task_modified = ? WHERE task_id = ?";

        $stmt = $this->execute($sql, array(
            $now->format('Y-m-d H:i:s'),
            $task_id,
        ));
    }

    public function updateIsDone($task_id, $task_is_done)
    {
        $now = new DateTime();
        $sql = "UPDATE tasks SET task_is_done = ?, task_modified = ? WHERE task_id = ?";

        $stmt = $this->execute($sql, array(
            $task_is_done,
            $now->format('Y-m-d H:i:s'),
            $task_id,
        ));
    }

    public function validateAdd($post)
    {
        $task_name  = $post['task_name'];
        $task_size  = $post['task_size'];

        $errors = array();

        if(!strlen($task_name)) {
            $errors[] = 'タスク名を入力してね。';
        }

        if(!$task_size) {
            $errors[] = 'タスクの大きさを選んでね。';
        } else if (!(1 <= $task_size && $task_size <= 5)) {
            $errors[] = '不正な数値です。';
        }

        return $errors;
    }
}

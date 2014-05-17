<?php

class TaskRepository extends DbRepository
{
    public function fetchTopIndex($user_id)
    {
        $now   = new DateTime();
        $today = $now->format('Y-m-d');

        $sql = "SELECT t.task_id,
                       t.task_name,
                       t.task_is_done,
                       t.task_limit,
                       t.task_finish,
                       t.task_created,
                       tc.category_id,
                       c.category_name
                    FROM tasks t
                        LEFT JOIN tasks_categories tc ON tc.task_id = t.task_id
                        LEFT JOIN categories c ON c.category_id = tc.category_id
                    WHERE t.user_id = ?
                        AND (DATE_FORMAT(t.task_limit,'%Y-%m-%d') = ?
                        OR ((DATE_FORMAT(t.task_limit,'%Y-%m-%d') < ?)
                            AND t.task_is_done = 0
                            )
                        OR DATE_FORMAT(t.task_finish,'%Y-%m-%d') = ?)
                    ORDER BY t.task_limit DESC";

        return $this->fetchAll($sql, array($user_id, $today, $today, $today));
    }

    public function fetchById($task_id)
    {
        $sql = "SELECT COUNT(task_id) as count
                    FROM tasks t
                    WHERE task_id = ?
                ";

        $row = $this->fetch($sql, array($task_id));
        return $row['count'];
    }

    public function fetchIsDoneById($task_id) {
        $sql = "SELECT task_is_done
                    FROM tasks
                    WHERE task_id = ?
                ";
        return $this->fetch($sql, array($task_id));
    }

    public function insert($user_id, $post)
    {
        $now = new DateTime();

        $sql = "INSERT INTO tasks (user_id,
                                   task_name,
                                   task_limit,
                                   task_created,
                                   task_modified
                                   )
                    VALUES(?,?,?,?,?)";

        $stmt = $this->execute($sql, array(
            $user_id,
            $post['task_name'],
            $post['task_limit'],
            $now->format('Y-m-d H:i:s'),
            $now->format('Y-m-d H:i:s'),
        ));

        return $this->lastInsertId();
    }

    public function delete($task_id)
    {
        $sql = "DELETE FROM tasks WHERE task_id = ?";

        $stmt = $this->execute($sql, array($task_id));
    }

    public function updateIsDone($task_id, $task_is_done)
    {
        $now = new DateTime();
        $today = $now->format('Y-m-d H:i:s');

        $sql = "UPDATE tasks
                    SET task_is_done = ?,
                        task_finish = ?,
                        task_modified = ?
                    WHERE task_id = ?";

        $stmt = $this->execute($sql, array(
            $task_is_done,
            $today,
            $today,
            $task_id,
        ));
    }
    public function toggleIsDoneById($task_id) {
        $row = $this->fetchIsDoneById($task_id);
        $task_is_done = $row['task_is_done'];
        if($task_is_done === '1') {
            $toggled_is_done = '0';
        } else {
            $toggled_is_done = '1';
        }

        $now = new DateTime();
        $today = $now->format('Y-m-d H:i:s');

        $sql = "UPDATE tasks
                    SET task_is_done = ?,
                        task_finish = ?,
                        task_modified = ?
                    WHERE task_id = ?";
        $stmt = $this->execute($sql, array(
            $toggled_is_done,
            $today,
            $today,
            $task_id
        ));

        if($stmt) {
            return $toggled_is_done;
        }
        return false;
    }

    public function validateAdd($post)
    {
        $task_name  = $post['task_name'];

        $errors = array();

        if(!strlen($task_name)) {
            $errors[] = 'タスク名を入力してね。';
        }

        return $errors;
    }
}

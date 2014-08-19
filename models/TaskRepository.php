<?php

class TaskRepository extends DbRepository
{
    public function fetchTodays($user_id)
    {
        $now   = new DateTime();
        $today = $now->format('Y-m-d');

        $sql = "SELECT t.task_id,
                       t.task_name,
                       t.task_is_done,
                       t.task_text,
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
                    ORDER BY t.task_is_done ASC,t.task_sequence ASC";

        return $this->fetchAll($sql, array($user_id, $today, $today, $today));
    }

    public function fetchTopList($user_id, $year, $month)
    {
        $yyyymm = $year.'-'.$month;

        $sql = "SELECT t.task_name,
                       t.task_finish,
                       c.category_name
                    FROM tasks t
                        LEFT JOIN tasks_categories tc ON tc.task_id = t.task_id
                        LEFT JOIN categories c ON c.category_id = tc.category_id
                    WHERE t.user_id = ? AND DATE_FORMAT(t.task_finish,'%Y-%m') = ? AND t.task_is_done = 1
                    ORDER BY t.task_finish ASC";

        return $this->fetchAll($sql, array($user_id, $yyyymm));
    }

    public function fetchTopFuture($user_id)
    {
        $sql = "SELECT t.task_name,
                       t.task_limit,
                       c.category_name
                    FROM tasks t
                        LEFT JOIN tasks_categories tc ON tc.task_id = t.task_id
                        LEFT JOIN categories c ON c.category_id = tc.category_id
                    WHERE t.user_id = ? AND t.task_is_done = 0
                    ORDER BY t.task_limit ASC";

        return $this->fetchAll($sql, array($user_id));
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
        $now = new DateTime();
        $today = $now->format('Y-m-d H:i:s');
        $row = $this->fetchIsDoneById($task_id);
        $task_is_done = $row['task_is_done'];
        if($task_is_done === '1') {
            $toggled_is_done = '0';
            $finish_date = NULL;
        } else {
            $toggled_is_done = '1';
            $finish_date = $today;
        }



        $sql = "UPDATE tasks
                    SET task_is_done = ?,
                        task_finish = ?,
                        task_modified = ?
                    WHERE task_id = ?";
        $stmt = $this->execute($sql, array(
            $toggled_is_done,
            $finish_date,
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

    public function updateSequence($sequence, $task_id) {
        $sql = "UPDATE tasks
                    SET task_sequence = ?
                    WHERE task_id = ?
                ";
        $stmt = $this->execute($sql, array(
            $sequence,
            $task_id
        ));
        return $stmt;
    }

    public function updateComment($task_id, $task_text) {
        $sql = "UPDATE tasks
                    SET task_text = ?
                    WHERE task_id = ?
                ";
        $stmt = $this->execute($sql, array(
            $task_text,
            $task_id
        ));
        return $stmt;
    }

    public function fetchComment($task_id) {
        $sql = "SELECT task_id, task_text
                FROM tasks
                WHERE task_id = ?";

        return $this->fetch($sql, array($task_id));
    }

    public function fetchFinishTasks($user_id) {
        $sql = "SELECT count(task_id) as count
                FROM tasks
                WHERE user_id = ?
                AND task_is_done = 1";

        return $this->fetch($sql, array($user_id));
    }
}

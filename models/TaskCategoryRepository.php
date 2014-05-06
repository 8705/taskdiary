<?php

class TaskCategoryRepository extends DbRepository
{
    public function insert($task_id, $category_id)
    {
        $sql = "INSERT INTO tasks_categories (task_id, category_id) VALUES (?, ?)";

        $stmt = $this->execute($sql, array($task_id, $category_id));
    }

    public function deleteByTaskId($task_id)
    {
        $sql = "DELETE FROM tasks_categories WHERE task_id = ?";

        $stmt = $this->execute($sql, array($task_id));
    }
}

<?php

/**
 * AccountController.
 *
 * @author 8705
 */
class CompleteBehavior extends DbRepository
{
    public function CalculateSumSizeOfAllTasks($project_id)
    {
        $sql = "SELECT SUM(task_size) as sum
                    FROM tasks
                    WHERE project_id = ? and task_del_flg = '0'
                ";

        $row = $this->fetch($sql, array($project_id));
        return $row['sum'];

    }

    public function CalculateSumSizeOfDoneTasks($project_id)
    {
        $sql = "SELECT SUM(task_size) as sum
                    FROM tasks
                    WHERE project_id = ? and task_is_done = '1' and task_del_flg = '0'
                ";

        $row = $this->fetch($sql, array($project_id));
        return $row['sum'];
    }

    public function CalculateAchievement($project_id)
    {
        $total_size = $this->CalculateSumSizeOfAllTasks($project_id);
        $done_size  = $this->CalculateSumSizeOfDoneTasks($project_id);
        if ($total_size == 0) {
            $achievement = 0;
        } else {
            $achievement = $done_size/$total_size;
        }

        return $achievement;
    }
}
<?php

/**
 * WorkTimeRepository.
 *
 * @author 8705
 */
class WorkTimeRepository extends DbRepository
{
    static private $user_data;

    public function fetchById($user_id)
    {
        if ( !is_null(self::$user_data) ) {
            return self::$user_data;
        }
        $sql = "SELECT * FROM work_time WHERE user_id = ?";

        return self::$user_data = $this->fetch($sql, array($user_id));

    }

    public function fetchWorkTimeMin($user_id)
    {
        $data = $this->fetchById($user_id);

        $binding_hour = (strtotime($data['end_time']) - strtotime($data['start_time'])) / 60;
        $resting = (strtotime($data['break_end']) - strtotime($data['break_start'])) / 60;
        return $binding_hour - $resting;
    }

    public function fetchWorkTimeInfo($user_id)
    {
        return $this->fetchById($user_id);
    }
}
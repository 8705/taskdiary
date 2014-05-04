<?php

/**
 * UserRepository.
 *
 * @author 8705
 */
class ProjectRepository extends DbRepository
{
    public function insert($user_id, $post)
    {
        $now = new DateTime();

        $sql = "INSERT INTO projects(user_id,
                                     project_name,
                                     project_text,
                                     project_created
                                     )
                    VALUES(?, ?, ?, ?)
        ";

        $stmt = $this->execute($sql, array($user_id,
                                           $post['project_name'],
                                           $post['project_text'],
                                           $now->format('Y-m-d H:i:s'),
        ));
    }

    public function fetchAllByUserId($user_id) {
        $sql = "SELECT *
                    FROM projects
                    WHERE user_id = ? AND project_del_flg = '0'
                    ORDER BY project_created DESC";

        return $this->fetchAll($sql, array($user_id));
    }


    public function fetchById($project_id)
    {
        $sql = "SELECT * FROM projects WHERE project_id = ?";

        return $this->fetch($sql, array($project_id));
    }

    public function fetchNameById($project_id)
    {
        $sql = "SELECT project_name FROM projects WHERE project_id = ?";
        $res = $this->fetch($sql, array($project_id));
        return $res['project_name'];
    }

    public function delete($project_id)
    {
        $now = new DateTime();
        $sql = "UPDATE projects
                    SET project_del_flg = '1', project_modified = ?
                    WHERE project_id = ?";

        $stmt = $this->execute($sql, array($now->format('Y-m-d H:i:s'),
                                           $project_id,
                               ));
    }

    public function validateAdd($post)
    {
        $errors = array();

        if(!strlen($post['project_name'])) {
            $errors[] = 'プロジェクト名を入力してね';
        }

        return $errors;
    }
}

<?php

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

    public function fetchTopIndex($user_id) {
        $sql = "SELECT p.project_id,
                       p.project_name
                    FROM projects p
                        LEFT JOIN users_projects up ON p.project_id = up.project_id
                    WHERE up.user_id = ?
                ";

        return $this->fetchAll($sql, array($user_id));
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

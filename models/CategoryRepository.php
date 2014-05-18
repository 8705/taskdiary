<?php

class CategoryRepository extends DbRepository
{
    public function insert($user_id, $post)
    {
        $now = new DateTime();

        $sql = "INSERT INTO categories(user_id,
                                     category_name,
                                     category_created
                                    ) VALUES (?, ?, ?)
                ";

        $stmt = $this->execute($sql, array($user_id,
                                           $post['category_name'],
                                           $now->format('Y-m-d H:i:s'),
        ));
    }

    public function fetchSideColum($user_id)
    {
        $sql = "SELECT category_id,
                       category_name
                    FROM categories
                    WHERE user_id = ?
               ";

        return $this->fetchAll($sql, array($user_id));
    }

    public function fetchByID($category_id)
    {
        $sql = "SELECT COUNT(category_id) as count
                    FROM categories
                    WHERE category_id = ?
               ";
        $row = $this->fetch($sql, array($category_id));
        return $row['count'];
    }

    public function validateAdd($post)
    {
        $errors = array();

        if(!strlen($post['category_name'])) {
            $errors[] = 'カテゴリー名を入力してね';
        }

        return $errors;
    }
}

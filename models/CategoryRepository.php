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
                        AND category_del_flg = 0
               ";

        return $this->fetchAll($sql, array($user_id));
    }

    public function fetchDelFlgByID($category_id)
    {
        $sql = "SELECT category_del_flg
                    FROM categories
                    WHERE category_id = ?
               ";
        return $this->fetch($sql, array($category_id));
    }

    public function delete($category_id) {
        $sql = "UPDATE categories
                    SET category_del_flg = 1
                    WHERE category_id = ?
               ";
        return $this->execute($sql, array($category_id));
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

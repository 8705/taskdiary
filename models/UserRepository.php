<?php

/**
 * UserRepository.
 *
 * @author 8705
 */
class UserRepository extends DbRepository
{
    public function insert($post)
    {
        $post['user_password'] = $this->hashPassword($post['user_password']);
        $now = new DateTime();

        $sql = "INSERT INTO users (
                    user_name,
                    user_mail,
                    user_password,
                    user_created,
                    user_modified
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )";

        $stmt = $this->execute(
                    $sql,
                    array($post['user_name'],
                          $post['user_mail'],
                          $post['user_password'],
                          $now->format('Y-m-d H:i:s'),
                          $now->format('Y-m-d H:i:s'))
                );
    }

    public function validateRegister($post)
    {
        $errors = array();

        if (!strlen($post['user_name'])) {
            $errors[] = 'ユーザIDを入力してください';
        } else if (!preg_match('/^\w{3,20}$/', $post['user_name'])) {
            $errors[] = 'ユーザIDは半角英数字およびアンダースコアを3 ～ 20 文字以内で入力してください';
        } else if (!$this->isUniqueName($post['user_name'])) {
            $errors[] = 'ユーザIDは既に使用されています';
        }

        if (!strlen($post['user_password'])) {
            $errors[] = 'パスワードを入力してください';
        } else if (!(4 <= strlen($post['user_password']) && strlen($post['user_password']) <= 30)) {
            $errors[] = 'パスワードは4 ～ 30 文字以内で入力してください';
        }

        return $errors;
    }

    public function validateLogIn($post)
    {
        $errors = array();

        if (!strlen($post['user_name'])) {
            $errors[] = 'ユーザIDを入力してください';
        }

        if (!strlen($post['user_password'])) {
            $errors[] = 'パスワードを入力してください';
        }

        return $errors;
    }

    public function hashPassword($password)
    {
        return sha1($password . 'SecretKey');
    }

    public function fetchByName($user_name)
    {
        $sql = "SELECT * FROM users WHERE user_name = ?";

        return $this->fetch($sql, array($user_name));
    }

    public function isUniqueName($user_name)
    {
        $sql = "SELECT COUNT(user_id) as count FROM users WHERE user_name = ?";

        $row = $this->fetch($sql, array($user_name));
        if ($row['count'] === '0') {
            return true;
        }

        return false;
    }

}

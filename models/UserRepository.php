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

    public function insertByTwitter($post)
    {
        if(!$post['user_mail']) {
            $post['user_mail'] = null;
        }
        $now = new DateTime();

        $sql = "INSERT INTO users (
                    user_name,
                    user_mail,
                    user_created,
                    user_modified
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?
                )";

        $stmt = $this->execute(
                    $sql,
                    array($post['user_name'],
                          $post['user_mail'],
                          $now->format('Y-m-d H:i:s'),
                          $now->format('Y-m-d H:i:s'))
                );
    }

    public function validateRegister($post)
    {
        $errors = array();

        if (!strlen($post['user_name'])) {
            $errors['user_name'][] = 'ユーザIDを入力してください';
        } else if (!preg_match('/^\w{3,20}$/', $post['user_name'])) {
            $errors['user_name'][] = 'ユーザIDは半角英数字およびアンダースコアを3 ～ 20 文字以内で入力してください';
        } else if (!$this->isUniqueName($post['user_name'])) {
            $errors['user_name'][] = 'ユーザIDは既に使用されています';
        }

        if(strlen($post['user_mail'])) {
            if(strlen($post['user_mail']) !== mb_strlen($post['user_mail'])) {
                $errors['user_mail'][] = 'メールアドレスは半角英数字およびアンダースコアのみで入力して下さい';
            } else if(!preg_match('/@/', $post['user_mail'])) {
                $errors['user_mail'][] = 'メールアドレスを正しく入力して下さい';
            } else if(!$this->isUniqueMail($post['user_mail'])) {
                $errors['user_mail'][] = 'メールアドレスは既に使用されています';
            }
        }

        if (!strlen($post['user_password'])) {
            $errors['user_password'][] = 'パスワードを入力してください';
        } else if (!(4 <= strlen($post['user_password']) && strlen($post['user_password']) <= 30)) {
            $errors['user_password'][] = 'パスワードは4 ～ 30 文字以内で入力してください';
        }

        return $errors;
    }

    public function validateTwitterRegister($post)
    {
        $errors = array();

        if (!strlen($post['user_name'])) {
            $errors['user_name'][] = 'ユーザIDを入力してください';
        } else if (!preg_match('/^\w{3,20}$/', $post['user_name'])) {
            $errors['user_name'][] = 'ユーザIDは半角英数字およびアンダースコアを3 ～ 20 文字以内で入力してください';
        } else if (!$this->isUniqueName($post['user_name'])) {
            $errors['user_name'][] = 'ユーザIDは既に使用されています';
        }

        if(strlen($post['user_mail'])) {
            if(strlen($post['user_mail']) !== mb_strlen($post['user_mail'])) {
                $errors['user_mail'][] = 'メールアドレスは半角英数字およびアンダースコアのみで入力して下さい';
            }
            if(!$this->isUniqueMail($post['user_mail'])) {
                $errors['user_mail'][] = 'メールアドレスは既に使用されています';
            }
        }


        return $errors;
    }

    public function validateLogIn($post)
    {
        $errors = array();

        if (!strlen($post['user_name'])) {
            $errors['user_name'][] = 'ユーザIDを入力してください';
        } else if (!preg_match('/^\w{3,20}$/', $post['user_name'])) {
            $errors['user_name'][] = 'ユーザIDは半角英数字およびアンダースコアを3 ～ 20 文字以内で入力してください';
        }

        if (!strlen($post['user_password'])) {
            $errors['user_password'][] = 'パスワードを入力してください';
        } else if (!(4 <= strlen($post['user_password']) && strlen($post['user_password']) <= 30)) {
            $errors['user_password'][] = 'パスワードは4 ～ 30 文字以内で入力してください';
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

    public function fetchById($user_id)
    {
        $sql = "SELECT * FROM users WHERE user_id = ?";

        return $this->fetch($sql, array($user_id));
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

    public function isUniqueMail($user_mail)
    {
        $sql = "SELECT COUNT(user_id) as count FROM users WHERE user_mail = ?";

        $row = $this->fetch($sql, array($user_mail));
        if ($row['count'] === '0') {
            return true;
        }

        return false;
    }

    public function doneActivateById($user_id)
    {
        $user = $this->fetchById($user_id);
        if($user['authority_id'] !== "3") {
            return false;
        }

        $sql = "UPDATE users SET authority_id = 2 WHERE user_id = ?";
        $stmt = $this->execute(
                    $sql,
                    array($user_id)
                );
        return $stmt;
    }

    public function updateImage($image)
    {

    }
}

<?php

/**
 * UserRepository.
 *
 * @author 8705
 */
class TwitterOauthRepository extends DbRepository
{
    public function insert($user_id, $twitter_id, $oauth_token, $oauth_token_secret )
    {
        $sql = "INSERT INTO twitter_oauth (
                user_id,
                twitter_id,
                tw_oauth_token,
                tw_oauth_token_secret,
            )
            VALUES (
                ?,
                ?,
                ?,
                ?
            )";
        $stmt = $this->execute(
                    $sql,
                    array(
                        $user_id,
                        $twitter_id,
                        $oauth_token,
                        $oauth_token_secret
                    )
                );
    }

    public function updateAccessToken($twitter_id, $oauth_token, $oauth_token_secret)
    {
        $sql = "UPDATE twitter_oauth
                SET
                    tw_oauth_token = ?,
                    tw_oauth_token_secret = ?
                WHERE
                    twitter_id = ?";
        $stmt = $this->execute(
                    $sql,
                    array($oauth_token, $oauth_token_secret,$twitter_id)
                );
        return $stmt;
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

        return $errors;
    }

    public function isRegistration($twitter_id)
    {
        $sql = "SELECT COUNT(twitter_id) as count FROM twitter_0auth WHERE twitter_id = ?";

        $row = $this->fetch($sql, array($twitter_id));
        if ($row['count'] === '0') {
            return false;
        }

        return true;
    }
}

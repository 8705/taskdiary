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
                tw_oauth_token_secret
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

    public function fetchByTwitterId($twitter_id)
    {
        $sql = "SELECT
                    t.user_id,
                    t.twitter_id,
                    t.tw_oauth_token,
                    t.tw_oauth_token_secret
                FROM twitter_oauth as t
                WHERE twitter_id = ?";

        return = $this->fetch($sql, array($twitter_id));
    }
}

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
            )"
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
}

<?php

/**
 * AutologinRepository.
 *
 * @author 8705
 */
class AutologinRepository extends DbRepository
{
    public function insert($user_id, $token, $expires)
    {
        $sql        = "INSERT INTO autologin (
                            user_id,
                            autologin_token,
                            expires
                        )
                        VALUES
                        (
                            ?,
                            ?,
                            ?
                        )";
        $stmt = $this->execute(
                $sql,
                array($user_id,
                      $token,
                      $expires,
        ));
    }

    public function setAuthToken($user_id, $days = '7')
    {
        $timeout = $days * 24 * 60 * 60; //１週間
        $expires = time() + $timeout;
        $count = 0;
        do {
            $token = sha1($user_id . microtime() . 'sldkfjsdfsjiu097yih^asfsjs_ohas');
            $isUnique = $this->isUniqeToken($token);
            if($isUnique) {
                $this->insert((int)$user_id, $token, $expires);
            }
        } while($isUnique === false && ++$count < 10);
        setcookie('token', $token, $expires, '/');  //第４引数のパスをルートにしとかないと、このスクリプトが実行したページのみ有効になる
    }

    public function isUniqeToken($token)
    {
        $sql = "SELECT COUNT(user_id) as count FROM autologin WHERE autologin_token = ?";

        $row = $this->fetch($sql, array($token));
        if ($row['count'] === '0') {
            return true;
        }

        return false;
    }

    public function checkAuthToken($token)
    {
        $sql = "SELECT a.user_id, a.autologin_token, a.expires
                    FROM autologin as a
                    WHERE autologin_token = ?";

        $row = $this->fetch($sql, array($token));

        //該当なし、不正トークンの可能性
        if(!$row) {
            return false;
        }

        //トークン有効期限切れ
        if($row['expires'] < time()) {
            $this->deleteByUserId($row['user_id']);

            return false;
        }

        return $row['user_id'];
    }

    public function deleteByUserId($user_id)
    {
        $sql = "DELETE FROM autologin
                WHERE user_id = ?";
        $stmt = $this->execute($sql, array($user_id));
    }

    public function deleteByToken($autologin_token)
    {
        $sql = "DELETE FROM autologin
                WHERE autologin_token = ?";
        $stmt = $this->execute($sql, array($autologin_token));
    }

    public function fetchByToken($token)
    {
        $sql = "SELECT a.user_id, a.activation_token FROM activation as a WHERE a.activation_token = ?";

        return $this->fetch($sql, array($user_name));
    }
    public function fetchByUserId($user_id)
    {
        $sql = "SELECT a.user_id, a.activation_token FROM activation as a WHERE a.user_id = ?";

        return $this->fetch($sql, array($user_id));
    }
}

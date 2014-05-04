<?php

/**
 * UserRepository.
 *
 * @author 8705
 */
class ActivationRepository extends DbRepository
{
    public function insert($user_id, $activation_token)
    {
        $sql = "INSERT INTO activation (
                    user_id,
                    activation_token
                )
                VALUES
                (
                    ?,
                    ?
                )";

        $stmt = $this->execute(
                    $sql,
                    array(
                        $user_id,
                        $activation_token
                    )
                );
    }

    public function fetchBytoken($token)
    {
        $sql = "SELECT a.user_id, a.activation_token FROM activation as a WHERE a.activation_token = ?";

        return $this->fetch($sql, array($user_name));
    }
    public function fetchByUserId($user_id)
    {
        $sql = "SELECT a.user_id, a.activation_token FROM activation as a WHERE a.user_id = ?";

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

}

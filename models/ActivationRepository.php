<?php

/**
 * ActivationRepository.
 *
 * @author 8705
 */
class ActivationRepository extends DbRepository
{
    public function insert($user_id, $activation_token)
    {
        if($this->fetchByUserId($user_id)) {
            $sql = "UPDATE activation
                        SET activation_token = :acrivation_token
                        WHERE user_id = :user_id";
        } else {
            $sql = "INSERT INTO activation (
                        user_id,
                        activation_token
                    )
                    VALUES
                    (
                        :user_id,
                        :activation_token
                    )";
        }

        $stmt = $this->execute(
                    $sql,
                    array(
                        ':user_id'           => $user_id,
                        ':activation_token'  => $activation_token
                    )
                );
    }
    public function delete($user_id)
    {
        $sql = "DELETE FROM activation WHERE user_id = ?";
        $stmt = $this->execute(
            $sql,
            array($user_id)
        );
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

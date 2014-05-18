<?php

/**
 * DbRepository.
 *
 * @author Katsuhiro Ogawa <fivestar@nequal.jp>
 */
abstract class DbRepository
{
    protected $con;

    /**
     * コンストラクタ
     *
     * @param PDO $con
     */
    public function __construct($con)
    {
        $this->setConnection($con);
    }

    /**
     * コネクションを設定
     *
     * @param PDO $con
     */
    public function setConnection($con)
    {
        $this->con = $con;
    }

    /**
     * クエリを実行
     *
     * @param string $sql
     * @param array $params
     * @return PDOStatement $stmt
     */
    public function execute($sql, $params = array())
    {
        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * クエリを実行し、結果を1行取得
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetch($sql, $params = array())
    {
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * クエリを実行し、結果をすべて取得
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAll($sql, $params = array())
    {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * トランザクション
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function begin()
    {
        if (!$this->db_manager->isBegin()) {
            $this->con->beginTransaction();
            $this->db_manager->setBegin(true);
        }
    }

    public function commit()
    {
        if ($this->db_manager->isBegin()) {
            $this->con->commit();
            $this->db_manager->setBegin(false);
        }
    }

    public function rollback()
    {
        if ($this->db_manager->isBegin()) {
            $this->con->rollback();
            $this->db_manager->setBegin(false);
        }
    }

    /**
     *直前のinsertしたレコードのオートインクリメントで振られたIDを取得
     *
     */
    public function lastInsertId()
    {
        return $this->con->lastInsertId();
    }
}

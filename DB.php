<?php

/**
 * Project: RenderPage
 * File:    DB.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is DB class
 */
class DB {

    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * Instance of PDO class
     *
     * @var object
     */
    private $dbh;

    /**
     * Is connected
     *
     * @var boolean
     */
    private $isConnected = false;

    /**
     * Connect to DB
     */
    private function connect() {
        $conf = include APP_DIR . '/conf/db.php';
        try {
            $this->dbh = new \PDO($conf['dsn'], $conf['username'], $conf['password'], [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$conf['charset']}"]);
            $this->isConnected = true;
        } catch (RenderPageException $e) {
            echo 'Unable to connect: ' . $e->getMessage();
        }
    }

    /**
     * Executes an SQL statement
     *
     * @param string $sql
     * @param array $inputParameters
     *
     * @return object returning a result set as a PDOStatement object
     */
    public function query(string $sql, array $inputParameters = []) {
        if (!$this->isConnected) {
            $this->connect();
        }

        $sth = $this->dbh->prepare($sql);
        $sth->execute($inputParameters);

        return $sth;
    }

    /**
     * Get array
     *
     * @param string $sql
     * @param array $inputParameters
     *
     * @return array
     */
    public function getArray(string $sql, array $inputParameters = []) {
        $sth = $this->query($sql, $inputParameters);
        return $sth->fetchAll();
    }

    /**
     * Get row
     *
     * @param string $sql
     * @param array $inputParameters
     *
     * @return array
     */
    public function getRow(string $sql, array $inputParameters = []) {
        $sth = $this->query($sql, $inputParameters);
        return $sth->fetch();
    }

    /**
     * Get one
     *
     * @param string $sql
     * @param array $inputParameters
     *
     * @return mixed
     */
    public function getOne(string $sql, array $inputParameters = []) {
        $row = $this->getRow($sql, $inputParameters);

        if (!$row) {
            return false;
        }

        return $row[0];
    }

    /**
     * Insert
     *
     * @param string $into table name
     * @param array $data data
     *
     * @return int insert id
     */
    public function insert(string $into, array $data): int {
        $sql = 'INSERT INTO `' . str_replace('.', '`.`', $into) . '` (`';
        $sql .= implode('`, `', array_keys($data)) . '`) VALUES (';
        $sql .= implode(', ', array_fill(0, count($data), '?')) . ')';
        $this->query($sql, array_values($data));
        return $this->dbh->lastInsertId();
    }

    /**
     * Truncate table
     *
     * @param string $table table name
     */
    public function truncate(string $table) {
        $sql = 'TRUNCATE TABLE `' . str_replace('.', '`.`', $table) . '`';
        $this->query($sql);
    }

}

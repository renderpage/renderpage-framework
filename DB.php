<?php

/*
 * The MIT License
 *
 * Copyright (c) 2015-2019 Sergey Pershin
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Project: RenderPage
 * File:    DB.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0-alpha
 */

namespace vendor\pershin\renderpage;

use PDO;

/**
 * This is DB class
 */
class DB {

    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * Database table prefix.
     *
     * @var string
     */
    public static $tablePrefix = '';

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

    private function __construct() {
        $conf = include APP_DIR . '/conf/db.php';
        if (isset($conf['tablePrefix'])) {
            self::$tablePrefix = $conf['tablePrefix'];
        }
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
     * Get one
     *
     * @param string $sql
     * @param array $inputParameters
     *
     * @return mixed
     */
    public function getOne(string $sql, array $inputParameters = []) {
        $row = $this->getRow($sql, $inputParameters);
        return $row ? $row[0] : false;
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
     * Truncate table
     *
     * @param string $table table name
     */
    public function truncate(string $table) {
        $sql = 'TRUNCATE TABLE `' . str_replace('.', '`.`', $table) . '`';
        $this->query($sql);
    }

    /**
     * Connect to DB
     */
    private function connect() {
        try {
            $conf = include APP_DIR . '/conf/db.php';
            $this->dbh = new PDO($conf['dsn'], $conf['username'], $conf['password'], [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$conf['charset']}"]);
            $this->isConnected = true;
        } catch (RenderPageException $e) {
            echo 'Unable to connect: ' . $e->getMessage();
        }
    }

}

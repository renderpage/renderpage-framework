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
class DB
{
    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * Instance of PDO class
     *
     * @var object
     */
    public $dbh;

    /**
     * Is connected
     *
     * @var boolean
     */
    public $isConnected = false;

    /**
     * Connect to DB
     */
    public function connect()
    {
        $conf = require_once APP_DIR . '/conf/db.php';

        try {
            $this->dbh = new \PDO($conf['dsn'], $conf['username'], $conf['password'], [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$conf['charset']}"]);
            $this->isConnected = true;
        } catch (RenderPageException $e) {
            // echo 'Unable to connect: ' . $e->getMessage();
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
    public function getArray($sql, $inputParameters = [])
    {
        if (!$this->isConnected) {
            $this->connect();
        }

        $sth = $this->dbh->prepare($sql);
        $sth->execute($inputParameters);

        $result = $sth->fetchAll();

        return $result;
    }

    /**
     * Get row
     *
     * @param string $sql
     * @param array $inputParameters
     *
     * @return array
     */
    public function getRow($sql, $inputParameters = [])
    {
        if (!$this->isConnected) {
            $this->connect();
        }

        $sth = $this->dbh->prepare($sql);
        $sth->execute($inputParameters);

        $result = $sth->fetch();

        return $result;
    }

    /**
     * Insert
     *
     * @param string $into table name
     * @param array $data data
     *
     * @return int insert id
     */
    public function insert($into, $data)
    {
        if (!$this->isConnected) {
            $this->connect();
        }

        $into = str_replace('.', '`.`', $into);
        $fields = implode('`, `', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO `{$into}` (`{$fields}`) VALUES ({$values});";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array_values($data));

        return $this->dbh->lastInsertId();
    }

    /**
     * Truncate table
     *
     * @param string $table table name
     */
    public function truncate($table)
    {
        $sql = "TRUNCATE TABLE `{$table}`";

        $sth = $this->dbh->prepare($sql);
        $sth->execute();
    }
}

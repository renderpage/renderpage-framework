<?php
/**
 * Project: RenderPage
 * File:    Model.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is Model class
 */
class Model
{
    /**
     * Instance of Language class
     *
     * @var object
     */
    public $language;

    /**
     * Instance of DB class
     *
     * @var object
     */
    public $db;

    /**
     * Init
     */
    public function __construct()
    {
        // Create instance of Language class
        $this->language = Language::getInstance();

        // Create instance of DB class
        $this->db = DB::getInstance();
    }

    /**
     * <<magic>> Getter.
     *
     * @param string $name property name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $method = "get{$name}";
        return $this->{$method}();
    }

    /**
     * <<magic>> Setter.
     *
     * @param string $name property name
     * @param mixed $value parameter passed to setter
     */
    public function __set($name, $value)
    {
        $method = "set{$name}";
        $this->{$method}($value);
    }
}

<?php
/**
 * Project: RenderPage
 * File:    Session.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is Session class
 */
class Session
{
    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * Init
     */
    public function start()
    {
        session_name('SESSID');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Get a variable from the session array.
     *
     * @param string $name variable name
     *
     * @return mixed
     */
    public function get($name)
    {
        $this->start();

        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return NULL;
    }

    /**
     * Set a variable in the session array.
     *
     * @param string $name variable name
     * @param mixed $value value
     *
     * @return void
     */
    public function set($name, $value)
    {
        $this->start();

        $_SESSION[$name] = $value;
    }

    /**
     * Unset a variable in the session array.
     *
     * @param string $name variable name
     *
     * @return void
     */
    public function del($name)
    {
        $this->start();

        unset($_SESSION[$name]);
    }
}

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
class Session {

    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * Session name
     */
    const DEFAULT_SESSION_NAME = 'SESSID';

    /**
     * Init
     */
    public function start() {
        if (file_exists(APP_DIR . '/conf/session.php')) {
            $conf = require APP_DIR . '/conf/session.php';
            if (isset($conf['name'])) {
                session_name($conf['name']);
            } else {
                session_name(self::DEFAULT_SESSION_NAME);
            }
            if (isset($conf['domain'])) {
                session_set_cookie_params(0, '/', $conf['domain']);
            }
        } else {
            session_name(self::DEFAULT_SESSION_NAME);
        }

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
    public function get(string $name) {
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
    public function set(string $name, $value) {
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
    public function del(string $name) {
        $this->start();

        unset($_SESSION[$name]);
    }

}

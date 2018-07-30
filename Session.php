<?php

/*
 * The MIT License
 *
 * Copyright 2018 Sergey Pershin <sergey dot pershin at hotmail dot com>.
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

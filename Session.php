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
     * Gets a the session variable value.
     *
     * @param string $key The session variable name.
     *
     * @return mixed The session variable value, or <b>FALSE</b> if the session variable does not exist.
     */
    public function get(string $key) {
        $value = false;
        $this->start();

        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
        }

        return $value;
    }

    /**
     * Removes a session variable.
     *
     * @param string $key The name of the session variable to be removed.
     *
     * @return void
     */
    public function remove(string $key) {
        $this->start();
        unset($_SESSION[$key]);
    }

    /**
     * Sets a session variable.
     *
     * @param string $key Session variable name.
     * @param mixed $value Session variable value.
     *
     * @return void
     */
    public function set(string $key, $value) {
        $this->start();
        $_SESSION[$key] = $value;
    }

    /**
     * Start new or resume existing session.
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

}

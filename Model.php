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
abstract class Model {

    /**
     * Instance of Language class
     *
     * @var \renderpage\libs\Language
     */
    public $language;

    /**
     * Instance of DB class
     *
     * @var \renderpage\libs\DB
     */
    public $db;

    /**
     * Init
     */
    public function __construct() {
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
    public function __get(string $name) {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
    }

    /**
     * <<magic>> Setter.
     *
     * @param string $name property name
     * @param mixed $value parameter passed to setter
     */
    public function __set(string $name, $value) {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }
    }

    /**
     * Allias for $this->language->_($category, $str)
     *
     * @param string $category
     * @param string $str
     *
     * @return string
     */
    public function _(string $category, string $str) {
        return $this->language->_($category, $str);
    }

}

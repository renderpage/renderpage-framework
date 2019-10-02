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
 * File:    Controller.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0-alpha
 */

namespace vendor\pershin\renderpage;

/**
 * This is Controller class.
 */
abstract class Controller {

    /**
     * Instance of Language class
     *
     * @var \renderpage\libs\Language
     */
    public $language;

    /**
     * Instance of Request class
     *
     * @var \renderpage\libs\Request
     */
    public $request;

    /**
     * Instance of Response class
     *
     * @var \renderpage\libs\Response
     */
    public $response;

    /**
     * Instance of View class
     *
     * @var \renderpage\libs\View
     */
    public $view;

    /**
     * Init
     */
    public function __construct() {
        // Create instance of Request class
        $this->request = Request::getInstance();

        // Create instance of Request class
        $this->response = Response::getInstance();

        // Create instance of Language class
        $this->language = Language::getInstance();

        // Create instance of View class
        $this->view = new View;
    }

    /**
     * Before action
     */
    public function before() {
        // none
    }

    /**
     * After action
     */
    public function after() {
        // none
    }

}

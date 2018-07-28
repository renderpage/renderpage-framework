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
 * File:    RenderPage.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0a
 */

namespace renderpage\libs;

/**
 * This is the main RenderPage class
 */
class RenderPage {

    /**
     * RenderPage version
     */
    const RENDERPAGE_VERSION = '1.0.0a';

    /**
     * Charset
     *
     * @var string
     */
    public static $charset = 'UTF-8';

    /**
     * Instance of Request class
     *
     * @var \renderpage\libs\Request
     */
    public $request;

    /**
     * Instance of Language class
     *
     * @var \renderpage\libs\Language
     */
    public $language;

    /**
     * Route instance
     *
     * @var \renderpage\libs\Route
     */
    private $route;

    /**
     * Instance of active controller
     *
     * @var object
     */
    private $controller;

    /**
     * Output
     *
     * @var mixed
     */
    public $outputData = false;

    /**
     * Init
     */
    public function __construct() {
        // Create instance of Request class
        $this->request = Request::getInstance();

        // Create instance of Language class
        $this->language = Language::getInstance();
    }

    /**
     * Routing
     *
     * @return \renderpage\libs\RenderPage
     */
    public function route(): RenderPage {
        $this->route = new Route;
        $this->route->run();

        if ($this->route->controllerName) {
            $class = '\app\controllers\\' . $this->route->controllerName;
            $this->controller = new $class;
        }

        return $this;
    }

    /**
     * Application execute
     *
     * @return \renderpage\libs\RenderPage
     */
    public function execute(): RenderPage {
        if (!empty($this->controller)) {
            // Before action
            $this->controller->before();

            // Action run
            $this->outputData = $this->controller->{$this->route->actionName}($this->route->params);

            // After action
            $this->controller->after();
        }

        return $this;
    }

    /**
     * Outputting data
     */
    public function output() {
        if (false === $this->outputData) {
            header('Content-Type: text/html; charset=' . self::$charset, true, 404);
            $view = new View;
            $view->title = '404';
            echo $view->render('404', 'error');
        } elseif (is_array($this->outputData)) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($this->outputData);
        } else {
            header('Content-Type: text/html; charset=' . self::$charset);
            echo $this->outputData;
        }
    }

}

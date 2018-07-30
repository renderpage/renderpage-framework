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
 * File:    Route.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is Route class
 */
class Route {

    /**
     * Default controller name
     */
    const DEFAULT_CONTROLLER_NAME = 'DefaultController';

    /**
     * Default action name
     */
    const DEFAULT_ACTION_NAME = 'actionIndex';

    /**
     * Action name
     *
     * @var string
     */
    public $actionName = '';

    /**
     * Controller directory
     *
     * @var string
     */
    public $controllerDir = 'controllers';

    /**
     * Controller name
     *
     * @var string
     */
    public $controllerName = '';

    /**
     * Language name
     *
     * @var string
     */
    public $language = '';

    /**
     * Route match params
     *
     * @var array
     */
    public $params = [];

    /**
     * Route rules
     *
     * @var array
     */
    public $routeRules = ['/^\/$/' => [],
        '/^\/(?<controller>[\w-]+)$/' => [],
        '/^\/(?<controller>[\w-]+)\/(?<action>[\w-]+)$/' => [],
        '/^\/(?<action>[\w-]+)$/' => []
    ];

    /**
     * URL path
     *
     * @var string
     */
    public $urlPath;

    /**
     * Init
     */
    public function __construct() {
        // Get url path
        $this->urlPath = Request::getInstance()->getUrlPath();
    }

    /**
     * Get controller name
     *
     * @param string $str
     *
     * @return string
     */
    private function getControllerName($str) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str))) . 'Controller';
    }

    /**
     * Get controller filename
     *
     * @param string $controllerName
     *
     * @return string
     */
    private function getControllerFilename($controllerName) {
        return APP_DIR . "/{$this->controllerDir}/{$controllerName}.php";
    }

    /**
     * Get action name
     *
     * @param string $str
     *
     * @return string
     */
    private function getActionName($str) {
        return 'action' . str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    /**
     * Run route
     *
     * @return boolean
     */
    public function run() {
        if (file_exists(APP_DIR . '/conf/route.php')) {
            $this->routeRules = include APP_DIR . '/conf/route.php';
        }

        foreach ($this->routeRules as $pattern => $params) {
            if (preg_match($pattern, $this->urlPath, $matches)) {

                if (!empty($matches['controller'])) {
                    $controllerName = $this->getControllerName($matches['controller']);
                } else {
                    if (!empty($params['controller'])) {
                        $controllerName = $this->getControllerName($params['controller']);
                    } else {
                        $controllerName = self::DEFAULT_CONTROLLER_NAME;
                    }
                }

                $controllerFilename = $this->getControllerFilename($controllerName);

                if (!empty($matches['action'])) {
                    $actionName = $this->getActionName($matches['action']);
                } else {
                    if (!empty($params['action'])) {
                        $actionName = $this->getActionName($params['action']);
                    } else {
                        $actionName = self::DEFAULT_ACTION_NAME;
                    }
                }

                if (file_exists($controllerFilename)) {
                    include_once $controllerFilename;

                    if (method_exists('\app\controllers\\' . $controllerName, $actionName)) {
                        $this->controllerName = $controllerName;
                        $this->actionName = $actionName;
                        $this->params = $matches;
                        if (!empty($matches['language'])) {
                            $this->language = $matches['language'];
                        }
                        return true;
                    }
                }
            }
        }

        return false;
    }

}

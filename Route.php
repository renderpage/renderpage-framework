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
 * File:    Route.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0-alpha
 */

namespace vendor\pershin\renderpage;

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
    public $routeRules = [
        '/^\/$/' => [],
        '/^\/(?<controller>[\w-]+)$/' => [],
        '/^\/(?<controller>[\w-]+)\/(?<action>[\w-]+)$/' => [],
        '/^\/(?<action>[\w-]+)$/' => []
    ];

    /**
     * URL path
     *
     * @var string
     */
    public $urlPath = '';

    /**
     * Init
     */
    public function __construct() {
        // Get URL path
        $this->urlPath = Request::getInstance()->getUrlPath();
    }

    /**
     * Run route
     *
     * @return boolean
     */
    public function run(): bool {
        $ok = false;

        if (file_exists(APP_DIR . '/conf/route.php')) {
            $this->routeRules = include APP_DIR . '/conf/route.php';
        }

        foreach ($this->routeRules as $pattern => $params) {
            if ($this->matches($pattern, $params)) {
                $ok = true;
                break;
            }
        }

        return $ok;
    }

    /**
     * Gets action name
     *
     * @param array $matches
     * @param array $params
     *
     * @return string
     */
    private function getActionName(array $matches, array $params): string {
        $action = false;

        if (!empty($matches['action'])) {
            $action = $matches['action'];
        } elseif (!empty($params['action'])) {
            $action = $params['action'];
        }

        return $action ?
                'action' . str_replace(' ', '', ucwords(str_replace('-', ' ', $action))) :
                self::DEFAULT_ACTION_NAME;
    }

    /**
     * Gets controller filename
     *
     * @param string $controllerName
     *
     * @return string
     */
    private function getControllerFilename($controllerName) {
        return APP_DIR . "/{$this->controllerDir}/{$controllerName}.php";
    }

    /**
     * Gets controller name
     *
     * @param array $matches
     * @param array $params
     *
     * @return string
     */
    private function getControllerName(array $matches, array $params): string {
        $controller = false;

        if (!empty($matches['controller'])) {
            $controller = $matches['controller'];
        } elseif (!empty($params['controller'])) {
            $controller = $params['controller'];
        }

        return $controller ?
                str_replace(' ', '', ucwords(str_replace('-', ' ', $controller))) . 'Controller' :
                self::DEFAULT_CONTROLLER_NAME;
    }

    /**
     * Tests if the route matches
     *
     * @param string $pattern
     * @param array $params
     *
     * @return boolean
     */
    private function matches(string $pattern, array $params): bool {
        $ok = false;
        $matches = [];

        if (1 === preg_match($pattern, $this->urlPath, $matches)) {
            $controllerName = $this->getControllerName($matches, $params);
            $controllerFilename = $this->getControllerFilename($controllerName);
            $actionName = $this->getActionName($matches, $params);

            if (file_exists($controllerFilename) && method_exists('\app\controllers\\' . $controllerName, $actionName)) {
                $this->controllerName = $controllerName;
                $this->actionName = $actionName;
                $this->params = $matches;
                $this->language = !empty($matches['language']) ? $matches['language'] : $this->language;

                $ok = true;
            }
        }

        return $ok;
    }

}

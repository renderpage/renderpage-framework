<?php

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
 * Debugging mode.
 */
if (!defined('RENDERPAGE_DEBUG')) {
    define('RENDERPAGE_DEBUG', false);
}

/**
 * Absolute path to RenderPage library files.
 */
if (!defined('RENDERPAGE_DIR')) {
    define('RENDERPAGE_DIR', __DIR__);
}

/**
 * Absolute path to application files.
 */
if (!defined('APP_DIR')) {
    define('APP_DIR', dirname(__DIR__) . '/app');
}

/**
 * Absolute path to compiled files.
 */
if (!defined('COMPILE_DIR')) {
    define('COMPILE_DIR', APP_DIR . '/compile');
}

/**
 * Load always needed external class files
 */
require_once RENDERPAGE_DIR . '/traits/Singleton.php';
require_once RENDERPAGE_DIR . '/RenderPageAutoloader.php';
require_once RENDERPAGE_DIR . '/RenderPageException.php';
require_once RENDERPAGE_DIR . '/Request.php';
require_once RENDERPAGE_DIR . '/Route.php';
require_once RENDERPAGE_DIR . '/Session.php';
require_once RENDERPAGE_DIR . '/Language.php';
require_once RENDERPAGE_DIR . '/DB.php';
require_once RENDERPAGE_DIR . '/Model.php';
require_once RENDERPAGE_DIR . '/View.php';
require_once RENDERPAGE_DIR . '/Controller.php';

/**
 * Autoloader
 */
spl_autoload_register(['\renderpage\libs\RenderPageAutoloader', 'autoload']);

/**
 * Errors
 */
set_exception_handler(['\renderpage\libs\RenderPageException', 'exceptionHandler']);
set_error_handler(['\renderpage\libs\RenderPageException', 'errorHandler']);

/**
 * This is the main RenderPage class
 */
class RenderPage {

    /**
     * RenderPage version
     */
    const RENDERPAGE_VERSION = '1.0.0a';

    /**
     * Recompile every time
     *
     * @var boolean
     */
    public static $forceCompile = false;

    /**
     * Charset
     *
     * @var string
     */
    public static $charset = 'UTF-8';

    /**
     * Instance of Request class
     *
     * @var object
     */
    public $request;

    /**
     * Instance of Language class
     *
     * @var object
     */
    public $language;

    /**
     * Route instance
     *
     * @var object
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
        // Debug mode
        if (RENDERPAGE_DEBUG) {
            self::$forceCompile = true;
        }

        // Create instance of Request class
        $this->request = Request::getInstance();

        // Create instance of Language class
        $this->language = Language::getInstance();
    }

    /**
     * Routing
     *
     * @return boolean
     */
    public function route() {
        $this->route = new Route;

        $this->route->run();

        if ($this->route->controllerName != '') {
            $controllerName = '\app\controllers\\' . $this->route->controllerName;
            $this->controller = new $controllerName;
            return true;
        }

        return false;
    }

    /**
     * Application execute
     */
    public function execute() {
        if (!empty($this->controller)) {
            // Before action
            $this->controller->before();

            // Action run
            $this->outputData = $this->controller->{$this->route->actionName}($this->route->params);

            // After action
            $this->controller->after();
        }
    }

    /**
     * Outputting data
     */
    public function output() {
        if ($this->outputData === false) {
            header('Content-Type: text/html; charset=' . self::$charset, true, 404);
            echo (new View)->setVar('title', '404')->render('404', 'error');
        } elseif (is_array($this->outputData)) {
            header('Content-Type: application/json');
            echo json_encode($this->outputData);
        } else {
            header('Content-Type: text/html; charset=' . self::$charset);
            echo $this->outputData;
        }
    }

}

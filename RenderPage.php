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
 * Load always needed external class files
 */
require_once RENDERPAGE_DIR . '/RenderPageException.php';
require_once RENDERPAGE_DIR . '/Route.php';
require_once RENDERPAGE_DIR . '/Model.php';
require_once RENDERPAGE_DIR . '/View.php';
require_once RENDERPAGE_DIR . '/Controller.php';

/**
 * This is the main RenderPage class
 */
class RenderPage
{
    /**
     * RenderPage version
     */
    const RENDERPAGE_VERSION = '1.0.0a';

    /**
     * Route instance
     *
     * @var object
     */
    public $route;

    /**
     * Instance of active controller
     *
     * @var object
     */
    public $controller;

    /**
     * Output
     *
     * @var mixed
     */
    public $outputData = false;

    /**
     * Init
     */
    public function __construct()
    {
        include_once APP_DIR . '/config.php';

        // Errors
        set_exception_handler(['\renderpage\libs\RenderPageException', 'exceptionHandler']);
        set_error_handler(['\renderpage\libs\RenderPageException', 'errorHandler']);

        // Autoloader
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    
    /**
     * Autoload classes.
     *
     * @param string $class class name.
     */
    public static function autoload($class)
    {
        $filename = dirname(APP_DIR) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if (file_exists($filename)) {
            include_once $filename;
        }
    }

    /**
     * Routing
     *
     * @return boolean
     */
    public function route()
    {
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
    public function execute()
    {
        if (!empty($this->controller)) {
            $this->outputData = $this->controller->{$this->route->actionName}($this->route->params);
        }
    }

    /**
     * Outputting data
     */
    public function output()
    {
        if ($this->outputData === false) {
            header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
            $view = new View;
            $view->setVar('title', '404');
            echo $view->render('404', 'error');
        } elseif (is_array($this->outputData)) {
            header('Content-Type: application/json');
            echo json_encode($this->outputData);
        } else {
            header('Content-Type: text/html; charset=utf-8');
            echo $this->outputData;
        }
    }
}

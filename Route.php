<?php
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
class Route
{
    /**
     * Controller directory
     *
     * @var string
     */
    public $controllerDir = 'controllers';

    /**
     * URL path
     *
     * @var string
     */
    public $urlPath;

    /**
     * Route rules
     *
     * @var array
     */
    public $routeRules = ['/^\/$/' => []];

    /**
     * Default controller name
     *
     * @var string
     */
    public $defaultControllerName = 'DefaultController';

    /**
     * Default action name
     *
     * @var string
     */
    public $defaultActionName = 'actionIndex';

    /**
     * Controller name
     *
     * @var string
     */
    public $controllerName = '';

    /**
     * Action name
     *
     * @var string
     */
    public $actionName = '';

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
     * Init
     */
    public function __construct()
    {
        // Get url path
        $this->urlPath = Request::getInstance()->getUrlPath();
    }

    /**
     * Add route rule
     *
     * @param string $pattern
     * @param string $params
     */
    public function addRouteRule($pattern, $params = [])
    {
        $this->routeRules[$pattern] = $params;
    }

    /**
     * Get controller name
     *
     * @param string $str
     *
     * @return string
     */
    private function getControllerName($str)
    {
        return str_replace(' ' , '', ucwords(str_replace('-' , ' ', $str))) . 'Controller';
    }

    /**
     * Get controller filename
     *
     * @param string $controllerName
     *
     * @return string
     */
    private function getControllerFilename($controllerName)
    {
        return APP_DIR . "/{$this->controllerDir}/{$controllerName}.php";
    }

    /**
     * Get action name
     *
     * @param string $str
     *
     * @return string
     */
    private function getActionName($str)
    {
        return 'action' . str_replace(' ' , '', ucwords(str_replace('-' , ' ', $str)));
    }

    /**
     * Run route
     *
     * @return boolean
     */
    public function run()
    {
        include_once APP_DIR . '/route.php';

        foreach ($this->routeRules as $pattern => $params) {
            if (preg_match($pattern, $this->urlPath, $matches)) {

                if (!empty($matches['controller'])) {
                    $controllerName = $this->getControllerName($matches['controller']);
                } else {
                    if (!empty($params['controller'])) {
                        $controllerName = $this->getControllerName($params['controller']);
                    } else {
                        $controllerName = $this->defaultControllerName;
                    }
                }

                $controllerFilename = $this->getControllerFilename($controllerName);

                if (!empty($matches['action'])) {
                    $actionName = $this->getActionName($matches['action']);
                } else {
                    if (!empty($params['action'])) {
                        $actionName = $this->getActionName($params['action']);
                    } else {
                        $actionName = $this->defaultActionName;
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

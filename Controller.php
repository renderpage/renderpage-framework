<?php

/**
 * Project: RenderPage
 * File:    Controller.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is Controller class.
 */
abstract class Controller {

    /**
     * Instance of Request class
     *
     * @var \renderpage\libs\Request
     */
    protected $request;

    /**
     * Instance of Language class
     *
     * @var \renderpage\libs\Language
     */
    protected $language;

    /**
     * Instance of View class
     *
     * @var \renderpage\libs\View
     */
    protected $view;

    /**
     * Last-Modified
     *
     * @var int Unix timestamp
     */
    public $lastModified = 0;

    /**
     * Init
     */
    public function __construct() {
        // Create instance of Request class
        $this->request = Request::getInstance();

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

    /**
     * Redirect
     *
     * @param string $location
     * @param int $httpResponseCode
     *
     * @return string
     */
    public function redirect(string $location, int $httpResponseCode = 302): string {
        header("Location: {$location}", true, $httpResponseCode);
        return '';
    }

}

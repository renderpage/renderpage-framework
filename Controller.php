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
 * This is Controller class
 */
class Controller
{
    /**
     * Instance of Language class
     *
     * @var object
     */
    public $language;

    /**
     * Instance of View class
     *
     * @var object
     */
    public $view;

    /**
     * Init
     */
    public function __construct()
    {
        // Create instance of Language class
        $this->language = Language::getInstance();

        // Create instance of View class
        $this->view = new View;
    }

    /**
     * Allias for $this->view->language->_($category, $str)
     *
     * @param string $category
     * @param string $str
     *
     * @return string
     */
    public function _($category, $str)
    {
        return $this->view->language->_($category, $str);
    }
}

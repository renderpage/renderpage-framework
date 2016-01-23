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
        // Create instance of View class
        $this->view = new View;
    }
}

<?php
/**
 * Project: RenderPage
 * File:    Singleton.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs\traits;

trait Singleton {

    private static $instance;

    private function __construct()
    {
        // none
    }

    private function __clone()
    {
        // none
    }

    private function __wakeup()
    {
         // none
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}

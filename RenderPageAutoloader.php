<?php
/**
 * Project: RenderPage
 * File:    RenderPageAutoloader.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is Autoloader class
 */
class RenderPageAutoloader
{
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
}

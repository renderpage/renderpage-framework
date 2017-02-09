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
class RenderPageAutoloader {

    /**
     * Get full path to file.
     *
     * @param string $class class name.
     */
    private static function getFilename($class) {
        if (strpos($class, __NAMESPACE__) !== false) {
            return RENDERPAGE_DIR . DIRECTORY_SEPARATOR . substr($class, strlen(__NAMESPACE__) + 1) . '.php';
        }
        return dirname(APP_DIR) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    }

    /**
     * Autoload classes.
     *
     * @param string $class class name.
     */
    public static function autoload($class) {
        $filename = self::getFilename($class);
        if (file_exists($filename)) {
            include_once $filename;
        }
    }

}

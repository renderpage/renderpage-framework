<?php
/**
 * Project: RenderPage
 * File:    RenderPageException.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

use Exception;

/**
 * This is RenderPageException class
 */
class RenderPageException extends Exception
{
    /**
     * Error message
     *
     * @var string
     */
    public static $errstr;

    /**
     * Error file name
     *
     * @var string
     */
    public static $errfile;

    /**
     * Error line
     *
     * @var int
     */
    public static $errline;

    /**
     * Init
     *
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param array $errcontext
     */
    public function __construct($errno, $errstr, $errfile = '', $errline = 0, $errcontext = [])
    {
        self::$errstr = $errstr;
        self::$errfile = $errfile;
        self::$errline = $errline;
    }

    /**
     * Exception handler (display error message)
     *
     * @param Exception $e
     */
    public static function exceptionHandler(Exception $e)
    {
        $view = new View;

        $view->setVar('title', 'Exception');
        $view->setVar('errstr', self::$errstr);
        $view->setVar('errfile', self::$errfile);
        $view->setVar('errline', self::$errline);
        $view->setVar('e', $e);

        echo $view->render('exception', 'error');
    }

    /**
     * Error handler
     *
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param array $errcontext
     */
    public static function errorHandler($errno, $errstr, $errfile = '', $errline = 0, $errcontext = [])
    {
        throw new RenderPageException($errno, $errstr, $errfile, $errline, $errcontext);
        return true;
    }
}

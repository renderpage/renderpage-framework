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
        parent::__construct($errstr, $errno);
    }

    /**
     * Exception handler (display error message)
     *
     * @param Exception $e
     */
    public static function exceptionHandler(Exception $e)
    {
        // 500 Internal Server Error
        header('Content-Type: text/html; charset=utf-8', true, 500);
        $trace = $e->getTrace();
        $source = self::source($trace[0]['file'], $trace[0]['line']);
        include __DIR__ . '/templates/exception.php';
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
    }

    /**
     * Highlight source code, and select line by number
     *
     * @param string  $file
     * @param integer $line
     *
     * @return string
     */
    public static function source($file, $line)
    {
        $tokens = token_get_all(file_get_contents($file));

        //$source = "<div class=line><span class=number>1</span>";
        $source = '';
        //$lines = [];

        foreach ($tokens as $token) {
            if (is_array($token)) {            
                $source .= '<span class="' . strtolower(token_name($token[0])) . '">';
                $source .= htmlspecialchars($token[1], ENT_NOQUOTES);
                $source .= '</span>';
            } else {
                $source .= $token;
            }
        }

        //$source .= '</div>';

        return $source;
    }
}

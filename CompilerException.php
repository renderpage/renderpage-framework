<?php
/**
 * Project: RenderPage
 * File:    CompilerException.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is CompilerException class
 */
class CompilerException extends RenderPageException
{
    /**
     * Init
     *
     * @param string $message
     * @param int $code
     * @param int $severity
     * @param string $filename
     * @param int $line
     * @param Exception $previous
     */
    public function __construct(
        $message,
        $code = 0,
        $severity = E_ERROR,
        $filename = __FILE__,
        $line = __LINE__,
        $previous = NULL
    ) {
        parent::__construct($message, $code, $severity, $filename, $line, $previous);
    }
}

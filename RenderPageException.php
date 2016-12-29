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
use ErrorException;

/**
 * This is RenderPageException class
 */
class RenderPageException extends ErrorException
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

    /**
     * Exception handler (display error message)
     *
     * @param Exception $e
     */
    public static function exceptionHandler($e)
    {
        // 500 Internal Server Error
        header('Content-Type: text/html; charset=utf-8', true, 500);

        // Get information
        $class   = get_class($e);
        $code    = $e->getCode();
        $message = $e->getMessage();
        $file    = $e->getFile();
        $line    = $e->getLine();
        $trace   = $e->getTrace();
        $source  = self::source($file, $line);

        // Show
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
        throw new RenderPageException($errstr, $errno, E_ERROR, $errfile, $errline);
    }

    /**
     * Highlight source code, and select line by number
     *
     * @param string  $file
     * @param integer $line
     *
     * @return string
     */
    public static function source($file, $line_number, $padding = 5)
    {
		if (!$file OR !is_readable($file)) {
			// Continuing will cause errors
			return FALSE;
		}

		// Open the file and set the line position
		$file = fopen($file, 'r');
		$line = 0;

		// Set the reading range
		$range = ['start' => $line_number - $padding, 'end' => $line_number + $padding];

		// Set the zero-padding amount for line numbers
		$format = '% '.strlen($range['end']).'d';

		$source = '';
		while (($row = fgets($file)) !== false) {
			// Increment the line number
			if (++$line > $range['end']) {
				break;
            }

			if ($line >= $range['start']) {
				// Make the row safe for output
				$row = htmlspecialchars($row, ENT_NOQUOTES, RenderPage::$charset);

				// Trim whitespace and sanitize the row
				$row = '<span class="number">' . sprintf($format, $line) . '</span> '.$row;

				if ($line === $line_number) {
					// Apply highlighting to this row
					$row = '<span class="line highlight">'.$row.'</span>';
				} else {
					$row = '<span class="line">'.$row.'</span>';
				}

				// Add to the captured source
				$source .= $row;
			}
		}

		// Close the file
		fclose($file);

		return $source;
        /*
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

        return $source;*/
    }
}

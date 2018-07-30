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

namespace renderpage\libs\exceptions;

use Exception,
    ErrorException,
    renderpage\libs\RenderPage;

/**
 * This is RenderPageException class
 */
class RenderPageException extends ErrorException {

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
    $message, $code = 0, $severity = E_ERROR, $filename = __FILE__, $line = __LINE__, $previous = NULL
    ) {
        parent::__construct($message, $code, $severity, $filename, $line, $previous);
    }

    /**
     * Exception handler (display error message)
     *
     * @param Exception $e
     */
    public static function exceptionHandler($e) {
        // 500 Internal Server Error
        header('Content-Type: text/html; charset=' . RenderPage::$charset, true, 500);

        // Get information
        $class = get_class($e);
        $code = $e->getCode();
        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        $trace = $e->getTrace();
        $source = self::source($file, $line);

        // Write to file
        $log = "{$message} in {$file} on line {$line}";
        error_log($log);

        // Send to admin
        if (file_exists(APP_DIR . '/conf/main.php')) {
            $conf = require APP_DIR . '/conf/main.php';
            if (isset($conf['error']['email']) && isset($conf['error']['from'])) {
                error_log($log, 1, $conf['error']['email'], "Subject: Error\r\nFrom: {$conf['error']['from']}");
            }
        }

        // Show
        include RENDERPAGE_DIR . '/templates/exception.php';
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
    public static function errorHandler($errno, $errstr, $errfile = '', $errline = 0, $errcontext = []) {
        throw new RenderPageException($errstr, $errno, E_ERROR, $errfile, $errline);
    }

    /**
     * Source code, and select line by number
     *
     * @param string  $filename
     * @param integer $line
     *
     * @return string|boolean
     */
    public static function source($filename, $lineNumber, $padding = 5) {
        if (!is_readable($filename)) {
            return false;
        }

        $fileHandle = fopen($filename, 'r');
        $line = 0;
        $range = ['start' => $lineNumber - $padding, 'end' => $lineNumber + $padding];
        $format = '% ' . strlen($range['end']) . 'd';
        $source = '';

        while (($row = fgets($fileHandle)) !== false) {
            if (++$line > $range['end']) {
                break;
            }

            if ($line >= $range['start']) {
                $row = htmlspecialchars($row, ENT_NOQUOTES, RenderPage::$charset);

                $row = '<span class="number">' . sprintf($format, $line) . '</span> ' . $row;

                if ($line === $lineNumber) {
                    $row = '<span class="line highlight">' . $row . '</span>';
                } else {
                    $row = '<span class="line">' . $row . '</span>';
                }

                $source .= $row;
            }
        }

        fclose($fileHandle);

        return $source;
    }

}

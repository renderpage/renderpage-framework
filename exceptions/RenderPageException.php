<?php

/*
 * The MIT License
 *
 * Copyright (c) 2015-2019 Sergey Pershin
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Project: RenderPage
 * File:    RenderPageException.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0-alpha
 */

namespace vendor\pershin\renderpage\exceptions;

use Exception,
    ErrorException,
    vendor\pershin\renderpage\RenderPage,
    vendor\pershin\renderpage\View;

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
        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
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

        $view = new View;
        $view->title = '500';
        $view->assign('e', $e);
        $view->assign('source', $source);

        ob_clean();

        if (RENDERPAGE_DEBUG) {
            echo $view->render('debug-500', 'debug');
        } else {
            echo $view->render('500', 'error');
        }
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

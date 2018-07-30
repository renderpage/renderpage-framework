<?php

/*
 * The MIT License
 *
 * Copyright 2018 Sergey Pershin <sergey dot pershin at hotmail dot com>.
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
 * Debugging mode.
 */
if (!defined('RENDERPAGE_DEBUG')) {
    define('RENDERPAGE_DEBUG', false);
}

/**
 * Absolute path to RenderPage library files.
 */
if (!defined('RENDERPAGE_DIR')) {
    define('RENDERPAGE_DIR', __DIR__);
}

/**
 * Absolute path to application files.
 */
if (!defined('APP_DIR')) {
    define('APP_DIR', dirname(__DIR__) . '/app');
}

/**
 * Load always needed external class files
 */
require_once RENDERPAGE_DIR . '/traits/Singleton.php';
require_once RENDERPAGE_DIR . '/Request.php';
require_once RENDERPAGE_DIR . '/Route.php';
require_once RENDERPAGE_DIR . '/Session.php';
require_once RENDERPAGE_DIR . '/Language.php';
require_once RENDERPAGE_DIR . '/DB.php';
require_once RENDERPAGE_DIR . '/Model.php';
require_once RENDERPAGE_DIR . '/View.php';
require_once RENDERPAGE_DIR . '/Controller.php';

/**
 * Autoloader
 */
spl_autoload_register(function ($class) {
    if (0 === strpos($class, 'renderpage\libs\\')) {
        $filename = RENDERPAGE_DIR . DIRECTORY_SEPARATOR . substr(strtr($class, '\\', DIRECTORY_SEPARATOR), strlen('renderpage\libs\\')) . '.php';
    } else {
        $filename = dirname(APP_DIR) . DIRECTORY_SEPARATOR . strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';
    }
    include_once $filename;
});

/**
 * Errors
 */
set_exception_handler(['\renderpage\libs\exceptions\RenderPageException', 'exceptionHandler']);
set_error_handler(['\renderpage\libs\exceptions\RenderPageException', 'errorHandler']);

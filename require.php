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
 * Absolute path to project directory.
 */
if (!defined('RENDERPAGE_PROJECT_DIR')) {
    define('RENDERPAGE_PROJECT_DIR', dirname(dirname(dirname(__DIR__))));
}

/**
 * Absolute path to application files.
 */
if (!defined('APP_DIR')) {
    define('APP_DIR', RENDERPAGE_PROJECT_DIR . DIRECTORY_SEPARATOR . 'app');
}

/**
 * Debug mode
 */
if (RENDERPAGE_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

/**
 * Load always needed external class files
 */
require_once RENDERPAGE_DIR . DIRECTORY_SEPARATOR . 'traits'
        . DIRECTORY_SEPARATOR . 'Singleton.php';
require_once RENDERPAGE_DIR . DIRECTORY_SEPARATOR . 'Request.php';
require_once RENDERPAGE_DIR . DIRECTORY_SEPARATOR . 'Route.php';
require_once RENDERPAGE_DIR . DIRECTORY_SEPARATOR . 'Session.php';
require_once RENDERPAGE_DIR . DIRECTORY_SEPARATOR . 'Language.php';
require_once RENDERPAGE_DIR . DIRECTORY_SEPARATOR . 'DB.php';
require_once RENDERPAGE_DIR . DIRECTORY_SEPARATOR . 'Model.php';
require_once RENDERPAGE_DIR . DIRECTORY_SEPARATOR . 'View.php';
require_once RENDERPAGE_DIR . DIRECTORY_SEPARATOR . 'Controller.php';

/**
 * Autoloader
 */
spl_autoload_register(function ($class) {
    $filename = RENDERPAGE_PROJECT_DIR . DIRECTORY_SEPARATOR
            . strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';
    include_once $filename;
});

set_exception_handler([
    'vendor\pershin\renderpage\exceptions\RenderPageException',
    'exceptionHandler'
]);

set_error_handler([
    'vendor\pershin\renderpage\exceptions\RenderPageException',
    'errorHandler'
]);

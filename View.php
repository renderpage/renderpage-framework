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
 * Project: RenderPage
 * File:    View.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

use renderpage\libs\exceptions\ViewException;

/**
 * Template engine
 */
class View {

    const SCOPE_LAYOUT = 'layout';
    const SCOPE_TEMPLATE = 'template';

    /**
     * Instance of Language class
     *
     * @var object
     */
    public $language;

    /**
     * Template JavaScript files
     *
     * @var array
     */
    public $scripts = [];

    /**
     * Template CSS files
     *
     * @var array
     */
    public $styles = [];

    /**
     * Template directory
     *
     * @var string
     */
    public $templateDir = APP_DIR . DIRECTORY_SEPARATOR . 'templates';

    /**
     * Tag title
     *
     * @var string
     */
    public $title = '';

    /**
     * The main content
     *
     * @var string
     */
    public $workarea = '';

    /**
     * Template variables
     *
     * @var array
     */
    private $variables = [
        self::SCOPE_LAYOUT => [],
        self::SCOPE_TEMPLATE => []
    ];

    /**
     * Using in fetch
     *
     * @var string
     */
    private $filename = '';

    public function _(string $category, string $str): string {
        return Language::getInstance()->_($category, $str);
    }

    /**
     * Init
     */
    public function __construct() {
        // Create instance of Language class
        $this->language = Language::getInstance();
    }

    /**
     * Adds script file the layout
     *
     * @param string $src The JavaScript file.
     *
     * @return \renderpage\libs\View
     */
    public function addScript(string $src) {
        $this->scripts[] = ['src' => $src];
        return $this;
    }

    /**
     * Adds CSS file to the layout
     *
     * @param string $href The CSS file.
     *
     * @return \renderpage\libs\View
     */
    public function addStyle(string $href) {
        $this->styles[] = ['href' => $href];
        return $this;
    }

    /**
     * Assigns variables to the templates
     *
     * @param string $name The name of the variable being assigned.
     * @param mixed $value The value being assigned.
     * @param string $scope The scope of the assigned variable: 'layout' or 'template'
     *
     * @return \renderpage\libs\View
     *
     * @throws ViewException
     */
    public function assign(string $name, $value, $scope = self::SCOPE_TEMPLATE): View {
        if (self::SCOPE_LAYOUT !== $scope && self::SCOPE_TEMPLATE !== $scope) {
            throw new ViewException('$scope is invalid');
        }
        $this->variables[$scope][$name] = $value;
        return $this;
    }

    /**
     * Returns the template output
     *
     * @param string $template The name of the template file.
     * @param string $scope The scope of the assigned variable: 'layout' or 'template'
     *
     * @return string
     *
     * @throws ViewException
     */
    public function fetch(string $template, string $scope = self::SCOPE_TEMPLATE): string {
        $this->filename = $this->templateDir . DIRECTORY_SEPARATOR . $template . '.php';
        if (!is_file($this->filename)) {
            $this->filename = RENDERPAGE_DIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template . '.php';
        }
        if (!is_file($this->filename)) {
            throw new ViewException('fetch(' . $this->filename . ') failed to open: No such file');
        }
        unset($template);
        extract($this->variables[$scope], EXTR_REFS);
        ob_start();
        include $this->filename;
        return ob_get_clean();
    }

    /**
     * Render template
     *
     * @param string $template The name of the template file.
     * @param string $layout The name of the layout file.
     *
     * @return string
     */
    public function render(string $template, $layout = 'default'): string {
        $this->workarea = $this->fetch($template);
        return $this->fetch('layouts' . DIRECTORY_SEPARATOR . $layout, self::SCOPE_LAYOUT);
    }

}

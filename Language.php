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
 * File:    Language.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is Language class
 */
class Language {

    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * Language code
     *
     * @var string
     */
    public $code = 'en-us';

    /**
     * Strings
     *
     * @var array
     */
    public $strings = [];

    /**
     * Get text
     *
     * @param string $category
     * @param string $str
     *
     * @return string
     */
    public function getText(string $category, string $str): string {
        if (empty($this->strings[$this->code][$category])) {
            $this->loadCategory($category);
        }

        if (!empty($this->strings[$this->code][$category][$str])) {
            $str = $this->strings[$this->code][$category][$str];
        }

        return $str;
    }

    /**
     * Sets current language
     *
     * @param string $code
     */
    public function setCurrentLanguage(string $code) {
        $this->code = $code;
    }

    /**
     * Load XML file
     */
    private function loadCategory(string $category) {
        $filename = APP_DIR . '/languages/' . $this->code . '/' . $category . '.xml';
        $xml = simplexml_load_file($filename);
        foreach ($xml->children() as $child) {
            $attributes = $child->attributes();
            $this->strings[$this->code][$category][(string) $attributes->name] = (string) $child[0];
        }
    }

}

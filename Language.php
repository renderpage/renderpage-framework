<?php

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
    public function _(string $category, string $str): string {
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

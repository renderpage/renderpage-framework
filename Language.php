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
     * Load XML files
     */
    public function loadFiles() {
        // Get compile filename
        $compileFilename = COMPILE_DIR . "/xml_languages.php";

        // Remove compile file if forceCompile == true
        if (RenderPage::$forceCompile) {
            if (file_exists($compileFilename)) {
                unlink($compileFilename);
            }
        }

        // [Compile]
        if (!file_exists($compileFilename)) {
            include_once RENDERPAGE_DIR . '/Compiler.php';
            (new Compiler)->compileLanguages($compileFilename);
        }

        $this->strings = require_once $compileFilename;
    }

    /**
     * Set current language
     *
     * @param string $code
     */
    public function setCurrentLanguage(string $code) {
        $this->code = $code;
    }

    /**
     * Get text
     *
     * @param string $category
     * @param string $str
     *
     * @return string
     */
    public function _(string $category, string $str): string {
        if (empty($this->strings)) {
            $this->loadFiles();
        }

        if (!empty($this->strings[$this->code][$category][$str])) {
            return $this->strings[$this->code][$category][$str];
        }

        return $str;
    }

}

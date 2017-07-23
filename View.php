<?php

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

/**
 * This is View class
 */
class View {

    /**
     * Template directory
     *
     * @var string
     */
    public $templateDir = 'templates';

    /**
     * Template extension
     *
     * @var string
     */
    public $extension = '.tpl';

    /**
     * Instance of Language class
     *
     * @var object
     */
    public $language;

    /**
     * Template variables
     *
     * @var array
     */
    private $variables = [
        'cssFiles' => [],
        'jsFiles' => []
    ];

    /**
     * Used for check templates
     *
     * @var boolean
     */
    private $getFiles = false;

    /**
     * Init
     */
    public function __construct() {
        // Create instance of Language class
        $this->language = Language::getInstance();
    }

    /**
     * Add variable to template
     *
     * @param string $name var name
     * @param mixed $value value var
     */
    public function setVar(string $name, $value) {
        $this->variables[$name] = $value;
        return $this;
    }

    /**
     * Add css file to template
     *
     * @param string $href css file
     */
    public function addCss(string $href) {
        $this->variables['cssFiles'][] = ['href' => $href];
        return $this;
    }

    /**
     * Add script file to template
     *
     * @param string $src js file
     */
    public function addScript(string $src) {
        $this->variables['jsFiles'][] = ['src' => $src];
        return $this;
    }

    /**
     * Get template filename
     *
     * @param string $template template name
     *
     * @return string|boolean
     */
    public function getTemplateFilename(string $template) {
        $filename = APP_DIR . "/{$this->templateDir}/{$template}{$this->extension}";
        if (file_exists($filename)) {
            return $filename;
        }
        $filename = RENDERPAGE_DIR . "/templates/{$template}{$this->extension}";
        if (file_exists($filename)) {
            return $filename;
        }
        return false;
    }

    /**
     * Get layout filename
     *
     * @param string $layout layout name
     *
     * @return string|boolean
     */
    public function getLayoutFilename(string $layout) {
        $filename = APP_DIR . "/{$this->templateDir}/layouts/{$layout}{$this->extension}";
        if (file_exists($filename)) {
            return $filename;
        }
        $filename = RENDERPAGE_DIR . "/{$this->templateDir}/layouts/{$layout}{$this->extension}";
        if (file_exists($filename)) {
            return $filename;
        }
        return false;
    }

    /**
     * Get compile filename
     *
     * @param string $template template name
     * @param string|boolean $layout layout template name
     *
     * @return string Filename
     */
    public function getCompileFilename(string $template, $layout): string {
        $filename = COMPILE_DIR . '/tpl_' . str_replace(DIRECTORY_SEPARATOR, '_ds_', $template);
        if ($layout) {
            $filename .= '_layout_' . $layout;
        }
        $filename .= '.php';
        return $filename;
    }

    /**
     * Clears compiled template files or file
     *
     * @param string $template template name
     */
    public function clearCompiledTemplate($template = NULL) {
        $pattern = COMPILE_DIR . '/tpl_';
        if ($template) {
            $pattern .= str_replace(DIRECTORY_SEPARATOR, '_ds_', $template);
        }
        $pattern .= '*.php';
        foreach (glob($pattern) as $filename) {
            unlink($filename);
        }
    }

    /**
     * Render template
     *
     * @param string $template template name
     * @param string|boolean $layout layout template name
     *
     * @return mixed
     */
    public function render(string $template, $layout = 'default') {
        // Get compile filename
        $compileFilename = $this->getCompileFilename($template, $layout);

        // [ Compile ]
        if (!$this->compileCheck($compileFilename)) {
            require_once RENDERPAGE_DIR . '/Compiler.php';
            (new Compiler($this))->compile($template, $layout, $compileFilename);
        }

        // Output
        ob_start();
        include $compileFilename;
        return ob_get_clean();
    }

    /**
     * Check template for modifications
     *
     * @param string $filename
     *
     * @return boolean
     */
    private function compileCheck(string $filename): bool {
        if (RenderPage::$forceCompile) {
            return false;
        }
        if (!file_exists($filename)) {
            return false;
        }
        $this->getFiles = true;
        $files = include $filename;
        $this->getFiles = false;
        foreach ($files as $file) {
            if (!file_exists($file['filename'])) {
                return false;
            }
            if (filemtime($file['filename']) != $file['modified']) {
                return false;
            }
        }
        return true;
    }

}

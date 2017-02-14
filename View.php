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
    }

    /**
     * Add css file to template
     *
     * @param string $href css file
     */
    public function addCss(string $href) {
        $this->variables['cssFiles'][] = ['href' => $href];
    }

    /**
     * Add script file to template
     *
     * @param string $src js file
     */
    public function addScript(string $src) {
        $this->variables['jsFiles'][] = ['src' => $src];
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
     * @return string
     */
    public function getCompileFilename(string $template, string $layout) {
        if (!$templateFilename = $this->getTemplateFilename($template)) {
            return false;
        }
        $templateLastModified = filemtime($templateFilename);
        $compileFilename = COMPILE_DIR . "/tpl_";
        $compileFilename .= str_replace('/', '_ds_', $template);
        $compileFilename .= "_{$templateLastModified}";
        if ($layout) {
            if (!$layoutFilename = $this->getLayoutFilename($layout)) {
                return false;
            }
            $layoutLastModified = filemtime($layoutFilename);
            $compileFilename .= "_layout_{$layout}_{$layoutLastModified}";
        }
        return "{$compileFilename}.php";
    }

    /**
     * Clears compiled template files or file
     *
     * @param string $template template name
     */
    public function clearCompiledTemplates($template = NULL) {
        $compiledTplPattern = COMPILE_DIR . "/tpl_";
        if ($template) {
            $compiledTplPattern .= str_replace('/', '_ds_', $template);
        }
        $compiledTplPattern .= '*.php';
        foreach (glob($compiledTplPattern) as $filename) {
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

        // Remove compile file if forceCompile == true
        if (RenderPage::$forceCompile) {
            if (file_exists($compileFilename)) {
                unlink($compileFilename);
            }
        }

        // [Compile]
        if (!file_exists($compileFilename)) {
            include_once RENDERPAGE_DIR . '/Compiler.php';
            (new Compiler)->compile($this, $template, $layout);
        }

        // Output
        ob_start();
        include $compileFilename;

        // Check compiler version
        if ($rpVersion != RenderPage::RENDERPAGE_VERSION) {
            unlink($compileFilename);
        }

        return ob_get_clean();
    }

}

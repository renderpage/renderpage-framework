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
class View
{
    /**
     * Recompile templates every time
     *
     * @var boolean
     */
    public $forceCompile = false;

    /**
     * Template directory
     *
     * @var string
     */
    public $templateDir = 'templates';

    /**
     * Compile directory
     *
     * @var string
     */
    public $compileDir = 'compile';

    /**
     * Template extension
     *
     * @var string
     */
    public $extension = '.tpl';

    /**
     * Template variables
     *
     * @var array
     */
    private $variables = [];

    /**
     * Init
     */
    public function __construct()
    {
        if (RENDERPAGE_DEBUG) {
            $this->forceCompile = true;
        }
    }

    /**
     * Add variable to template
     *
     * @param string $name var name
     * @param mixed $value value var
     */
    public function setVar($name, $value)
    {
        $this->variables[$name] = $value;
    }

    /**
     * Get template filename
     *
     * @param string $template template name
     *
     * @return string
     */
    public function getTemplateFilename($template)
    {
        $templateFilename = APP_DIR . "/{$this->templateDir}/{$template}{$this->extension}";
        if (!file_exists($templateFilename)) {
            $templateFilename = RENDERPAGE_DIR . "/templates/{$template}{$this->extension}";
        }
        if (!file_exists($templateFilename)) {
            return false;
        }
        return $templateFilename;
    }

    /**
     * Get layout filename
     *
     * @param string $layout layout name
     *
     * @return string
     */
    public function getLayoutFilename($layout)
    {
        $layoutFilename = APP_DIR . "/{$this->templateDir}/layouts/{$layout}{$this->extension}";
        if (!file_exists($layoutFilename)) {
            $layoutFilename = RENDERPAGE_DIR . "/{$this->templateDir}/layouts/{$layout}{$this->extension}";
        }
        if (!file_exists($layoutFilename)) {
            return false;
        }
        return $layoutFilename;
    }

    /**
     * Get compile filename
     *
     * @param string $template template name
     * @param string|boolean $layout layout template name
     *
     * @return string
     */
    public function getCompileFilename($template, $layout)
    {
        if (!$templateFilename = $this->getTemplateFilename($template)) {
            return false;
        }
        $templateLastModified = filemtime($templateFilename);
        $compileFilename = APP_DIR . "/{$this->compileDir}/tpl_";
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
    public function clearCompiledTemplates($template = NULL)
    {
        $compiledTplPattern = APP_DIR . "/{$this->compileDir}/tpl_";
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
    public function render($template, $layout = 'default')
    {
        // Get compile filename
        $compileFilename = $this->getCompileFilename($template, $layout);

        // Remove compile file if forceCompile == true
        if ($this->forceCompile) {
            if (file_exists($compileFilename)) {
                unlink($compileFilename);
            }
        }

        // [Compile]
        if (!file_exists($compileFilename)) {
            include_once RENDERPAGE_DIR . '/compiler/Compiler.php';
            (new Compiler)->compile($this, $template, $layout);
        }

        // Output
        ob_start();
        include $compileFilename;
        return ob_get_clean();
    }
}

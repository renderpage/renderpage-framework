<?php
/**
 * Project: RenderPage
 * File:    Compiler.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is Compiler class
 */
class Compiler
{
    /**
     * Left-delimiter
     *
     * @var string
     */
    public $leftDelimiter = '{';

    /**
     * Right-delimiter
     *
     * @var string
     */
    public $rightDelimiter = '}';

    /**
     * Replace instructions
     *
     * @var array
     */
    private $replaceInstructions = [];

    /**
     * Template contents
     *
     * @var string
     */
    private $templateData = '';

    /**
     * Compile contents
     *
     * @var string
     */
    private $compileData = '';

    /**
     * Init
     */
    public function __construct()
    {
        // none
    }

    /**
     * Add new replace instruction
     *
     * @param string $pattern pattern for replace
     * @param string $callback function for replace
     */
    public function addReplaceInstruction($pattern, $callback)
    {
        $this->replaceInstructions[$pattern] = $callback;
    }

    /**
     * Compile Block
     *
     * @param object $view instance of View class
     * @param string $block
     *
     * @return string
     */
    public function compileBlock($view, $block)
    {
        // Load needed external files
        foreach (glob(RENDERPAGE_DIR . '/compiler/tpl/*.php') as $filename) {
            include_once $filename;
        }

        // Run replace
        foreach ($this->replaceInstructions as $pattern => $callback) {
            $block = preg_replace_callback(
                "/(\t+| +|){$this->leftDelimiter}(\t+| +|){$pattern}{$this->rightDelimiter}/si",
                $callback,
                $block
            );
        }

        return $block;
    }

    /**
     * Compile
     *
     * @param object $view instance of View class
     * @param string $template template name
     * @param string|boolean $layout layout template name
     *
     * @return int
     */
    public function compile($view, $template, $layout)
    {
        // Remove old compile files
        $view->clearCompiledTemplates($template);

        // Get template contents
        $templateFilename = $view->getTemplateFilename($template);
        if (!file_exists($templateFilename)) {
            throw new RenderPageException(0, "{$template} is not exists");
            return 0;
        }

        $this->templateData = file_get_contents($templateFilename);

        if ($layout) {
            // Get layout contents
            $layoutFilename = $view->getLayoutFilename($layout);
            if (!file_exists($layoutFilename)) {
                throw new RenderPageException(0, "{$layout} is not exists");
                return 0;
            }
            $layoutData = file_get_contents($layoutFilename);

            // workarea
            $this->compileData = preg_replace_callback(
                "/(\t+| +|){$this->leftDelimiter}workarea{$this->rightDelimiter}/i",
                function ($matches) {
                    return rtrim(preg_replace('!^!m', $matches[1], $this->templateData));
                },
                $layoutData
            );
        } else {
            $this->compileData = $this->templateData;
        }

        $this->compileData = $this->compileBlock($view, $this->compileData);

        // Add compile comment
        $compileData = '<?php /* RenderPage version: ' .
                       RenderPage::RENDERPAGE_VERSION . ', ' .
                       'created on ' . date('c') .
                       ' */ ?>' . $this->compileData;

        // Write compile file
        return file_put_contents($view->getCompileFilename($template, $layout), $this->compileData);
    }
}

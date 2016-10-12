<?php
/**
 * Project: RenderPage
 * File:    CompilerVersion.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs\compiler;

/**
 * This is CompilerVersion class
 */
class CompilerVersion
{
    /**
     * Instance of Compiler class
     *
     * @var object
     */
    public $compiler;

    /**
     * Get RenderPage version
     *
     * @param array $params
     *
     * @return string
     */
    public function getVersion($params)
    {
        return \renderpage\libs\RenderPage::RENDERPAGE_VERSION;
    }
}

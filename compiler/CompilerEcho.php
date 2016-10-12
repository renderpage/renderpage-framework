<?php
/**
 * Project: RenderPage
 * File:    CompilerEcho.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs\compiler;

/**
 * This is CompilerEcho class
 */
class CompilerEcho
{
    /**
     * Instance of Compiler class
     *
     * @var object
     */
    public $compiler;

    /**
     * Echo
     *
     * @param array $params
     *
     * @return string
     */
    public function openTag($params)
    {
        $varName = $this->compiler->getVariable($params[0]);
        return "<?php if (!empty({$varName})) { echo {$varName}; }; ?>";
    }
}

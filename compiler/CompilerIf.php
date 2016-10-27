<?php
/**
 * Project: RenderPage
 * File:    CompilerIf.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs\compiler;

/**
 * This is CompilerIf class
 */
class CompilerIf
{
    /**
     * Instance of Compiler class
     *
     * @var object
     */
    public $compiler;

    /**
     * If
     *
     * @param array $params
     *
     * @return string
     */
    public function openTag($params)
    {
        $varName = $this->compiler->getVariable($params[0]);
        if (!empty($params[1])) {
            return "<?php if (!empty({$varName}) && ({$varName} {$params[1]} {$params[2]})) { ?>";
        }
        return "<?php if (!empty({$varName}) && {$varName}) { ?>";
    }

    /**
     * Endif
     *
     * @param array $params
     *
     * @return string
     */
    public function closeTag($params)
    {
        return '<?php } ?>';
    }
}

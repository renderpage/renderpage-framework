<?php
/**
 * Project: RenderPage
 * File:    CompilerForeach.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs\compiler;

/**
 * This is CompilerForeach class
 */
class CompilerForeach
{
    /**
     * Instance of Compiler class
     *
     * @var object
     */
    public $compiler;

    /**
     * Foreach
     *
     * @param array $params
     *
     * @return string
     */
    public function openTag($params)
    {
        $fromVar = $this->compiler->getVariable($params[0]);
        $itemVar = $this->compiler->getVariable($params[2]);

        return '<?php ' .
            "if (!empty({$fromVar}) && is_array({$fromVar})) { " .
            "foreach ({$fromVar} as {$itemVar}) { ?>";
    }

    /**
     * Endforeach
     *
     * @param array $params
     *
     * @return string
     */
    public function closeTag($params)
    {
        return '<?php } } ?>';
    }
}

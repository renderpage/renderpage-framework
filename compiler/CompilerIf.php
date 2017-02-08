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

use renderpage\libs\CompilerException;

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

    public $filename;

    public $line;

    private $comparisonOperators = [
        '==',  // Equal
        '===', // Identical
        '!=',  // Not equal
        '<>',  // Not equal
        '!==', // Not identical
        '<',   // Less than
        '>',   // Greater than
        '<=',  // Less than or equal to
        '>=',  // Greater than or equal to
        '<=>'  // Spaceship
    ];

    /**
     * If
     *
     * @param array $params
     *
     * @return string
     */
    public function openTag($params)
    {
        if (!empty($params[3])) {
            throw new CompilerException('Parse error: syntax error - too many args', 0, E_ERROR, $this->filename, $this->line);
        }

        if (substr($params[0], 0, 1) === '!') {
            $varName = $this->compiler->getVariable(substr($params[0], 1));
            return "<?php if (empty({$varName}) || !{$varName}) { ?>";
        }

        $varName = $this->compiler->getVariable($params[0]);

        if (!empty($params[1])) {
            if (!in_array($params[1], $this->comparisonOperators)) {
                throw new CompilerException('Parse error: syntax error - invalid comparison operator', 0, E_ERROR, $this->filename, $this->line);
            }
            return "<?php if (isset({$varName}) && ({$varName} {$params[1]} {$params[2]})) { ?>";
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

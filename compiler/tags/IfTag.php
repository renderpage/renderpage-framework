<?php

/**
 * Project: RenderPage
 * File:    IfTag.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs\compiler\tags;

use renderpage\libs\{
    interfaces\CompilerTagInterface,
    compiler\CompilerTag,
    CompilerException
};

/**
 * This is CompilerIf class
 */
class IfTag extends CompilerTag implements CompilerTagInterface {

    private $comparisonOperators = [
        '==', // Equal
        '===', // Identical
        '!=', // Not equal
        '<>', // Not equal
        '!==', // Not identical
        '<', // Less than
        '>', // Greater than
        '<=', // Less than or equal to
        '>=', // Greater than or equal to
        '<=>'  // Spaceship
    ];

    /**
     * If
     *
     * @param array $params
     *
     * @return string
     */
    public function openTag(array $params): string {
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
     * Else
     *
     * @param array $params
     *
     * @return string
     */
    public function elseTag(): string {
        return '<?php } else { ?>';
    }

    /**
     * Endif
     *
     * @param array $params
     *
     * @return string
     */
    public function closeTag(): string {
        return '<?php } ?>';
    }

}

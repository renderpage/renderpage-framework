<?php

/**
 * Project: RenderPage
 * File:    ForeachTag.php
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
 * This is ForeachTag class
 */
class ForeachTag extends CompilerTag implements CompilerTagInterface {

    /**
     * Foreach
     *
     * @param array $params
     *
     * @return string
     */
    public function openTag(array $params): string {
        $from = $this->compiler->getVariable($params[0]);
        $item = $this->compiler->getVariable($params[2]);
        return '<?php ' .
                "if (!empty({$from}) && is_array({$from})) { " .
                "foreach ({$from} as {$item}) { ?>";
    }

    /**
     * Endforeach
     *
     * @return string
     */
    public function closeTag() {
        return '<?php } } ?>';
    }

}

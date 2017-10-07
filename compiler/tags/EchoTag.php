<?php

/**
 * Project: RenderPage
 * File:    EchoTag.php
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
 * This is EchoTag class
 */
class EchoTag extends CompilerTag implements CompilerTagInterface {

    /**
     * Echo
     *
     * @param array $params
     *
     * @return string
     */
    public function openTag(array $params): string {
        return '<?= ' . $this->compiler->getVariable($params[0]) . ' ?? \'\'; ?>';
    }

}

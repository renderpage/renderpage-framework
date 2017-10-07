<?php

/**
 * Project: RenderPage
 * File:    LanguageTag.php
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
 * This is LanguageTag class
 */
class LanguageTag extends CompilerTag implements CompilerTagInterface {

    /**
     * _
     *
     * @param array $params
     *
     * @return string
     */
    public function openTag(array $params): string {
        return "<?= \$this->language->_('{$params[0]}', '{$params[1]}'); ?>";
    }

}

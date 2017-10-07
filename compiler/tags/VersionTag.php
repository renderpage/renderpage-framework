<?php

/**
 * Project: RenderPage
 * File:    VersionTag.php
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
 * This is VersionTag class
 */
class VersionTag extends CompilerTag implements CompilerTagInterface {

    /**
     * Gets RenderPage version
     *
     * @param array $params
     *
     * @return string
     */
    public function openTag(array $params): string {
        return \renderpage\libs\RenderPage::RENDERPAGE_VERSION;
    }

}

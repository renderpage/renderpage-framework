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

namespace renderpage\libs\interfaces;

/**
 * This is Compiler Tag Interface
 */
interface CompilerTagInterface {

    public function openTag(array $params): string;
}

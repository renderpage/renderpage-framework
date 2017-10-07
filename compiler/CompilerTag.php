<?php

/**
 * Project: RenderPage
 * File:    CompilerTag.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs\compiler;

/**
 * This is CompilerTag class
 */
abstract class CompilerTag {

    /**
     * Instance of Compiler class
     *
     * @var object
     */
    public $compiler;

    /**
     * Template file name
     *
     * @var string
     */
    public $filename;

    /**
     * Line
     *
     * @var int
     */
    public $line;

}

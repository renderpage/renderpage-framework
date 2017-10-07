<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace renderpage\libs\compiler;

/**
 * Description of CompilerTag
 *
 * @author Sergey
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

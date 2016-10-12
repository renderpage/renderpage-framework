<?php
/**
 * Project: RenderPage
 * File:    CompilerWorkarea.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs\compiler;

/**
 * This is CompilerWorkarea class
 */
class CompilerWorkarea
{
    /**
     * Instance of Compiler class
     *
     * @var object
     */
    public $compiler;

    /**
     * Parse expr
     *
     * @param array $params
     *
     * @return mixed
     */
    public function parseExpr($params)
    {
        /*if ($params['name'] == 'workarea') {
            $result = $params;
            $result['inc'] = $compiler->parse($compiler->files['template']['filename']);
            return $result;
        }*/

        return false;
    }
}

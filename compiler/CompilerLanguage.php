<?php
/**
 * Project: RenderPage
 * File:    CompilerLanguage.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs\compiler;

/**
 * This is CompilerLanguage class
 */
class CompilerLanguage
{
    /**
     * Instance of Compiler class
     *
     * @var object
     */
    public $compiler;

    /**
     * _
     *
     * @param array $params
     *
     * @return string
     */
    public function _($params)
    {
        return "<?php echo \$this->language->_('{$params[0]}', '{$params[1]}'); ?>";
    }
}

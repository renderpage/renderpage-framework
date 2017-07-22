<?php

/**
 * Project: RenderPage
 * File:    Minify.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is Minify class
 */
class Minify {

    /**
     * Minify
     *
     * @param string $input
     *
     * @return string
     */
    public static function minifyPhp(string $input): string {
        /**
         * \R - line break: matches \n, \r and \r\n
         * \s - any whitespace character
         */
        return preg_replace(['/>\R[\s]+</', '/>\R</', '/ \?><\?php /'], ['><', '><', ' '], $input);
    }

}

<?php
/**
 * Project: RenderPage
 * File:    Request.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is Request class
 */
class Request
{
    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * Get url path
     *
     * @return string
     */
    public function getUrlPath()
    {
        // Remove query
        $urlPath = explode('?', $_SERVER['REQUEST_URI']);
        $urlPath = array_shift($urlPath);

        // Remove duplicate slashes
        $urlPath = '/' . implode('/', array_diff(explode('/', $urlPath), ['']));

        return urldecode($urlPath);
    }

    /**
     * Is XMLHttpRequest request
     *
     * @return boolean
     */
    public function getIsAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}

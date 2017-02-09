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
class Request {

    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * <<magic>> Getter.
     *
     * @param string $name property name
     *
     * @return mixed
     */
    public function __get(string $name) {
        $method = "get{$name}";
        return $this->{$method}();
    }

    /**
     * Uniform Resource Identifier
     * @return mixed
     */
    public function getRequestUri() {
        return filter_input(INPUT_SERVER, 'REQUEST_URI');
    }

    /**
     * Get url path
     *
     * @return string
     */
    public function getUrlPath() {
        // Remove query
        $urlPath = current(explode('?', $this->requestUri));

        // Remove duplicate slashes
        $urlPath = '/' . implode('/', array_diff(explode('/', $urlPath), ['']));

        return urldecode($urlPath);
    }

    /**
     * Is a POST request
     * @return boolean
     */
    public function getIsPost() {
        $requestMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        return $requestMethod === 'POST' ? true : false;
    }

    /**
     * Is XMLHttpRequest request
     *
     * @return boolean
     */
    public function getIsAjax() {
        $xRequestedWith = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');
        return $xRequestedWith === 'XMLHttpRequest';
    }

}

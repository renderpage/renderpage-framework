<?php

/*
 * The MIT License
 *
 * Copyright 2018 Sergey Pershin <sergey dot pershin at hotmail dot com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

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
 * The Request class (HTTP request).
 *
 * @property string $gTLD The generic Top-Level Domain.
 * @property string $host The HTTP host.
 * @property bool $isAjax This is an AJAX (XMLHttpRequest) request.
 * @property bool $isPost This is a POST request.
 * @property string $uri REQUEST_URI.
 * @property string $urlPath The path from $uri.
 */
final class Request {

    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * Getter
     *
     * @param string $name The property name.
     *
     * @return mixed
     */
    public function __get(string $name) {
        $methodName = 'get' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }
    }

    /**
     * Gets generic Top-Level Domain
     *
     * @return string
     */
    public function getGTLD(): string {
        return substr(strrchr($this->host, '.'), 1);
    }

    /**
     * HTTP host
     *
     * @return string
     */
    public function getHost(): string {
        return (string) filter_input(INPUT_SERVER, 'HTTP_HOST');
    }

    /**
     * Is XMLHttpRequest request
     *
     * @return bool
     */
    public function getIsAjax(): bool {
        $xRequestedWith = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');
        return 'XMLHttpRequest' === $xRequestedWith ? true : false;
    }

    /**
     * Is a POST request
     *
     * @return bool
     */
    public function getIsPost(): bool {
        $requestMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        return 'POST' === $requestMethod ? true : false;
    }

    /**
     * Uniform Resource Identifier
     *
     * @return string
     */
    public function getUri(): string {
        return (string) filter_input(INPUT_SERVER, 'REQUEST_URI');
    }

    /**
     * Gets the URL path
     *
     * @return string
     */
    public function getUrlPath() {
        // Remove query
        $urlPath = current(explode('?', $this->uri));

        // Remove duplicate slashes
        $urlPath = '/' . implode('/', array_diff(explode('/', $urlPath), ['']));

        return urldecode($urlPath);
    }

}

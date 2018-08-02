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

namespace renderpage\libs;

/**
 * The Response class (HTTP response).
 *
 * @author Sergey Pershin <sergey dot pershin at hotmail dot com>
 */
final class Response {

    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * The HTTP response code.
     *
     * @var int
     */
    public $code = 200;

    /**
     * The response content.
     *
     * @var string
     */
    public $content = '';

    /**
     * The original response data.
     *
     * @var string|array|object|bool
     */
    public $data = false;

    /**
     * Redirect to the specified URL.
     *
     * @param string $location The URL.
     * @param int $httpResponseCode The response code.
     *
     * @return string
     */
    public function redirect(string $location, int $httpResponseCode = 302) {
        $this->code = $httpResponseCode;
        header('Location: ' . $location);
    }

    /**
     * Sends the response to the client.
     */
    public function send() {
        if (is_string($this->data)) {
            $this->content = $this->data;
        } elseif (is_array($this->data) || is_object($this->data)) {
            header('Content-Type: application/json; charset=UTF-8');
            $this->content = json_encode($this->data);
        }
        http_response_code($this->code);
        echo $this->content;
    }

}

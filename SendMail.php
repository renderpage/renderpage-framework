<?php

/**
 * Project: RenderPage
 * File:    SendMail.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is SendMail class
 */
class SendMail {

    /**
     * Receiver of the mail.
     *
     * @var string $to
     */
    public $to = '';

    /**
     * Sender of the mail.
     *
     * @var string $to
     */
    public $from = '';

    /**
     * Subject of the email to be sent.
     *
     * @var string $subject
     */
    public $subject = '';

    /**
     * Template name of the email to be sent.
     *
     * @var string $template
     */
    public $template;

    /**
     * Additional headers.
     *
     * @var string $headers
     */
    private $headers;

    /**
     * Init
     */
    public function __construct() {
        $this->from = strtoupper($_SERVER['HTTP_HOST']) . '<noreply@' . $_SERVER['HTTP_HOST'] . '>';
    }

    /**
     * Add additional header.
     *
     * @param string $header
     */
    public function addHeader(string $header) {
        $this->headers .= $header . "\r\n";
    }

    /**
     * Send email
     *
     * @return boolean
     */
    public function send() {
        $this->addHeader('MIME-Version: 1.0');
        $this->addHeader('Content-Type: text/html; charset=' . RenderPage::$charset);
        $this->addHeader("To: {$this->to}");
        $this->addHeader("From: {$this->from}");
        $message = (new View)->render($this->template, false);
        return mail($this->to, $this->subject, $message, $this->headers);
    }

}

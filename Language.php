<?php
/**
 * Project: RenderPage
 * File:    Language.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

require_once 'traits/Singleton.php';

/**
 * This is Language class
 */
class Language
{
    /**
     * Singleton trait
     */
    use traits\Singleton;

    /**
     * Language code
     *
     * @var string
     */
    public $code = 'en-us';

    /**
     * Strings
     *
     * @var array
     */
    private $strings;

    /**
     * Load XML file
     *
     * @param string $category
     */
    private function loadFile($category)
    {
        $filename = APP_DIR . "/languages/{$this->code}/{$category}.xml";
        if (file_exists($filename)) {
            $xml = simplexml_load_file($filename);

            foreach ($xml->children() as $child) {
                $attributes = $child->attributes();
                $this->strings[$this->code][$category][(string)$attributes->name] = $child[0];
            }
        } else {
            $this->strings[$this->code][$category] = [];
        }
    }

    /**
     * Set current language
     *
     * @param string $code
     */
    public function setCurrentLanguage($code)
    {
        $this->code = $code;
    }

    /**
     * Get text
     *
     * @param string $category
     * @param string $str
     *
     * @return string
     */
    public function _($category, $str)
    {
        if (empty($this->strings[$this->code][$category])) {
            $this->loadFile($category);
        }

        if (!empty($this->strings[$this->code][$category][$str])) {
            return $this->strings[$this->code][$category][$str];
        }

        return $str;
    }
}

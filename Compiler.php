<?php

/**
 * Project: RenderPage
 * File:    Compiler.php
 *
 * @link    http://www.renderpage.org/
 * @author  Sergey Pershin <sergey dot pershin at hotmail dot com>
 * @package RenderPage
 * @version 1.0.0
 */

namespace renderpage\libs;

/**
 * This is Compiler class
 */
class Compiler {

    /**
     * Left-delimiter
     *
     * @var string
     */
    public $leftDelimiter = '{';

    /**
     * Right-delimiter
     *
     * @var string
     */
    public $rightDelimiter = '}';

    /**
     * Tpl files
     *
     * @var array
     */
    public $files = [];

    /**
     * Instructions
     *
     * @var array
     */
    public $instructions = [];

    /**
     * Class instance
     *
     * @var array
     */
    private $classInst = [];

    /**
     * Instance of View class
     *
     * @var object
     */
    private $view;

    /**
     * Init
     *
     * @param \renderpage\libs\View $view
     */
    public function __construct(View $view = NULL) {
        $this->view = $view;
    }

    /**
     * Write compile file
     *
     * @param string $filename pattern for replace
     * @param string $data
     *
     * @return int
     */
    public function writeFile(string $filename, string $data) {
        $dir = dirname($filename);
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        if (is_writable($dir)) {
            return file_put_contents($filename, $data, LOCK_EX);
        } else {
            throw new RenderPageException(0, "Write error: {$dir} is not writable", __FILE__, __LINE__);
        }

        return 0;
    }

    /**
     * Compile variable
     *
     * @param string $name
     *
     * @return string
     */
    public function getVariable(string $name): string {
        return '$this->variables[\'' . str_replace(['$', '.'], ['', "']['"], $name) . '\']';
    }

    /**
     * Parse expr
     *
     * @param string $expr
     *
     * @return array
     */
    public function parseExpr(string $expr) {
        $aExpr = array_diff(preg_split("/ |\t|\n/", $expr), ['']);
        $result['name'] = array_shift($aExpr);
        $result['params'] = $aExpr;

        // workarea
        if ($result['name'] == 'workarea') {
            $result['inc'] = $this->parse($this->files['template']['filename']);
        }

        // include
        if ($result['name'] == 'include') {
            $includeFilename = APP_DIR . '/' . $this->view->templateDir . '/' . $result['params'][0];
            $this->files[] = [
                'filename' => $includeFilename,
                'modified' => filemtime($includeFilename)
            ];
            $result['inc'] = $this->parse($includeFilename);
        }

        // echo
        if ($result['name'][0] == '$') {
            $result['params'] = [$result['name']];
            $result['name'] = 'echo';
        }

        // language
        if ($result['name'][0] == '"') {
            $result['params'] = explode('.', trim($result['name'], '"'));
            $result['name'] = 'language';
        }

        return $result;
    }

    /**
     * Parse
     *
     * @param string $filename file name
     *
     * @return array
     */
    public function parse(string $filename) {
        $result = [];
        $buffer = '';

        $fp = fopen($filename, 'r');
        $line = 1;
        while (($char = fgetc($fp)) !== false) {
            if ($char == PHP_EOL) {
                ++$line;
            }
            switch ($char) {
                case $this->leftDelimiter:
                    $result[] = [
                        'filename' => $filename,
                        'line' => $line,
                        'type' => 'raw',
                        'data' => $buffer
                    ];
                    $buffer = '';
                    break;
                case $this->rightDelimiter:
                    $result[] = [
                        'filename' => $filename,
                        'line' => $line,
                        'type' => 'expr',
                        'expr' => $this->parseExpr($buffer)
                    ];
                    $buffer = '';
                    break;
                default:
                    $buffer .= $char;
            }
        }
        fclose($fp);

        if ($buffer != '') {
            $result[] = [
                'filename' => $filename,
                'line' => $line,
                'type' => 'raw',
                'data' => $buffer
            ];
        }

        return $result;
    }

    /**
     * Code generation
     *
     * @param array $parseTree
     *
     * @return string
     */
    public function codeGeneration(array $parseTree): string {
        $result = '';

        foreach ($parseTree as $foo) {
            switch ($foo['type']) {
                case 'raw':
                    $result .= $foo['data'];
                    break;
                case 'expr':
                    if (!empty($this->instructions[$foo['expr']['name']])) {
                        $className = $this->instructions[$foo['expr']['name']]['className'];
                        $method = $this->instructions[$foo['expr']['name']]['method'];

                        // Init class
                        if (!isset($this->classInst[$className])) {
                            $this->classInst[$className] = new $className;
                            $this->classInst[$className]->compiler = $this;
                        }

                        $this->classInst[$className]->filename = $foo['filename'];
                        $this->classInst[$className]->line = $foo['line'];

                        $result .= $this->classInst[$className]->$method($foo['expr']['params']);
                    }

                    if (!empty($foo['expr']['inc'])) {
                        // Recursion
                        $result .= $this->codeGeneration($foo['expr']['inc']);
                    }
                    break;
            }
        }

        return $result;
    }

    /**
     * Code optimization
     * @param string $data
     *
     * @return string
     */
    public function optimization(string $data): string {
        require_once RENDERPAGE_DIR . '/Minify.php';
        return Minify::minifyPhp($data);
    }

    /**
     * Compile
     *
     * @param string $template template name
     * @param string|boolean $layout layout template name
     * @param string $compileFilename
     *
     * @return int
     */
    public function compile(string $template, $layout, string $compileFilename): int {
        // Load instructions
        $this->instructions = require_once RENDERPAGE_DIR . '/compiler/instructions.php';

        // Remove old compile files
        $this->view->clearCompiledTemplate($template);

        $templateFilename = $this->view->getTemplateFilename($template);
        if (!$templateFilename) {
            throw new CompilerException('Template "' . $template . '" not found');
        }
        $this->files['template'] = [
            'filename' => $templateFilename,
            'modified' => filemtime($templateFilename)
        ];

        if ($layout) {
            $layoutFilename = $this->view->getLayoutFilename($layout);
            if (!$layoutFilename) {
                throw new CompilerException('Layout "' . $layout . '" not found');
            }
            $this->files['layout'] = [
                'filename' => $layoutFilename,
                'modified' => filemtime($layoutFilename)
            ];
            $parseFilename = &$layoutFilename;
        } else {
            $parseFilename = &$templateFilename;
        }

        // Parse
        $parseTree = $this->parse($parseFilename);

        // Code generation
        $code = $this->codeGeneration($parseTree);

        // Add compile comment
        $data = '<?php /* RenderPage version: ' .
                RenderPage::RENDERPAGE_VERSION . ', created on ' .
                date('c') . ' */' . PHP_EOL;

        $data .= 'if ($this->getFiles) { return ' . var_export($this->files, true) . '; } ?>';

        $data .= $code . PHP_EOL;

        // Code optimization
        $data = $this->optimization($data);

        // Write compile file
        return $this->writeFile($compileFilename, $data);
    }

    /**
     * Compile languages
     *
     * @param string $compileFilename
     */
    public function compileLanguages(string $compileFilename) {
        $strings = [];

        foreach (glob(APP_DIR . '/languages/*/*.xml') as $filename) {
            $code = basename(dirname($filename));
            $category = basename($filename, ".xml");

            $xml = simplexml_load_file($filename);

            foreach ($xml->children() as $child) {
                $attributes = $child->attributes();
                $strings[$code][$category][(string) $attributes->name] = (string) $child[0];
            }

            $data = '<?php return ' . var_export($strings, true) . ';' . PHP_EOL;

            // Write compile file
            $this->writeFile($compileFilename, $data);
        }
    }

}

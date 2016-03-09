<?php
/**
 * Lang
 *
 * @param array $params
 *
 * @return string
 */
function RPCompileLang($params)
{
    $output = "<?php echo \$this->language->_('{$params[3]}', '{$params[4]}'); ?>";
    return $output;
}

$this->addReplaceInstruction('["|\'](.*?)\.(.*?)["|\']', 'RPCompileLang');


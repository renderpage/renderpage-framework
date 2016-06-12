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
    return "<?php echo \$this->language->_('{$params[3]}', '{$params[4]}'); ?>";
}

$this->addReplaceInstruction('["|\'](.*?)\.(.*?)["|\']', 'RPCompileLang');

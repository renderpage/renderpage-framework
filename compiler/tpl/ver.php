<?php
/**
 * Echo
 *
 * @param array $params
 *
 * @return string
 */
function RPCompileVersion($params)
{
    return $params[1] . renderpage\libs\RenderPage::RENDERPAGE_VERSION;
}

$this->addReplaceInstruction('#VERSION', 'RPCompileVersion');

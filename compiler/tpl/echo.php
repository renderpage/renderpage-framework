<?php
/**
 * Echo
 *
 * @param array $params
 *
 * @return string
 */
function RPCompileEcho($params)
{
    $varName = RPGetCompileVarName($params[3]);
    $output = '<?php ';
    $output .= "if (!empty({$varName})) { ";
    $output .= "echo {$varName}; } ?>";
    return $output;
}

$this->addReplaceInstruction('\$(.*?)', 'RPCompileEcho');

<?php
/**
 * If
 *
 * @param array $params
 *
 * @return string
 */
function RPCompileIf($params)
{
    $varName = RPGetCompileVarName($params[3]);
    $output = '<?php ';
    $output .= "if (!empty({$varName}) && {$varName}) { ?>";
    return $output;
}

/**
 * Endif
 *
 * @param array $params
 *
 * @return string
 */
function RPCompileEndif($params)
{
    $output = '<?php } ?>';
    return $output;
}

$this->addReplaceInstruction('if \$(.*?)', 'RPCompileIf');
$this->addReplaceInstruction('\/if', 'RPCompileEndif');

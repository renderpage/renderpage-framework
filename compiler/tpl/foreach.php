<?php
/**
 * Foreach
 *
 * @param array $params
 *
 * @return string
 */
function RPCompileForeach($params)
{
    $arrayVarName = RPGetCompileVarName($params[3]);
    $output = '<?php ';
    $output .= "if (!empty({$arrayVarName}) && is_array({$arrayVarName})) { ";
    $output .= "foreach ({$arrayVarName} as ";
    $output .= RPGetCompileVarName($params[4]);
    $output .= ') { ?>';
    $output .= preg_replace('!^!m', $params[1], ltrim($params[5]));
    $output .= '<?php } } ?>';
    return $output;
}

$this->addReplaceInstruction('foreach \$(.*?) as \$(.*?)\}(.+?)(\t+| +|)\{\/foreach', 'RPCompileForeach');

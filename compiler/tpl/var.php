<?php
/**
 * Get compile variable name
 *
 * @param string $name variable name
 *
 * @return string
 */
function RPGetCompileVarName($name)
{
    $name = str_replace('.', "']['", $name);
    return "\$this->variables['{$name}']";
}

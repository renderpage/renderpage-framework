<?php

return $instructions = [
    'echo' => [
        'className' => '\renderpage\libs\compiler\CompilerEcho',
        'method' => 'openTag'
    ],
    'if' => [
        'className' => '\renderpage\libs\compiler\CompilerIf',
        'method' => 'openTag'
    ],
    'else' => [
        'className' => '\renderpage\libs\compiler\CompilerIf',
        'method' => 'elseTag'
    ],
    '/if' => [
        'className' => '\renderpage\libs\compiler\CompilerIf',
        'method' => 'closeTag'
    ],
    'foreach' => [
        'className' => '\renderpage\libs\compiler\CompilerForeach',
        'method' => 'openTag'
    ],
    '/foreach' => [
        'className' => '\renderpage\libs\compiler\CompilerForeach',
        'method' => 'closeTag'
    ],
    'language' => [
        'className' => '\renderpage\libs\compiler\CompilerLanguage',
        'method' => '_'
    ],
    '#VERSION' => [
        'className' => '\renderpage\libs\compiler\CompilerVersion',
        'method' => 'getVersion'
    ]
];

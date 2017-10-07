<?php

return $instructions = [
    'echo' => [
        'className' => '\renderpage\libs\compiler\tags\EchoTag',
        'method' => 'openTag'
    ],
    'if' => [
        'className' => '\renderpage\libs\compiler\tags\IfTag',
        'method' => 'openTag'
    ],
    'else' => [
        'className' => '\renderpage\libs\compiler\tags\IfTag',
        'method' => 'elseTag'
    ],
    '/if' => [
        'className' => '\renderpage\libs\compiler\tags\IfTag',
        'method' => 'closeTag'
    ],
    'foreach' => [
        'className' => '\renderpage\libs\compiler\tags\ForeachTag',
        'method' => 'openTag'
    ],
    '/foreach' => [
        'className' => '\renderpage\libs\compiler\tags\ForeachTag',
        'method' => 'closeTag'
    ],
    'language' => [
        'className' => '\renderpage\libs\compiler\tags\LanguageTag',
        'method' => 'openTag'
    ],
    '#VERSION' => [
        'className' => '\renderpage\libs\compiler\tags\VersionTag',
        'method' => 'openTag'
    ]
];

<?php

$config = [
    "core" => [
        "router" => [
            "class" => "\vole\Router",
        ]
    ],
    "routes" => [
        "index" => "site/index",    //  @note [URl]/index => controller/Site.php->actionIndex
    ],
    "extensions" => [
        
    ]
];

return $config;
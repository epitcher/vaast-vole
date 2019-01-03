<?php

$config = [
    "assets" => [
        "/web",
    ],
    "error" => "site/error",
    "core" => [
        "router" => [
            "class" => "\\vole\\Router",
        ]
    ],
    "routes" => [
        "/" => "site/index",
        "/index" => "site/index",    //  @note [URl]/index => controller/Site.php->actionIndex
    ],
    "extensions" => [
        
    ]
];

return $config;
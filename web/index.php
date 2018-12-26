<?php

define( "VOLE_CONSOLE", FALSE );
define( "VOLE_ROOT", __DIR__ . "//..//" );

require( VOLE_ROOT . "vaast/vole/Application.php" );

$config = require VOLE_ROOT . "config/config.php";

( new vole\web\Application( $config ) )->run();
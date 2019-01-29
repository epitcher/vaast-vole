<?php

namespace vole\web;

use Vole;
use Exception;
use \app\controllers;

require __DIR__ . "/src/Vole.php";

global $argv;

class Application
{
    public function __construct( $config=NULL )
    {
        if( is_null( $config ) ) { throw new Exception("No configuration provided..."); }
        Vole::$app = (object)[];
        Vole::$system = (object)[];

        Vole::$system->Console = VOLE_CONSOLE;
        Vole::$system->Config = json_decode( json_encode( $config, FALSE ) );
    }

    public function run()
    {
        $this->setup();
        $this->loadBase();
        $this->loadCore();
        $this->loadExtensions();
        $this->route();
    }

    //  @important Intiail pass over route- Determines where route is sourced from.
    private function setup()
    {
        if( Vole::$system->Console )
        {
            $this->setup_cli();
        } else {
            $this->setup_cgi();
        }
    }

    private function setup_cli()
    {
        global $argv;
        Vole::$system->Controllers = "console";
        Vole::SetRoute( $argv[1] );
        Vole::$system->Params = array_slice( $argv, 2, count( $argv ) - 2 );
    }

    private function setup_cgi()
    {
        Vole::$system->Controllers = "site";
        Vole::SetRoute( "/" . substr(
            $_SERVER["REQUEST_URI"],
            1,
            strlen( $_SERVER["REQUEST_URI"] )
        ) );
    }

    //  @note Load all required source files.
    //  @todo Add config field to build atop loading source. Will allow devs to build directly ontop of frame.
    private function loadBase()
    {
        require_all( __DIR__ . "//base//" );
    }
    private function loadCore()
    {
        require_all( __DIR__ . "//core//" );
    }
    private function loadExtensions()
    {
        //  @important Does not take into account Extensions being configured but not existing.
        foreach( Vole::$system->Config->extensions as $extension => $obj )
        {
            $file = VOLE_ROOT . "extensions//" . $extension . ".php";
            if( !is_file( $file ) ) { continue; }
            require( $file );
            $f = "\\app\\Extension\\" . $extension;
            Vole::$app->{$extension} = new $f;
        }
    }

    private function route()
    {
        Vole::Run();
    }

}
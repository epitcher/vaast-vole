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
        // $this->loadController();
        $this->route();
        //  @important "route" will exit process unless path is not found
        //  @important route failure now handled in Router.
        // $this->routeFailure();
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
        Vole::$system->Route = $argv[1];
        Vole::$system->Params = array_slice( $argv, 2, count( $argv ) - 2 );
    }

    private function setup_cgi()
    {
        Vole::$system->Controllers = "site";
        Vole::$system->Route = substr(
            $_SERVER["REQUEST_URI"],
            1,
            strlen( $_SERVER["REQUEST_URI"] )
        );
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

    /**
     *  Auto-determines the required controller based off Application route.
     */
    private function loadController()
    {
        $breaker = strpos( Vole::$system->Route, "/" ); 
        Vole::$system->Controller 
            = ucwords( 
                substr( Vole::$system->Route, 0, $breaker )
            );
        Vole::$system->Action 
            = ucwords( 
                substr( 
                    Vole::$system->Route, $breaker + 1,
                    strlen( Vole::$system->Route ) - $breaker
                )
            );
        $controller = VOLE_ROOT
        . Vole::$system->Controllers . "/" 
        . Vole::$system->Controller . ".php";
        //  @note Throw 404ish like error here.
        //  @note Cannot throw 404 as router ( core is loaded, custom may not be ) not yet loaded.
        if( !is_file( $controller ) ) { throw new Exception("Controller does not exist"); }
        require( $controller );
    }

    //  @important ALl controllers will not be loaded by default. Only controller requested.
    //  @note Load all files within __ROOT__/controllers
    private function loadAllControllers()
    {
        $files = scandir_filter(
            VOLE_ROOT . Vole::$system->Controllers . "/", SCANDIR_FILE
        );
        foreach( $files as $file )
        {
            require( VOLE_ROOT . Vole::$system->Controllers . "/" . $file );
        }
    }
    
    private function routeFind()
    {
        $files = scandir_filter(
            VOLE_ROOT . Vole::$system->Controllers . "/", SCANDIR_FILE
        );
        foreach( $files as $controllers )
        {
            $methods = get_class_methods( 
                "\\app\\" . Vole::$system->Controllers . "\\" . pathinfo( $controllers )["filename"]
            );
            //  @note Filter out all non "action" prefixed methods.
            $methods = array_filter( $methods, function( $val ) {
                if( strlen( $val ) > 5 )
                {
                    if( substr( $val, 0, 6 ) != "action" )
                    {
                        return FALSE;
                    } else {
                        return TRUE;
                    }
                }
            } );
        }
    }

    private function route()
    {
        Vole::Run();
    }
/*
    private function route()
    {
        require_once(
            VOLE_ROOT . Vole::$system->Controllers . "/" . Vole::$system->Controller . ".php"
        );
        $f= "\\app\\" . Vole::$system->Controllers . "\\" . Vole::$system->Controller;
        $class = new $f;
        
        //  @note This is not ready for controller level redirects.
        //  @note Controller level redirects could be built into Controller obj

        ob_start();
        ob_end_clean();
        ob_start();
        echo $class->{ "action" . Vole::$system->Action }( ...Vole::$system->Params );
        ob_flush();

        if( Vole::$system->Console ) { endln(); }
        exit;
    }
*/

    private function routeFailure()
    {
        println( "No route could be found..." );
    }

}
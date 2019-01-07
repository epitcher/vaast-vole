<?php

namespace vole;

use Vole;
use vole\BaseRouter;

class Router extends BaseRouter
{

    public function __construct()
    {

    }
    public static function Error( $error=404 )
    {
        self::_doError();
    }
    public function Route()
    {
        if( is_null( Vole::$system->Route ) ) { throw new Exception("Error Processing Request", 1); }
        if( $this->_isAsset() )
        {
            return $this->_doAsset();
        }
        else if( $this->_isRoute() )
        {
            return $this->_doRoute();
        }
        else
        {
            return $this->_doError();
        }
    }

    //  @section Assets
    //  @note Does not check if asset exists, only checks if route conforms to asset form.
    private function _isAsset()
    {
        if( is_file( VOLE_ROOT . "//web//" . Vole::$system->Route ) )
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    private function _doAsset()
    {
        $file = VOLE_ROOT . "//web//" . Vole::$system->Route;
        if( is_file( $file ) )
        {
            header( 'Content-Type: ' . mime_content_type( $file ) );
            return file_get_contents( $file );
        }
        $this->_doError();
    }

    //  @section Routes
    private function _isRoute()
    {
        //  @note The problem here is the route currently drops the root forward slash.
        foreach( Vole::$system->Config->routes as $map => $route )
        {
            //  @important Only an exact match will map
            if( "/" . Vole::$system->Route == $map )
            {
                Vole::SetRoute( $route );
            }
        }
        // if( property_exists( Vole::$system->Config->routes, Vole::$system->Route ) )
        // {
        //     println( Vole::$system->Route );
        //     Vole::SetRoute( Vole::$system->Config->routes[ Vole::$system->Route ] );
        // }
        Vole::SetRoute( Vole::$system->Route );
        if( 
            !is_file( 
                VOLE_ROOT
                . "//" . Vole::$system->Controllers
                . "//" . Vole::$system->Controller
                . ".php" 
                ) 
            )
        {
            return FALSE;
        }
        $this->_loadRoute();
        $methods = get_class_methods( 
            "\\app\\" . Vole::$system->Controllers . "\\" . Vole::$system->Controller
        );
        $actionLocated = FALSE;
        foreach( $methods as $method )
        {
            if( substr( $method, 0, 6 ) != "action" ) { continue; }
            if( substr( $method, 6 ) == Vole::$system->Action )
            {
                $actionLocated = TRUE;
            }
        }
        return $actionLocated;
    }
    private function _loadRoute()
    {
        //  @note I don't want a require_one, change this
        require_once( 
            VOLE_ROOT
            . "/" . Vole::$system->Controllers
            . "/" . Vole::$system->Controller
            . ".php"  
        );   
    }
    //  @todo Route must be able to follow through other routes. E.G site/logout -> site/login
    private function _doRoute()
    {
        $f= "\\app\\" . Vole::$system->Controllers . "\\" . Vole::$system->Controller;
        $class = new $f;
        return $class->{ "action" . Vole::$system->Action }( ...Vole::$system->Params );
    }

    //  @section Errors
    private static function _doError( $code=404 )
    {
        Vole::SetRoute( Vole::$system->Config->error );
        return Vole::Run();
    }

}
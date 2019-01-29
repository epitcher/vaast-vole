<?php

namespace vole;

use Vole;
use vole\BaseRouter;

class Router extends BaseRouter
{

    public function __construct()
    {
        Vole::$system->Params = [];
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
    private function _parseRoute()
    {
        //  @important For rapid development leave simple routing to string comparison, move to array comp if variables found.
        //  @note This checks for exact route matches.
        foreach( Vole::$system->Config->routes as $map => $route )
        {
            if( Vole::$system->Route == $map )
            {
                Vole::SetRoute( $route );
                //  @note If a route is found ends method execution.
                return;
            }
        }

        //  @note If no route match has been found move onto variable search.
        
        $inputStack = explode( "/", Vole::$system->Route );
        //  @note Route stack must be loaded, then overriden.
        $routeStack = explode( "/", $map );
        $varStack = [];

        foreach( $routeStack as $index => $part )
        {
            if( !array_key_exists( $index, $inputStack ) ) { continue; }
            $is_var=[];
            preg_match( "/{{(.*?)}}/", $part, $is_var );
            if( count( $is_var ) == 0 ) { continue; }
            array_push( $varStack, $inputStack[ $index ] );
            
            //  @important This should be a temp fix.
            if( is_null( $inputStack[ $index ] ) || !(strlen( $inputStack[ $index ] ) > 0) ) { continue; }
            $inputStack[ $index ] = "###";
        }
        //  @note Proceed with array comparison matching at this point ( <###> to be treated as wildcard )

        //  @note Cycle through all configured routes.
        foreach( Vole::$system->Config->routes as $map => $route )
        {
            $tempStack = explode( "/", $map );

            $failure = FALSE;

            //  @note This loop is to compare is route components are equalised.
            foreach( $tempStack as $index => $val )
            {
                if( !array_key_exists( $index, $inputStack ) ) { continue; }
                if( count( $inputStack ) != count( $tempStack ) ) { $failure = TRUE; }
                if( 
                    ($val != $inputStack[ $index ])
                    &&
                    ($inputStack[ $index ] != "###") 
                )
                {
                    $failure = TRUE;
                }

            }

            if( $failure ) { continue; }

            foreach( array_reverse( $varStack ) as $var )
            {
                array_unshift( Vole::$system->Params, $var );   
            }

            Vole::SetRoute( $route );

            return;
        }


        return;
    }
    private function _isRoute()
    {
        Vole::$system->Uri = Vole::$system->Route;
        $this->_parseRoute();

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
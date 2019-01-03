<?php

require __DIR__ . '/BaseVole.php';

class Vole extends \vole\BaseVole
{
    //
    ////    PUBLIC
    public static function Redirect( $route=NULL )
    {
        if( is_null( $route ) ) { throw new Exception( "Attempt to set route to NULL" ); }
        self::redirect( $route );
    }
    public static function SetRoute( $route=NULL )
    {
        if( is_null( $route ) ) { throw new Exception( "Attempt to set route to NULL" ); }
        self::_setRoute( $route );
    }
    public static function Run()
    {
        return self::_run();
    }

    //
    ////    PRIVATE
    private static function _redirect( $route )
    {
        Vole::SetRoute( $route );
        return Vole::Run();
    }
    private static function _setRoute( $route )
    {
        Vole::$system->Route = $route;
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
    }
    private static function _run()
    {
        ob_start();
        ob_end_clean();
        ob_start();
        echo ( ( new Vole::$system->Config->core->router->class )
            ->Route() );
        ob_flush();
        exit;
    }
    //  @note The controller corresponding to the route must be loaded when route is set.
    //  @note Route cannot be loaded at time it is set, may be route, asset or error.
}

define("SCANDIR_ALL", 0);
define("SCANDIR_SHORT", 1);
define("SCANDIR_FILE", 2);
define("SCANDIR_DIR", 3);

//DEV START
function endln() { if( !VOLE_CONSOLE ) { echo "<br>"; } else { echo"\n"; } }
function println($pre) { echo $pre; endln(); }
//DEV END

/**
 *  @function scandir_file
 *  @param $path : string : Directory to scan
 *  @return array : Files found
 *  
 *  Scans directory for all files. ( Will not list subdirectories )
 */
function scandir_file( $path=NULL )
{
    return scandir_filter( $path, NULL, SCANDIR_FILE );
}

/**
 *  @function scandir_dir
 *  @param $path : string : Directory to scan
 *  @return array : Files found
 * 
 *  Scans directory for all available subdirectories. ( Will not return files )
 */
function scandir_dir( $path=NULL )
{
    return scandir_filter( $path, NULL, SCANDIR_DIR );
}

/**
 *  @function scandir_filter
 *  @param $path : string : Directory to scan
 *  @param $search : string : Substring to search for ( Case sensetive )
 *  @param $mode : int : Type of item to search.
 *  @return array : Files found
 * 
 *  Scans directory and returns based off criteria. 
 */
function scandir_filter( $path=NULL, $search=NULL, $mode=SCANDIR_ALL )
{
    if( is_null( $path ) ) { throw new Exception( "Cannot scan provided path, NULL given..." ); }
    $files = array_diff(scandir($path), array('.', '..'));
    foreach( $files as $file=>$key )
    {
        if( is_dir( $file ) ) { unset( $files[$key] ); }
    }

    return array_values( $files );
}

function require_all( $dir=NULL )
{
    if( is_null( $dir ) ) { throw new Exception( "Cannot require directory NULL" ); }
    if( !is_dir( $dir ) ) { throw new Exception( "Cannot require, directory does not exist..." );  }
    $files = scandir_file( $dir );
    foreach( $files as $file )
    {
        require( $dir . $file );
    }
}
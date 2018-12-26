<?php

namespace vole;

class BaseObject {

    public function __set( $name, $value )
    {
        $f="set".ucword($name);
        if( function_exists( $f ) )
        { 
            $f($value);
        } else {
            $this->$name = $value;
        }
    }
    public function __get( $name )
    {
        $f="set".ucword($name);
        if( function_exists( $f ) )
        { 
            $f($value);
        } else {
            $this->$name = $value;
        }
    }

}
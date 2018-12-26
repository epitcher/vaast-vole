<?php

namespace app\console;

use vole\Controller;

class Utility extends Controller
{

    public function actionTest()
    {
        echo "This is a test";
    }
    public function actionTestParam( $param1, $param2, $param3 )
    {
        println( $param1 );
        println( $param2 );
        println( $param3 );
    }

}
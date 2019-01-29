<?php

namespace app\site;

use vole\Controller;

class Site extends Controller
{

    public function actionIndex()
    {
        return "Index page";
    }
    public function actionLogin()
    {
        return "Login page";
    }
    public function actionContact()
    {
        return "Contact page";
    }
    public function actionProduct( $productId=NULL )
    {
        if( is_null( $productId ) ) { return "No product could be found..."; }
        return "This is product : " . $productId;
    }
    public function actionMulti( $test1=NULL, $test2=NULL, $test3=NULL )
    {
        println( "$test1 $test2 $test3" );
        return "End of Test";
    }
    public function actionError()
    {
        return "This is an error page.";
    }

}
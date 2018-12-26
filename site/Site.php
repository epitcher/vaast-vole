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

}
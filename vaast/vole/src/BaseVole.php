<?php

namespace vole;

class BaseVole
{

    public static $app;
    public static $system;

    public function __construct()
    {
        $this->$app = new stdClass();
    }

}
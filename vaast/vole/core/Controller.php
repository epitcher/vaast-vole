<?php

namespace vole;

use vole\BaseController;

class Controller extends BaseController
{

    public function Render( $view, $params )
    {
        // Vole::$system->Layout;
        $this->_render( $view, $params );
    }

    public function Redirect( $action, $params )
    {
        $this->_redirect( $action, $params );
    }

    protected function _render( $view, $params )
    {

    }

    protected function _redirect( $action, $params )
    {

    }

}
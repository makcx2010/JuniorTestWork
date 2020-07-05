<?php

namespace application\core;

class View
{

    public $layout = 'default';
    public $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function renderPage($title, $vars = [])
    {
        $path = 'application/views/pages/' . $this->path . '.php';

        if (file_exists($path)) {
            ob_start();
            require $path;
            $content = ob_get_clean();

            require 'application/views/layouts/' . $this->layout . '.php';
        }
    }

}
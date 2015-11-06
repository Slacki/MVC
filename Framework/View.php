<?php

namespace Framework;

class View
{
    private $viewsDirectory;

    public function __construct()
    {
        $this->viewsDirectory = App::$app->config->applicationDirectory . '/views/';
    }

    public function render($view, $useLayout = true)
    {
        ob_start();
        if ($useLayout) {
            $this->_renderWithLayout($view);
        } else {
            require($this->viewsDirectory . $view . '.php');
        }
        ob_end_flush();
    }

    private function _renderWithLayout($view)
    {
        // primitive layout as header and footer files
        require($this->viewsDirectory . 'header.php');
        require($this->viewsDirectory . $view . '.php');
        require($this->viewsDirectory . 'footer.php');
    }
}
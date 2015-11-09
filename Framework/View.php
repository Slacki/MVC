<?php

namespace Framework;

class View
{
    public $viewsDirectory;
    private $_data = [];

    public function __construct()
    {
        $this->viewsDirectory = App::$app->config->applicationDirectory . '/views/';
    }

    public function render($view, $useLayout = true)
    {
        $viewPath = $this->viewsDirectory . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new \Exception('View ' . $view . ' does not exist.');
        }

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

    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }
    }

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }
}
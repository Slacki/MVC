<?php

namespace Framework;

/**
 * Class View
 * Used to rendering pages and passing data to views (templates)
 *
 * @package Framework
 */
class View
{
    /**
     * @var string $viewsDirectory determines where views' files are located
     */
    public $viewsDirectory;

    /**
     * @var array $_data Storage for data passed to views
     */
    private $_data = [];

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->viewsDirectory = App::$app->config->applicationDirectory . '/views/';
    }

    /**
     * Renders the page using buffer functions.
     *
     * @param string $view Path to view file, usually "controller/action", without expression
     * @param bool|true $useLayout Whether use layout for view
     * @throws \Exception When view file doesn't exist or couldn't be found
     */
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

    /**
     * Renders the page without layout
     *
     * @param string $view Path to view file
     */
    private function _renderWithLayout($view)
    {
        // primitive layout as header and footer files
        require($this->viewsDirectory . 'header.php');
        require($this->viewsDirectory . $view . '.php');
        require($this->viewsDirectory . 'footer.php');
    }

    /**
     * Retrieves data from view
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }
    }

    /**
     * Passes data to view
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }
}
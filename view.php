<?php
/**
 * Part of the Phrame
 *
 * @package    Core
 * @version    0.4.0
 * @author     Phrame Development Team
 * @license    MIT License
 * @copyright  2012 Phrame Development Team
 * @link       http://phrame.itworks.in.ua/
 */

namespace Phrame\Core;

class View
{
    /**
     * Application object
     * 
     * @var  Application
     */
    protected $app = null;

    /**
     * Asset object
     * 
     * @var  Asset
     */
    protected $asset = null;

    /**
     * Lang object
     * 
     * @var  Lang
     */
    protected $lang = null;

    /**
     * View name
     * 
     * @var  string
     */
    protected $view_name = null;

    /**
     * Data for view
     * 
     * @var  array
     */
    protected $data = array();

    /**
     * Creates View object
     * 
     * @param   string       $view_name  View name
     * @param   array        $data       Data for view
     * @param   Application  $app        Application object
     * @return  void
     */
    public function __construct($view_name, $data = array(), $app = null)
    {
        $this->view_name  = $view_name;
        $this->data       = $data;
        $this->app        = $app ?: Application::instance();
        $this->asset      = new Asset($this->app);
        $this->lang       = new Lang($this->app);
    }

    /**
     * Returns view data
     * 
     * @param   string  $name  Data name
     * @return  mixed
     */
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * Sets data for view
     * 
     * @param   string  $name   Data name
     * @param   mixed   $value  Data value
     * @return  void
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Renders view
     * 
     * @return  string
     */
    public function render()
    {
        foreach ($this->data as &$data)
        {
            if (is_string($data))
            {
                $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            }
        }

        extract($this->data, EXTR_REFS);

        ob_start();
        if (is_file(APPLICATIONS_PATH.'/'.$this->app->name.'/themes/'.$this->app->config->theme.'/'.$this->view_name.'.php'))
        {
            include APPLICATIONS_PATH.'/'.$this->app->name.'/themes/'.$this->app->config->theme.'/'.$this->view_name.'.php';
        }
        // checking system theme
        elseif (is_file(APPLICATIONS_PATH.'/'.$this->app->name.'/themes/system/'.$this->view_name.'.php'))
        {
            include APPLICATIONS_PATH.'/'.$this->app->name.'/themes/system/'.$this->view_name.'.php';
        }
        elseif (is_file(__DIR__.'/themes/system/'.$this->view_name.'.php'))
        {
            include __DIR__.'/themes/system/'.$this->view_name.'.php';
        }
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Converts view to string
     * 
     * @return  string
     */
    public function __toString()
    {
        return $this->render();
    }

}

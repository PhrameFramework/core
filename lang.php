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

/**
 * Lang class
 */
class Lang
{
    /**
     * Application name
     * 
     * @var  string
     */
    protected $app_name;

    /**
     * Lang configuration
     * 
     * @var  Config
     */
    protected $config;

    /**
     * Application language
     * 
     * @var  string
     */
    protected $language;

    /**
     * Default application language
     * 
     * @var  string
     */
    protected $default_language;

    /**
     * Translations
     * 
     * @var  string
     */
    protected $translations;

    /**
     * Default translations
     * 
     * @var  string
     */
    protected $default_translations;

    /**
     * Constructs Lang object
     * 
     * @param  string  $app_name  Application name
     */    
    public function __construct($app_name = null)
    {
        $this->app_name  = $app_name ?: APPLICATION_NAME;
        $this->config    = new Config('lang', $this->app_name);

        $this->language              = $this->config['language'] === 'auto' ? strtolower(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2)) : $this->config['language'];
        $this->default_language      = $this->config['default_language'];
        $this->translations          = isset($this->config['translations'][$this->language]) ? $this->config['translations'][$this->language] : array();
        $this->default_translations  = isset($this->config['translations'][$this->default_language]) ? $this->config['translations'][$this->default_language] : array();
    }

    /**
     * Returns translated string
     * 
     * @param   string  $str  String
     * @return  string
     */
    public function get($str)
    {
        return isset($this->translations[$str]) ? $this->translations[$str] : (isset($this->default_translations[$str]) ? $this->default_translations[$str] : $str);
    }

}

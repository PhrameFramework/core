<?php
/**
 * Part of the Phrame
 *
 * @package    Core
 * @version    0.0.0
 * @author     Phrame Development Team
 * @license    MIT License
 * @copyright  2012 Phrame Development Team
 * @link       http://phrame.itworks.in.ua/
 */

namespace Phrame\Core;

class Asset
{
    /**
     * Application object
     * 
     * @var  Application
     */
    protected $application = null;

    /**
     * Asset configuration
     * 
     * @var  Config
     */
    protected $config = null;

    /**
     * Asset file prefix
     * 
     * @var  string
     */
    protected $file_prefix = '';

    /**
     * Constructs Asset object
     * 
     * @param  Application  $application  Application object
     */    
    public function __construct($application = null)
    {
        $this->application  = $application ?: Application::instance();
        $this->config       = new Config('asset', $this->application);
        $this->file_prefix  = md5($this->application->name);
    }

    /**
     * Renders tags
     * 
     * @param   string  $file_name   Asset file name
     * @param   string  $asset_type  Asset type (img|css|js)
     * @param   array   $attributes  Tag attributes
     * @return  string
     */
    public function render_asset($file_name, $asset_type, $attributes = array())
    {
        $fn    = explode('/', $file_name);
        $file  = $this->file_prefix.'-'.array_pop($fn);
        $dir   = implode('/', $fn);
        $dir  .= ! empty($dir) ? '/' : '';

        $theme_file   = APPLICATIONS_PATH.'/'.$this->application->name.'/themes/'.$this->application->config->theme.'/assets/'.$asset_type.'/'.$file_name;
        $public_file  = PUBLIC_PATH.'/assets/'.$asset_type.'/'.$dir.$file;
        $public_url   = $this->application->config->base_url.'/assets/'.$asset_type.'/'.$dir.$file;

        if ( ! is_file($public_file) or filemtime($public_file) != filemtime($theme_file))
        {
            if ( ! is_dir(dirname($public_file)))
            {
                mkdir(dirname($public_file), 0777, true);
            }

            copy($theme_file, $public_file);
            touch($public_file, filemtime($theme_file));
        }

        if ($this->config->append_timestamp === true)
        {
            $public_url .= '?'.filemtime($public_file);
        }

        $attr = '';
        foreach($attributes as $name => $value)
        {
            $attr .= $name.'="'.$value.'" ';
        }

        $html = '';

        switch ($asset_type)
        {
            case ('img'):
            {
                $html = '<img src="'.$public_url.'" '.$attr.'/>';
                break;
            }
            case ('css'):
            {
                $html = '<link type="text/css" rel="stylesheet" href="'.$public_url.'" '.$attr.'/>';
                break;
            }
            case ('js'):
            {
                $html = '<script type="text/javascript" src="'.$public_url.'" '.$attr.'></script>';
                break;
            }
        }

        return $html;
    }

    /**
     * Image asset
     * 
     * @param   string  $file_name   File name
     * @param   array   $attributes  Tag attributes
     * @return  string
     */
    public function img($file_name, $attributes = array())
    {
        return $this->render_asset($file_name, 'img', $attributes);
    }

    /**
     * Style asset
     * 
     * @param   string  $file_name   File name
     * @param   array   $attributes  Tag attributes
     * @return  string
     */
    public function css($file_name, $attributes = array())
    {
        return $this->render_asset($file_name, 'css', $attributes);
    }

    /**
     * Script asset
     * 
     * @param   string  $file_name   File name
     * @param   array   $attributes  Tag attributes
     * @return  string
     */
    public function js($file_name, $attributes = array())
    {
        return $this->render_asset($file_name, 'js', $attributes);
    }

}

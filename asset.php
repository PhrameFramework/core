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
 * Asset class
 */
class Asset
{
    /**
     * Application name
     * 
     * @var  string
     */
    protected $app_name = null;

    /**
     * Asset configuration
     * 
     * @var  Config
     */
    protected $config = null;

    /**
     * Constructs Asset object
     * 
     * @param  string  $app_name  Application name
     */    
    public function __construct($app_name = null)
    {
        $this->app_name = $app_name ?: APPLICATION_NAME;
        $this->config   = new Config('asset', $this->app_name);
    }

    /**
     * Copies directory $from_path into $to_path
     *
     * @param  string  $from_path
     * @param  string  $to_path
     */
    protected function copy_dir($from_path, $to_path)
    {
        $from_path = rtrim($from_path, '/');
        $to_path   = rtrim($to_path, '/');

        if( ! is_dir($to_path))
        {
            mkdir($to_path, 0777, true);
        }

        if (is_dir($from_path))
        {
            chdir($from_path);
            $handle = opendir('.');
            while (($file = readdir($handle)) !== false)
            {
                if (($file != '.') and ($file != '..'))
                {
                    if (is_dir($file))
                    {
                        $this->copy_dir($from_path.'/'.$file, $to_path.'/'.$file);
                        chdir($from_path); 
                    }
                    elseif (is_file($file))
                    {
                        copy($from_path.'/'.$file, $to_path.'/'.$file);
                    }
                }
            }
            closedir($handle);
        }
    }

    /**
     * Copies all assets into public folder
     * 
     * @param   bool  $force_copy  Do not check is_dir
     * @return  void
     */
    public function publish($force_copy = false)
    {
        $app = Applications::instance($this->app_name);

        if ($force_copy or ! is_dir(PUBLIC_PATH.'/assets/'.$this->app_name.'/'.$app->config['theme']))
        {
            $this->copy_dir(
                APPLICATIONS_PATH.'/'.$this->app_name.'/themes/'.$app->config['theme'].'/assets',
                PUBLIC_PATH.'/assets/'.$this->app_name.'/'.$app->config['theme']
            );
        }
    }

    /**
     * Copies file into public folder
     * and returns public url
     * 
     * @param   string  $file_name   Asset file name
     * @param   string  $asset_type  Asset type (img|css|js)
     * @return  string
     */
    public function publish_file($file_name, $asset_type)
    {
        $app = Applications::instance($this->app_name);

        $theme_file   = APPLICATIONS_PATH.'/'.$this->app_name.'/themes/'.$app->config['theme'].'/assets/'.$asset_type.'/'.$file_name;
        $public_file  = PUBLIC_PATH.'/assets/'.$this->app_name.'/'.$app->config['theme'].'/'.$asset_type.'/'.$file_name;
        $public_url   = $app->config['base_url'].'/assets/'.$this->app_name.'/'.$app->config['theme'].'/'.$asset_type.'/'.$file_name;

        if ( ! is_file($public_file) or filemtime($public_file) != filemtime($theme_file))
        {
            if ( ! is_dir(dirname($public_file)))
            {
                mkdir(dirname($public_file), 0777, true);
            }

            copy($theme_file, $public_file);
            touch($public_file, filemtime($theme_file));
        }

        if ($this->config['append_timestamp'] === true)
        {
            $public_url .= '?'.filemtime($public_file);
        }

        return $public_url;
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
        $public_url = $this->publish_file($file_name, $asset_type);

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

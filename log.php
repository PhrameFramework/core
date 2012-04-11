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

class Log
{
    /**
     * Logfile name
     * 
     * @var  string
     */
    protected $file_name;

    /**
     * Constructs Log object
     * 
     * @param   string  $app_name  Application name
     * @return  void
     */    
    public function __construct($app_name = null)
    {
        $app_name  = $app_name ?: APPLICATION_NAME;

        $dir_name  = APPLICATIONS_PATH.'/'.$app_name.'/logs/';

        if ( ! is_dir($dir_name))
        {
            mkdir($dir_name, 0777, true);
        }

        $this->file_name = $dir_name.date('Y-m-d').'.log';
    }

    /**
     * Writes message to the logfile
     * 
     * @param   string  $message  Message to write
     * @return  void
     */
    public function write($message)
    {
        $message = '['.date('Y-m-d H:i:s').'] '.$message."\n";

        $f = fopen($this->file_name, 'a');
        fwrite($f, $message);
        fclose($f);
    }

}

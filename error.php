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

class Error
{
    /**
     * Application object
     * 
     * @var  Application
     */
    protected $app = null;

    /**
     * Constructs Error object
     * 
     * @param   Application  $app  Application object
     * @return  void
     */    
    public function __construct($app = null)
    {
        $this->app  = $app ?: Applications::instance();
    }

    /**
     * Exception handler
     * 
     * @param   \Exception  $exception  Exception to handle
     * @return  void
     */
    public function exception_handler($exception)
    {
        if (isset($this->app->log))
        {
            $log_message = 'type: '.get_class($exception).'. ';
            $log_message .= 'code: '.$exception->getCode().'. ';
            $log_message .= 'message: '.$exception->getMessage().'. ';
            $log_message .= 'file: '.$exception->getFile().'. ';
            $log_message .= 'line: '.$exception->getLine();

            $this->app->log->write($log_message);
        }

        if ($this->app->config->display_exceptions)
        {
            $view = new View(
                'errors/exception',
                array(
                    'type'     => get_class($exception),
                    'code'     => $exception->getCode(),
                    'message'  => $exception->getMessage(),
                    'file'     => $exception->getFile(),
                    'line'     => $exception->getLine(),
                    'trace'    => $exception->getTrace()
                ),
                $this->app
            );

            echo $view;
        }
    }

    /**
     * Error handler
     * 
     * @param   int     $errno    Level of the error
     * @param   string  $errstr   Error message
     * @param   string  $errfile  Filename that the error was raised in
     * @param   int     $errline  Line number the error was raised at
     * @return  void
     */
    public function error_handler($errno, $errstr, $errfile, $errline)
    {
        if (isset($this->app->log))
        {
            $log_message = 'code: '.$errno.'. ';
            $log_message .= 'message: '.$errstr.'. ';
            $log_message .= 'file: '.$errfile.'. ';
            $log_message .= 'line: '.$errline;

            $this->app->log->write($log_message);
        }

        if (error_reporting() & $errno)
        {
            $view = new View(
                'errors/error',
                array(
                    'code'     => $errno,
                    'message'  => $errstr,
                    'file'     => $errfile,
                    'line'     => $errline
                ),
                $this->app
            );

            echo $view;
        }
    }

}

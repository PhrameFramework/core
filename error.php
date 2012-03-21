<?php
/**
 * Part of the Phrame
 *
 * @package    Core
 * @version    0.3.0
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
    protected $application = null;

    /**
     * Constructs Error object
     * 
     * @param   Application  $application  Application object
     * @return  void
     */    
    public function __construct($application = null)
    {
        $this->application  = $application ?: Application::instance();
    }

    /**
     * Exception handler
     * 
     * @param   Exception  $exception  Exception to handle
     * @return  void
     */
    public function exception_handler($exception)
    {
        if (isset($this->application->log))
        {
            $log_message = 'type: '.get_class($exception).'. ';
            $log_message .= 'code: '.$exception->getCode().'. ';
            $log_message .= 'message: '.$exception->getMessage().'. ';
            $log_message .= 'file: '.$exception->getFile().'. ';
            $log_message .= 'line: '.$exception->getLine();

            $this->application->log->write($log_message);
        }

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
            $this->application
        );

        echo $view;
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
        if (isset($this->application->log))
        {
            $log_message = 'code: '.$errno.'. ';
            $log_message .= 'message: '.$errstr.'. ';
            $log_message .= 'file: '.$errfile.'. ';
            $log_message .= 'line: '.$errline;

            $this->application->log->write($log_message);
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
                $this->application
            );

            echo $view;
        }
    }

}

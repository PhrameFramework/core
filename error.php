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
     * Exception handler
     * 
     * @param   Exception  $exception  Exception to handle
     * @return  void
     */
    public static function exception_handler($exception)
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
            )
        );

        echo $view;
    }

    /**
     * Exception handler
     * 
     * @param   int     $errno    Level of the error
     * @param   string  $errstr   Error message
     * @param   string  $errfile  Filename that the error was raised in
     * @param   int     $errline  Line number the error was raised at
     * @return  void
     */
    public static function error_handler($errno, $errstr, $errfile, $errline)
    {
        if (error_reporting() & $errno)
        {
            $view = new View(
                'errors/error',
                array(
                    'code'     => $errno,
                    'message'  => $errstr,
                    'file'     => $errfile,
                    'line'     => $errline
                )
            );

            echo $view;
        }
    }

}

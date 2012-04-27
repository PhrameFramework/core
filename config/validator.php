<?php
/**
 * Validator config
 *
 * Copy to the application config directory and make your changes
 */

return array(

    /**
     * Required field
     */
    'required'  => array(
        'rule'     => '\w+',
        'message'  => '%name% field is required',
    ),

    /**
     * Numeric field
     */
    'num' => array(
        'rule'     => '^\d+$',
        'message'  => '%name% field may only contain numbers',
    ),

    /**
     * Alpha fiels
     */
    'alpha' => array(
        'rule'     => '^([a-zA-Z])+$',
        'message'  => '%name% field may only contain letters',
    ),

    /**
     * Alpha-numeric field
     */
    'alpha_num' => array(
        'rule'     => '^([a-zA-Z0-9])+$',
        'message'  => '%name% field may only contain letters and numbers',
    ),

);

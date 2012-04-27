<?php
/**
 * Lang config
 * 
 * Copy to the application config directory and make your changes
 */

return array(
    
    /**
     * Application language
     */
    'language'          => 'auto',

    /**
     * Default application language
     */
    'default_language'  => 'en',

    /**
     * Translations
     */
    'translations'  => array(

        'en'  => array(
            //'Home'  => 'Home',

        ),

        'ru'  => array(
            //'Home'  => 'Главная',
            '%name% field is required'                          => 'Поле %name% обязательно для заполнения',
            '%name% field may only contain numbers'             => 'Поле %name% должно содержать только цифры',
            '%name% field may only contain letters'             => 'Поле %name% должно содержать только буквы',
            '%name% field may only contain letters and numbers' => 'Поле %name% должно содержать только буквы и цифры',

        ),

    ),

);

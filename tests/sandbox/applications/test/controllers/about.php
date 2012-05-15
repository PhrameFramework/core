<?php

namespace Test\Controllers;

use Phrame\Core;

class About extends Core\Controller
{
    public function index()
    {
        $this->layout->content = new Core\View(
            'about',
            array(
                'name' => 'Phrame'
            )
        );
    }

}

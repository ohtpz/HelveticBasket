<?php

namespace Carloscrndn\HelveticBasket\Controllers;

use Slim\Views\PhpRenderer;
use Carloscrndn\HelveticBasket\Models\User;

abstract class BaseController
{
    /**
     * @var PhpRenderer
     */
    protected PhpRenderer $view;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->view = new PhpRenderer(__DIR__ . '/../views', [
            'user' => User::current()
        ]);

        $this->view->setLayout("layout.php");
    }
}

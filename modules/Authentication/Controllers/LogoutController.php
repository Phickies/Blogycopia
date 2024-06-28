<?php

declare(strict_types=1);

namespace App\Authentication\Controllers;

use Core\Controller;
use Core\SessionHandler;


/**
 * LogoutController handles cleaning user session and logout process
 */
class LogoutController extends Controller
{

    private SessionHandler $session;

    public function __construct(SessionHandler $session)
    {
        parent::__construct();
        $this->session = $session;
    }


    /**
     * End session, delete cookies
     */
    public function logout()
    {
        $this->session->destroy();
        $this->redirect("/authentication/login");
    }
}

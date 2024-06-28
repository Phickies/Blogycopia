<?php

declare(strict_types=1);

namespace App\Authentication\Controllers;

use Core\Controller;
use Core\SessionHandler;


/**
 * LoginController handles rendering the login page and user authentication.
 */
class LoginController extends Controller
{

    private SessionHandler $session;

    public function __construct(SessionHandler $session)
    {
        parent::__construct();
        $this->session = $session;
    }


    /**
     * Renders the login page. Redirects to home if user is already authenticated.
     */
    public function renderLoginPage()
    {

        if ($this->session->isAuthenticated()) {
            $this->redirect("/");
        }

        $this->getLoginPage();
    }


    /**
     * Authenticates the user based on the provided username and password.
     * Sets the session username and redirects to home if authentication is successful.
     * Renders the login page with an error message if authentication fails.
     */
    public function authenticateUser()
    {
        $username = $this->getInputUsername();
        $password = $this->getInputPassword();

        if (!$this->isUsernameValid($username)) {
            $message = "No username or email found?";
            $this->getLoginPage($message);
        } else if (!$this->isPasswordValid($password)) {
            $message = "Incorrect password. Try again!";
            $this->getLoginPage($message);
        } else {
            $this->session->set("username", $username);
            $this->redirect("/");
        }
    }


    /**
     * Renders the login page with an optional error message.
     *
     * @param string|null $error The error message to display on the login page.
     */
    private function getLoginPage($error = null)
    {
        $this->render("Authentication/View/login.php", ["error" => $error], isCustomViewFile: true);
    }


    /**
     * Retrieves and sanitizes the input username from the POST request.
     *
     * @return string The sanitized username.
     */
    private function getInputUsername(): string
    {
        return filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    }


    /**
     * Retrieves and sanitizes the input password from the POST request.
     *
     * @return string The sanitized password.
     */
    private function getInputPassword(): string
    {
        return filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    }


    /**
     * Validates the provided username against a predefined value.
     *
     * @param string $username The username to validate.
     * @return bool True if the username is valid, false otherwise.
     */
    private function isUsernameValid(string $username): bool
    {
        return $username == "Phicks";
    }


    /**
     * Validates the provided password against a predefined value.
     *
     * @param string $password The password to validate.
     * @return bool True if the password is valid, false otherwise.
     */
    private function isPasswordValid(string $password): bool
    {
        return $password == "12345";
    }
}

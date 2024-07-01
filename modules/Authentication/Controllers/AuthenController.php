<?php

declare(strict_types=1);

namespace App\Authentication\Controllers;

use Core\Controller;
use Core\SessionHandler;
use App\Authentication\Models\UserModel;


class AuthenController extends Controller
{
    private SessionHandler $session;
    private UserModel $userModel;


    public function __construct(SessionHandler $session)
    {
        parent::__construct();
        $this->session = $session;
        $this->userModel = $this->model(UserModel::class);
    }


    public function register()
    {
        $username = $this->getInputUsername();
        $email = $this->getInputEmail();
        $password = $this->getInputPassword();

        $result = $this->userModel->createUser($username, $email, $password);

        if (!$result) {
            $this->getRegisterPage("Registeration for user failed. Please try again latter");
        } else if ($result == -1) {
            $this->getRegisterPage("The username and email is already taken or you have been registered");
        } else {
            $this->getSuccessPage("Congratulation!! Registeration for new user successully");
        }
    }


    public function redirectToLoginPage()
    {
        $this->redirect("/authentication/login");
    }


    public function sendCodeToEmail()
    {
        if ($this->isEmailValid($this->getInputEmail())) {
            $this->getCodeTypeInPage();
        } else {
            $this->getForgotPasswordPage("You have not registered with this email.");
        }
    }


    /**
     * Renders the forget password page. Redirects to home if user is already authenticated.
     */
    public function renderForgotPasswordPage()
    {

        if ($this->session->isAuthenticated()) {
            $this->redirect("/");
        }

        $this->getForgotPasswordPage();
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
     * Renders the register page. Redirects to home if user is already authenticated.
     */
    public function renderRegisterPage()
    {
        if ($this->session->isAuthenticated()) {
            $this->redirect("/");
        }

        $this->getRegisterPage();
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

        $user = $this->isUsernameValid($username);

        if (!$user) {
            $message = "No username or email found?";
            $this->getLoginPage($message);
        } else if (!$this->isPasswordValid($user, $password)) {
            $message = "Incorrect password. Try again!";
            $this->getLoginPage($message);
        } else {
            $this->session->set("username", $user["name"]);
            $this->redirect("/");
        }
    }


    /**
     * End session, delete cookies
     */
    public function logout()
    {
        $this->session->destroy();
        $this->redirect("/authentication/login");
    }


    /**
     * Renders the code type in page with an optional error message.
     *
     * @param string|null $error The error message to display on the code type in page.
     */
    private function getCodeTypeInPage($error = null)
    {
        $this->render("Authentication/View/code-type-in.php", ["error" => $error], isCustomViewFile: true);
    }



    /**
     * Renders the register page with an optional error message.
     *
     * @param string|null $error The error message to display on the register page.
     */
    private function getForgotPasswordPage($error = null)
    {
        $this->render("Authentication/View/forgot-password.php", ["error" => $error], isCustomViewFile: true);
    }


    /**
     * Renders the register page with an optional error message.
     *
     * @param string|null $error The error message to display on the register page.
     */
    private function getRegisterPage($error = null)
    {
        $this->render("Authentication/View/register.php", ["error" => $error], isCustomViewFile: true);
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
     * Renders the success registered page.
     *
     * @param string $message The success message to display on the page.
     */
    private function getSuccessPage($message)
    {
        $this->render("Authentication/View/success.php", ["message" => $message], isCustomViewFile: true);
    }


    /**
     * Retrieves and sanitizes the input email from the POST request.
     *
     * @return string The sanitized email.
     */
    private function getInputEmail(): string
    {
        return filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
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
     * Validates the provided email.
     *
     * @param string $email The email to validate.
     * @return bool True if the email is found in database, false otherwise.
     */
    private function isEmailValid(string $email): bool
    {
        return $this->userModel->getUserByEmail($email)? true: false;
    }


    /**
     * Validates the provided username.
     *
     * @param string $username The username to validate.
     * @return array|false User information if the username is found in database, false otherwise.
     */
    private function isUsernameValid(string $username): array|false
    {
        $user = $this->userModel->getUserByEmailorUserName($username);

        if (!$user) {
            return false;
        }
        return $user;
    }


    /**
     * Validates the provided password.
     *
     * @param string $password The password to validate.
     * @return bool True if the password is valid, false otherwise.
     */
    private function isPasswordValid(array $user, string $password): bool
    {
        if (password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }
}

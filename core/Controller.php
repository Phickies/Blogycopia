<?php

declare(strict_types=1);

namespace Core;

use Exception;
use Core\View;
use Core\Router;


class Controller
{

    protected $templateView;


    public function __construct()
    {
    }


    public function model(?string $modelClass = null)
    {
        if (!$modelClass) {
            return null;
        }

        if (class_exists($modelClass)) {
            return new $modelClass();
        } else {
            error_log("Model class '$modelClass' not found.");
            $router = new Router();
            $router->handleError(500, "Database not found");
        }
    }


    /**
     * Render the front-end view page
     * @param string $filePath Path to the view file that you want to render.
     * @param array|null $data Data to pass into the page, e.g., ['title' => 'My Page']
     * @param bool $isCustomViewFile Flag to use custom view file without built-in template.
     */
    public function render(string $filePath, mixed $data = null, bool $isCustomViewFile = false)
    {
        $isCustomViewFile ? $this->handleViewFile($filePath, $data) : $this->addToTemplate($filePath, $data);
    }


    protected function isAuthenticatedUser()
    {
        // Router might cause this problems
        return $_SESSION["username"] == "Alan";
    }


    /**
     * Redirect to other module via calling that module router
     * @param string $uri Set the raw uri.
     */
    protected function redirect(string $uri)
    {
        header("Location: $uri");
        die();
    }


    /**
     * Add built-in template for the page.
     */
    private function addToTemplate(string $filePath, mixed $data)
    {
        $templateView = new View();
        $templateView->addHeader();
        $this->handleViewFile($filePath, $data);
        $templateView->addFooter();
    }


    /**
     * Load and render a view file with optional data. 
     * @throws Exception if the file is missing.
     */
    private function handleViewFile(string $filePath, array $data = null)
    {
        $path = BASE_DIR . "/modules/" . $filePath;
        if (!file_exists($path)) {
            throw new Exception("View file '{$filePath}' not found.");
        }

        if (is_array($data)) {
            extract($data);
        }
        include $path;
    }
}

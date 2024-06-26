<?php

declare(strict_types=1);

namespace Core;

use Core\View;
use Exception;

class Controller
{


    protected $templateView;


    public function __construct()
    {
        $this->templateView = new View();
    }


    /**
     * Render the front-end view page
     * @param string $filePath Path the view file that you want to render.
     * @param mixed $data Data file to pass into the page, Example your page need $data["title
     * "] so you must pass in the ["title" => "something"]
     * @param bool $isCustomViewFile If you want to render your own designed view file and dont want to include
     * built-in template view.
     */
    public function render(string $filePath, mixed $data = null, bool $isCustomViewFile = false): void
    {
        if (!$isCustomViewFile) {
            $this->addToTemplate($filePath, $data);
            return;
        }
        $this->handleViewFile($filePath, $data);
    }


    /**
     * Add built-in template for the page.
     */
    private function addToTemplate(string $filePath, mixed $data): void
    {
        $this->templateView->addHeader();
        $this->handleViewFile($filePath, $data);
        $this->templateView->addFooter();
    }


    // NEED A BETTER WAY TO HANDLE ERROR IN THIS CODE
    private function handleViewFile(string $filePath, mixed $data): void
    {
        $path = BASE_DIR . "/modules/" . $filePath;

        if (file_exists($path)) {
            if ($data !== null) {
                extract($data);
            }
            include_once $path;
        } else {
            throw new Exception("The view file path is not corrected or missing", 1);
        }
    }
}

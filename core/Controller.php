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
     * @param string $filePath Path to the view file that you want to render.
     * @param array|null $data Data to pass into the page, e.g., ['title' => 'My Page']
     * @param bool $isCustomViewFile Flag to use custom view file without built-in template.
     */
    public function render(string $filePath, mixed $data = null, bool $isCustomViewFile = false)
    {
        $isCustomViewFile ? $this->handleViewFile($filePath, $data) : $this->addToTemplate($filePath, $data);
    }


    /**
     * Add built-in template for the page.
     */
    private function addToTemplate(string $filePath, mixed $data)
    {
        $this->templateView->addHeader();
        $this->handleViewFile($filePath, $data);
        $this->templateView->addFooter();
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

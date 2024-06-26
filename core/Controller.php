<?php

declare(strict_types=1);

namespace Core;

use Core\View;


class Controller
{


    protected $templateView;


    public function __construct()
    {
        $this->templateView = new View();
    }


    /**
     * Render the front-end view page
     */
    public function render(string $filePath, bool $isCustomViewFile = false): void
    {

        if (!$isCustomViewFile) {
            $this->addToTemplate($filePath);
            return;
        }
        $this->handleViewFile($filePath);
    }


    private function addToTemplate(string $filePath): void
    {
        $this->templateView->addHeader();
        $this->handleViewFile($filePath);
        $this->templateView->addFooter();
    }


    private function handleViewFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            require_once $filePath;
        } else {
            require_once $this->templateView->renderError(404);
        }
    }
}

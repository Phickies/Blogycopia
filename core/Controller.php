<?php

declare(strict_types=1);

namespace Core;

use Core\View;


class Controller {


    protected $templateView;


    public function __construct(){
        $this->templateView = new View();
    }


    /**
     * Render the front-end view page
     */
    public function render(string $filePath, bool $isCustom = false) {

        if ($isCustom) {
            // logic to add the module view file using $filePath
            return;
        }
        
        $this->templateView->addHeader();
        // That same logic above
        $this->templateView->addFooter();
    }

}
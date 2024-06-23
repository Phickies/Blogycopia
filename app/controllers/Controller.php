<?php

/**
 * Controller for handling logic between databse and user end point
 */
class Controller {
    
    /**
     * Render front-end view
     */
    public static function render(string $name_view_file, string $title = "No title", array $data = []) {

        extract($data);
        
        require_once __DIR__ . '/../views/templates/layout.view.php';
    }
}
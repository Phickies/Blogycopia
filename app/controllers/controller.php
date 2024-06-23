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
        
        $file_path = PATH_DIR . "/views/$name_view_file.view.php";

        if (file_exists($file_path)) {
            include(PATH_DIR . "/views/templates/layout.view.php");
        } else {
            include(PATH_DIR . "/views/templates/error.view.php");
        }
    }


    /**
     * Redirect to another resource pages
     */
    public static function redirect(string $file_url) {
        header("Location: $file_url");
        die();
    }
}
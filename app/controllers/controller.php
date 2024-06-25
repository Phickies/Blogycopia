<?php
declare(strict_types=1);

/**
 * Controller for handling logic between databse and user end point
 */
class Controller {

    public function __construct(){}
    
    /**
     * Render front-end view
     */
    public function render(string $name_view_file, string $title = "No title", array $data = []) {

        extract($data);
        
        $file_path = BASE_DIR . "/views/$name_view_file.view.php";

        if (file_exists($file_path)) {
            include(BASE_DIR . "/views/templates/layout.view.php");
        } else {
            include(BASE_DIR . "/views/templates/error.view.php");
        }
    }


    /**
     * Redirect to another resource pages
     */
    public function redirect(string $file_url) {
        header("Location: $file_url");
        die();
    }
}
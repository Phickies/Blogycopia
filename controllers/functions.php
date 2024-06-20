<?php
/**
 * This is the control logic
 */


/**
 * Fucntion to linker the controller to render the view
 */
function render(string $nameViewFile, array $models) {
    $title = getPageTitle($models);

    include('views/templates/layout.view.php');
}


/**
 * Function to extract and return the tilte from the models package
 * from [title] key.
 * Assigned return variable to "No page title" if not found
 */
function getPageTitle(array $arr): string{
    if (array_key_exists("title", $arr)) {
        return $arr["title"];
    }
    return "No page title?";
}
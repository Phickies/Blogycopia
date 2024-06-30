<?php

declare(strict_types=1);

namespace Helpers;


class Debug
{

    public static function printList(...$items)
    {
        echo "<pre>"; // Use <pre> tags to format the output for readability
        foreach ($items as $index => $item) {
            echo "Item " . ($index + 1) . ":\n";
            var_dump($item); // Use var_dump to display detailed information about each item
            echo "\n";
        }
        echo "</pre>";
    }
}

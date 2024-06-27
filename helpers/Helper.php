<?php

declare(strict_types=1);

namespace Helpers;

use Exception;


class Helper
{

    public static function loadEnv(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new Exception("The .env file does not exist.");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), "#") === 0) {
                continue;   // Skip comments
            }

            list($key, $value) = explode("=", $line, 2);
            $key = trim($key);
            $value = trim($value);

            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv(sprintf("%s=%s", $key, $value));
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }

    
    public static function isClass($object, $class) {
        return $object instanceof $class;
    }


    public static function removeFirstSegment($path) {
        // Split the path into segments
        $segments = explode('/', $path);
    
        // Remove the empty element at the beginning if the path started with a slash
        if ($segments[0] === '') {
            array_shift($segments); // Remove the leading empty segment
        }
    
        // Remove the first real segment
        array_shift($segments);
    
        $newPath = '/' . join('/', $segments);
    
        // Trim a trailing slash if the new path isn't just '/'
        return $newPath === '/' ? $newPath : rtrim($newPath, '/');
    }
}

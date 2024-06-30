<?php

declare(strict_types=1);

namespace Helpers;

use Exception;


class Helper
{

    public static function loadEnv(string $filePath)
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


    public static function removeFirstSegment($path): string
    {
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


    /**
     * Extracts the first segment from a path-like string.
     * 
     * @param string $path The path string from which to extract the first segment.
     * @return string The first segment of the path. Returns false if no valid segment is found.
     */
    public static function getFirstSegment(string $path): string|bool
    {
        // Trim the leading and trailing slashes to avoid empty segments
        $trimmedPath = trim($path, '/');

        // Split the string by slashes
        $segments = explode('/', $trimmedPath);

        if (!$segments[0]) {
            return false;
        }

        // Return the first segment or an empty string if no segments are available
        return "/" . $segments[0];
    }



}

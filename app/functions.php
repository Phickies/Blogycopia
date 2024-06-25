<?php
declare(strict_types=1);

function load_env(string $filePath) : void {
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


function load_files(array $files): void {
    foreach ($files as $file) {
        require_once BASE_DIR . $file;
    }
}

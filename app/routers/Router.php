<?php
declare(strict_types=1);

/**
 * Router class for managing routing url
 */
class Router {

    protected $routers = [];

    public function __construct(){}


    /**
     * Add new router to the router handler
     */
    public function route(string $method, string $uri, callable $function): void {
        $this->routers[$method][$uri] = $function;
    }


    /**
     * dispatch the request url from the user to link with controller.
     */
    public function dispatch() {
        $method = $this->getMethod();
        $uri = $this->getFilteredUri();
        $query = $this->getFilteredQuery();
    
        if (!$this->isMethodValid($method)) {
            $this->handleError(405, "Method not allowed");
            return;
        }

        if (!$this->isUriValid($method, $uri)) {
            $this->handleError(404, "Page not found");
            return;
        }

        if (!$this->isQueryValid($query)) {
            $this->handleError(403, "Invalid query");
            return;
        }

        call_user_func($this->routers[$method][$uri], $query);
    }


    private function getFilteredQuery(): array {
        return $this->filter($this->retrivedQuery());
    }


    private function filter(array $query): ?array {
        foreach ($query as $key => &$value) {
            $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            if (!$value) {
                return null;
            } 
        }
        return $query;
    }


    private function retrivedQuery(): array {
        $queryString = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
        $queryParams = [];
        // Parse the query from string to list
        if ($queryString) {
            parse_str($queryString, $queryParams);
        }
        return $queryParams;
    }


    private function getFilteredUri(): string {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }


    private function getMethod(): string {
        return $_SERVER["REQUEST_METHOD"];
    }


    private function isMethodValid(string $method): bool {
        return isset($this->routers[$method]);
    }


    private function isQueryValid(?array $query): bool {
        return $query !== null;
    }


    private function isUriValid(string $method, string $uri): bool {
        return isset($this->routers[$method][$uri]);
    }


    private function handleError(int $error_code, string $error_description): void {
        http_response_code($error_code);
        echo $error_description;
    }
}
<?php

declare(strict_types=1);

namespace Core;


class Router
{

    private $routelist = [];


    public function __construct()
    {
    }


    /**
     * add router to controller
     * @param string $method Any valid request method. pass in as a string. For example, "get", "Post", "UPDATE", etc.. However, be consistance
     * with your naming of the method.
     * @param string $uri Router index for specify track. For example, "/", "/hello", "/foo/bar", "/foo{}", etc..
     * @param mixed $controller Controller class of the module you want to route the url to. Must pass in as 
     * a class Controller NOT INSTANCE. For example HomeController::class, LoginController::class, etc..
     * @param string $function Name of the function you want to execute upon routing successfully to the controller. For example, after
     * routing successfully, execute method display from the Controller you pass in before.
     */
    public function add(string $method, string $uri, $controller, string $function)
    {
        $this->routelist[$method][$uri] = ["class" => $controller, "function" => $function];
    }


    /**
     * handle the received request and dispatch it to the desirer controller
     */
    public function handleRequest()
    {
        $request = $this->getRequest();

        if (!$request) {
            echo "Bad request";
            http_response_code(400);
        }

        $method = $request["method"];
        $uri = $request["uri"];

        // REFACTOR THIS CODE> 
        if (isset($this->routelist[$method][$uri])) {
            $route = $this->routelist[$method][$uri];
            $controllerClass = $route["class"];
            $function = $route["function"];

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $function)) {
                    call_user_func_array([$controller, $function], $request["query"]);
                } else {
                    $this->handleError(404, "Method $function not found in controller $controllerClass");
                }
            } else {
                $this->handleError(404, "Controller class $controllerClass not found");
            }
        } else {
            $this->handleError(404, "No route matched for $method $uri");
        }
        // REFACTOR THIS CODE
    }


    public function handleError(int $errorCode, string $description)
    {
        http_response_code($errorCode);

        $errorController = new \App\Error\Controllers\ErrorController();
        $errorController->displayErrorPage($errorCode, $description);
    }


    /**
     * fetch and return the request method from the url.
     * return null if not found or invalid method
     */
    private function getMethod(): ?string
    {
        return $_SERVER["REQUEST_METHOD"];
    }


    /**
     * fetch and return the queries from the url.
     * return null if not found or invalid queries
     */
    private function getQuery(): ?array
    {
        return $this->filterQuery($this->retrivedQuery());
    }


    /**
     * fetch and return the uri from the url.
     * return null if not found or invalid uri
     */
    private function getUri(): ?string
    {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }


    /**
     * fetch and return method, uri and queries as a list from the url
     * return null if invalid request
     */
    private function getRequest(): ?array
    {
        $method = $this->getMethod();
        $uri = $this->getUri();
        $params = $this->getQuery();

        if (!$method || !$uri || $params === null) {
            return null;
        }

        return [
            "method" => $method,
            "uri" => $uri,
            "query" => $params
        ];
    }


    /**
     * filter queries.
     * return null if invalid queries
     */
    private function filterQuery(array $query): ?array
    {

        if ($query === null) {
            return null;
        }

        foreach ($query as $key => &$value) {
            $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            if ($value == null) {
                return null;
            }
        }

        return $query;
    }


    /**
     * retrieve queries and convert it to array
     * return null if invalid queries
     */
    private function retrivedQuery(): ?array
    {
        $queryString = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
        $queryParams = [];
        // Parse the query from string to list
        if ($queryString) {
            parse_str($queryString, $queryParams);
        }
        return $queryParams;
    }
}

<?php

declare(strict_types=1);

namespace Core;

use Exception;
use Helpers\Helper;


class Router
{

    protected $routelist = [];


    public function __construct()
    {
    }


    /**
     * add router to controller.
     * @param string $arequest_method Any valid request method. pass in as a string. For example, "get", "Post", "UPDATE", etc.. However, be consistance
     * with your naming of the method.
     * @param string $uri Router index for specify track. For example, "/", "/hello", "/foo/bar", "/foo{}", etc..
     * @param mixed $object_class Controller class or Router class of the module you want to route the url to. Must pass in as 
     * a class Controller or Router NOT INSTANCE. For example HomeController::class, LoginController::class, ErrorRouter::class etc..
     * @param string $method Name of the method you want to execute upon routing successfully to the controller. For example, after
     * routing successfully, execute method display from the Controller you pass in before.
     */
    public function add(string $request_method, string $uri, $object_class, string $method)
    {
        $this->routelist[$request_method][$uri] = ["class" => $object_class, "method" => $method];
    }


    /**
     * dispatch the made request into the desire controllers or modular routers
     */
    public function dispatch()
    {
        $route = $this->handleRequest();

        $objectClass = $route["object"]["class"];
        $method = $route["object"]["method"];


        if (!class_exists($objectClass)) {
            $this->handleError(404, "Both Controller class or Router class $objectClass not found");
            die();
        }

        $object = new $objectClass();

        if (!method_exists($object, $method)) {
            $this->handleError(404, "Method $method of class $objectClass not found");
            die();
        }

        call_user_func_array([$object, $method], $route["query"]);
    }


    /**
     * handle the received request and return the route.
     * return a route array contain the object/modular that the it route to and the query/parameter for the 
     * function that will be callback when routing successful.
     * Example [object, query];
     */
    private function handleRequest(): array
    {
        $request = $this->getRequest();

        if (!$request) {
            $this->handleError(400, "Bad Request");
            die();
        }

        $method = $request["method"];
        $uri = $request["uri"];


        if ($this->isFoundRoute($method, $uri)) {
            $this->handleError(404, "No route matched for $method $uri");
            die();
        }

        return [
            "object" => $this->routelist[$method][$uri],
            "query" => [$request["query"]]
        ];
    }


    /**
     * routing to error module Need to be in Error routing.
     */
    public function handleError(int $errorCode, string $description): void
    {
        http_response_code($errorCode);

        try {
            $errorController = new \App\Error\Controllers\ErrorController();
            $errorController->displayErrorPage($errorCode, $description);
        } catch (Exception $e) {
            echo "Module for displaying error page has error: <br>";
            echo $e . "<br>";
            echo $description;
        }
    }


    /**
     * check for valid route to conroller based on method and uri.
     * return false if not found
     */
    private function isFoundRoute($method, $uri): bool
    {
        return !isset($this->routelist[$method][$uri]);
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
     * fetch and return method, uri and queries as a list from the url.
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
     * retrieve queries and convert it to array.
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

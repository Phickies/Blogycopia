<?php

declare(strict_types=1);

namespace Core;

use Exception;
use Helpers\Helper;


class Router
{

    protected $routeList = [];


    public function __construct()
    {
    }


    /**
     * Add a route to the module entry router.
     * @param string $entryRouterClass Class of the router in the module.
     * @param string $indexAddress The URI to access the router of that class.
     */
    public function addModule(string $entryRouterClass, string $indexAddress = "/")
    {
        $this->routeList[$indexAddress] = $entryRouterClass;
    }


    /**
     * Dispatch the request to the assigned module router for further dispatching.
     * @throws ErrorPage If any routing error occurs.
     */
    public function dispatchToModule()
    {
        $uri = $this->getUri();

        if (!isset($this->routeList[$uri])) {
            $this->handleError(404, "Page not found");
            die();
        }

        $moduleRouterClass = $this->routeList[$uri];
        if (!class_exists($moduleRouterClass)) {
            $this->handleError(404, "Modules of $moduleRouterClass not found");
            die();
        }

        $moduleRouter = new $moduleRouterClass();
        if (!method_exists($moduleRouter, "dispatch")) {
            $this->handleError(404, "Method 'dispatch' can't be found in class $moduleRouterClass");
            die();
        }

        $moduleRouter->dispatch();
    }


    /**
     * Add route to object controller.
     * @param string $requestMethod The HTTP method.
     * @param string $uri Router index for specifying track.
     * @param string $objectClass Controller class to route the URL to.
     * @param string $method Method to execute upon successful routing.
     */
    protected function add(string $request_method, string $uri, $object_class, string $method)
    {
        $this->routeList[$request_method][$uri] = ["class" => $object_class, "method" => $method];
    }


    /**
     * Handle and dispatch the made request to the desired controllers.
     * @throws ErrorPage If dispatching fails.
     */
    protected function dispatch()
    {
        $route = $this->handleRequest();
        $objectClass = $route["object"]["class"];
        $method = $route["object"]["method"];


        if (!class_exists($objectClass)) {
            $this->handleError(404, "Controller class $objectClass not found");
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
     * routing to error module Need to be in Error routing.
     */
    protected function handleError(int $errorCode, string $description)
    {
        http_response_code($errorCode);

        try {
            $errorController = new \App\Error\Controllers\ErrorController();
            $errorController->displayErrorPage($errorCode, $description);
        } catch (Exception $e) {
            echo "Module for displaying error page has error or missing: <br>";
            echo $e . "<br>";
            echo $description;
        }
    }


    /**
     * Handle received request and return the route.
     * @return array Contains the object/module and the query/parameter for the callback.
     * @throws ErrorPage If the request is bad or the route is not found.
     */
    private function handleRequest(): array
    {
        $request = $this->getRequest();

        if (!$request) {
            $this->handleError(400, "Bad Request");
            die();
        }

        return [
            "object" => $this->routeList[$request["method"]][$request["uri"]],
            "query" => [$request["query"]]
        ];
    }


    /**
     * fetch and return the queries from the url.
     * @return array|null Consist of key query and its values. Returns null if invalid.
     */
    private function getQuery(): ?array
    {
        return $this->filterQuery($this->retrieveQuery());
    }


    /**
     * Fetch and return the URI from the URL.
     * @return string Processed URI.
     */
    private function getUri(): string
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        return empty($uri) ? '/' : '/' . parse_url($uri, PHP_URL_PATH);
    }


    /**
     * Fetch and return request from global variable.
     * @return array|null Consists of method, uri, and query. Returns null if invalid.
     */
    private function getRequest(): ?array
    {
        $method = $_SERVER["REQUEST_METHOD"] ?? null;
        $uri = Helper::removeFirstSegment($this->getUri());
        $query = $this->getQuery();

        if (!$method || !$uri || $query === null) {
            return null;
        }

        return ["method" => $method, "uri" => $uri, "query" => $query];
    }


    /**
     * Filter queries.
     * @param array $query Array of query strings.
     * @return array|null Filtered query or null if invalid.
     */
    private function filterQuery(?array $query): ?array
    {
        if ($query === null) {
            return null;
        }
        
        foreach ($query as $key => $value) {
            $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            if (!$value) {
                return null;
            }
        }
        return $query;
    }


    /**
     * Retrieve and convert query strings to an array.
     * @return array|null Parsed query parameters or null if invalid.
     */
    private function retrieveQuery(): ?array
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

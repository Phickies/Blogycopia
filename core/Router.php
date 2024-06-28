<?php

declare(strict_types=1);

namespace Core;

use Exception;
use Helpers\Helper;
use Core\SessionHandler;
use Helpers\DependencyInjector;


class Router
{

    protected $routeList = [];
    protected SessionHandler $session;

    private $uri = null;
    private $trimmedUri = null;


    // Somehow make the router independence from the SessionHandler and make the sessionHandler act
    // as an injection dependency.
    public function __construct(SessionHandler $session = new SessionHandler())
    {
        $this->session = $session;
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
    public function dispatchToModule($uri = null)
    {
        $this->setUri($uri);

        if (!isset($this->routeList[$this->trimmedUri])) {
            $this->handleError(404, "Page not found");
            die();
        }

        $this->accessToModuleRouter($this->routeList[$this->trimmedUri]);
    }


    /**
     * Try to access to that module router
     * @throws ErrorPage If any routing error occurs.
     */
    public function accessToModuleRouter(mixed $moduleRouterClass)
    {

        if (!class_exists($moduleRouterClass)) {
            $this->handleError(500, "Modules of $moduleRouterClass not found");
            die();
        }

        // Add reference of session to the each other router
        $moduleRouter = new $moduleRouterClass($this->session);

        if (!method_exists($moduleRouter, "dispatchToController")) {
            $this->handleError(500, "Method 'dispatchToController' can't be found in class $moduleRouterClass");
            die();
        }

        // Check the routerList, 
        // If found key that start with "/" -> dispatchToModule() else dispatch()
        // this means that we detect if the router has another sub router routing into that or not.
        // remove the first segment of the uri means we move on to the next segment of the URL

        // NEED TO ADD A FUNCTION TO CONTROLL WHAT IF THE USER ONLY ACCESS /authentication NOT 
        // /authentication/login. ???? WE NEED TO CHECK THE $this->uri to see if they are have more slac or not

        Helper::containsSlashKey($moduleRouter->getRouteList()) ?
            $moduleRouter->dispatchToModule(Helper::removeFirstSegment($this->uri)) :
            $moduleRouter->dispatchToController(Helper::removeFirstSegment($this->uri));
    }


    protected function getRouteList(): array
    {
        return $this->routeList;
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
     * @param string $uri The string of segments to process.
     * @throws ErrorPage If dispatching fails.
     */
    protected function dispatchToController(string $uri)
    {

        // Find another way to re organised this code.
        $this->setUri($uri);

        $route = $this->handleRequest();
        $controllerClass = $route["controller"]["class"];
        $method = $route["controller"]["method"];


        if (!class_exists($controllerClass)) {
            $this->handleError(404, "Controller class $controllerClass not found");
            die();
        }

        $controller = $this->getControllerObject($controllerClass);
 

        if (!method_exists($controller, $method)) {
            $this->handleError(404, "Method $method of class $controllerClass not found");
            die();
        }

        call_user_func_array([$controller, $method], $route["query"]);
    }


    /**
     * routing to error module Need to be in Error routing.
     */
    public function handleError(int $errorCode, string $description)
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
            "controller" => $this->routeList[$request["method"]][$request["uri"]],
            "query" => [$request["query"]]
        ];
    }


    /**
     * Get the controller for that specific type of class.
     * Assigned Session dependency if need
     */
    private function getControllerObject($controllerClass): mixed {
        if ($this->session) {
            $injector = new DependencyInjector($this->session);
            return $injector->createController($controllerClass);
        } else {
            return new $controllerClass();
        }
    }


    /**
     * fetch and return the queries from the url.
     * @return array|null Consist of key query and its values. Returns null if invalid.
     */
    private function getQuery(): ?array
    {
        return $this->filterQuery($this->retrieveQuery());
    }


    /** REFACTOR THIS CODE */
    private function setUri($uri)
    {
        // Assign the URI
        if (!$uri) {
            $this->uri = $this->getUri();
        } else {
            $this->uri = $uri;
        }

        // Process the first URL segment and pass it along to dispatch
        $a = Helper::getFirstSegment($this->uri);
        if (!$a) {
            $this->trimmedUri = $this->uri;
        } else {
            $this->trimmedUri = $a;
        }
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
        $query = $this->getQuery();

        if (!$method || !$this->trimmedUri || $query === null) {
            return null;
        }

        return ["method" => $method, "uri" => $this->trimmedUri, "query" => $query];
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

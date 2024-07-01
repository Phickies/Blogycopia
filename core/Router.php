<?php

declare(strict_types=1);

namespace Core;

use Exception;

use Core\RouteList;
use Core\SessionHandler;

use Helpers\Helper;
use Helpers\DependencyInjector;


class Router
{

    protected RouteList $routeList;
    protected SessionHandler $session;

    private ?string $uri = null;
    private ?string $firstSegmentUri = null;


    // Somehow make the router independence from the SessionHandler and make the sessionHandler act
    // as an injection dependency.
    public function __construct(SessionHandler $session = new SessionHandler())
    {
        $this->routeList = new RouteList();
        $this->session = $session;
    }


    /**
     * Dispatches the incoming request to the appropriate controller based on the URI.
     *
     * This method sets the URI for routing, either from the provided argument or by defaulting 
     * to the current request URI (whats the user type in). It then retrieves the routing information 
     * using `handleRequest`. If a valid route is found, it instantiates the designated controller and calls the specified 
     * method. Errors are handled if either the controller class or method does not exist.
     *
     * @param string|null $uri Optional. The specific segment of the router index to process. If not provided, 
     *                          the URI is determined based on the current request. Examples include "/string" 
     *                          or "/hello/world".
     * @throws ErrorPage If the controller class or method is not found, or if other routing errors occur.
     */
    public function dispatchToController(?string $uri = null)
    {

        // Find another way to re organised this code.
        $this->setUri($uri);

        $route = $this->handleRequest();

        $controllerClass = $route["controller"]["class"];
        $method = $route["controller"]["method"];


        if (!class_exists($controllerClass)) {
            $this->handleError(404, "Controller class $controllerClass not found");
        }

        $controller = $this->getControllerObject($controllerClass);


        if (!method_exists($controller, $method)) {
            $this->handleError(404, "Method $method of class $controllerClass not found");
        }

        call_user_func_array([$controller, $method], $route["query"]);
    }


    /**
     * Dispatches the request to the specified module router based on the provided or current URI.
     *
     * This method sets the full URI and its first segment. It then checks if a route corresponding to the 
     * first segment exists in the route list. If the route exists, it proceeds to access the module router 
     * associated with this route. If no route is found, an error is handled by generating a 404 error page.
     *
     * @param string|null $uri Optional. The URI to dispatch. If null, the URI is obtained from the current request.
     * @throws ErrorPage If the URI does not match any configured route or other routing errors occur.
     */
    public function dispatchToModule(?string $uri = null)
    {
        $this->setUri($uri);

        if (!$this->routeList->hasKey(null, $this->firstSegmentUri)) {
            $this->handleError(404, "URI not found");
        }

        // Retrieve the module class from the route list and dispatch to it
        $moduleRouterClass = $this->routeList->get(null, $this->firstSegmentUri);
        $this->accessToModuleRouter($moduleRouterClass);
    }


    /**
     * Link route to object controller.
     * @param string $controllerClass Controller class to route the URL to.
     * @param string $uri Router index for specifying track.
     * @param string $requestMethod The HTTP method.
     * @param string $method Method of that class to execute upon successful routing.
     */
    public function linkController(string $controllerClass, string $uri, string $requestMethod, string $method)
    {
        $this->routeList->add($requestMethod, $uri, ["class" => $controllerClass, "method" => $method]);
    }


    /**
     * Link route to other module entry router.
     * @param string $entryRouterClass Class of the router in the module.
     * @param string $uri Router index to access the router of that class.
     */
    public function linkRouter(string $entryRouterClass, string $uri = "/")
    {
        $this->routeList->add(null, $uri, $entryRouterClass);
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
            echo $errorCode . "<br>";
            echo $description;
        }
        die();
    }


    protected function getRouteList(): RouteList
    {
        return $this->routeList;
    }


    /**
     * Attempts to dispatch a request to a module router based on the current URI segment.
     *
     * This method first checks if the specified module router class exists. If it does, it instantiates 
     * the router with the session dependency and retrieves its routing list. Depending on whether specific
     * routing keys exist in the module's route list, it either dispatches directly to a controller or
     * delegates further module dispatching.
     *
     * Optimizations include reducing redundant checks for the request method and improving route search efficiency.
     *
     * @param mixed $moduleRouterClass The class name of the module router to be instantiated.
     * @throws ErrorPage If the module router class does not exist or other routing errors occur.
     */
    private function accessToModuleRouter(mixed $moduleRouterClass)
    {
        if (!class_exists($moduleRouterClass)) {
            $this->handleError(404, "Module $moduleRouterClass not found");
        }

        // Instantiate the module router with the session dependency.
        $moduleRouter = new $moduleRouterClass($this->session);

        $moduleRouteList = $moduleRouter->getRouteList();
        $nextSegmentURL = Helper::removeFirstSegment($this->uri);

        // Dispatch to the appropriate method based on the existence of route keys.
        if (!$moduleRouteList->hasGetRouterKey()) {
            $moduleRouter->dispatchToController($nextSegmentURL);
        } else if ($moduleRouteList->hasUriKey($nextSegmentURL)) {
            $moduleRouter->dispatchToController($nextSegmentURL);
        } else {
            $moduleRouter->dispatchToModule($nextSegmentURL);
        }
    }


    /**
     * Processes the incoming request to determine the appropriate routing based on method and URI.
     *
     * This method retrieves the current request details and checks for their validity. It extracts the HTTP method,
     * URI, and any additional query parameters from the request. Based on these details, it identifies the appropriate
     * controller and associated method from the route list to handle the request. If the request details are missing or
     * incomplete, or if the specified route does not exist, an error is handled accordingly.
     *
     * @return array An associative array that contains:
     *               - 'controller': The controller and method responsible for handling the request.
     *               - 'query': An array of additional query parameters associated with the request.
     * @throws ErrorPage If the request is improperly formatted (resulting in a 'Bad Request' error) or if 
     *                   the specified route is not found (leading to a 'Not Found' error).
     */
    private function handleRequest(): array
    {
        $request = $this->getRequest();

        if (!$request) {
            $this->handleError(400, "Bad Request");
        }

        $controllerData = $this->routeList->get($request["method"], $request["uri"]);

        if (!$controllerData) {
            $this->handleError(404, "URI not found");
        }

        return [
            "controller" => $controllerData,
            "query" => [$request["query"]]
        ];
    }


    /**
     * Get the controller for that specific type of class.
     * Assigned Session dependency if need
     */
    private function getControllerObject($controllerClass): mixed
    {
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


    /**
     * Sets the full URI and its first segment to class properties.
     *
     * This method assigns the provided URI or, if not provided, retrieves the current request URI. 
     * It then determines the first segment of this URI using a helper function. If no segments are 
     * identified (i.e., the URI does not contain any slashes), it falls back to using the full URI 
     * as the first segment URI. This method is used to initialize and prepare URI data for routing decisions.
     *
     * @param string|null $uri Optional. The URI to be set. If not provided, the URI is obtained 
     *                          from the current request.
     */
    private function setUri(string|null $uri)
    {
        $this->uri = $uri ?: $this->getUri();
        $this->firstSegmentUri = Helper::getFirstSegment($this->uri) ?: $this->uri;
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

        if (!$method || !$this->firstSegmentUri || $query === null) {
            return null;
        }

        return ["method" => $method, "uri" => $this->firstSegmentUri, "query" => $query];
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

<?php

declare(strict_types=1);

namespace Core;

use Exception;
use Helpers\Helper;
use Core\SessionHandler;
use Helpers\DependencyInjector;


// Interface just for re-organise the class method. Delete after finish
interface RouterInterface {
    public function dispatchToController(?string $uri = null);
    public function dispatchToModule(?string $uri = null);
    public function linkController(string $controllerClass, string $uri, string $requestMethod, string $method);
    public function linkRouter(string $entryRouterClass, string $uri = "/");
}


class Router implements RouterInterface
{

    protected array $routeList = [];
    protected SessionHandler $session;

    private ?string $uri = null;
    private ?string $trimmedUri = null;


    // Somehow make the router independence from the SessionHandler and make the sessionHandler act
    // as an injection dependency.
    public function __construct(SessionHandler $session = new SessionHandler())
    {
        $this->session = $session;
    }


    /**
     * Link route to other module entry router.
     * @param string $entryRouterClass Class of the router in the module.
     * @param string $uri Router index to access the router of that class.
     */
    public function linkRouter(string $entryRouterClass, string $uri = "/")
    {
        $this->routeList["ROUTER"][$uri] = $entryRouterClass;
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
        $this->routeList[$requestMethod][$uri] = ["class" => $controllerClass, "method" => $method];
    }


    /**
     * Dispatch the request to the assigned module router for further dispatching.
     * @throws ErrorPage If any routing error occurs.
     */
    public function dispatchToModule(?string $uri = null)
    {
        $this->setUri($uri);

        if (!isset($this->routeList["ROUTER"][$this->trimmedUri])) {
            $this->handleError(404, "URI not found");
        }

        $this->accessToModuleRouter($this->routeList["ROUTER"][$this->trimmedUri]);
    }


    /**
     * Try to access to that module router
     * @throws ErrorPage If any routing error occurs.
     */
    private function accessToModuleRouter(mixed $moduleRouterClass)
    {

        if (!class_exists($moduleRouterClass)) {
            $this->handleError(404, "Modules of $moduleRouterClass not found");
        }

        // Instantiate the module router with the session dependency.
        $moduleRouter = new $moduleRouterClass($this->session);

        $moduleRouterList = $moduleRouter->getRouteList(); // Get list[first_key] [second_key]

        $nextSegmentURL = Helper::removeFirstSegment($this->uri);

        /***
         * REFACTOR THE CODE
         * - Make the routerList an Object that has
         *      + containRouterKey()
         *      + hasFoundKey(method, uri)
         * 
         * - Optimising the dispatchToController. Right now they check the request method TWICE, here and inside the getRequest METHOD.
         * - Make that so that the use can both use the dispatchToController standalone and also can put that code here.
         * - Make the router search faster.
         */
        if (!Helper::containsROUTERKey($moduleRouterList)) {
            $moduleRouter->dispatchToController($nextSegmentURL);
        } else {
            if ($this->hasMatchKey($moduleRouterList, $nextSegmentURL)) {
                $moduleRouter->dispatchToController($nextSegmentURL);
            } else {
                $moduleRouter->dispatchToModule($nextSegmentURL);
            }
        }
    }


    private function hasMatchKey($routeList, $uriSegment)
    {
        $currentRequestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        return isset($routeList[$currentRequestMethod]) && array_key_exists($uriSegment, $routeList[$currentRequestMethod]);
    }


    protected function getRouteList(): array
    {
        return $this->routeList;
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
        die();
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
     * as the trimmed URI. This method is used to initialize and prepare URI data for routing decisions.
     *
     * @param string|null $uri Optional. The URI to be set. If not provided, the URI is obtained 
     *                          from the current request.
     */
    private function setUri(string|null $uri)
    {
        $this->uri = $uri ?: $this->getUri();
        $this->trimmedUri = Helper::getFirstSegment($this->uri) ?: $this->uri;
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

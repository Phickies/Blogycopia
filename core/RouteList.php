<?php

declare(strict_types=1);

namespace Core;


/**
 * Manages the routing list, allowing for the registration and retrieval of routes based on request methods and URIs.
 *
 * This class provides functionality to add, retrieve, and check the existence of routes within an internal list,
 * where routes are differentiated by HTTP methods and URI patterns.
 */
class RouteList
{

    /**
     * Default key for router entries when no specific HTTP method is provided.
     */
    private const GET_ROUTER = "ROUTER";

    private array $list;


    public function __construct()
    {
        $this->list = [];
    }


    /**
     * Adds a route to the list with an optional specific HTTP method and URI.
     *
     * @param string|null $requestMethod The HTTP method for the route. If null, defaults to a generic 'ROUTER' key.
     * @param string $uri The URI that the route corresponds to.
     * @param string|array $destinationClass The controller class or an array specifying the controller and method.
     */
    public function add(?string $requestMethod = null, string $uri, string|array $destinationClass)
    {
        if ($requestMethod === null) {
            $requestMethod = self::GET_ROUTER;
        }
        $this->list[$requestMethod][$uri] = $destinationClass;
    }


    /**
     * Retrieves the destination class or plus its method for a given HTTP method and URI.
     *
     * @param string|null $requestMethod The HTTP method of the route to retrieve. Defaults to 'ROUTER' if null.
     * @param string $uri The URI of the route to retrieve.
     * @return string|array|null The controller class or an array specifying the controller and method associated with the route. 
     *                              Return null if not found route
     */
    public function get(?string $requestMethod = null, string $uri): string|array|null
    {
        if ($requestMethod === null) {
            $requestMethod = self::GET_ROUTER;
        }

        if (!isset($this->list[$requestMethod][$uri])) {
            return null;
        }

        return $this->list[$requestMethod][$uri];
    }


    /**
     * Retrieves the entire list of routes.
     *
     * @return array The array of all routes grouped by request methods.
     */
    public function getList()
    {
        return $this->list;
    }


    /**
     * Checks if the 'ROUTER' key is present in the route list keys.
     *
     * @return bool Returns true if the 'ROUTER' key is present, otherwise false.
     */
    public function hasGetRouterKey(): bool
    {
        foreach (array_keys($this->list) as $key) {
            if (strpos($key, self::GET_ROUTER) === 0) {
                return true;
            }
        }
        return false;
    }


    /**
     * Determines whether a specific route exists for a given HTTP method and URI.
     *
     * @param string|null $requestMethod The HTTP method to check for. Defaults to 'ROUTER' if null.
     * @param string $uri The URI to check within the specified method.
     * @return bool Returns true if the route exists, otherwise false.
     */
    public function hasKey(?string $requestMethod = null, string $uri): bool
    {
        if ($requestMethod === null) {
            $requestMethod = self::GET_ROUTER;
        }
        return isset($this->list[$requestMethod][$uri]);
    }


    /**
     * Checks if a specific URI exists within the routes for the current HTTP request method.
     *
     * @param string $uri The URI to check.
     * @return bool Returns true if the URI exists for the current request method, otherwise false.
     */
    public function hasUriKey($uri): bool
    {
        $currentRequestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        return isset($this->list[$currentRequestMethod]) && array_key_exists($uri, $this->list[$currentRequestMethod]);
    }
}

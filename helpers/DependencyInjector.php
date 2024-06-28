<?php

namespace Helpers;

use ReflectionClass;
use ReflectionException;

class DependencyInjector
{
    private $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function createController($controllerClass)
    {
        try {
            $reflectionClass = new ReflectionClass($controllerClass);
            $constructor = $reflectionClass->getConstructor();

            if ($constructor !== null) {
                $params = $constructor->getParameters();
                $initParams = [];

                foreach ($params as $param) {
                    // Check if the parameter type is 'SessionHandler'
                    if ($param->hasType() && $param->getType()->getName() === 'App\Session\SessionHandler') {
                        $initParams[$param->getName()] = $this->session;
                    } else {
                        // Assume the default value or null if no default is available
                        $initParams[$param->getName()] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
                    }
                }

                return $reflectionClass->newInstanceArgs($initParams);
            }

            // No constructor or no parameters
            return new $controllerClass;
        } catch (ReflectionException $e) {
            // Handle the error appropriately
            echo "Error: " . $e->getMessage();
        }
    }
}

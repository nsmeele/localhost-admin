<?php

namespace Routing;

use Symfony\Component\Routing\Loader\AttributeClassLoader;
use Symfony\Component\Routing\Route;

class AttributeRouteLoader extends AttributeClassLoader
{

    /**
     * @inheritDoc
     */
    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, object $attr)
    {
        $route->setDefault('_controller', [$method->getDeclaringClass()->getName(), $method->getName()]);
    }
}
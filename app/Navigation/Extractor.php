<?php

// src/Navigation/NavigationExtractor.php

namespace Navigation;

use Symfony\Component\Routing\RouteCollection;
use Attribute\MenuLabel;
use ReflectionMethod;

class Extractor
{
    public function extract(RouteCollection $routes): array
    {
        $navigation = [];

        foreach ($routes as $name => $route) {
            $defaults = $route->getDefaults();

            // Controller moet array zijn: [class, method]
            if (!isset($defaults['_controller']) || !is_array($defaults['_controller'])) {
                continue;
            }

            [$class, $method] = $defaults['_controller'];

            $meta = $this->getMenuMetadata($class, $method);
            if ($meta === null) {
                continue;
            }

            $navigation[] = [
                'name' => $name,
                'path' => $route->getPath(),
                'label' => $meta['label'],
                'icon' => $meta['icon'],
            ];
        }

        return $navigation;
    }

    private function getMenuMetadata(string $class, string $method): ?array
    {
        $methodReflection = new ReflectionMethod($class, $method);
        $attrs = $methodReflection->getAttributes(MenuLabel::class);

        if (!$attrs) {
            return null;
        }

        /** @var MenuLabel $instance */
        $instance = $attrs[0]->newInstance();

        return [
            'label' => $instance->label,
            'icon' => $instance->icon,
        ];
    }
}

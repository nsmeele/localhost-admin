<?php

namespace Component\Navigation;

use Service\NavigationService;

final class MenuItem
{
    public function __construct(
        public readonly NavigationService $navigation,
        public readonly string $url,
        public readonly string $uri,
        public readonly string $title,
        public string $path,
        public string $icon = 'chevron-right',
        public readonly ?MenuItem $parent = null,
        public array $children = [],
    ) {
    }

    public function getParents(): array
    {
        $parents = [];
        $parent  = $this->parent;

        while ($parent) {
            $parents[] = $parent;

            if ($parent->parent instanceof MenuItem) {
                $parent = $parent->parent;
            } else {
                $parent = false;
            }
        }

        return $parents;
    }

    public function getLabel(): string
    {
        $navigation = $this->navigation;
        return $navigation->getNavLabelByUri($this->uri);
    }
}

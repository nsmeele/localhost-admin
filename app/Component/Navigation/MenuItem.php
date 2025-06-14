<?php

namespace Component\Navigation;

final class MenuItem
{
    public function __construct(
        public readonly string $url,
        public readonly string $uri,
        public string $title,
        public string $path,
        public string $icon = 'chevron-right',
        public readonly ?MenuItem $parent = null,
        public array $children = [],
        private ?\SplFileInfo $file = null,
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

    public function setIcon(string $icon): MenuItem
    {
        $this->icon = $icon;
        return $this;
    }

    public function setTitle(string $title): MenuItem
    {
        $this->title = $title;
        return $this;
    }
}

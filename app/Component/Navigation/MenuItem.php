<?php

namespace Component\Navigation;

final class MenuItem
{
    public function __construct(
        private(set) readonly string $url,
        public readonly string $uri,
        private(set) string $title,
        public string $path,
        private(set) string $icon = 'chevron-right',
        private(set) readonly ?MenuItem $parent = null,
        public array $children = [],
        private(set) readonly ?\SplFileInfo $file = null,
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

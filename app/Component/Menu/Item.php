<?php

namespace Component\Menu;

final class Item
{
    private(set) string $menuLabel;

    public function __construct(
        private(set) readonly string $url,
        public readonly string $uri,
        private(set) string $title,
        public string $path,
        private(set) string $icon = 'chevron-right',
        private(set) readonly ?Item $parent = null,
        public array $children = [],
        private(set) readonly ?\SplFileInfo $file = null,
    ) {
        $this->menuLabel = $title;
    }

    public function getParents(): array
    {
        $parents = [];
        $parent  = $this->parent;

        while ($parent) {
            $parents[] = $parent;

            if ($parent->parent instanceof Item) {
                $parent = $parent->parent;
            } else {
                $parent = false;
            }
        }

        return $parents;
    }

    public function setIcon(string $icon): Item
    {
        $this->icon = $icon;
        return $this;
    }

    public function setTitle(string $title): Item
    {
        $this->title     = $title;
        $this->menuLabel = $title;
        return $this;
    }
}

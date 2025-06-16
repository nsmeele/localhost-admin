<?php

namespace Component\Navigation;

final class Item implements \Stringable
{
    public function __construct(
        private readonly string $url,
        private(set) readonly string $uri,
        private readonly string $menuLabel,
        private(set) readonly string $routeName,
        private readonly ?string $icon = null,
        private(set) ?Item $parent = null,
        private(set) array $children = [],
    ) {
    }

    public function isCurrent(): bool
    {
        global $request;
        return $request->get('_route') === $this->routeName;
    }

    public function hasActiveChild(): bool
    {
        foreach ($this->children as $child) {
            if ($child->isCurrent() || $child->hasActiveChild()) {
                return true;
            }
        }

        return false;
    }

    public function addChild(Item $child): Item
    {
        $child->parent = $this;
        $this->children[] = $child;
        return $this;
    }

    public function getMenuLabel(): string
    {
        return $this->menuLabel;
    }

    private function getIconHtml(): string
    {
        return $this->icon ? sprintf(
            '<span class="mr-1.5"><i class="fa-solid fa-fw fa-%s"></i></span>',
            htmlspecialchars($this->icon)
        ) : '';
    }

    public function __toString(): string
    {
        $labelHtml = htmlspecialchars($this->menuLabel);

        return sprintf(
            '<a href="%s">%s</a>',
            htmlspecialchars($this->url),
            $this->getIconHtml() . $labelHtml
        );
    }
}

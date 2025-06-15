<?php

namespace Component\Navigation;

final readonly class Item implements \Stringable
{

    public function __construct(
        private string $url,
        private string $uri,
        private string $menuLabel,
        private string $routeName,
        private string $icon = 'chevron-right',
        private ?Item $parent = null,
    ) {
    }

    public function isCurrent() : bool
    {
        global $request;
        return $request->get('_route') === $this->routeName;
    }

    public function getMenuLabel() : string
    {
        return $this->menuLabel;
    }

    public function __toString() : string
    {
        $iconHtml  = $this->icon ? sprintf(
            '<span class="mr-1.5"><i class="fa-solid fa-fw fa-%s"></i></span>',
            htmlspecialchars($this->icon)
        ) : '';
        $labelHtml = htmlspecialchars($this->menuLabel);

        return sprintf(
            '<a href="%s">%s</a>',
            htmlspecialchars($this->url),
            $iconHtml.$labelHtml
        );
    }

}
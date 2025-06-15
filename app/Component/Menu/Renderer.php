<?php

namespace Component\Menu;

use Component\MenuComponent;

final readonly class Renderer implements \Stringable
{
    public function __construct(
        private MenuComponent $menuComponent,
    ) {
    }

    private function fetchLevelItem(
        Item $navItem,
        bool $recursive = true,
        int $depth = 0,
    ): string {
        $navItemHtml = sprintf(
            '<a href="%s" class="%s">%s%s</a>',
            $navItem->url,
            'flex',
            (! empty($navItem->icon) ? '<span class="mr-1"><i class="fa-solid fa-fw fa-' . $navItem->icon . '"></i></span>' : ''),
            $navItem->title
        );

        $navItemClass = [];

        global $request;

        if (strpos($request->getRequestUri(), $navItem->uri) !== false) {
            $navItemClass[] = 'active';
        }

        $childrenHtml = '';

        if ($recursive && ! empty($navItem->children)) {
            $childrenHtml = $this->fetchLevel($navItem->children, $recursive, $depth + 1);
        }

        return sprintf(
            '<li class="%s">%s</li>',
            join(' ', $navItemClass),
            $navItemHtml . $childrenHtml
        );
    }

    private function fetchLevel(
        array $navItems,
        bool $recursive = true,
        int $depth = 0,
        bool $wrap = true
    ): string {
        $html = '';

        if (empty($navItems)) {
            return '';
        }

        foreach ($navItems as $navItem) {
            $html .= $this->fetchLevelItem($navItem, $recursive, $depth);
        }

        if ($wrap) {
            $html = sprintf($this->getContainerFormat(), $depth, $html);
        }

        return $html;
    }

    private function getContainerFormat(): string
    {
        return '<ul data-depth="%d">%s</ul>';
    }

    public function __toString(): string
    {
        $html = $this->fetchLevel(
            $this->menuComponent->getItems(),
            true,
            0,
            false
        );

        return sprintf($this->getContainerFormat(), 0, $html);
    }
}

<?php

namespace Component;

use Component\Navigation\MenuItem;
use Service\NavigationService;
use Service\RequestService;

final readonly class MenuComponent implements \Stringable
{
    public function __construct(
        protected NavigationService $navigationService,
        protected bool $includeHome = true,
    ) {
    }

    private function fetchLevelItem(
        MenuItem $navItem,
        bool $recursive = true,
        int $depth = 0,
    ): string {
        $navItemHtml = sprintf(
            '<a href="%s" class="%s">%s%s</a>',
            $navItem->url,
            join(' ', ['flex']),
            (! empty($navItem->icon) ? '<span class="ms-1"><i class="fa-solid fa-' . $navItem->icon . '"></i></span>' : ''),
            $navItem->title
        );

        $navItemClass = ['nav-item'];

        global $request;

        if (strpos($request->getRequestUri(), $navItem->uri) !== false) {
            $navItemClass[] = 'text-orange-500';
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
        if (! $this->includeHome) {
            return $this->fetchLevel(
                $this->navigationService->getItems($this->includeHome),
            );
        }

        $homeItem = $this->navigationService->getItems(true)[ 0 ];

        $html = $this->fetchLevelItem(
            $homeItem,
            false,
            0
        );

        $html .= $this->fetchLevel($homeItem->children, true, 0, false);

        return sprintf($this->getContainerFormat(), 0, $html);
    }
}

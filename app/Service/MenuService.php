<?php

namespace Service;

#[\AllowDynamicProperties]
class MenuService
{
    public function __construct(
        protected NavigationService $navigationService,
        protected bool $includeHome = true,
    ) {
    }

    protected function fetchLevelItem(
        NavigationItemService $navItem,
        bool $recursive = true,
        int $depth = 0
    ): string {
        $navLabels      = $this->navigationService->getNavLabels();
        $navLinkClasses = [
            'nav-link',
            'd-flex',
            'align-items-center',
        ];

        $navItemHtml = sprintf(
            '<a href="%s" class="%s">%s%s</a>',
            $navItem->url,
            join(' ', $navLinkClasses),
            (! empty($navItem->icon) ? '<span><i class="fa-solid fa-' . $navItem->icon . ' me-2"></i></span>' : ''),
            $navLabels[ $navItem->uri ]
        );

        $navItemClass = ['nav-item'];
        if (strpos(RequestService::getInstance()->getRequestUri(), $navItem->uri) !== false) {
            $navItemClass[] = 'active';
        }

        $childrenHtml = '';

        if ($recursive && ! empty($navItem->children)) {
            $childrenHtml = $this->fetchLevel($navItem->children, $recursive, $depth + 1);
        }

        return sprintf('<li class="%s">%s</li>', join(' ', $navItemClass), $navItemHtml . $childrenHtml);
    }

    public function fetchLevel(
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

    protected function getContainerFormat()
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

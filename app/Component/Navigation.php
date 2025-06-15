<?php

namespace Component;

use Component\Navigation\Item;

readonly class Navigation implements \Stringable
{

    /**
     * @var Item[] $items
     */
    private array $items;

    public function __construct(array $menu)
    {
        $this->items = $this->resolveExtractedMenuItemsToItems($menu);
    }

    private function resolveExtractedMenuItemsToItems(array $extractedMenuItems) : array
    {
        $items = [];
        foreach ($extractedMenuItems as $item) {
            $items[ $item[ 'path' ] ] = new Item(
                url: HOME_URL.$item[ 'path' ],
                uri: $item[ 'path' ],
                menuLabel: $item[ 'label' ],
                routeName: $item[ 'name' ] ?? '',
                icon: $item[ 'icon' ] ?? 'chevron-right',
                parent: $item[ 'parent' ] ?? null,
            );
        }
        return $items;
    }

    public function getCurrentRouteName() : string
    {
        global $request;
        return $request->get('_route') ?? '';
    }

    private function getContainerFormat(): string
    {
        return '<ul data-depth="%d">%s</ul>';
    }

    public function getMenuItem(string $path) : ?Item
    {
        return $this->items[ $path ] ?? null;
    }

    public function __toString() : string
    {
        $html = '';

        foreach ($this->items as $item) {
            $html .= sprintf("<li class=\"%s\">%s</li>", $item->isCurrent() ? 'active' : '', $item);
        }

        return sprintf(
            $this->getContainerFormat(),
            0,
            $html
        );
    }
}
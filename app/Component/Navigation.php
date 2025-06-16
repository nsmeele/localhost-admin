<?php

namespace Component;

use Component\Navigation\Item;

final class Navigation implements \Stringable
{
    /**
     * @var Item[] $items
     */
    private readonly array $items;

    private array $itemMapByRouteName = [];

    public function __construct(array $menu)
    {
        $this->items = $this->resolveExtractedMenuItemsToItems($menu);
    }

    private function resolveExtractedMenuItemsToItems(array $extractedMenuItems): array
    {
        $items        = [];
        $lazyChildren = [];

        foreach ($extractedMenuItems as $item) {
            $navItem = new Item(
                url: HOME_URL . $item[ 'path' ],
                uri: $item[ 'path' ],
                menuLabel: $item[ 'label' ],
                routeName: $item[ 'name' ] ?? '',
                icon: $item[ 'icon' ] ?? null,
            );

            $this->itemMapByRouteName[ $item[ 'name' ] ] = $navItem;

            $parentItem = $this->itemMapByRouteName[ $item[ 'parent' ] ] ?? null;

            if (! empty($item[ 'parent' ]) && ! isset($parentItem)) {
                $lazyChildren[] = [
                    'item'             => $navItem,
                    'parentIdentifier' => $item[ 'parent' ],
                ];
                continue;
            }

            if ($parentItem) {
                $parentItem->addChild($navItem);
            } else {
                $items[ $item[ 'name' ] ?? '' ] = $navItem;
            }
        }

        $this->resolveLazyChildren($lazyChildren);

        return $items;
    }

    private function resolveLazyChildren(array $lazyChildren): void
    {
        foreach ($lazyChildren as $child) {
            $parentIdentifier = $child[ 'parentIdentifier' ];
            $this->itemMapByRouteName[ $parentIdentifier ]?->addChild($child[ 'item' ]);
        }
    }

    private function getContainerFormat(): string
    {
        return '<ul data-depth="%d">%s</ul>';
    }

    public function getMenuItem(string $path): ?Item
    {
        return $this->itemMapByRouteName[ $path ] ?? null;
    }


    private function getLevelHtml(
        Item $item,
        int $depth = 0
    ): string {
        return sprintf(
            '<li class="%s">%s</li>',
            $item->isCurrent() || $item->hasActiveChild() ? 'active' : '',
            $item . $this->getContainerHtml($item->children, $depth + 1)
        );
    }

    /**
     * @param  Item[]  $items
     * @param  int  $depth
     * @return string
     */
    private function getContainerHtml(array $items, int $depth = 0): string
    {
        if (empty($items)) {
            return '';
        }

        $html = '';

        foreach ($items as $item) {
            $html .= $this->getLevelHtml($item, $depth);
        }

        return sprintf(
            $this->getContainerFormat(),
            $depth,
            $html
        );
    }

    public function __toString(): string
    {
        return $this->getContainerHtml($this->items);
    }
}

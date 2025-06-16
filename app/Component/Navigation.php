<?php

namespace Component;

use Component\Navigation\Item;

final class Navigation implements \Stringable
{
    /**
     * @var Item[] $items
     */
    private readonly array $items;

    private array $itemsByRouteName = [];

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
                icon: $item[ 'icon' ] ?? 'chevron-right',
            );

            $this->itemsByRouteName[ $item[ 'name' ] ] = $navItem;

            if (! empty($item[ 'parent' ]) && ! isset($items[ $item[ 'parent' ] ])) {
                $lazyChildren[] = [
                    'item'             => $navItem,
                    'parentIdentifier' => $item[ 'parent' ],
                ];
                continue;
            }

            if (! empty($item[ 'parent' ]) && isset($items[ $item[ 'parent' ] ])) {
                $parentItem = $items[ $item[ 'parent' ] ];
                $parentItem->addChild($navItem);
            } else {
                $items[ $item[ 'name' ] ?? '' ] = $navItem;
            }
        }

        foreach ($lazyChildren as $child) {
            $parentIdentifier = $child[ 'parentIdentifier' ];

            if (isset($items[ $parentIdentifier ])) {
                $items[ $parentIdentifier ]->addChild($child[ 'item' ]);
            } else {
                throw new \RuntimeException(
                    sprintf(
                        'Parent item "%s" not found for child item "%s".',
                        $parentIdentifier,
                        $child[ 'item' ]->getMenuLabel(),
                    )
                );
            }
        }

        return $items;
    }

    private function getContainerFormat(): string
    {
        return '<ul data-depth="%d">%s</ul>';
    }

    public function getMenuItem(string $path) : ?Item
    {
        return $this->itemsByRouteName[ $path ] ?? null;
    }

    private function getLevelHtml(
        Item $item,
        int $depth = 0
    ): string {
        $children = null;
        if (! empty($item->children)) {
            $childHtml = '';
            foreach ($item->children as $child) {
                $childHtml .= $this->getLevelHtml($child, $depth + 1);
            }
            $children = sprintf(
                $this->getContainerFormat(),
                $depth + 1,
                $childHtml
            );
        }

        return sprintf(
            '<li class="%s">%s</li>',
            $item->isCurrent() ? 'active' : '',
            $item . ($children ?? '')
        );
    }

    public function __toString(): string
    {
        $html = '';

        foreach ($this->items as $item) {
            if ($item->parent === null) {
                $html .= $this->getLevelHtml($item);
            }
        }

        return sprintf(
            $this->getContainerFormat(),
            0,
            $html
        );
    }
}

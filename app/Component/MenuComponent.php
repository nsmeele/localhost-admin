<?php

namespace Component;

use Component\Menu\Item;
use Service\MenuService;
use Symfony\Component\Finder\Finder;

final class MenuComponent
{
    /**
     * @var Item[]
     */
    protected array $items = array ();

    /**
     * @var Item[]
     */
    protected array $itemMap = array ();

    public function setItems(array $items): MenuComponent
    {
        $this->items = $items;
        $this->setItemMap($items);
        return $this;
    }

    public function addItem(Item $item): MenuComponent
    {
        $this->items[]               = $item;
        $this->itemMap[ $item->uri ] = $item;
        return $this;
    }

    public function setItemMap(array $items): MenuComponent
    {
        foreach ($items as $item) {
            if ($item instanceof Item) {
                $this->itemMap[ $item->uri ] = $item;
                if (! empty($item->children)) {
                    $this->setItemMap($item->children);
                }
            }
        }
        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemByUri(string $uri): ?Item
    {
        return $this->itemMap[ $uri ] ?? null;
    }
}

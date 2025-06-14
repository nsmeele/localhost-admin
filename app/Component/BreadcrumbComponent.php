<?php

namespace Component;

use Component\Menu\Item;

final readonly class BreadcrumbComponent implements \Stringable
{
    public function __construct(
        protected Item $currentNode
    ) {
    }

    public function __toString(): string
    {
        $parents = $this->currentNode->getParents();

        $html = '';
        foreach (array_reverse($parents) as $parent) {
            $html .= '<li class="breadcrumb-item">' .
                '<a href="' . $parent->url . '">' . $parent->title . '</a>' .
                '</li>';
        }

        $html .= '<li class="breadcrumb-item">' . $this->currentNode->title . '</li>';

        return sprintf('<ol class="breadcrumb">%s</ol>', $html);
    }
}

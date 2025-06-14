<?php

namespace Component;

use Component\Navigation\MenuItem;
use Service\NavigationService;

final readonly class BreadcrumbComponent implements \Stringable
{
    public function __construct(
        protected MenuItem $currentNode
    ) {
    }

    public function __toString(): string
    {
        $navigation = $this->currentNode->navigation;
        $parents    = $this->currentNode->getParents();

        $html = '';
        foreach (array_reverse($parents) as $parent) {
            if ($parent->uri === '/') {
                $html .= '<li class="breadcrumb-item">' .
                    '<a href="' . $parent->url . '"><i class="fa-solid fa-' . $parent->icon . '"></i></a>' .
                    '</li>';

                continue;
            }

            $html .= '<li class="breadcrumb-item">' .
                '<a href="' . $parent->url . '">' . $navigation->getNavLabelByUri($parent->uri) . '</a>' .
                '</li>';
        }

        $html .= '<li class="breadcrumb-item">' . $navigation->getNavLabelByUri($this->currentNode->uri) . '</li>';

        return sprintf('<ol class="breadcrumb">%s</ol>', $html);
    }
}

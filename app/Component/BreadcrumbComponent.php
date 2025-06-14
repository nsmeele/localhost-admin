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

    public function __toString() : string
    {
        $parents = $this->currentNode->getParents();

        $html = '';
        foreach (array_reverse($parents) as $parent) {
            if ($parent->uri === '/') {
                $html .= '<li class="breadcrumb-item">'.
                    '<a href="'.$parent->url.'"><i class="fa-solid fa-'.$parent->icon.'"></i></a>'.
                    '</li>';

                continue;
            }

            $html .= '<li class="breadcrumb-item">'.
                '<a href="'.$parent->url.'">'.$parent->title.'</a>'.
                '</li>';
        }

        $html .= '<li class="breadcrumb-item">'.$this->currentNode->title.'</li>';

        return sprintf('<ol class="breadcrumb">%s</ol>', $html);
    }
}

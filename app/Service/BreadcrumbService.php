<?php

namespace Service;

class BreadcrumbService
{
    public function __construct(
        protected NavigationItemService $currentNode
    ) {
    }

    protected function fetchItem(NavigationItemService $item)
    {
    }

    public function __toString(): string
    {
        /**
         * @var NavigationService $navigation
         */
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

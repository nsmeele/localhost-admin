<?php

namespace Service;

use Trait\DynamicPropertiesTrait;

#[\AllowDynamicProperties]
class NavigationItemService
{
    use DynamicPropertiesTrait;

    protected function getDefaultProperties(): array
    {
        return [
            'navigation' => null,
            'url'        => null,
            'uri'        => null,
            'title'      => null,
            'path'       => null,
            'icon'       => 'chevron-right',
            'parent'     => null,
            'children'   => array (),
        ];
    }

    public function getParents(): array
    {
        $parents = [];
        $parent  = $this->parent;

        while ($parent) {
            $parents[] = $parent;

            if ($parent->parent instanceof self) {
                $parent = $parent->parent;
            } else {
                $parent = false;
            }
        }

        return $parents;
    }

    public function getLabel(): string
    {
        $navigation = $this->navigation;
        return $navigation->getNavLabelByUri($this->uri);
    }
}

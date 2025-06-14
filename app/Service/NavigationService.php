<?php

namespace Service;

use Component\Navigation\MenuItem;

final class NavigationService
{
    protected array $navLabels = [];

    /**
     * @var MenuItem[]
     */
    protected array $items = array ();

    /**
     * @var MenuItem[]
     */
    protected array $itemMap = array ();

    public function __construct(
        protected readonly string $basePath,
    ) {
        $homeNavItem = new MenuItem(
            navigation: $this,
            url: HOME_URL,
            uri: '/',
            title: 'Home',
            path: false,
            icon: 'home',
        );

        $this->items          = [$homeNavItem];
        $this->itemMap[ '/' ] = $homeNavItem;
    }

    public function setFromPath(
        ?string $path = null,
        bool $recursive = true,
    ): NavigationService {
        if (! $path) {
            $path = $this->basePath;
        }

        $homeNavItem           = $this->items[ 0 ];
        $homeNavItem->children = $this->getItemsFromPath($homeNavItem, $path, $recursive);

        $this->setNavLabelsFromItems($this->items);

        return $this;
    }

    public function getItemsFromPath(
        MenuItem $parent,
        ?string $path = null,
        bool $recursive = true,
        int $depth = 0,
    ): array {
        if (! $path) {
            $path = $this->basePath;
        }

        $basePath = $this->basePath;

        $navItems = [];
        $files    = array_diff(scandir($path), array ('.', '..'));

        foreach ($files as $file) {
            if ($file === 'index.php') {
                // we handle that differently
                continue;
            }

            $subPath = $path . '/' . $file;
            $uri     = str_replace($basePath, '', $path . '/' . basename($file, '.php'));

            $navigationItem = new MenuItem(
                navigation: $this,
                url: HOME_URL . $uri,
                uri: $uri,
                title: ucfirst(strtolower(basename($subPath, '.php'))),
                path: $subPath,
                parent: $parent,
            );

            if (is_dir($subPath)) {
                $navigationItem->path .= '/index.php';

                if ($recursive) {
                    $navigationItem->children = $this->getItemsFromPath(
                        $navigationItem,
                        $subPath,
                        $recursive,
                        $depth + 1
                    );
                }
            }

            $this->itemMap[ $uri ] = $navigationItem;
            $navItems[ $uri ]      = $navigationItem;
        }

        return $navItems;
    }

    public function setNavLabelsFromItems(array $items): void
    {
        foreach ($items as $item) {
            $this->navLabels[ $item->uri ] = $item->title;
            if (! empty($item->children)) {
                $this->setNavLabelsFromItems($item->children);
            }
        }
    }

    public function setNavLabels(array $labels): NavigationService
    {
        $this->navLabels = array_merge($this->navLabels, $labels);
        return $this;
    }

    public function getNavLabels(): array
    {
        return $this->navLabels;
    }

    public function getItems(bool $includeHome = false): array
    {
        if ($includeHome) {
            return $this->items;
        }

        return $this->items[ 0 ]->children;
    }

    public function getItemByUri(string $uri): ?MenuItem
    {
        if (isset($this->itemMap[ $uri ])) {
            return $this->itemMap[ $uri ];
        }
    }

    public function getNavLabelByUri(string $uri): ?string
    {
        if (isset($this->navLabels[ $uri ])) {
            return $this->navLabels[ $uri ];
        }
    }

    public function getCurrentItem(): ?MenuItem
    {
        $requestUri = RequestService::getInstance()->getRequestUri();
        return $this->getItemByUri($requestUri);
    }

    public function getRootItem(): MenuItem
    {
        return $this->items[ 0 ];
    }
}

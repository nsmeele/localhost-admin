<?php

namespace Service;

use Component\Navigation\MenuItem;
use Symfony\Component\Finder\Finder;

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
    ) : NavigationService {
        $path                  = $path ?? $this->basePath;
        $homeNavItem           = $this->items[ 0 ];
        $homeNavItem->children = $this->getItemsFromPath($homeNavItem, $path, $recursive);

        return $this;
    }

    public function getItemsFromPath(
        MenuItem $parent,
        ?string $path = null,
        bool $recursive = true,
        int $depth = 0,
    ) : array {
        $path     = $path ?? $this->basePath;
        $basePath = $this->basePath;

        $navItems = [];
        $finder    = new Finder();

        foreach ($finder->in($path)->depth(0) as $file) {

            if ($file->getBasename() === 'index.php') {
                continue; // Skip index.php and home.php files
            }

            $subPath = $path.'/'.$file->getBasename();

            $uri = str_replace($basePath, '', $subPath);

            $navigationItem = new MenuItem(
                url: HOME_URL . $uri,
                uri: $uri,
                title: ucfirst(strtolower($file->getBasename('.php'))),
                path: $subPath,
                parent: $parent,
            );

            if ($file->isDir() && $recursive) {
                $navigationItem->path .= '/index.php';
                $navigationItem->children = $this->getItemsFromPath(
                    $navigationItem,
                    $subPath,
                    $recursive,
                    $depth + 1
                );

                $uri .= '/';
            }

            $this->itemMap[ $uri ] = $navigationItem;
            $navItems[ $uri ]      = $navigationItem;
        }

        return $navItems;
    }

    public function getItems(bool $includeHome = false) : array
    {
        if ($includeHome) {
            return $this->items;
        }

        return $this->items[ 0 ]->children;
    }

    public function getItemByUri(string $uri) : ?MenuItem
    {
        return $this->itemMap[ $uri ] ?? null;
    }

    public function getCurrentItem() : ?MenuItem
    {
        global $request;
        return $this->getItemByUri($request->getRequestUri());
    }

    public function getRootItem() : MenuItem
    {
        return $this->items[ 0 ];
    }
}

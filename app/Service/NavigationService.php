<?php

namespace Service;

use Component\Menu\Item;
use Symfony\Component\Finder\Finder;

final class NavigationService
{
    /**
     * @var Item[]
     */
    protected array $items = array ();

    /**
     * @var Item[]
     */
    protected array $itemMap = array ();

    public function __construct(
        protected readonly string $basePath,
    ) {
    }

    public function setFromPath(
        ?string $path = null,
        bool $recursive = true,
    ): NavigationService {
        $this->items = $this->getItemsFromPath(null, $path, $recursive);

        return $this;
    }

    public function getItemsFromPath(
        ?Item $parent = null,
        ?string $path = null,
        bool $recursive = true,
        int $depth = 0,
    ): array {
        $path     = $path ?? $this->basePath;
        $basePath = $this->basePath;

        $navItems = [];
        $finder   = new Finder();

        foreach ($finder->in($path)->depth(0) as $file) {
            if ($file->getBasename() === 'index.php') {
                continue; // Skip index.php and home.php files
            }

            $subPath = $path . '/' . $file->getBasename();

            $uri = str_replace($basePath, '', $subPath);

            $navigationItem = new Item(
                url: HOME_URL . $uri,
                uri: $uri,
                title: ucfirst(strtolower($file->getBasename('.php'))),
                path: $subPath,
                parent: $parent,
            );

            if ($file->isDir() && $recursive) {
                $navigationItem->path     .= '/index.php';
                $navigationItem->children = $this->getItemsFromPath(
                    $navigationItem,
                    $subPath,
                    $recursive,
                    $depth + 1
                );
            }

            $this->itemMap[ $uri ] = $navigationItem;
            $navItems[ $uri ]      = $navigationItem;
        }

        return $navItems;
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

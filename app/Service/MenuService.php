<?php

namespace Service;

use Component\Menu\Item;
use Symfony\Component\Finder\Finder;

final readonly class MenuService
{

    public function __construct(
        private string $basePath,
    ) {
    }

    public function getItemsFromPath(
        ?Item $parent = null,
        ?string $path = null,
        bool $recursive = true,
        int $depth = 0,
    ) : array {
        $path     = $path ?? $this->basePath;
        $basePath = $this->basePath;

        $navItems = [];

        $finder = new Finder();

        foreach ($finder->in($path)->depth(0) as $file) {
            if ($file->getBasename() === 'index.php') {
                continue;
            }

            $subPath = $path.'/'.$file->getBasename();

            $uri = str_replace($basePath, '', $subPath);

            $navigationItem = new Item(
                url: HOME_URL.$uri,
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
                    $depth + 1,
                );
            }

            $navItems[ $uri ] = $navigationItem;
        }

        return $navItems;
    }
}
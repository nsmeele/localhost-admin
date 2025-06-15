<?php

require_once 'setup.php';

global $request, $currentNavigationItem;

$fileSystem = new \Symfony\Component\Filesystem\Filesystem();

require_once ROOT_PATH . '/templates/layout/header.php';

if ($currentNavigationItem) {
    if ($fileSystem->exists($currentNavigationItem->path)) {
        require_once $currentNavigationItem->path;
    }
} else {
    echo '<h1>Page not found</h1>';
}

require_once ROOT_PATH . '/templates/layout/footer.php';

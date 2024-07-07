<?php

use Service\NavigationService;

require_once 'vendor/autoload.php';

define("ROOT_PATH", dirname(__FILE__));

$request = \Service\RequestService::getInstance();

define("HOME_URL", $request->getHomeUrl());

$basePath              = realpath(ROOT_PATH . '/pages');
$navLabels             = [];
$navigation            = (new NavigationService($basePath))->setFromPath();
$currentNavigationItem = $navigation->getCurrentItem();

try {
    $navigation->getItemByUri('/dashboard')->icon   = 'power-off fa-fw';
    $navigation->getItemByUri('/projects')->icon    = 'heart fa-fw';
    $navigation->getItemByUri('/server-info')->icon = 'battery-three-quarters fa-fw';

    $navigation->setNavLabels([
        '/projects'          => 'Projects',
        '/server-info'       => 'Server Info',
        '/projects/overview' => 'Admin',
        '/projects/edit'     => 'Edit Project',
        '/projects/new'      => 'New Project',
    ]);

    require_once 'layout/header.php';

    if ($currentNavigationItem && file_exists($currentNavigationItem->path)) {
        require_once $currentNavigationItem->path;
    }
} catch (\Throwable $exception) {
    printf($exception);
}


require_once 'layout/footer.php';

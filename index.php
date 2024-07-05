<?php

use Service\NavigationService;

define("ROOT_PATH", dirname(__FILE__));
define("HOME_URL", (empty($_SERVER[ 'HTTPS' ]) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]/localhost-admin");

require_once 'vendor/autoload.php';

$basePath              = realpath(ROOT_PATH . '/pages');
$navLabels             = [];
$navigation            = (new NavigationService($basePath))->setFromPath();
$currentNavigationItem = $navigation->getCurrentItem();

try {
    $navigation->getItemByUri('/dashboard')->icon   = 'power-off fa-fw';
    $navigation->getItemByUri('/projects')->icon    = 'heart fa-fw';
    $navigation->getItemByUri('/server-info')->icon = 'battery-three-quarters fa-fw';

    $navigation->setNavLabels([
        '/projects' => 'Projects',
        '/server-info' => 'Server Info',
        '/projects/overview' => 'Admin',
        '/projects/edit' => 'Edit Project',
        '/projects/new' => 'New Project',
    ]);

    require_once 'layout/header.php';

    if (file_exists($currentNavigationItem->path)) {
        require_once $currentNavigationItem->path;
    }
} catch (\Throwable $exception) {
    printf($exception);
}


require_once 'layout/footer.php';

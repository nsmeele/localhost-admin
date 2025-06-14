<?php

use Service\NavigationService;
use Symfony\Component\HttpFoundation\Request;

define("ROOT_PATH", realpath(dirname(__FILE__, 2)));

require_once ROOT_PATH.'/vendor/autoload.php';

global $request, $navigation, $currentNavigationItem;

$request = Request::createFromGlobals();

define("HOME_URL", $request->getSchemeAndHttpHost());

$basePath              = realpath(ROOT_PATH.'/templates/pages');
$navLabels             = [];
$navigation            = new NavigationService($basePath)->setFromPath();
$currentNavigationItem = $navigation->getItemByUri($request->getRequestUri());
$fileSystem            = new \Symfony\Component\Filesystem\Filesystem();

$navigation->getItemByUri('/home.php')
    ?->setIcon('home')
    ->setTitle('Home');

$navigation->getItemByUri('/projects')
    ?->setIcon('heart')
    ->setTitle('Projecten');

require_once ROOT_PATH.'/templates/layout/header.php';

if ($currentNavigationItem) {
    if ($fileSystem->exists($currentNavigationItem->path)) {
        require_once $currentNavigationItem->path;
    }
} else {
    echo '<h1>Page not found</h1>';
}

require_once ROOT_PATH.'/templates/layout/footer.php';

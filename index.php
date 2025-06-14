<?php

use Service\NavigationService;
use Symfony\Component\HttpFoundation\Request;

define("ROOT_PATH", realpath(__DIR__));

require_once ROOT_PATH.'/vendor/autoload.php';

global $request, $navigation, $currentNavigationItem;

$request = Request::createFromGlobals();

define("HOME_URL", $request->getSchemeAndHttpHost());

$basePath              = realpath(ROOT_PATH.'/templates/pages');
$navLabels             = [];
$navigation            = new NavigationService($basePath)->setFromPath();
$currentNavigationItem = $navigation->getCurrentItem();

$navigation->getItemByUri('/projects')->icon = 'heart fa-fw';

$navigation->setNavLabels(['/projects' => 'Projects',]);

require_once ROOT_PATH.'/templates/layout/header.php';

if ($currentNavigationItem && file_exists($currentNavigationItem->path)) {
    require_once $currentNavigationItem->path;
}

require_once ROOT_PATH.'/templates/layout/footer.php';

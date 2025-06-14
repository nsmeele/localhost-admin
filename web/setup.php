<?php

use Service\NavigationService;
use Symfony\Component\HttpFoundation\Request;

define("ROOT_PATH", realpath(dirname(__FILE__, 2)));

require_once ROOT_PATH . '/vendor/autoload.php';

global $request, $navigation, $currentNavigationItem;

$request = Request::createFromGlobals();

define("HOME_URL", $request->getSchemeAndHttpHost());

$basePath              = realpath(ROOT_PATH . '/templates/pages');
$navigation            = new NavigationService($basePath)->setFromPath();
$currentNavigationItem = $navigation->getItemByUri($request->getRequestUri());

$navigation->getItemByUri('/home.php')
    ?->setIcon('home')
    ->setTitle('Home');

$navigation->getItemByUri('/projects')
    ?->setIcon('heart');

$navigation->getItemByUri('/projects/new.php')?->setTitle('New project');
$navigation->getItemByUri('/projects/edit.php')?->setTitle('Edit project');
$navigation->getItemByUri('/projects/remove.php')?->setTitle('Remove project');

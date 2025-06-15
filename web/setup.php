<?php

use Component\MenuComponent;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;

define("ROOT_PATH", realpath(dirname(__FILE__, 2)));

require_once ROOT_PATH . '/vendor/autoload.php';

global $request, $navigation, $currentNavigationItem, $formFactory;

$request     = Request::createFromGlobals();
$formFactory = Forms::createFormFactoryBuilder()
    ->addExtension(new HttpFoundationExtension())
    ->getFormFactory();

define("HOME_URL", $request->getSchemeAndHttpHost());

$basePath              = realpath(ROOT_PATH . '/templates/pages');
$menuService           = new \Service\MenuService($basePath);
$navigation            = new MenuComponent()->setItems($menuService->getItemsFromPath());
$currentNavigationItem = $navigation->getItemByUri($request->getRequestUri());

$navigation->getItemByUri('/home.php')
    ?->setIcon('home')
    ->setTitle('Home');

$navigation->getItemByUri('/projects')?->setIcon('heart');
$navigation->getItemByUri('/projects/new.php')?->setTitle('New project');
$navigation->getItemByUri('/projects/edit.php')?->setTitle('Edit project');
$navigation->getItemByUri('/projects/remove.php')?->setTitle('Remove project');

<!DOCTYPE html>
<html class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo HOME_URL; ?>/assets/css/main.css" rel="stylesheet">

    <title>My Localhost Admin</title>

</head>
<body>

<div id="page-wrapper" class="d-flex flex-nowrap flex-grow-1 min-vh-100">
    <div id="sidebar" class="bg-dark text-white px-4 pt-4 d-flex flex-column position-relative">

        <div class="inner">
            <div class="h5">Menu</div>
            <nav>
                <?php
                echo new Service\MenuService($navigation, false);
                ?>
            </nav>

        </div>

        <div class="real-time mt-lg-auto position-sticky sticky-bottom py-4 border-top">
             <i class="fa-solid fa-calendar-alt" aria-hidden="true"></i>
            <span id="date">&nbsp;</span><br>
            <span id="clock">&nbsp;</span>
        </div>

    </div>

    <main class="w-100 p-4">

        <div class="container">

            <?php
            $breadcrumb = new \Service\BreadcrumbService($currentNavigationItem);
            echo $breadcrumb;
            ?>

            <h1><?php echo $currentNavigationItem->getLabel(); ?></h1>

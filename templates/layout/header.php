<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/dist/css/app.css" rel="stylesheet">

    <title>My Localhost Admin</title>

</head>
<body>

<div id="page-wrapper" class="flex">
    <div id="sidebar" class="bg-gray-900 text-gray-100 lg:min-h-screen min-w-[240px] p-8 flex column relative">

        <div class="inner">
            <div class="font-medium">Menu</div>
            <nav>
                <?php
                global $navigation;
                echo new Component\MenuComponent($navigation, true);
                ?>
            </nav>

        </div>

        <div class="absolute bottom-0 left-0">
             <i class="fa-solid fa-calendar-alt" aria-hidden="true"></i>
            <span id="date">&nbsp;</span><br>
            <span id="clock">&nbsp;</span>
        </div>

    </div>

    <main class="w-full p-4">

            <?php
            global $currentNavigationItem;
            if ($currentNavigationItem) {
                $breadcrumb = new Component\BreadcrumbComponent($currentNavigationItem);
                echo $breadcrumb;

                ?>
                <h1><?php echo $currentNavigationItem->title; ?></h1>
                <?php
            }

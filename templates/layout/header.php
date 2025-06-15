<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/dist/css/app.css" rel="stylesheet">

    <title>Localhost Manager</title>

</head>
<body>

<div id="page-wrapper" class="flex">
    <div id="sidebar" class="bg-gray-900 text-gray-100 lg:min-h-screen min-w-[240px] p-8 flex flex-col">

        <nav>
            <?php
            global $navigation;
            echo new \Component\Menu\Renderer($navigation);
            ?>
        </nav>


        <div class="mt-auto">
             <i class="fa-solid fa-calendar-alt" aria-hidden="true"></i>
            <span id="date">&nbsp;</span><br>
            <span id="clock">&nbsp;</span>
        </div>

    </div>

    <main class="w-full p-8">

            <?php
            global $currentNavigationItem;
            if ($currentNavigationItem) {
                $breadcrumb = new Component\BreadcrumbComponent($currentNavigationItem);
                echo $breadcrumb;

                ?>
                <h1><?php echo $currentNavigationItem->title; ?></h1>
                <?php
            }

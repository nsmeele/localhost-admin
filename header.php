<?php
define("ROOT_FOLDER", basename(dirname(__FILE__)));
define("ROOT_DIR", dirname(__FILE__));

include_once(ROOT_FOLDER . '/inc/functions.php');
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="local-admin/assets/img/xampp-logo.png">
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
    <script src="local-admin/assets/js/date.js"></script>
    <script src="local-admin/assets/js/main.js"></script>
    <link href="local-admin/assets/css/style.min.css" rel="stylesheet">

    <title>My Localhost Admin</title>

</head>
<body>

<div id="page-wrapper">
    <div class="nav-bg bg-dark">

        <div class="real-time">
            <!-- <i class="fa fa-calendar" aria-hidden="true"></i> -->
            <span id="date">&nbsp;</span><br>
            <span id="clock">&nbsp;</span>
        </div>

    </div>
    <nav>
        <?php include_once(ROOT_FOLDER . '/nav.php'); ?>
    </nav>


    <?php

    if (isset($_GET['p']) && in_array($_GET['p'], $filterItems)) {
        $type = $filterNavItems[$_GET['p']]['type'];
        if (isset($_GET['subpage'])) {
            $extension = (isset($filterNavItems[$_GET['p']]['subpages'])) ? '.' . $filterNavItems[$_GET['p']]['subpages'][$_GET['subpage']]['extension'] : '';
            $title = '<h1>' . $filterNavItems[$_GET['p']]['subpages'][$_GET['subpage']]['pretty-label'] . '</h1>';
        } elseif (isset($_GET['p']) && $_GET['p'] == 'dashboard') {
            $extension = ($filterNavItems[$_GET['p']]['type'] == 'file') ? '.' . $filterNavItems[$_GET['p']]['file-extension'] : '';
            $title = '<h1>Welkom bij XAMPP Admin Panel</h1>';
        } elseif (isset($_GET['p'])) {
            $extension = ($filterNavItems[$_GET['p']]['type'] == 'file') ? '.' . $filterNavItems[$_GET['p']]['file-extension'] : '';
            $title = '<h1>' . $filterNavItems[$_GET['p']]['pretty-label'] . '</h1>';
        }

    } elseif (empty($_GET['p'])) {

        $extension = "";
        reset($filterNavItems);
        $firstItem = key($filterNavItems);
        // $title = '<h1>'.$filterNavItems[$firstItem]['pretty-label'].'</h1>';
        $title = '<h1>Welkom bij XAMPP Admin Panel</h1>';
    }


    if (isset($_GET['p']) && isset($filterNavItems[$_GET['p']]['subpages'])) {

        if (isset($indexPage)) {
            $indexPage['parent-folder'] = basename(basename($indexPage['dirname']));
            $indexPath = $_GET['p'] . '/' . $indexPage['basename'];
        }

        reset($filterNavItems[$_GET['p']]['subpages']);
        $firstKey = key($filterNavItems[$_GET['p']]['subpages']);

        if (file_exists(dirname(__FILE__) . '/pages/' . $indexPath) && empty($_GET['subpage'])) {
            // Formule voor index opnieuw maken
            $page = $indexPath;
        } elseif (!file_exists(dirname(__FILE__) . '/pages/' . $indexPath) && empty($_GET['subpage'])) {
            $page = $_GET['p'] . '/' . $filterNavItems[$_GET['p']]['subpages'][$firstKey]['basename'];
        } elseif (isset($_GET['subpage'])) {
            $page .= '/' . $filterNavItems[$_GET['p']]['subpages'][$_GET['subpage']]['filename'];
        } elseif (isset($_GET['p']) && $filterNavItems[$_GET['p']]['type'] == 'folder' && isset($filterNavItems[$_GET['p']]['subpages'])) {
            $page .= '/' . $filterNavItems[$_GET['p']]['subpages'][$firstKey]['filename'];
        }

    } elseif (isset($_GET['p']) && $filterNavItems[$_GET['p']]['type'] == 'folder' && empty($filterNavItems[$_GET['p']]['subpages'])) {
        $page = '';
    }


    ?>

    <main>

        <article>


            <?php
            if (isset($_GET['subpage'])) : ?>
                <ol class="breadcrumb">
                    <?php

                    $breadcrumb['parent'] = $filterNavItems[$_GET['p']]['pretty-label'];
                    $breadcrumb['subpage'] = $filterNavItems[$_GET['p']]['subpages'][$_GET['subpage']]['pretty-label'];

                    $divider = "/";

                    echo '<li class="breadcrumb-item"><a href="index.php?p=' . $_GET['p'] . '">' . $breadcrumb['parent'] . '</a></li>';
                    echo '<li class="breadcrumb-item active">' . $breadcrumb['subpage'] . '</li>';


                    ?>
                </ol>
            <?php endif; ?>
            <div class="entry-main">
                <div class="entry-header <?php echo $filterNavItems[$_GET['p']]['label']; ?> col-md--12">
                    <div class="entry-title">
                        <?php echo $title; ?>
                    </div>
                </div>
                <div class="entry-content <?php echo $filterNavItems[$_GET['p']]['label']; ?>">
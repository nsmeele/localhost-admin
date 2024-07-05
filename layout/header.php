<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
    <link href="/assets/css/style.min.css" rel="stylesheet">

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
        <?php
        require_once 'nav.php';
        ?>
    </nav>

    <main>

        <article>

            <?php
            echo getBreadcrumbMenu();
            ?>

            <div class="entry-main">
                <div class="entry-header">
                    <div class="entry-title">
                    </div>
                </div>
                <div class="entry-content">
<!doctype html>
<html lang="en" data-bs-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/imgs/logo.svg" type="image/svg+xml">
        <title>blither - <?php echo $req[0]; ?></title>

        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/blither.css" rel="stylesheet">
        <script src="/js/bootstrap.min.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
          <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/imgs/logo.svg" class="rounded-3 me-2" height="50" style="background:white">
                blither
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">

<?php

include('../php/nav.php');

?>
            </div>
          </div>
        </nav>


<?php
// Filename: index.php
// Purpose: Main landing file of PORG

namespace frakturmedia\blither;

$req = explode("/", trim($_SERVER['REQUEST_URI'], "/"));

include('../php/initialization.php');

include('../php/layout/head.php');

echo '<div id="content" class="container mt-3">';


switch ($req[0]) {
case 'login':
    include '../php/login.php';
    break;

case 'courses':
    include '../php/courses.php';
    break;

case 'sessions':
    include '../php/sessions.php';
    break;

case 'start':
    include '../php/start.php';
    break;

default:
    include '../php/main.php';
}

echo '</div>';

include '../php/layout/foot.php';

// EOF


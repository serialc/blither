<?php
// Filename: php/courses.php
// Purpose: List the courses in the DB

namespace frakturmedia\blither;

require_once('../php/classes/course.php');

$course = new Course();

// Look at length of $req
// - if == 2, just list courses
// - if == 3 and $req[2] is 'new' show new course form
// - if == 4 and $req[2] is 'cid' show the course with the cid of $req[3]

if (count($req) === 1) {
    echo '<div class="row"><div class="col-12">';
    echo '<h1>Courses ';
    if ($user->getStatus() >= MEMBER_STATUS_CREATOR) {
        echo '<span class="float-end fs-4">';
        echo '<a href="/courses/new/"><button class="btn btn-success">New</button></a>';
        echo '</span>';
    }
    echo '</h1>';
    $course->displayList();
    echo '</div></div>';
} else {
    switch($req[1]) {

    case 'new':
        // only show users of CREATOR status
        if ($user->getStatus() >= MEMBER_STATUS_CREATOR) {
            $course->displayFormNewCourse();
        } else {
            echo "You don't have access. Log in?";
        }
        break;

    case 'edit':
        if ( is_numeric($req[2]) ) {
            $course->displayFormEditCourse($req[2]);
        }
        break;

    case 'detail':
        if ( is_numeric($req[2]) ) {
            $course->displayCid($req[2]);
        } else {
            "Unexpected course id.";
        }
        break;

    default:
        echo "Unexpected request in php/courses.php";
    }
}

// EOF

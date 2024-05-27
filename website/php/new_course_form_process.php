<?php
// Filename: new_course_form_process.php
// Purpose: show new course form and do processing

namespace frakturmedia\blither;

$submit_error = false;
if (isset($_POST['cname'])) {
    $check_fields = [
        'cname'=>"Course name",
        'ldesc'=>"Location",
        'ccode'=>"Country",
        'cdescription'=>"Course description",
        'basketsnum'=>"Number of baskets"
    ];

    // check that all the fields are submitted
    foreach ( $check_fields as $fieldkey => $fieldvalue ) {
        if (strcmp($_POST[$fieldkey], '') === 0) {
            printAlert($fieldvalue . " is missing");
            $submit_error = true;
        }
    }

    // process the input as it's all provided
    if (!$submit_error) {
        if ($course->submitNew( $_POST['cname'], $_POST['ldesc'], $_POST['ccode'], $_POST['cdescription'], $_POST['basketsnum'])) {
            printAlert("New course added successfully", "success");
        } else {
            printAlert("Failed to add course");
        }
    }
} 

if ($submit_error or !isset($_POST['cname'])) {
    include('../php/layout/new_course_form.php');
}

// EOF

<?php
// Filename: start.php
// Purpose: Only runs once when setting up admin username/password

namespace frakturmedia\blither;

require_once "../php/classes/user.php";

// Check we have no admin yet
if ($user->count() === 0) {

    // Is the form submitted?
    if (isset($_POST['adminUsername'])) {

        // Process submission
        if ($user->create(
                filter_input(INPUT_POST, 'adminUsername'),
                filter_input(INPUT_POST, 'adminEmail'),
                DEFAULT_ADMIN_STATUS,
                filter_input(INPUT_POST, 'inputPassword')
        )) {
            echo "<p>Admin user <strong>" . $user->getName() . "</strong> created.<br>Please log-in.</p>";
        } else {
            echo '<div class="alert alert-danger" role="alert"> Failed to create admin!</div>';
        }
    } else {
        // Show admin account creation form
        require_once '../php/layout/create_admin_form.html';
    }
} else {
    echo "<p>Administrator has already been created.</p>";
}

// EOF

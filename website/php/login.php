<?php

// Handles login and result of login attempt
// Filename: php/login.php

namespace frakturmedia\blither;

if (isset($_POST['inputPassword'])) {
    /* 
     * there is the login form processing
     * being called in php/preprocess.php
     */ 

    // getName returns false if the user is not logged in
    if (!$user->getName()) {
        // login failed
        echo <<< _END
            <div class="alert alert-danger" role="alert">
                Incorrect username and/or password!
            </div>
        _END;
    }
}

// show form
readfile('../php/layout/login_form.html');

// EOF

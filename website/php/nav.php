<?php
// Filename: inc/nav.php
// Purpose: Displays nav menu

namespace frakturmedia\blither;

# create menu items contextually (not logged in, logged in, user type, admin)
echo <<< END
<div class="justify-content-end" id="navbarSupportedContent">
    <div class="navbar-nav">
        <a href="/courses" class="nav-link mb-1 dl-bg-field-d text-light" title="Browse courses"><i class="fa fa-search" aria-hidden="true"></i> Courses</a>

END;

// if user is logged in
if ($user->getStatus() >= MEMBER_STATUS_BASIC) {
    // Profile
    echo '        <a href="/account" class="nav-link mb-1 dl-bg-quant-d text-light" title="See your profile"><i class="fa fa-user" aria-hidden="true"></i> Account</a>' . "\n";
    // Logout
    echo '        <a href="/logout" class="nav-link mb-1" title="Logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>' . "\n";
} else {
    // Login
    echo '        <a href="/login" class="nav-link mb-1 dl-bg-quant-d text-light" title="Login"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a>' . "\n";
    // Register
    echo '        <a href="/register" class="nav-link mb-1 dl-bg-quant-d text-light"><i class="fa fa-vcard-o"></i> Register</a>' . "\n";
}

// end container div and nav
echo '</div></div>';

// EOF

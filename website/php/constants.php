<?php
// Filename: constants.php
// Purpose: define values that are more general and not sensitive

namespace frakturmedia\blither;

// logging
define("LOGSDIR", "../logs/");

// TABLES
define("TABLE_USERS", "users");
define("TABLE_COURSES", "courses");
define("TABLE_HOLES", "holes");
define("TABLE_FRIENDS", "friends");
define("TABLE_SETTINGS", "settings");

// member types
define("MEMBER_STATUS_BASIC", 1);
define("MEMBER_STATUS_CREATOR", 16);
define("MEMBER_STATUS_ADMIN", 255);

// other
define("PASSWORD_HASH_COST", 10);
define("TIMEZONE", 'CET');
define("DEFAULT_ADMIN_STATUS", 255);
define("CONF_TEMPLATE", "../php/config_template.php");
define("CONF_FILE", "../php/config.php");
//define("ALLOWED_UPLOAD_FILE_TYPES", array("png", "gif", "svg", "jpg", "zip"));

// EOF

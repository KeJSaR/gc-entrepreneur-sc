<?php

defined('SC_SAFETY_CONST') or die;


// Define constants
require_once SC_ROOT_DIR . 'config' . DS . 'defines.php';

// Require config data
require_once SC_CONFIG_DIR . 'config.php';

// Check connection to database
require_once SC_MODEL_DIR . 'db_conn.php';

// Validate the URL
require_once SC_LIB_DIR . 'url_validate.php';

// Set language constants
require_once SC_LANG_DIR . 'ru.php';

// Get data from user and hanle it
require_once SC_CONTROLLER_DIR . 'handler.php';

// Collect data from database
require_once SC_CONTROLLER_DIR . 'collector.php';

// Render the page
if (isset($print) && $print == 1) {
    require_once SC_VIEW_DIR . 'print.php';
} else {
    require_once SC_VIEW_DIR . 'constructor.php';
}

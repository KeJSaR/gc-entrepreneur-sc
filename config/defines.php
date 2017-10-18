<?php

defined('SC_SAFETY_CONST') or die;

$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$sc_url =  'https://' . $_SERVER['HTTP_HOST'] . $uri_parts[0];

define('SC_URL', $sc_url);

define('SC_TIME_FORMAT',  'Y-m-d H:i:s');
define('SC_DEFAULT_PAGE', 'main');

define('SC_CONFIG_DIR',     SC_ROOT_DIR . 'config'     . DS);
define('SC_LANG_DIR',       SC_ROOT_DIR . 'config'     . DS . 'lang'  . DS);
define('SC_CONTROLLER_DIR', SC_ROOT_DIR . 'controller' . DS);
define('SC_LIB_DIR',        SC_ROOT_DIR . 'controller' . DS . 'lib'   . DS);
define('SC_LOGS_DIR',       SC_ROOT_DIR . 'logs'       . DS);
define('SC_MODEL_DIR',      SC_ROOT_DIR . 'model'      . DS);
define('SC_VIEW_DIR',       SC_ROOT_DIR . 'view'       . DS);
define('SC_CSS_DIR',        SC_ROOT_DIR . 'view'       . DS . 'css'   . DS);
define('SC_FONTS_DIR',      SC_ROOT_DIR . 'view'       . DS . 'fonts' . DS);
define('SC_HTML_DIR',       SC_ROOT_DIR . 'view'       . DS . 'html'  . DS);
define('SC_CONTENTS_DIR',   SC_ROOT_DIR . 'view'       . DS . 'html'  . DS . 'contents' . DS);
define('SC_PARTS_DIR',      SC_ROOT_DIR . 'view'       . DS . 'html'  . DS . 'contents' . DS . 'parts' . DS);
define('SC_IMG_DIR',        SC_ROOT_DIR . 'view'       . DS . 'img'   . DS);
define('SC_JS_DIR',         SC_ROOT_DIR . 'view'       . DS . 'js'    . DS);

define('BR', "\n");

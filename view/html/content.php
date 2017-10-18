<?php

defined('SC_SAFETY_CONST') or die;

switch ($current_page)
{
    case 'main':
    case FALSE:
        require_once(SC_CONTENTS_DIR . 'main.php');
        break;

    case 'goods':
    case 'debit':
    case 'credit':
    case 'move':
        require_once(SC_CONTENTS_DIR . 'data.php');
        break;

    case 'income':
        require_once(SC_CONTENTS_DIR . 'income.php');
        break;

    case 'outcome':
        require_once(SC_CONTENTS_DIR . 'outcome.php');
        break;

    case 'settings':
        require_once(SC_CONTENTS_DIR . 'settings.php');
        break;

    case 'error':
    default:
        require_once(SC_CONTENTS_DIR . 'error.php');
        break;
}

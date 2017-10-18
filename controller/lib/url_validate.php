<?php

defined('SC_SAFETY_CONST') or die;

/**
 * $page_array, $range_array and $theme_array defined in config.php
 */
$stock_array_raw   = get_stocks();
$section_array_raw = get_classes();

$stock_array       = make_array($stock_array_raw,   FALSE, 'stock_id');
$section_array     = make_array($section_array_raw, FALSE, 'class_id');

function get_value($entry, $entry_array)
{

    if (array_key_exists($entry, $_GET)) {

        $entry_value = htmlspecialchars($_GET[$entry]);
        $entry_exist = in_array($entry_value, $entry_array);

        if ($entry_exist) {
            return $entry_value;
        }

    }

    return FALSE;

}

$current_page    = get_value('page',     $page_array);
$current_stock   = get_value('stock',    $stock_array);
$current_section = get_value('section',  $section_array);
$current_range   = get_value('range',    $range_array);
$current_theme   = get_value('theme',    $theme_array);

$print           = get_value('print',    $print_array);
$sub_page        = get_value('sub',      $sub_array);

if ($current_page === FALSE) {
    $current_page = 'main';
}

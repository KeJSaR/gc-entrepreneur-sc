<?php

defined('SC_SAFETY_CONST') or die;


function make_main_nav($side)
{
    global $current_page,
           $main_nav_array;
    $main_nav_html = '';
    $i = 0;
    $fa = array(
        "-chevron-up",
        "-chevron-down",
        "-cog",
    );

    foreach ($main_nav_array[$side] as $key => $value) {

        $main_nav_html .= '<li';

        if ($key == $current_page || ($key == 'main' && $current_page == FALSE) ) {
            $main_nav_html .= ' class="active"';
        }

        $main_nav_html .= '><div class="button" data-main-nav="' . $key . '">';

        if ($side == 'right') {
            $main_nav_html .= '<i class="fa fa' . $fa[$i++] . '"></i>'
                            . '<span> ' . $value . '</span>';
        } else {
            $main_nav_html .= $value;
        }

        $main_nav_html .= '</div></li>' . BR;

    }

    return $main_nav_html;
}

?>
                <h1><?php echo L_NAME; ?></h1>

                <ul class="left">
<?php echo make_main_nav('left'); ?>
                </ul>

                <ul class="right">
<?php echo make_main_nav('right'); ?>
                </ul>

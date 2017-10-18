<?php

defined('SC_SAFETY_CONST') or die;

if (
    $current_page == 'goods'  ||
    $current_page == 'debit'  ||
    $current_page == 'credit' ||
    $current_page == 'move'
) {

    $script_name = 'data';

} else if ($current_page == 'outcome' || $current_page == 'settings') {

    if ($sub_page) {

        $script_name = $sub_page;

    } else {

        $script_name = $current_page;

    }

} else if ($current_page == 'main') {

    $script_name = 'main';

} else {

    $script_name = $current_page;

}

/**
 * THEME CHOOSER
 */
function make_theme_chooser ()
{
    global $theme_pics_array,
           $theme;

    $html = '';

    foreach ($theme_pics_array as $key => $value) {

        $html .= '<a href="#" data-theme="' . $key . '" class="theme-chooser';

        if ($theme == $key) {
            $html .= ' active';
        }

        $html .= '">' . BR;
        $html .= '<i id="' . $key . '" class="fa ' . $value . '"></i>' . BR;
        $html .= '</a>' . BR;
    }

    return $html;
}

?><!DOCTYPE html>
<html lang="ru">
    <head>

<?php require_once(SC_HTML_DIR . 'head.php'); ?>

    </head>
    <body id="page-<?php echo $current_page; ?>">

        <div class="container-fluid">
            <div id="content" class="row">

<?php require_once(SC_HTML_DIR . 'content.php'); ?>

            </div>
        </div>

        <div id="main-nav">

<?php require_once(SC_HTML_DIR . 'main-nav.php'); ?>

        </div>

        <div id="copyright">
            <p class="left">Тема:
<?php echo make_theme_chooser(); ?>
            </p>
            <p class="right">Складской учёт / Stock Control v. 1.0</p>
        </div>

        <!-- jQuery & custom JavaScript file -->
        <script src="view/js/jquery-2.1.4.min.js"></script>
        <script src="view/js/bootstrap.min.js"></script>
        <script src="view/js/bootstrap-datepicker.min.js"></script>
        <script src="view/js/bootstrap-datepicker.ru.min.js"></script>
        <script src="view/js/jquery.mousewheel.min.js"></script>
        <script src="view/js/jquery.mCustomScrollbar.js"></script>
        <script src="view/js/<?php echo $script_name; ?>.js"></script>
        <script src="view/js/script.js"></script>

    </body>
</html>

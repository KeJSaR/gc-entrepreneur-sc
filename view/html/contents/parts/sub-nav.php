<?php

defined('SC_SAFETY_CONST') or die;


/**
 * STOCK NAV
 */

function make_stock_nav ()
{
    global $current_page,
           $current_stock,
           $stock_array;

    $html = '';

    if ($current_page !== 'outcome') {
        $html  .= '<div data-store-id="all" class="button';

        if ($current_stock == FALSE) {
            $html .= ' active';
        }

        $html .= '">' . L_ALL . '</div>' . BR;
    }

    foreach ($stock_array as $key => $value) {

        $html .= '<div data-store-id="' . $key . '" class="button';

        if ($current_stock == $key) {
            $html .= ' active';
        }

        $html .= '">' . $value . '</div>' . BR;
    }

    return $html;
}

$stock_nav_html = make_stock_nav();

?>

                    <div class="stock left">

<?php

if (   $current_page == 'main'
    || $current_page == 'goods'
    || $current_page == 'debit'
    || $current_page == 'credit'
    || $current_page == 'outcome' && $sub_page == 'sale' ) :

    echo $stock_nav_html;

endif;

?>

                    </div>

                    <div class="sub right">
<?php if ($current_page == 'outcome') : ?>

                <a id="move-button"
                    href="<?php echo SC_URL; ?>?page=outcome&sub=move"
                    class="button<?php if ($sub_page == 'move') echo ' active'; ?>"
                    data-sub-page="move"
                    role="button">Перемещение</a>

                <a id="sale-button"
                    href="<?php echo SC_URL; ?>?page=outcome&sub=sale"
                    class="button<?php if ($sub_page == 'sale') echo ' active'; ?>"
                    data-sub-page="sale"
                    role="button">Продажа / Списание</a>

<?php else : ?>
                        <div id="excel" class="button" data-sub-nav="excel"><i class="fa fa-file-excel-o"></i></div>

                        <div id="print" class="button" data-sub-nav="print"><i class="fa fa-print"></i></div>
<?php endif; ?>
                    </div>





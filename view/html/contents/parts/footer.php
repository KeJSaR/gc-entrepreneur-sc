<?php

defined('SC_SAFETY_CONST') or die;


/**
 * RANGE NAV
 */
function make_range_nav ()
{
    global $data_range_array,
           $current_range;

    $html  = '<div data-range="all" class="button';

    if ($current_range == FALSE) {
        $html .= ' active';
    }

    $html .= '">' . L_ALL . '</div>' . BR;

    foreach ($data_range_array as $key => $value) {

        $html .= '<div data-range="' . $key . '" class="button';

        if ($current_range == $key) {
            $html .= ' active';
        }

        $html .= '">' . $value . '</div>' . BR;
    }

    return $html;
}

function make_footer_sale_html ($footer_data, $current_range)
{
    foreach ($footer_data as $key => $value) {
        if ($value == 0) {
            $footer_data[$key] = '<strong>--</strong>';
        } else if (is_numeric($value)) {
            $footer_data[$key] = '<strong>' . number_format($value, 0, '', ' ') . '</strong>';
        }
    }

    if ($current_range == 'w') {
        $range = 'неделю';
    } else if ($current_range == 'm') {
        $range = 'месяц';
    } else {
        $range = 'сутки';
    }

    $html = 'Всего за ' . $range . ': товаров продано ' . $footer_data['sale_quantity'] . ' шт. ' . BR;
    $html .= 'Доход: ' . $footer_data['sale_amount'] . ' руб. ' . BR;
    $html .= 'Предоставлено скидок: ' . $footer_data['sale_discount'] . ' руб. ' . BR;

    return $html;
}

$sale_html = make_footer_sale_html($footer_data, $current_range);

?>

                    <div class="left">
<?php echo $sale_html; ?>
                    </div>

<?php if (   $current_page == 'main'
          || $current_page == 'debit'
          || $current_page == 'credit'
          || $current_page == 'move' ) : ?>

                    <div id="range" class="right">
                        <div class="buttons">

<?php echo make_range_nav(); ?>

                        </div>
                    </div>

<?php endif; ?>

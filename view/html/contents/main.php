<?php

defined('SC_SAFETY_CONST') or die;

// number_format($a, 0, '', ' ')

function wrap_tag ($tag, $attr, $text)
{
    $attr_html = '';

    if ($text === 0) {
        $text = '--';
    } else if (is_numeric($text)) {

        if ($text < 0) {

            $text = 0 - $text;

            $text = '&ndash; ' . number_format($text, 0, '', ' ');

        } else {

            $text = number_format($text, 0, '', ' ');

        }

    }

    if ($attr) {

        foreach ($attr as $key => $value) {
            $attr_html .= ' ' . $key . '="' . $value . '"';
        }

    }

    $html  = '<' . $tag . $attr_html . '>' . $text . '</' . $tag . '>' . BR;

    return $html;
}

function prepare_tbody_html ($content_data)
{
    $html = '';

    foreach ($content_data as $stock_id => $value) {

        $row_content = '';

        $attr = ['class' => 'text-right'];
        $text = $value['stock_alias'];
        $row_content .= wrap_tag('th', $attr, $text);

        $content = [
            'sale-quantity'       => $value['sale_quantity'],
            'sale-amount'         => $value['sale_amount'],
            'sale-discount'       => $value['sale_discount'],
            'moving-in-quantity'  => $value['moving_in_quantity'],
            'moving-in-amount'    => $value['moving_in_amount'],
            'moving-out-quantity' => $value['moving_out_quantity'],
            'moving-out-amount'   => $value['moving_out_amount'],
            'moving-total-amount' => $value['moving_total_amount'],
            'total-amount'        => $value['total_amount'],
            'total-remainder'     => $value['total_remainder'],
        ];

        foreach ($content as $class_name => $text) {
            $attr = ['class' => $class_name];
            $row_content .= wrap_tag('td', $attr, $text);
        }

        $html .= wrap_tag('tr', $attr, $row_content);

    }

    return $html;
}

$tbody_html = prepare_tbody_html($content_data);

?>
                <div>

<?php // require_once(SC_PARTS_DIR . 'sub-nav.php'); ?>

                </div>
                <div>

                    <div>
                        <table class="table table-striped text-center table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="3"></th>
                                    <th class="text-center" colspan="3"><?php echo L_SALE; ?></th>
                                    <th class="text-center" colspan="5"><?php echo L_MOVING; ?></th>
                                    <th class="text-center" colspan="2"><?php echo L_TOTAL; ?></th>
                                </tr>
                                <tr>
                                    <th class="text-center" rowspan="2"><?php echo L_SALE_QUANTITY; ?></th>
                                    <th class="text-center" rowspan="2"><?php echo L_SALE_AMOUNT; ?></th>
                                    <th class="text-center" rowspan="2"><?php echo L_SALE_DISCOUNT; ?></th>
                                    <th class="text-center" colspan="2">Приход</th>
                                    <th class="text-center" colspan="2">Расход</th>
                                    <th class="text-center" rowspan="2"><?php echo L_MOVING_TOTAL_AMOUNT; ?></th>
                                    <th class="text-center" rowspan="2"><?php echo L_TOTAL_AMOUNT; ?></th>
                                    <th class="text-center" rowspan="2"><?php echo L_TOTAL_REMAINDER; ?></th>
                                </tr>
                                <tr>
                                    <th class="text-center"><?php echo L_MOVING_IN_QUANTITY; ?></th>
                                    <th class="text-center"><?php echo L_MOVING_IN_AMOUNT; ?></th>
                                    <th class="text-center"><?php echo L_MOVING_OUT_QUANTITY; ?></th>
                                    <th class="text-center"><?php echo L_MOVING_OUT_AMOUNT; ?></th>
                                </tr>
                            </thead>

                            <tbody>
<?php echo $tbody_html; ?>
                            </tbody>

                        </table>
                    </div>

                </div>
                <footer>

<?php require_once(SC_PARTS_DIR . 'footer.php'); ?>

                </footer>

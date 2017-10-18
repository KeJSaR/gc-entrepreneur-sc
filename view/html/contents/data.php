<?php

defined('SC_SAFETY_CONST') or die;

// var_dump($content_data);
// var_dump($quantity);
// die;

switch ($current_page) {
    case 'goods':
        $titles = [
            L_PROD_NAME,
            L_PROD_PRICE,
            FALSE,
            L_PROD_QUANTITY,
            L_PROD_AMOUNT,
            L_PROD_COMMENT
        ];
        $content_data_html = goods_data();
        break;

    case 'debit':
        $titles = [
            L_PROD_DATA,
            L_PROD_NAME,
            L_PROD_PRICE,
            FALSE,
            L_PROD_QUANTITY,
            L_PROD_BUY_PRICE,
            L_PROD_SUM,
            L_PROD_COST,
            L_PROD_COMMENT
        ];
        $content_data_html = debit_data();
        break;

    case 'credit':
        $titles = [
            L_PROD_DATA,
            L_PROD_NAME,
            L_PROD_SELL_PRICE,
            L_PROD_DISCOUNT,
            FALSE,
            L_PROD_REST,
            L_PROD_AMOUNT,
            L_PROD_COMMENT
        ];
        $content_data_html = credit_data();
        break;

    case 'move':
        $titles = [
            L_PROD_DATA,
            L_PROD_NAME,
            L_PROD_PRICE,
            FALSE,
            L_PROD_COMMENT
        ];
        $content_data_html = moving_data();
        break;
}

$headers_html = headers($titles, $location_names, $current_stock);

function wrap_tag ($tag, $attr, $text)
{
    $attr_html = '';

    if ($attr) {

        foreach ($attr as $key => $value) {
            $attr_html .= ' ' . $key . '="' . $value . '"';
        }

    }

    $html  = '<' . $tag . $attr_html . '>' . $text . '</' . $tag . '>' . BR;

    return $html;
}

function location_names ($location_names, $current_stock)
{
    global $current_page;
    $html = '';

    foreach ($location_names as $id => $alias) {

        $attr = ['data-location-name-id' => $id];

        if ($current_stock && $current_page === 'goods') {

            $text = L_PROD_QUANTITY . '<br>' . $alias;
            $html .= wrap_tag('th', $attr, $text);

            $text = L_PROD_AMOUNT . '<br>' . $alias;
            $html .= wrap_tag('th', FALSE, $text);

        } else if ($current_stock && $current_page === 'debit') {

            $text = L_PROD_QUANTITY;
            $html .= wrap_tag('th', $attr, $text);

        } else {

            $text = $alias;
            $html .= wrap_tag('th', $attr, $text);

        }

    }

    return $html;
}

function headers ($titles, $location_names, $current_stock)
{
    global $current_page;
    $html = '';

    foreach ($titles as $text) {
        if ($text) {
            if ($current_page == 'debit'
                && $current_stock
                && $text == L_PROD_QUANTITY
            ) {
                continue;
            } else {
                $html .= wrap_tag('th', FALSE, $text);
            }

        } else {
            $html .= location_names($location_names, $current_stock);
        }
    }

    return $html;
}

function comment ($t)
{
    $attr = [
        'href'           => '#',
        'data-toggle'    => 'tooltip',
        'data-placement' => 'left',
        'title'          => $t
    ];
    $text = '<i class="fa fa-check"></i>';

    return wrap_tag('a', $attr, $text);
}

/**
 * #############################################################################
 * #### GOODS DATA #############################################################
 * #############################################################################
 */
function goods_data ()
{
    global $content_data,
           $current_stock,
           $quantity;

    if (!$current_stock) {
        global $location_names;
    }

    $html = '';
    $in_total_quantity = 0;
    $in_total_amount   = 0;
    $product_total_amount = 0;

    foreach ($content_data as $product_id => $product_array) {

// #### QUANTITY HTML ##########################################################

        $quantity_html  = '';
        $quantity_array = $quantity[$product_id];

        // PRICES EXISTENCE CHECK ##############################################

        $check = FALSE;

        foreach ($quantity_array as $v) {

            if ($v) {
                $check = TRUE;
            }

        }

        if (!$check) {
            continue;
        }

        // END of PRICES EXISTENCE CHECK #######################################

        $in_total_quantity += $product_array['quantity'];
        $in_total_amount   += $product_array['amount'];

        foreach ($quantity_array as $v) {

            $attr = ['class' => 'quantity'];
            $text = ($v)?number_format($v, 0, '', ' '):'-';

            $quantity_html .= wrap_tag('td', $attr, $text);

        }

        if ($current_stock) {

            $q = $quantity_array[0];
            $a = $product_array['price'] * $q;

            $product_total_amount += $a;

            $attr = ['class' => 'amount'];
            $text = number_format($a, 0, '', ' ');

            $quantity_html .= wrap_tag('td', $attr, $text);

        }

// #### END of QUANTITY HTML ###################################################

        $html .= '<tr data-product-id="' . $product_id . '">'    . BR;

        $row_data = [
            'alias',
            'price',
            FALSE,
            'quantity',
            'amount',
            'comment'
        ];

        foreach ($row_data as $value) {

            if ($value) {

                $attr = ['class' => $value];

                if (is_numeric($product_array[$value])) {

                    $text = number_format($product_array[$value], 0, '', ' ');

                } else if ($value == 'comment' && $product_array[$value] !== '') {

                    $text = comment($product_array['comment']);

                } else {

                    $text = $product_array[$value];

                }


                $html .= wrap_tag('td', $attr, $text);

            } else {

                $html .= $quantity_html;

            }

        }

        // End of row
        $html .= '</tr>' . BR;
    }

// #### PRODUCT QUANTITIES #####################################################

    $total_html  = '';

    if ($current_stock) {

        $array = [
            'total-quantity' => $quantity['total'][$current_stock],
            'total-amount'   => $product_total_amount
        ];

        foreach ($array as $key => $value) {
            $attr = ['class' => $key];
            $text = number_format($value, 0, '', ' ');

            $total_html .= wrap_tag('td', $attr, $text);
        }

    } else {

        $locations = array_keys($location_names);

        foreach ($locations as $stock_id) {

            $n    = $quantity['total'][$stock_id];
            $text = number_format($n, 0, '', ' ');

            $total_html .= wrap_tag('td', FALSE, $text);

        }

    }

    $html .= '<tr id="in-total">' . BR;

    $array = [
        'alias',
        'price',
        FALSE,
        'quantity',
        'amount',
        'comment'
    ];

    foreach ($array as $value) {

        if ($value) {

            switch ($value) {

                case 'price':
                    $text = 'Итого:';
                    break;

                case 'quantity':
                    $text = number_format($in_total_quantity, 0, '', ' ');
                    break;

                case 'amount':
                    $text = number_format($in_total_amount, 0, '', ' ');
                    break;

                default:
                    $text = ' ';
                    break;

            }

            $html .= wrap_tag('td', FALSE, $text);

        } else {

            $html .= $total_html;

        }

    }

    $html .= '</tr>' . BR;

// #### END of PRODUCT QUANTITIES ##############################################

    return $html;
} // end of goods data

/**
 * #############################################################################
 * #### DEBIT DATA #############################################################
 * #############################################################################
 */
function debit_data ()
{
    global $content_data,
           $current_stock,
           $quantity;

    if (!$current_stock) {
        global $location_names;
    }

    $html = '';
    $in_total_price    = 0;
    $in_total_purchase = 0;
    $in_total_quantity = 0;
    $in_total_amount   = 0;
    $in_total_cost     = 0;
    $product_total_amount = 0;

    foreach ($content_data as $purchase_id => $purchase_array) {

// #### QUANTITY HTML ##########################################################

        $quantity_html  = '';
        $quantity_array = $quantity[$purchase_id];

        foreach ($quantity_array as $v) {

            $attr = ['class' => 'quantity'];
            $text = ($v)?number_format($v, 0, '', ' '):'-';

            $quantity_html .= wrap_tag('td', $attr, $text);

            if ($current_stock) {

                $product_total_amount += $v;

                $purchase_array['amount'] = $v * $purchase_array['purchase'];
                $purchase_array['cost']   = $v * $purchase_array['price'];

            }

        }

        $in_total_price    += $purchase_array['price'];
        $in_total_purchase += $purchase_array['purchase'];
        $in_total_quantity += $purchase_array['quantity'];
        $in_total_amount   += $purchase_array['amount'];
        $in_total_cost     += $purchase_array['cost'];

// #### END of QUANTITY HTML ###################################################

        $html .= '<tr data-purchase-id="' . $purchase_id . '">'    . BR;

        if ($current_stock) {
            $row_data = [
                'date',
                'alias',
                'price',
                FALSE,
                'purchase',
                'amount',
                'cost',
                'comment'
            ];
        } else {
            $row_data = [
                'date',
                'alias',
                'price',
                FALSE,
                'quantity',
                'purchase',
                'amount',
                'cost',
                'comment'
            ];
        }


        foreach ($row_data as $value) {

            if ($value) {

                $attr = ['class' => $value];

                if (is_numeric($purchase_array[$value])) {

                    $text = number_format($purchase_array[$value], 0, '', ' ');

                } else if ($value == 'comment' && $purchase_array[$value] !== '') {

                    $text = comment($purchase_array['comment']);

                } else {

                    $text = $purchase_array[$value];

                }


                $html .= wrap_tag('td', $attr, $text);

            } else {

                $html .= $quantity_html;

            }

        }

        // End of row
        $html .= '</tr>' . BR;
    }

// #### PRODUCT QUANTITIES #####################################################

    $total_html  = '';

    if ($current_stock) {

        $attr = ['class' => 'total-amount'];
        $text = number_format($product_total_amount, 0, '', ' ');

        $total_html .= wrap_tag('td', $attr, $text);

    } else {

        $locations = array_keys($location_names);

        foreach ($locations as $stock_id) {

            $n    = $quantity['total'][$stock_id];
            $text = number_format($n, 0, '', ' ');

            $total_html .= wrap_tag('td', FALSE, $text);

        }

    }

    $html .= '<tr id="in-total">' . BR;

    $array = [
        'date',
        'alias',
        'price',
        FALSE,
        'quantity',
        'purchase',
        'amount',
        'cost',
        'comment'
    ];

    foreach ($array as $value) {

        if ($value) {

            if ($current_stock && $value === 'quantity') {
                continue;
            }

            switch ($value) {

                case 'price':
                    $text = 'Итого:';
                    break;

                case 'price':
                    $text = number_format($in_total_price, 0, '', ' ');
                    break;

                case 'quantity':
                    $text = number_format($in_total_quantity, 0, '', ' ');
                    break;

                case 'purchase':
                    $text = number_format($in_total_purchase, 0, '', ' ');
                    break;

                case 'amount':
                    $text = number_format($in_total_amount, 0, '', ' ');
                    break;

                case 'cost':
                    $text = number_format($in_total_cost, 0, '', ' ');
                    break;

                default:
                    $text = ' ';
                    break;

            }

            $html .= wrap_tag('td', FALSE, $text);

        } else {

            $html .= $total_html;

        }

    }

    $html .= '</tr>' . BR;

// #### END of PRODUCT QUANTITIES ##############################################

    return $html;
} // end of debit data

/**
 * #############################################################################
 * #### CREDIT DATA ############################################################
 * #############################################################################
 */
function credit_data ()
{
    global $content_data,
           $current_stock,
           $quantity;

    if (!$current_stock) {
        global $location_names;
        $stocks = array_keys($location_names);
    }

    $row_data = [
        'date',
        'alias',
        'price',
        'discount',
        FALSE,
        'remainder',
        'amount',
        'comment'
    ];

    $html = '';
    $in_total_discount    = 0;
    $in_total_amount      = 0;
    $product_total_amount = 0;

    foreach ($content_data as $sale_id => $sale_array) {

// #### QUANTITY HTML ##########################################################

        $quantity_html  = '';
        $quantity_array = $quantity[$sale_id];

        $temp_quantity = 0;

        foreach ($quantity_array as $v) {

            $temp_quantity += (int)$v;

            $attr = ['class' => 'quantity'];
            $text = ($v)?number_format($v, 0, '', ' '):'-';

            $quantity_html .= wrap_tag('td', $attr, $text);

            if ($current_stock) {

                $product_total_amount += $v;

            }

        }

        $sale_array['discount'] *= $temp_quantity;

        $in_total_amount   += $sale_array['amount'];
        if ($sale_array['price'] != '0') {
            $in_total_discount += $sale_array['discount'];
        }

// #### END of QUANTITY HTML ###################################################

        $danger = ($sale_array['price'] == 0) ? ' class="danger"' : '';
        $html .= '<tr' . $danger . ' data-sale-id="' . $sale_id . '">'. BR;

        foreach ($row_data as $value) {

            if ($value) {

                $attr = ['class' => $value];

                if ($value == 'price' && $sale_array['price'] == 0) {

                    $text = '--';

                } else if ($value == 'discount' && ($sale_array['discount'] == 0 || $sale_array['price'] == 0) ) {

                    $text = '--';

                } else if ($value == 'amount' && $sale_array['amount'] == 0) {

                    $text = '--';

                } else if ($value == 'comment') {

                    if ($sale_array['comment'] !== '' || $sale_array['price'] == 0) {
                        if ($sale_array['price'] == 0) {
                            $comment = 'Общая стоимость товара на момент списания: '
                                        . $sale_array['discount'] . ' руб. '
                                        . $sale_array['comment'];
                        } else {
                            $comment = $sale_array['comment'];
                        }
                        $text = comment($comment);
                    } else {
                        $text = '';
                    }

                } else if (is_numeric($sale_array[$value])) {

                    $text = number_format($sale_array[$value], 0, '', ' ');

                } else {

                    $text = $sale_array[$value];

                }

                $html .= wrap_tag('td', $attr, $text);

            } else {

                $html .= $quantity_html;

            }

        }

        // End of row
        $html .= '</tr>' . BR;
    }

// #### PRODUCT QUANTITIES #####################################################

    $row_html = '';

    foreach ($row_data as $value) {

        if ($value) {

            switch ($value) {

                case 'price':
                    $text = 'Итого:';
                    break;

                case 'discount':
                    $text = number_format($in_total_discount, 0, '', ' ');
                    break;

                case 'amount':
                    $text = number_format($in_total_amount, 0, '', ' ');
                    break;

                default:
                    $text = ' ';
                    break;

            }

            $row_html .= wrap_tag('td', FALSE, $text);

        } else {

            if ($current_stock) {

                $attr = ['class' => 'total-amount'];
                $text = number_format($product_total_amount, 0, '', ' ');

                $row_html .= wrap_tag('td', $attr, $text);

            } else {

                foreach ($stocks as $stock_id) {

                    $n    = $quantity['total'][$stock_id];
                    $text = number_format($n, 0, '', ' ');

                    $row_html .= wrap_tag('td', FALSE, $text);

                }

            }

        }

    }

    $attr = ['id' => 'in-total'];
    $text = $row_html;

    $html .= wrap_tag('tr', $attr, $text);

// #### END of PRODUCT QUANTITIES ##############################################

    return $html;
} // end of credit data

/**
 * #############################################################################
 * #### MOVING DATA ############################################################
 * #############################################################################
 */
function moving_data ()
{
    global $content_data,
           $location_names,
           $quantity;

    $row_data = [
        'date',
        'alias',
        'price',
        FALSE,
        'comment'
    ];

    $html = '';

    $stocks = array_keys($location_names);

    foreach ($stocks as $stock_id) {
        $quantity['total'][$stock_id] = 0;
    }

    foreach ($content_data as $moving_id => $moving_array) {

// #### QUANTITY HTML ##########################################################

        $q_out  = $quantity[$moving_id]['out'];
        $q_in   = $quantity[$moving_id]['in'];

        $quantity_html   = '';

        foreach ($stocks as $stock_id) {

            $attr = ['class' => 'quantity'];
            $text = '--';

            foreach ($q_in as $id => $quant) {
                if ($id == $stock_id) {

                    $attr['style'] = 'color: green;';
                    $text = $quant;

                    $quantity['total'][$id] += $quant * $moving_array['price'];

                    break;
                }
            }

            foreach ($q_out as $id => $quant) {
                if ($id == $stock_id) {

                    $attr['style'] = 'color: red;';
                    $text = $quant;

                    $quantity['total'][$id] -= $quant * $moving_array['price'];

                }
            }

            $quantity_html .= wrap_tag('td', $attr, $text);

        }

// #### END of QUANTITY HTML ###################################################

        // MOVING ID for row
        $html .= '<tr data-moving-id="' . $moving_id . '">' . BR;

        foreach ($row_data as $value) {

            if ($value) {

                $attr = ['class' => $value];

                if (is_numeric($moving_array[$value])) {

                    $text = number_format($moving_array[$value], 0, '', ' ');

                } else if ($value == 'comment' && $moving_array[$value] !== '') {

                    $text = comment($moving_array['comment']);

                } else {

                    $text = $moving_array[$value];

                }

                $html .= wrap_tag('td', $attr, $text);

            } else {

                $html .= $quantity_html;

            }

        }

        // End of row
        $html .= '</tr>' . BR;
    }

// #### PRODUCT QUANTITIES #####################################################

    $row_html = '';

    foreach ($row_data as $value) {

        if ($value) {

            switch ($value) {

                case 'price':
                    $text = 'Итого:';
                    break;

                default:
                    $text = ' ';
                    break;

            }

            $row_html .= wrap_tag('td', FALSE, $text);

        } else {

            foreach ($stocks as $stock_id) {

                $n    = $quantity['total'][$stock_id];
                $text = number_format($n, 0, '', ' ');

                $row_html .= wrap_tag('td', FALSE, $text);

            }

        }

    }

    $attr = ['id' => 'in-total'];
    $text = $row_html;

    $html .= wrap_tag('tr', $attr, $text);

// #### END of PRODUCT QUANTITIES ##############################################

    return $html;

} // end of moving data

?>
                <div id="sub-nav">

<?php require_once(SC_PARTS_DIR . 'sub-nav.php'); ?>

                </div><!-- end of sub-nav -->
                <aside>

<?php require_once(SC_PARTS_DIR . 'aside.php'); ?>

                </aside>
                <section>

                    <div id="section-header">
                        <table>
                            <tr>
                            </tr>
                        </table>
                    </div>
                    <div id="section-content">
                        <table>
                            <thead>
                                <tr>
<?php echo $headers_html; ?>
                                </tr>
                            </thead>
                            <tbody>

<?php echo $content_data_html; ?>

                            </tbody>
                        </table>
                    </div><!-- end of #section-content -->

                </section>
                <footer>

<?php require_once(SC_PARTS_DIR . 'footer.php'); ?>

                </footer>

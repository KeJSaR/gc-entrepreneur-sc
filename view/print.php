<?php

defined('SC_SAFETY_CONST') or die;

/**
 * STOCK NAMES html
 * CONTENT DATA html
 *     QUANTITY html
 */

switch ($current_page) {
    case 'goods':
        $content_data_html    = make_goods_data_html();
        $content_headers_html = make_goods_headers_html();
        break;

    case 'debit':
        $content_data_html    = make_debit_data_html();
        $content_headers_html = make_debit_headers_html();
        break;

    case 'credit':
        $content_data_html    = make_credit_data_html();
        $content_headers_html = make_credit_headers_html();
        break;

    case 'move':
        $content_data_html    = make_moving_data_html();
        $content_headers_html = make_moving_headers_html();
        break;
}

/**
 * #############################################################################
 * ### TABLE HEADERS html                                                    ###
 * #############################################################################
 */

function make_location_names_html ()
{
    global $location_names;
    $html = '';

    foreach ($location_names as $id => $alias) {
        $html .= '<th data-location-name-id="' . $id . '">'
                        . $alias
                        . '</th>' . BR;
    }

    return $html;
}

/**
 * ### GOODS DATA ##############################################################
 *
 * name_alias
 * product_price
 *
 * product_quantity
 * product_amount
 * product_comment
 *
 */
function make_goods_headers_html ()
{
    $html  = '<th>' . L_PROD_NAME       . '</th>' . BR;
    $html .= '<th>' . L_PROD_PRICE      . '</th>' . BR;
    $html .= make_location_names_html();
    $html .= '<th>' . L_PROD_QUANTITY   . '</th>' . BR;
    $html .= '<th>' . L_PROD_AMOUNT     . '</th>' . BR;
    $html .= '<th>' . L_PROD_COMMENT    . '</th>' . BR;

    return $html;
}

/**
 * ### DEBIT DATA ##############################################################
 *
 * purchase_date
 * name_alias
 * purchase_price
 * product_price
 *
 * purchase_quantity
 * purchase_amount
 * purchase_comment
 *
 */
function make_debit_headers_html ()
{
    $html  = '<th>' . L_PROD_DATA       . '</th>' . BR;
    $html .= '<th>' . L_PROD_NAME       . '</th>' . BR;
    $html .= '<th>' . L_PROD_BUY_PRICE  . '</th>' . BR;
    $html .= '<th>' . L_PROD_PRICE      . '</th>' . BR;
    $html .= make_location_names_html();
    $html .= '<th>' . L_PROD_QUANTITY   . '</th>' . BR;
    $html .= '<th>' . L_PROD_AMOUNT     . '</th>' . BR;
    $html .= '<th>' . L_PROD_COMMENT    . '</th>' . BR;

    return $html;
}

/**
 * ### CREDIT DATA #############################################################
 *
 * sale_date
 * name_alias
 * sale_price
 * sale_discount
 *
 * sale_remainder
 * sale_amount
 * sale_comment
 *
 */
function make_credit_headers_html ()
{
    $html  = '<th>' . L_PROD_DATA       . '</th>' . BR;
    $html .= '<th>' . L_PROD_NAME       . '</th>' . BR;
    $html .= '<th>' . L_PROD_SELL_PRICE . '</th>' . BR;
    $html .= '<th>' . L_PROD_DISCOUNT   . '</th>' . BR;
    $html .= make_location_names_html();
    $html .= '<th>' . L_PROD_REST       . '</th>' . BR;
    $html .= '<th>' . L_PROD_AMOUNT     . '</th>' . BR;
    $html .= '<th>' . L_PROD_COMMENT    . '</th>' . BR;

    return $html;
}

/**
 * ### MOVING DATA #############################################################
 *
 * moving_date
 * name_alias
 * product_price
 *
 * moving_comment
 *
 */
function make_moving_headers_html ()
{
    $html  = '<th>' . L_PROD_DATA       . '</th>' . BR;
    $html .= '<th>' . L_PROD_NAME       . '</th>' . BR;
    $html .= '<th>' . L_PROD_PRICE      . '</th>' . BR;
    $html .= make_location_names_html();
    $html .= '<th>' . L_PROD_COMMENT    . '</th>' . BR;

    return $html;
}


/**
 * #############################################################################
 * ### QUANTITY html                                                         ###
 * #############################################################################
 */

function make_quantity_html ($id)
{
    global $quantity;
    $quantity_array = $quantity[$id];
    $html  = '';
    $check = FALSE;

    foreach ($quantity_array as $value) {

        if ($value !== '--') {

            $check = TRUE;

        }

        $html .= '<td class="quantity">' . $value . '</td>' . BR;

    }

    if (!$check) {

        $html = FALSE;

    }

    return $html;
}

function make_quantity_movings_html ($id)
{
    global $quantity,
           $location_names;

    $stock_id_array = array_keys($location_names);
    $quantity_out   = $quantity[$id]['out'];
    $quantity_in    = $quantity[$id]['in'];

    $html  = '';

    foreach ($stock_id_array as $stock_id) {

        foreach ($quantity_in as $stock_id_in => $moving_in_quantity) {
            if ($stock_id_in == $stock_id) {
                $quantity_html = ' style="color: green;">' . $moving_in_quantity;
                break;
            } else {
                $quantity_html = '>--';
            }
        }

        foreach ($quantity_out as $stock_id_out => $moving_out_quantity) {
            if ($stock_id_out == $stock_id) {
                $quantity_html = ' style="color: red;">' . $moving_out_quantity;
            }
        }

        $html .= '<td class="quantity"' . $quantity_html . '</td>' . BR;


    }

    return $html;
}

function make_comment_html ($comment)
{
    $comment_html = '<a href="#" data-toggle="tooltip" data-placement="left" title="' . $comment . '">'
                        . '<span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>'
                        . '</a>';

    return $comment_html;
}

/**
 * #############################################################################
 * ### CONTENT DATA html                                                     ###
 * #############################################################################
 */

/**
 * GOODS DATA ##################################################################
 *
 * row: product_id
 * name_alias
 * product_price
 * :: product_quantity
 * product_quantity
 * product_amount
 *
 */
function make_goods_data_html ()
{
    global $content_data;
    $html = '';

    foreach ($content_data as $product_id => $product_array) {

        $quantity_html = make_quantity_html($product_id);

        if (!$quantity_html) {
            continue;
        }

        // PRODUCT ID for row
        $html .= '<tr data-product-id="'
                           . $product_id
                       . '">'    . BR;

        // NAME ALIAS
        $html .= '<td class="name-alias">'
                           . $product_array['name_alias']
                       . '</td>' . BR;

        // PRODUCT PRICE
        $html .= '<td class="product-price">'
                           . $product_array['product_price']
                       . '</td>' . BR;

        // Array with PRODUCT QUANTITIES for each Store
        $html .= $quantity_html;

        // PRODUCT QUANTITY
        $html .= '<td class="product-quantity">'
                           . $product_array['product_quantity']
                       . '</td>' . BR;

        // PRODUCT AMOUNT
        $html .= '<td class="product-amount">'
                           . $product_array['product_amount']
                       . '</td>' . BR;

        // PRODUCT COMMENT
        $html .= '<td class="product-comment">';

        if ($product_array['product_comment'] !== '') {
            $html .= make_comment_html($product_array['product_comment']);
        }

        $html .= '</td>' . BR;

        // End of row
        $html .= '</tr>' . BR;
    }

    return $html;
} // end of goods data

/**
 * DEBIT DATA ##################################################################
 *
 * row: purchase_id
 * purchase_date
 * name_alias
 * purchase_price
 * product_price
 * :: income_quantity
 * purchase_quantity
 * purchase_amount
 * purchase_comment
 *
 */
function make_debit_data_html ()
{
    global $content_data;
    $html = '';

    foreach ($content_data as $purchase_id => $purchase_array) {

        $quantity_html = make_quantity_html($purchase_id);

        if (!$quantity_html) {
            continue;
        }

        // PURCHASE ID for row
        $html .= '<tr data-purchase-id="'
                           . $purchase_id
                       . '">'    . BR;

        // PURCHASE DATE
        $html .= '<td class="purchase-date">'
                           . $purchase_array['purchase_date']
                       . '</td>' . BR;

        // NAME ALIAS
        $html .= '<td class="name-alias">'
                           . $purchase_array['name_alias']
                       . '</td>' . BR;

        // PURCHASE PRICE
        $html .= '<td class="purchase-price">'
                           . $purchase_array['purchase_price']
                       . '</td>' . BR;

        // PRODUCT PRICE
        $html .= '<td class="product-price">'
                           . $purchase_array['product_price']
                       . '</td>' . BR;

        // Array with INCOME QUANTITIES for each Store
        $html .= $quantity_html;

        // PRODUCT QUANTITY
        $html .= '<td class="purchase-quantity">'
                           . $purchase_array['purchase_quantity']
                       . '</td>' . BR;

        // PURCHASE AMOUNT
        $html .= '<td class="purchase-amount">'
                           . $purchase_array['purchase_amount']
                       . '</td>' . BR;

        // PURCHASE COMMENT
        $html .= '<td class="purchase-comment">';

        if ($purchase_array['purchase_comment'] !== '') {
            $html .= make_comment_html($purchase_array['purchase_comment']);
        }

        $html .= '</td>' . BR;

        // End of row
        $html .= '</tr>' . BR;
    }

    return $html;
} // end of debit data

/**
 * CREDIT DATA #################################################################
 *
 * row: sale_id
 * sale_date
 * name_alias
 * sale_price
 * sale_discount
 * :: sale_quantity
 * sale_remainder
 * sale_amount
 * sale_comment
 *
 */
function make_credit_data_html ()
{
    global $content_data;
    $html = '';

    foreach ($content_data as $sale_id => $sale_array) {

        $quantity_html = make_quantity_html($sale_id);

        if (!$quantity_html) {
            continue;
        }

        // SALE ID for row
        $html .= '<tr data-sale-id="'
                           . $sale_id
                       . '">'    . BR;

        // SALE DATE
        $html .= '<td class="sale-date">'
                           . $sale_array['sale_date']
                       . '</td>' . BR;

        // NAME ALIAS
        $html .= '<td class="name-alias">'
                           . $sale_array['name_alias']
                       . '</td>' . BR;

        // SALE PRICE
        $html .= '<td class="sale-price">'
                           . $sale_array['sale_price']
                       . '</td>' . BR;

        // SALE DISCOUNT
        $html .= '<td class="sale-discount">'
                           . $sale_array['sale_discount']
                       . '</td>' . BR;

        // Array with SALE QUANTITIES for each Store
        $html .= $quantity_html;

        // SALE REMAINDER
        $html .= '<td class="sale-remainder">'
                           . $sale_array['sale_remainder']
                       . '</td>' . BR;

        // SALE AMOUNT
        $html .= '<td class="sale-amount">'
                           . $sale_array['sale_amount']
                       . '</td>' . BR;

        // SALE COMMENT
        $html .= '<td class="sale-comment">';

        if ($sale_array['sale_comment'] !== '') {
            $html .= make_comment_html($sale_array['sale_comment']);
        }

        $html .= '</td>' . BR;

        // End of row
        $html .= '</tr>' . BR;
    }

    return $html;
} // end of credit data

/**
 * MOVING DATA #################################################################
 *
 * row: moving_id
 * moving_date
 * name_alias
 * product_price
 * :: moving_quantity
 * moving_comment
 *
 */
function make_moving_data_html ()
{
    global $content_data;
    $html = '';

    foreach ($content_data as $moving_id => $moving_array) {

        // MOVING ID for row
        $html .= '<tr data-moving-id="'
                           . $moving_id
                       . '">'    . BR;

        // MOVING DATE
        $html .= '<td class="moving-date">'
                           . $moving_array['moving_date']
                       . '</td>' . BR;

        // NAME ALIAS
        $html .= '<td class="name-alias">'
                           . $moving_array['name_alias']
                       . '</td>' . BR;

        // PRODUCT PRICE
        $html .= '<td class="product-price">'
                           . $moving_array['product_price']
                       . '</td>' . BR;

        // Array with MOVING QUANTITIES for each Store
        $html .= make_quantity_movings_html($moving_id);

        // MOVING COMMENT
        $html .= '<td class="moving-comment">';

        if ($moving_array['moving_comment'] !== '') {
            $html .= make_comment_html($moving_array['moving_comment']);
        }

        $html .= '</td>' . BR;

        // End of row
        $html .= '</tr>' . BR;
    }

    return $html;
} // end of moving data

?><!DOCTYPE html>
<html lang="ru">
    <head>

<?php require_once(SC_HTML_DIR . 'head.php'); ?>

    </head>
    <body id="page-<?php echo $current_page; ?>" class="print">

        <div class="container-fluid">
            <div id="content" class="row">

                <section>

                    <div id="section-content">
                        <table>
                            <thead>
                                <tr>
<?php echo $content_headers_html; ?>
                                </tr>
                            </thead>
                            <tbody>

<?php echo $content_data_html; ?>

                            </tbody>
                        </table>
                    </div><!-- end of #section-content -->

                </section>

            </div>
        </div>

        <!-- jQuery & custom JavaScript file -->
        <script src="view/js/jquery-2.1.4.min.js"></script>
        <script src="view/js/bootstrap.min.js"></script>
        <script src="view/js/print.js"></script>

    </body>
</html>

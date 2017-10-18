<?php

defined('SC_SAFETY_CONST') or die;

/**
 * 1. Hanle data from INCOME
 * 2. Hanle data from OUTCOME
 * 3. Hanle data from SETTINGS
 */


/**
 * #############################################################################
 * UNIVERSAL DATA ##############################################################
 * #############################################################################
 */

require_once SC_LIB_DIR . 'url_validate.php';

if ($current_theme) {
    update_theme($current_theme);
}

/**
 * #############################################################################
 * INCOME DATA #################################################################
 * #############################################################################
 */

/**
array (size=24) NEW                                  array (size=24) EXIST
  'name_alias_old' => string '82' (length=2)           'name_alias_old' => string '82' (length=2)
  'name_id' => string '82' (length=2)                  'name_id' => string '82' (length=2)
  'supplier_id' => string '10' (length=2)              'supplier_id' => string '10' (length=2)
  'product_price' => string '200' (length=3)           'product_price' => string '200' (length=3)
  'product_id' => string '' (length=0)                 'product_id' => string '307' (length=3)
  'purchase_price' => string '150' (length=3)          'purchase_price' => string '160' (length=3)
  'purchase_amount' => string '750' (length=3)         'purchase_amount' => string '480' (length=3)
  'purchase_date' => string '2016-03-26' (length=10)   'purchase_date' => string '2016-03-26' (length=10)
  'product_comment' => string '' (length=0)            'product_comment' => string '' (length=0)
  'purchase_comment' => string '' (length=0)           'purchase_comment' => string '' (length=0)
  'location_old_1' => string '2' (length=1)            'location_old_1' => string '2' (length=1)
  'location_new_1' => string '2' (length=1)            'location_new_1' => string '' (length=0)
  'location_old_2' => string '3' (length=1)            'location_old_2' => string '5' (length=1)
  'location_new_2' => string '3' (length=1)            'location_new_2' => string '2' (length=1)
  'location_old_3' => string '' (length=0)             'location_old_3' => string '1' (length=1)
  'location_new_3' => string '' (length=0)             'location_new_3' => string '1' (length=1)
  'location_old_4' => string '' (length=0)             'location_old_4' => string '--' (length=2)
  'location_new_4' => string '' (length=0)             'location_new_4' => string '' (length=0)
  'location_old_5' => string '' (length=0)             'location_old_5' => string '--' (length=2)
  'location_new_5' => string '' (length=0)             'location_new_5' => string '' (length=0)
  'location_old_6' => string '' (length=0)             'location_old_6' => string '--' (length=2)
  'location_new_6' => string '' (length=0)             'location_new_6' => string '' (length=0)
  'product_quantity' => string '' (length=0)           'product_quantity' => string '5' (length=1)
  'purchase_quantity' => string '5' (length=1)         'purchase_quantity' => string '3' (length=1)
 */

if ( $current_page == 'db_correct' ) 
{
    $correct_prod_data = get_correct_prod_data();
    foreach ($correct_prod_data as $corr_product) {
        
        $corr_id = $corr_product['product_id'];
        $corr_price = intval($corr_product['product_price']);
        $corr_quant = intval($corr_product['product_quantity']);
        $corr_amount = $corr_price * $corr_quant;
        
        update_correct_amount($corr_id, $corr_amount);
    }
}

if ( $current_page == 'income'
    && isset($_POST['purchase_quantity'])
    && $_POST['purchase_quantity'] != 0
) {
    $name_id           = $_POST['name_id'];
    $purchase_price    = $_POST['purchase_price'];
    $purchase_amount   = $_POST['purchase_amount'];
    $purchase_date     = $_POST['purchase_date'];
    $purchase_quantity = $_POST['purchase_quantity'];
    $product_price     = $_POST['product_price'];
    $supplier_id       = $_POST['supplier_id'];
    $product_id        = $_POST['product_id'];
    $purchase_comment  = $_POST['purchase_comment'];
    $product_comment   = $_POST['product_comment'];

    if ( $product_id == '' ) {
        $product_new = TRUE;
    }  else {
        $product_new = FALSE;
    }

    if ($_POST['product_quantity'] == '') {
        $product_quantity = $_POST['purchase_quantity'];
    } else {
        $product_quantity = $_POST['product_quantity'];
    }

    $stocks_array = get_stocks();

    foreach ($stocks_array as $v) {

        $stock_id = $v['stock_id'];
        $location_old = 'location_old_' . $stock_id;
        $location_new = 'location_new_' . $stock_id;

        if (is_numeric($_POST[$location_old])) {
            $$location_old = $_POST[$location_old];
        } else {
            $$location_old = 0;
        }

        if (is_numeric($_POST[$location_new])) {
            $$location_new = $_POST[$location_new];
        } else {
            $$location_new = 0;
        }

    }

    if ( $product_new ) {

        $product_amount = $product_price * $product_quantity;

        set_new_product($name_id, $product_price, $product_quantity,
            $product_amount, $product_comment);

        $product_id = get_last_id('product');

    } else {

        $new_product_quantity = $product_quantity + $purchase_quantity;

        $new_product_amount   = ( $new_product_quantity * $product_price );

        update_product($product_id, $new_product_quantity, $new_product_amount,
            $product_comment);

    }

    set_purchase($product_id, $purchase_price, $purchase_date, $supplier_id,
        $purchase_quantity, $purchase_amount, $purchase_comment);

    $purchase_id = get_last_id('purchase');

    foreach ($stocks_array as $v) {

        $stock_id          = $v['stock_id'];

        $location_old_name = 'location_old_' . $stock_id;
        $location_new_name = 'location_new_' . $stock_id;

        $location_quantity = $$location_old_name;
        $income_quantity   = $$location_new_name;

        if ($income_quantity != 0) {

            set_income($purchase_id, $stock_id, $income_quantity);

            if ( $product_new ) {

                set_location($product_id, $stock_id, $income_quantity);

            } else {

                $location_id = get_location_id($product_id, $stock_id);

                if (is_numeric($location_id)) {

                    update_location_by_id($location_quantity, $location_id);

                } else {

                    set_location($product_id, $stock_id, $income_quantity);

                }
            }
        }
    }
}

/**
 * #############################################################################
 * OUTCOME DATA / SELL #########################################################
 * #############################################################################
 */

/**
array (size=12)
  'name_alias_old' => string '82' (length=2)
  'name_id' => string '82' (length=2)
  'product_id' => string '209' (length=3)
  'product_price' => string '2100' (length=4)
  'stock_id' => string '3' (length=1)
  'product_quantity' => string '58' (length=2)
  'location_quantity' => string '30' (length=2)
  'sale_price' => string '2200' (length=4)
  'sale_amount' => string '22000' (length=5)
  'sale_quantity' => string '10' (length=2)
  'sale_date' => string '2016-03-26' (length=10)
  'sale_comment' => string '' (length=0)
 */

// var_dump($_POST);

if ( $current_page == 'outcome'
    && $sub_page == 'sale'
    && isset($_POST['product_id'])
    && isset($_POST['stock_id'])
    && isset($_POST['product_price'])
    && isset($_POST['sale_date'])
    && isset($_POST['sale_quantity'])
    && isset($_POST['sale_amount'])
) {
    $product_id        = $_POST['product_id'];
    $stock_id          = $_POST['stock_id'];
    $sale_quantity     = $_POST['sale_quantity'];
    $sale_date         = $_POST['sale_date'];
    $product_quantity  = $_POST['product_quantity'];
    $product_price     = $_POST['product_price'];
    $sale_comment      = $_POST['sale_comment'];
    $location_quantity = $_POST['location_quantity'];

    $write_off = (isset($_POST['write_off'])) ? TRUE : FALSE;

    $sale_price = ($write_off) ? 0 : $_POST['sale_price'];

    if ($location_quantity == 0) {
        delete_location($product_id, $stock_id);
    } else {
        update_location($location_quantity, $product_id, $stock_id);
        $product_amount = $product_price * $product_quantity;
        update_product($product_id, $product_quantity, $product_amount);
    }

    $sale_discount  = $product_price - $sale_price;
    $sale_amount    = $sale_price * $sale_quantity;
    $sale_remainder = $product_quantity;

    set_sale($product_id, $stock_id, $sale_price, $sale_quantity,
        $sale_discount, $sale_amount, $sale_remainder, $sale_date,
        $sale_comment);

}

/**
 * #############################################################################
 * OUTCOME DATA / MOVE #########################################################
 * #############################################################################
 */

if ( $current_page == 'outcome'
    && $sub_page == 'move'
    && isset($_POST['moving_quantity'])
) {

    $product_id      = $_POST['product_id'];
    $moving_quantity = $_POST['moving_quantity'];
    $moving_date     = $_POST['moving_date'];
    $moving_comment  = $_POST['moving_comment'];
    $out_stock_id    = $_POST['stock_radio'];

    $old_stock_name = 'old_stock_' . $out_stock_id;
    $out_stock_value = $_POST[$old_stock_name];

    set_movings($product_id, $moving_date, $moving_comment);

    $moving_id = get_last_id('moving');

    set_movings_out($moving_id, $out_stock_id, $moving_quantity);

    if ($out_stock_value == 0) {
        delete_location($product_id, $out_stock_id);
    } else {
        update_location($out_stock_value, $product_id, $out_stock_id);
    }

    $stocks_array = get_stocks();

    foreach ($stocks_array as $value) {

        $stock_id = $value['stock_id'];

        $new_stock_id = 'new_stock_' . $stock_id;
        $old_stock_id = 'old_stock_' . $stock_id;

        $new_stock = $_POST[$new_stock_id];
        $old_stock = $_POST[$old_stock_id];

        if ($new_stock > 0) {

            set_movings_in($moving_id, $stock_id, $new_stock);

            $location_id = get_location_id($product_id, $stock_id);

            if ( isset($location_id) && is_numeric($location_id) ) {
                update_location_by_id($old_stock, $location_id);
            } else {
                set_location($product_id, $stock_id, $old_stock);
            }
        }
    }
}

/**
 * #############################################################################
 * SETTINGS DATA ###############################################################
 * #############################################################################
 */

/*
 * admin page CLASS
 */
if ( $current_page == 'settings' &&
     $sub_page == 'classes'
) {

    // Add new product class to database
    if (
        isset($_POST['parent_class'])      &&
        isset($_POST['new_class'])         &&
        $_POST['new_class'] !== ''
    ) {
        if ($_POST['parent_class'] == '') {
            $class_parent_id = 0;
        } else {
            $class_parent_id = $_POST['parent_class'];
        }
        $class_alias = $_POST['new_class'];

        set_class($class_parent_id, $class_alias);
    }

    // Change name of the class
    if (
        isset($_POST['change_class'])      &&
        isset($_POST['new_class_name'])    &&
        $_POST['new_class_name'] !== ''
    ) {
        $class_alias = $_POST['new_class_name'];
        $class_id    = $_POST['change_class'];

        update_class($class_alias, $class_id);
    }

    // Delete selected class from database
    if (
        isset($_POST['delete_class'])      &&
        is_numeric($_POST['delete_class'])
    ) {
        $class_id   = $_POST['delete_class'];

        delete_class($class_id);
    }
// end of admin page class
}

/*
 * admin page CLASS
 */
if ( $current_page == 'settings' &&
     $sub_page == 'names'
) {

    // Add new product class to database
    if (
        isset($_POST['parent_class'])      &&
        isset($_POST['new_name'])         &&
        $_POST['new_name'] !== ''
    ) {
        $class_id   = $_POST['parent_class'];
        $name_alias = $_POST['new_name'];

        set_name($class_id, $name_alias);
    }

    // Change name of the class
    if (
        isset($_POST['change_name'])      &&
        isset($_POST['new_name_alias'])    &&
        $_POST['new_name_alias'] !== ''
    ) {
        $name_alias = $_POST['new_name_alias'];
        $name_id    = $_POST['change_name'];

        update_name($name_alias, $name_id);
    }

    // Delete selected class from database
    if (
        isset($_POST['delete_name'])      &&
        is_numeric($_POST['delete_name'])
    ) {
        $name_id = $_POST['delete_name'];

        delete_name($name_id);
    }
// end of admin page class
}

/*
 * admin page SUPPLIERS
 */
if ( $current_page == 'settings' &&
     $sub_page == 'suppliers'
) {

    // Add new supplier to database
    if (
        isset($_POST['new_city']) &&
        isset($_POST['new_alias']) &&
        isset($_POST['new_phone']) &&
        isset($_POST['new_address']) &&
        isset($_POST['new_comment'])
    ) {
        $supplier_city    = $_POST['new_city'];
        $supplier_alias   = $_POST['new_alias'];
        $supplier_phone   = $_POST['new_phone'];
        $supplier_address = $_POST['new_address'];
        $supplier_comment = $_POST['new_comment'];

        set_new_supplier($supplier_city, $supplier_alias,
            $supplier_phone, $supplier_address, $supplier_comment);
    }

    // Change supplier data
    if (
        isset($_POST['update_supplier_id']) &&
        is_numeric($_POST['update_supplier_id'])
    ) {
        $supplier_id      = $_POST['update_supplier_id'];
        $supplier_city    = $_POST['update_city'];
        $supplier_alias   = $_POST['update_alias'];
        $supplier_phone   = $_POST['update_phone'];
        $supplier_address = $_POST['update_address'];
        $supplier_comment = $_POST['update_comment'];

        update_supplier($supplier_id, $supplier_city, $supplier_alias,
            $supplier_phone, $supplier_address, $supplier_comment);
    }

    // Delete selected supplier from database
    if (
        isset($_POST['delete_supplier_id']) &&
        is_numeric($_POST['delete_supplier_id'])
    ) {
        $supplier_id = $_POST['delete_supplier_id'];

        delete_supplier($supplier_id);
    }
// end of admin page class
}

function order_stocks ()
{
    $raw = get_stocks();

    $stocks_array = make_array($raw, FALSE, 'stock_id');

    $stock_order = 1;

    foreach ($stocks_array as $stock_id) {
        update_stock_order($stock_id, $stock_order);
        $stock_order += 1;
    }

}

/*
 * admin page STOCKS
 */
if ( $current_page == 'settings' &&
     $sub_page == 'stocks'
) {

    // Add new stock to database
    if (
        isset($_POST['new_stock'])
    ) {
        $stock_alias = $_POST['new_stock'];

        set_new_stock($stock_alias);
    }

    // Change stock data
    if (
        isset($_POST['update_stock_id']) &&
        is_numeric($_POST['update_stock_id'])
    ) {
        $stock_id    = $_POST['update_stock_id'];
        $stock_alias = $_POST['update_stock_alias'];

        update_stock($stock_id, $stock_alias);
    }

    // Delete selected stock from database
    if (
        isset($_POST['delete_stock_id']) &&
        is_numeric($_POST['delete_stock_id'])
    ) {
        $stock_id = $_POST['delete_stock_id'];

        delete_stock($stock_id);

        order_stocks();
    }
// end of admin page stock
}

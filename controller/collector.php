<?php

defined('SC_SAFETY_CONST') or die;

/**
 * UNIVERSAL DATA
 * MAIN DATA
 * INFO DATA
 * INCOME DATA
 * OUTCOME DATA
 * SETTINGS DATA
 */

/**
 * #############################################################################
 * Prepare the data depending on the page ######################################
 * #############################################################################
 */

/**
$content_data
 *
array (size=294)
  70 =>
    array (size=5)
      'name_alias' => string '"Сп.кост.Reebok"трикот.' (length=35)
      'product_price' => string '3000' (length=4)
      'product_quantity' => string '20' (length=2)
      'product_amount' => string '60000' (length=5)
      'product_comment' => string '' (length=0)
  209 =>
    array (size=5)
      'name_alias' => string 'Бриджи.Шорты' (length=23)
      'product_price' => string '2100' (length=4)
      'product_quantity' => string '58' (length=2)
      'product_amount' => string '121800' (length=6)
      'product_comment' => string 'zxcv' (length=4)

$quantity
array (size=294)
  70 =>
    array (size=6)
      0 => string FALSE (length=2)
      1 => string FALSE (length=2)
      2 => string '5' (length=1)
      3 => string '5' (length=1)
      4 => string '5' (length=1)
      5 => string '5' (length=1)
  209 =>
    array (size=6)
      0 => string FALSE (length=2)
      1 => string FALSE (length=2)
      2 => string '20' (length=2)
      3 => string '10' (length=2)
      4 => string '2' (length=1)
      5 => string '2' (length=1)
 */

switch ($current_page)
{
    case 'main':
        $content_data = prepare_main_data($current_range);
        break;

    case 'goods':
    case 'debit':
    case 'credit':
    case 'move':

        // Prepare global variables for given page
        $location_names = prepare_location_names($stock_array_raw, $current_stock);
        $content_data   = prepare_content_data($current_page, $current_section);
        $quantity       = prepare_quantity($current_page, $current_stock);
        break;

    case 'income':
        // $supplier_data = get_supplier_data();
        break;

    case 'outcome':
        // $content_data = prepare_outcome_data();
        break;

    case 'settings':
        // $content_data = prepare_settings_data();
        break;
}

$footer_data = prepare_footer_data($current_range);

/**
 * #############################################################################
 * UNIVERSAL DATA ##############################################################
 * #############################################################################
 */


/*
 * ### Prepare MAIN NAV data ###################################################
 */
$main_nav_array = array(

    'left'  => array(
        // Main
        'main'     => L_MAIN,
        // Info
        'goods'    => L_GOODS,
        'debit'    => L_DEBIT,
        'credit'   => L_CREDIT,
        'move'     => L_MOVE,
    ),

    'right' => array(
        // Edit
        'income'   => L_INCOME,
        'outcome'  => L_OUTCOME,
        // Admin
        'settings' => L_SETTINGS,
    ),

);


/*
 * ### Prepare data RANGE ######################################################
 */

$data_range_array = array(
    'd' => L_DAY,
    'w' => L_WEEK,
    'm' => L_MONTH,
);


/*
 * ### Prepare THEME data ######################################################
 */

$theme = get_theme();


/*
 * ### Prepare THEME PICS data #################################################
 */

$theme_pics_array = array(
    'light' => 'fa-star-o',
    'dark'  => 'fa-star-half-o',
    'night' => 'fa-star',
);


/*
 * ### Prepare LOCATIONS data ##################################################
 */

$stock_array = make_array(get_stocks(), 'stock_id', 'stock_alias');


/*
 * ### Prepare ASIDE data ######################################################
 */

function build_class_array ()
{
    $class_object = get_classes_obj();

    $m_data = array(
            'items'   => array(),
            'parents' => array()
    );

    while ($m_item = $class_object->fetch(PDO::FETCH_ASSOC)) {
        $class_id        = $m_item['class_id'];
        $class_parent_id = $m_item['class_parent_id'];

        $m_data['items'][$class_id] = $m_item;
        $m_data['parents'][$class_parent_id][] = $class_id;
    }

    return $m_data;
}

// ### end of aside data


/**
 * #############################################################################
 * FOOTER DATA #################################################################
 * #############################################################################
 */
function prepare_footer_data ($date_range)
{
    $total_sale_quantity = 0;
    $total_sale_amount   = 0;
    $total_sale_discount = 0;

    $raw_sale_data = get_footer_sale_data ($date_range);

    foreach ($raw_sale_data as $key => $value) {

        $amount   = (int)$value['sale_amount'];
        $discount = (int)$value['sale_discount'];

        if ($amount === 0) {
            $value['sale_discount'] = 0;
        }

        $total_sale_quantity += $value['sale_quantity'];
        $total_sale_amount   += $value['sale_amount'];
        $total_sale_discount += $value['sale_discount'];
    }

    $footer_data = [
        'sale_quantity' => $total_sale_quantity,
        'sale_amount'   => $total_sale_amount,
        'sale_discount' => $total_sale_discount
    ];

    return $footer_data;
}


/**
 * #############################################################################
 * MAIN DATA ###################################################################
 * #############################################################################
 */
function initial_stock_data ($array) {

    $raw_stock = get_initial_stock();

    $raw_stock_alias = make_array($raw_stock, 'stock_id', 'stock_alias');
    $raw_stock_id    = make_array($raw_stock, FALSE, 'stock_id');

    if ($array == 'alias') {
        return $raw_stock_alias;
    }

    if ($array == 'id') {
        return $raw_stock_id;
    }
}

function initial_moving_data ($array, $date_range)
{
    $raw_moving = get_initial_moving($date_range);

    $raw_moving_id_product_id = make_array($raw_moving, 'moving_id', 'product_id');
    $raw_moving_id            = make_array($raw_moving, FALSE, 'moving_id');

    if ($array == 'product') {
        return $raw_moving_id_product_id;
    }

    if ($array == 'id') {
        return $raw_moving_id;
    }
}

function prepare_main_data ($date_range)
{
    $result_array = [];

    $raw_stock_id = initial_stock_data('id');

    foreach ($raw_stock_id as $stock_id) {

        $result_array[$stock_id] = prepare_main_row($stock_id, $date_range);

    }

    return $result_array;
}

function prepare_main_row ($stock_id, $date_range)
{
    // ALIAS #########################################
    $raw_stock_alias = initial_stock_data('alias');
    $stock_alias     = $raw_stock_alias[$stock_id];

    // SALE ##########################################
    $s_data = prepare_main_sale($stock_id, $date_range);

    $s_quantity = $s_data['quantity'];
    $s_amount   = $s_data['amount'];
    $s_discount = $s_data['discount'];

    // MOVING ########################################
    $m_data = prepare_main_moving($stock_id, $date_range);

    $m_in_quantity  = $m_data['in_quantity'];
    $m_in_amount    = $m_data['in_amount'];
    $m_out_quantity = $m_data['out_quantity'];
    $m_out_amount   = $m_data['out_amount'];
    $m_total_amount = $m_data['total_amount'];

    // TOTAL #########################################
    $total_amount    = (int)$s_amount + (int)$m_total_amount;
    $total_remainder = prepare_main_total_remainder($stock_id);

    // RESULT ARRAY ##################################
    $result_array = [
        // alias
        'stock_alias'         => $stock_alias,
        // sale
        'sale_quantity'       => $s_quantity,
        'sale_amount'         => $s_amount,
        'sale_discount'       => $s_discount,
        // moving
        'moving_in_quantity'  => $m_in_quantity,
        'moving_in_amount'    => $m_in_amount,
        'moving_out_quantity' => $m_out_quantity,
        'moving_out_amount'   => $m_out_amount,
        'moving_total_amount' => $m_total_amount,
        // total
        'total_amount'        => $total_amount,
        'total_remainder'     => $total_remainder,
    ];

    return $result_array;
}

function prepare_main_sale ($stock_id, $date_range)
{
    $raw_sale_array = get_main_sale_data($date_range, $stock_id);

    foreach ($raw_sale_array as $key => $value) {

        $amount   = (int)$value['sale_amount'];
        $discount = (int)$value['sale_discount'];

        if ($amount === 0) {
            $raw_sale_array[$key]['sale_amount']   = 0 - $value['sale_discount'];
            $raw_sale_array[$key]['sale_discount'] = 0;
        }

    }

    $raw_quantity = make_array($raw_sale_array, FALSE, 'sale_quantity');
    $raw_amount   = make_array($raw_sale_array, FALSE, 'sale_amount');
    $raw_discount = make_array($raw_sale_array, FALSE, 'sale_discount');

    $quantity = array_sum($raw_quantity);
    $amount   = array_sum($raw_amount);
    $discount = array_sum($raw_discount);

    $sale_data = [
        'quantity' => $quantity,
        'amount'   => $amount,
        'discount' => $discount,
    ];

    return $sale_data;
}

function prepare_main_moving ($stock_id, $date_range)
{
    $in_quantity  = 0;
    $in_amount    = 0;
    $out_quantity = 0;
    $out_amount   = 0;
    $total_amount = 0;

    $raw_moving_id_product_id = initial_moving_data('product', $date_range);
    $raw_moving_id            = initial_moving_data('id', $date_range);

    foreach ($raw_moving_id as $moving_id) {

        // product_price
        $product_id    = $raw_moving_id_product_id[$moving_id];
        $product_price = get_main_product_price($product_id);

        // in_quantity
        $current_in_quantity = get_main_moving_in_quantity($moving_id, $stock_id);
        $in_quantity         = $in_quantity + (int)$current_in_quantity;

        // in_amount
        $current_in_amount = $product_price * $current_in_quantity;
        $in_amount         = $in_amount + (int)$current_in_amount;

        // out_quantity
        $current_out_quantity = get_main_moving_out_quantity($moving_id, $stock_id);
        $out_quantity         = $out_quantity + (int)$current_out_quantity;

        // out_amount
        $current_out_amount = $product_price * $current_out_quantity;
        $out_amount         = $out_amount + (int)$current_out_amount;

    }

    $total_amount = $in_amount - $out_amount;

    $moving_data = [
        'in_quantity'  => $in_quantity,
        'in_amount'    => $in_amount,
        'out_quantity' => $out_quantity,
        'out_amount'   => $out_amount,
        'total_amount' => $total_amount,
    ];

    return $moving_data;
}

function prepare_main_total_remainder ($stock_id)
{
    $total_amount = 0;

    $raw_products = get_main_location_stock_data($stock_id);
    $quantity_arr = make_array($raw_products, 'product_id', 'location_quantity');
    $id_arr       = make_array($raw_products, FALSE, 'product_id');

    foreach ($id_arr as $product_id) {
        $price  = get_main_product_price($product_id);
        $amount = $price * $quantity_arr[$product_id];
        $total_amount = $total_amount + $amount;
    }

    return $total_amount;
}


/**
 * #############################################################################
 * INFO DATA ###################################################################
 * #############################################################################
 */


/**
 * ### Prepare LOCATION NAMES ##################################################
 */

function prepare_location_names ($stock_array_raw, $current_stock)
{
    $location_names = array();

    if ($current_stock) {
        foreach ($stock_array_raw as $arr) {

            $stock_id    = $arr['stock_id'];
            $stock_alias = $arr['stock_alias'];

            if ($stock_id == $current_stock) {
                $location_names[$stock_id] = $stock_alias;
                break;
            }
        }
    } else {
        foreach ($stock_array_raw as $arr) {

            $stock_id    = $arr['stock_id'];
            $stock_alias = $arr['stock_alias'];

            $location_names[$stock_id] = $stock_alias;
        }
    }

    return $location_names;
}

// ### end of prepare location names

/**
 * ### Prepare CONTENT DATA ####################################################
 */

function prepare_content_data ($current_page, $current_section)
{
    $page_id  = $current_page;
    $class_id = $current_section;

    switch ($page_id) {
        case 'goods':
            $content_data = get_goods_data($class_id);
            break;

        case 'debit':
            $content_data = get_debit_data($class_id);
            break;

        case 'credit':
            $content_data = get_credit_data($class_id);
            break;

        case 'move':
            $content_data = get_move_data($class_id);
            break;
    }

    return $content_data;
}

/*
 * Get GOODS data
 *
 * row: product_id
 * product_id
 * name_alias
 * product_price
 * :: location_quantity
 * product_quantity
 * product_amount
 *
 */

function get_goods_data ($class_id)
{
    $name_aliase_array = get_names_data($class_id);
    $name_id_array     = array_keys($name_aliase_array);

    $product_id_array  = array();
    $goods_data        = array();

    // product_id
    foreach ($name_id_array as $name_id) {

        $product_id_raw = get_product_id($name_id);

        foreach ($product_id_raw as $arr) {

            array_push($product_id_array, $arr['product_id']);

        }
    }

    // goods_data
    foreach ($product_id_array as $product_id) {

        $product_data = get_product_data($product_id);

        $name_id      = $product_data['name_id'];
        $name_alias   = $name_aliase_array[$name_id];

        $product_item = array(
            'alias'    => $name_alias,
            'price'    => $product_data['product_price'],
            'quantity' => $product_data['product_quantity'],
            'amount'   => $product_data['product_amount'],
            'comment'  => $product_data['product_comment']
        );

        $goods_data[$product_id] = $product_item;
    }

    return $goods_data;
}

// end of get goods data


/*
 * Get DEBIT data
 *
 * row: purchase_id
 * purchase_id
 * purchase_date
 * name_alias
 * purchase_price
 * product_price
 * purchase_quantity
 * :: income_quantity
 * purchase_amount
 * purchase_comment
 *
 */

function get_debit_data ($class_id)
{
    // If user choosed goods section -
    //     we get all goods names from database by class_id.
    // If user did not choose goods section -
    //     we get all goods names in general.
    // As a result we get an array of 'name_id' => 'name_alias'.
    $name_aliase_array = get_names_data($class_id);

    // From $name_aliase_array we make an array of $key => 'name_id'.
    $name_id_array     = array_keys($name_aliase_array);

    $debit_data        = array();
    $purchase_id_array = array();

    // If user had select a goods section -
    // we should to leave the purchase_id that belongs to the sected
    // section (class_id) only.
    if ( $class_id ) {

        $product_id_array  = array();

        // We need to form a list of all product_id for every name_id we have
        foreach ($name_id_array as $name_id) {

            // At the first we get raw product_id data from database
            $product_id_raw = get_product_id($name_id);

            // At the second we make an array of $key => 'product_id'.
            foreach ($product_id_raw as $arr) {
                array_push($product_id_array, $arr['product_id']);
            }
        }

        $purchase_id_raw = get_purchase_id();

        foreach ($purchase_id_raw as $purchase_id => $product_id) {

            if (in_array($product_id, $product_id_array)) {
                $purchase_id_array[$purchase_id] = $product_id;
            }
        }

    // If user had not select a goods section -
    // we should to choose any purchase_id
    } else {

        $purchase_id_array = get_purchase_id();

    }

    // debit_data
    foreach ($purchase_id_array as $purchase_id => $product_id) {

        $purchase_data = get_purchase_data($purchase_id);
        $product_data  = get_product_data($product_id);

        $name_id       = $product_data['name_id'];
        $name_alias    = $name_aliase_array[$name_id];

        $cost = $product_data['product_price'] * $purchase_data['purchase_quantity'];

        $purchase_item = array(
            'date'     => $purchase_data['purchase_date'],
            'alias'    => $name_alias,
            'price'    => $product_data['product_price'],
            'quantity' => $purchase_data['purchase_quantity'],
            'purchase' => $purchase_data['purchase_price'],
            'amount'   => $purchase_data['purchase_amount'],
            'cost'     => $cost,
            'comment'  => $purchase_data['purchase_comment']
        );

        $debit_data[$purchase_id] = $purchase_item;
    }

    return $debit_data;
}

// end of get debit data


/*
 * Get CREDIT data
 *
 * row: sale_id
 * sale_id
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

function get_credit_data ($class_id)
{
    $name_aliase_array = get_names_data($class_id);
    $name_id_array     = array_keys($name_aliase_array);

    $credit_data       = array();
    $sale_id_array     = array();

    if ($class_id) {

        $product_id_array  = array();

        // product_id
        foreach ($name_id_array as $name_id) {

            $product_id_raw = get_product_id($name_id);

            foreach ($product_id_raw as $arr) {
                array_push($product_id_array, $arr['product_id']);
            }
        }

        $sale_id_raw = get_sale_id();

        foreach ($sale_id_raw as $sale_id => $product_id) {

            if (in_array($product_id, $product_id_array)) {
                $sale_id_array[$sale_id] = $product_id;
            }

        }

    } else {

        $sale_id_array = get_sale_id();

    }

    // credit_data
    foreach ($sale_id_array as $sale_id => $product_id) {

        $sale_data    = get_sale_data($sale_id);
        $product_data = get_product_data($product_id);

        $name_id       = $product_data['name_id'];
        $name_alias    = $name_aliase_array[$name_id];

        $sale_item = array(
            'id'        => $sale_data['sale_id'],
            'date'      => $sale_data['sale_date'],
            'alias'     => $name_alias,
            'price'     => $sale_data['sale_price'],
            'discount'  => $sale_data['sale_discount'],
            'remainder' => $sale_data['sale_remainder'],
            'amount'    => $sale_data['sale_amount'],
            'comment'   => $sale_data['sale_comment'],
        );

        $credit_data[$sale_id] = $sale_item;
    }

    return $credit_data;
}

// end of get credit data


/*
 * Get MOVE data
 *
 * row: moving_id
 * moving_id
 * moving_date
 * name_alias
 * product_price
 * :: moving_quantity
 * moving_comment
 *
 */

function get_move_data ($class_id)
{
    $name_aliase_array = get_names_data($class_id);
    $name_id_array     = array_keys($name_aliase_array);

    $move_data         = array();
    $moving_id_array   = array();

    if ($class_id) {

        $product_id_array  = array();

        // product_id
        foreach ($name_id_array as $name_id) {

            $product_id_raw = get_product_id($name_id);

            foreach ($product_id_raw as $arr) {
                array_push($product_id_array, $arr['product_id']);
            }
        }

        $moving_id_raw = get_moving_id();

        foreach ($moving_id_raw as $moving_id => $product_id) {

            if (in_array($product_id, $product_id_array)) {
                $moving_id_array[$moving_id] = $product_id;
            }

        }

    } else {

        $moving_id_array = get_moving_id();

    }

    // move_data
    foreach ($moving_id_array as $moving_id => $product_id) {

        $moving_data   = get_moving_data($moving_id);
        $product_data  = get_product_data($product_id);

        $name_id       = $product_data['name_id'];
        $name_alias    = $name_aliase_array[$name_id];

        $moving_item = array(
            'id'      => $moving_data['moving_id'],
            'date'    => $moving_data['moving_date'],
            'alias'   => $name_alias,
            'price'   => $product_data['product_price'],
            'comment' => $moving_data['moving_comment']
        );

        $move_data[$moving_id] = $moving_item;
    }

    return $move_data;
}

// end of get move data

// ### end of prepare content data


/**
 * ### Prepare QUANTITY ########################################################
 */

function prepare_quantity ($current_page, $current_stock)
{
    switch ($current_page) {
        case 'goods':
            $quantity = get_goods_quantity($current_stock);
            break;

        case 'debit':
            $quantity = get_debit_quantity($current_stock);
            break;

        case 'credit':
            $quantity = get_credit_quantity($current_stock);
            break;

        case 'move':
            $quantity = get_move_quantity();
            break;
    }

    return $quantity;
}


/**
 * ### GOODS quantity ###
 */

function get_goods_quantity ($current_stock)
{
    global $content_data;
    if (!$current_stock) {
        global $location_names;
    }

    $goods_quantity   = array();
    $product_id_array = array_keys($content_data);
    if (!$current_stock) {

        $stock_id_array   = array_keys($location_names);

        foreach ($stock_id_array as $value) {
            $goods_quantity['total'][$value] = 0;
        }

    } else {
        $goods_quantity['total'][$current_stock] = 0;
    }

    foreach ($product_id_array as $product_id) {

        $location_data_raw = get_locations_data($product_id, $current_stock);
        $quantity_row      = array();
        $quantity          = FALSE;

        if (!$current_stock) {

            foreach ($stock_id_array as $stock_id) {

                foreach ($location_data_raw as $arr) {

                    if ($stock_id == $arr['stock_id']) {

                        $quantity = $arr['location_quantity'];
                        $goods_quantity['total'][$stock_id] += $quantity;
                        break;

                    } else {
                        $quantity = FALSE;
                    }
                }

                array_push($quantity_row, $quantity);
            }

        } else {

            if ($location_data_raw) {
                $quantity = $location_data_raw[0]['location_quantity'];
                $goods_quantity['total'][$current_stock] += $quantity;
            }

            array_push($quantity_row, $quantity);

        }

        $goods_quantity[$product_id] = $quantity_row;
    }

    return $goods_quantity;
}

// end of goods quantity

/**
 * ### DEBIT quantity ###
 */

function get_debit_quantity ($current_stock)
{
    global $content_data;
    if (!$current_stock) {
        global $location_names;
    }

    $debit_quantity    = array();
    $purchase_id_array = array_keys($content_data);
    if (!$current_stock) {

        $stock_id_array   = array_keys($location_names);

        foreach ($stock_id_array as $value) {
            $debit_quantity['total'][$value] = 0;
        }

    } else {
        $debit_quantity['total'][$current_stock] = 0;
    }

    foreach ($purchase_id_array as $purchase_id) {

        $income_data_raw = get_incomes_data($purchase_id, $current_stock);
        $quantity_row      = array();
        $quantity          = FALSE;

        if (!$current_stock) {

            foreach ($stock_id_array as $stock_id) {

                foreach ($income_data_raw as $arr) {

                    if ($stock_id == $arr['stock_id']) {

                        $quantity = $arr['income_quantity'];
                        $debit_quantity['total'][$stock_id] += $quantity;
                        break;

                    } else {
                        $quantity = FALSE;
                    }
                }

                array_push($quantity_row, $quantity);
            }

        } else {

            if ($income_data_raw) {
                $quantity = $income_data_raw[0]['income_quantity'];
                $debit_quantity['total'][$current_stock] += $quantity;
            }

            array_push($quantity_row, $quantity);

        }

        $debit_quantity[$purchase_id] = $quantity_row;
    }

    return $debit_quantity;
}

function get_credit_quantity ($current_stock)
{
    global $content_data;
    if (!$current_stock) {
        global $location_names;
    }

    $credit_quantity = array();
    $sale_id_array   = array_keys($content_data);
    if (!$current_stock) {

        $stock_id_array   = array_keys($location_names);

        foreach ($stock_id_array as $value) {
            $credit_quantity['total'][$value] = 0;
        }

    } else {
        $credit_quantity['total'][$current_stock] = 0;
    }

    foreach ($sale_id_array as $sale_id) {

        $outcomes_data_raw = get_outcomes_data($sale_id, $current_stock);
        $quantity_row      = array();
        $quantity          = FALSE;

        if (!$current_stock) {

            foreach ($stock_id_array as $stock_id) {

                foreach ($outcomes_data_raw as $arr) {

                    if ($stock_id == $arr['stock_id']) {

                        $quantity = $arr['sale_quantity'];
                        $credit_quantity['total'][$stock_id] += $quantity;
                        break;

                    } else {
                        $quantity = FALSE;
                    }
                }

                array_push($quantity_row, $quantity);

            }

        } else {

            if ($outcomes_data_raw) {
                $quantity = $outcomes_data_raw[0]['sale_quantity'];
                $credit_quantity['total'][$current_stock] += $quantity;
            }

            array_push($quantity_row, $quantity);

        }

        $credit_quantity[$sale_id] = $quantity_row;
    }

    return $credit_quantity;
}

function get_move_quantity ()
{
    global $content_data;

    $moving_id_array  = array_keys($content_data);
    $moving_quantity  = array();

    foreach ($moving_id_array as $moving_id) {

        $movings_out  = get_movings_out_data($moving_id);
        $movings_in   = get_movings_in_data($moving_id);

        $quantity_row = array(
            'out' => $movings_out,
            'in'  => $movings_in
        );

        $moving_quantity[$moving_id] = $quantity_row;

    }

    return $moving_quantity;
}


/**
 * #############################################################################
 * INCOME DATA #################################################################
 * #############################################################################
 */

if ($current_page == 'income' && $current_section) {
    $name_aliase_array = get_names($current_section);
}


/**
 * #############################################################################
 * OUTCOME DATA ################################################################
 * #############################################################################
 */

if ($current_page == 'outcome' && $sub_page == 'sale' && $current_section) {

    if ($current_stock) {
        $name_aliase_array = get_names_for_move($current_section, $current_stock);
    } else {
        $name_aliase_array = get_names($current_section);
    }

}

function get_names_for_move ($current_section, $current_stock)
{
    // 1. Get all possible name ids for given section (class of goods).
    // 2. For each name id - get product ids belong to it.
    // 3. Get all product ids given stock has.
    // 4. For every product id we have from the names -
    //      check if the array from locations has the same id.
    //      If so - add it to the result array.
    // 5. For every product id from the result array - get its name id -
    //      to form an array of the name ids.
    // 6. Get rid of duplicates in the array of the name ids &
    //      make name aliases array from it.

    // 1.
    $section_name_id_array = get_names_id($current_section);

    $name_product_id_array   = [];
    $stock_product_id_array  = [];
    $result_product_id_array = [];
    $name_ids_array = [];
    $names_array = [];

    // 2.
    foreach ($section_name_id_array as $v) {
        $product_id = get_product_id($v['name_id']);
        foreach ($product_id as $v) {
            array_push($name_product_id_array, $v['product_id']);
        }
    }

    // 3.
    $temp_locations_array = get_locations_product_id($current_stock);

    foreach ($temp_locations_array as $v) {
        array_push($stock_product_id_array, $v['product_id']);
    }

    // 4.
    foreach ($name_product_id_array as $product_id) {
        if (in_array($product_id, $stock_product_id_array)) {
            array_push($result_product_id_array, $product_id);
        }
    }

    // 5.
    foreach ($result_product_id_array as $product_id) {
        $name_id    = get_name_id($product_id);
        array_push($name_ids_array, $name_id);
    }

    // 6.
    $name_ids_array_unique = array_unique($name_ids_array);

    foreach ($name_ids_array_unique as $name_id) {
        $name_alias = get_name_alias($name_id);
        $names_array[$name_id] = $name_alias;
    }

    return $names_array;
}

if ($current_page == 'outcome' && $sub_page == 'move' && $current_section) {
    $name_aliase_array = get_names($current_section);
}


/**
 * #############################################################################
 * SETTINGS DATA ###############################################################
 * #############################################################################
 */

if ($current_page == 'settings' && $sub_page == 'names' && $current_section) {
    $name_aliase_array = get_names($current_section);
}

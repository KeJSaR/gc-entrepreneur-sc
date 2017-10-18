<?php

defined('SC_SAFETY_CONST') or die;

// select * from `sales` where `sale_date` >= DATE_SUB(CURDATE(), INTERVAL 10 DAY) .....MONTH
// select * from `sales` where `sale_date` >= '2015-07-02' and `sale_date` <= '2015-07-08'
// select * from `sales` where `sale_date` BETWEEN CURDATE() - INTERVAL 10 DAY AND CURDATE()

/**
 * #############################################################################
 * ### Working with DATA                                                     ###
 * #############################################################################
 */

function make_array ($db_array, $db_key, $db_value)
{
    $result_array = [];

    foreach ($db_array as $value) {

        if (!$db_key) {

            array_push($result_array, $value[$db_value]);

        } else {

            $result_key   = $value[$db_key];
            $result_value = $value[$db_value];

            $result_array[$result_key] = $result_value;
        }
    }
    return $result_array;
}

function make_range ($type)
{
    global $current_range;

    switch ($current_range) {
        case 'd':
            $range = 'WHERE ' . $type . '_date = CURDATE() ';
            break;

        case 'w':
            $range = 'WHERE ' . $type . '_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ';
            break;

        case 'm':
            $range = 'WHERE ' . $type . '_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ';
            break;

        default:
            $range = '';
            break;
    }

    return $range;
}

/**
 * #############################################################################
 * ### Getting the DATA                                                      ###
 * #############################################################################
 */

function get_correct_prod_data()
{
    global $dbh;

    $sql = 'SELECT  product_id, product_price, product_quantity
            FROM products';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    return $sth->fetchAll();
}

function get_stocks ()
{
    global $dbh;

    $sql = 'SELECT *
            FROM stocks
            ORDER BY stock_order';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    return $sth->fetchAll();
}

function get_classes ()
{
    global $dbh;

    $sql = 'SELECT *
            FROM classes
            ORDER BY class_alias, class_id';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    return $sth->fetchAll();
}

function get_classes_obj ()
{
    global $dbh;

    $sql = 'SELECT *
            FROM classes
            ORDER BY class_parent_id, class_alias';

    $sth = $dbh->query($sql);

    return $sth;
}

function get_theme ()
{
    global $dbh;

    $sql = 'SELECT option_theme
            FROM options';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    $theme_array = $sth->fetch();

    return $theme_array['option_theme'];
}

function get_suppliers ()
{
    global $dbh; // supplier_city, supplier_alias, supplier_address, supplier_phone

    $sql = 'SELECT supplier_id, supplier_city, supplier_alias, supplier_address, supplier_phone
            FROM suppliers
            ORDER BY supplier_city, supplier_alias';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    return $sth->fetchAll();
}

function get_last_id ($name)
{
    global $dbh;

    $sql = 'SELECT ' . $name . '_id
            FROM ' . $name . 's
            ORDER BY ' . $name . '_id DESC
            LIMIT 1';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    $array = $sth->fetch();

    return $array["{$name}_id"];
}

/**
 * #############################################################################
 * ### DATA functions                                                        ###
 * #############################################################################
 */

/**
 * ### NAMES data ##############################################################
 */

function get_names_data ($class_id)
{
    global $dbh;

    $sql = 'SELECT name_id, name_alias
            FROM names ';
    if ($class_id) {
        $sql .= 'WHERE class_id = :class_id ';
    }
    $sql .= 'ORDER BY name_alias';

    $sth = $dbh->prepare($sql);

    if ($class_id) {
        $sth->execute(array(':class_id' => $class_id));
    } else {
        $sth->execute();
    }

    $array  = $sth->fetchAll();
    $result = make_array($array, 'name_id', 'name_alias');

    return $result;
}


/**
 * ### PRODUCT data ############################################################
 */

function get_product_id ($name_id)
{
    global $dbh;

    $sql = 'SELECT product_id
            FROM products
            WHERE name_id = :name_id
            ORDER BY product_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':name_id' => $name_id));

    return $sth->fetchAll();
}

function get_product_data ($product_id)
{
    global $dbh;

    $sql = 'SELECT name_id, product_price,
                   product_quantity, product_amount, product_comment
            FROM products
            WHERE product_id = :product_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':product_id' => $product_id));

    $result_array = $sth->fetchAll();

    if ($result_array) {
        $result = $result_array[0];
    } else {
        $result = FALSE;
    }

    return $result;
}


/**
 * ### PURCHASE data ###########################################################
 */

function get_purchase_id ()
{
    global $dbh;

    $sql = 'SELECT purchase_id, product_id
            FROM purchases '
                . make_range('purchase') . '
            ORDER BY purchase_date';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    $array  = $sth->fetchAll();
    $result = make_array($array, 'purchase_id', 'product_id');

    return $result;
}

function get_purchase_data ($purchase_id)
{
    global $dbh;

    $sql = 'SELECT purchase_date, purchase_price, purchase_quantity,
                   purchase_amount, purchase_comment
            FROM purchases
            WHERE purchase_id = :purchase_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':purchase_id' => $purchase_id));

    $result_array = $sth->fetchAll();

    if ($result_array) {
        $result = $result_array[0];
    } else {
        $result = FALSE;
    }

    return $result;
}


/**
 * ### SALES data ##############################################################
 */

function get_sale_id ()
{
    global $dbh;

    $sql = 'SELECT sale_id, product_id
            FROM sales '
                . make_range('sale') . '
            ORDER BY sale_date';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    $array  = $sth->fetchAll();
    $result = make_array($array, 'sale_id', 'product_id');

    return $result;
}

function get_sale_data ($sale_id)
{
    global $dbh;

    $sql = 'SELECT sale_id, sale_date, sale_price, sale_discount,
                   sale_remainder, sale_amount, sale_comment
            FROM sales
            WHERE sale_id = :sale_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':sale_id' => $sale_id));

    $result_array = $sth->fetchAll();

    return $result_array[0];
}


/**
 * ### MOVINGS data ############################################################
 */

function get_moving_id ()
{
    global $dbh;

    $sql = 'SELECT moving_id, product_id
            FROM movings '
                . make_range('moving') . '
            ORDER BY moving_date';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    $array  = $sth->fetchAll();
    $result = make_array($array, 'moving_id', 'product_id');

    return $result;
}

function get_moving_data ($moving_id)
{
    global $dbh;

    $sql = 'SELECT moving_id, moving_date, moving_comment
            FROM movings
            WHERE moving_id = :moving_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':moving_id' => $moving_id));

    $result_array = $sth->fetchAll();

    return $result_array[0];
}


/**
 * ### LOCATIONS data ##########################################################
 */

function get_locations_data ($product_id, $stock_id)
{
    global $dbh;

    $sql = 'SELECT stock_id, location_quantity
            FROM locations
            WHERE product_id = :product_id';
    if ($stock_id) {
        $sql .= ' AND stock_id = :stock_id';
    }

    $sth = $dbh->prepare($sql);

    if ($stock_id) {
        $sth->execute(array(
            ':product_id' => $product_id,
            ':stock_id'   => $stock_id
        ));
    } else {
        $sth->execute(array(
            ':product_id' => $product_id
        ));
    }

    return $sth->fetchAll();
}

function get_incomes_data ($purchase_id, $stock_id)
{
    global $dbh;

    $sql = 'SELECT stock_id, income_quantity
            FROM incomes
            WHERE purchase_id = :purchase_id';
    if ($stock_id) {
        $sql .= ' AND stock_id = :stock_id';
    }

    $sth = $dbh->prepare($sql);

    if ($stock_id) {
        $sth->execute(array(
            ':purchase_id' => $purchase_id,
            ':stock_id'   => $stock_id
        ));
    } else {
        $sth->execute(array(
            ':purchase_id' => $purchase_id
        ));
    }

    return $sth->fetchAll();
}

function get_outcomes_data ($sale_id, $stock_id)
{
    global $dbh;

    $sql = 'SELECT stock_id, sale_quantity
            FROM sales
            WHERE sale_id = :sale_id';
    if ($stock_id) {
        $sql .= ' AND stock_id = :stock_id';
    }

    $sth = $dbh->prepare($sql);

    if ($stock_id) {
        $sth->execute(array(
            ':sale_id' => $sale_id,
            ':stock_id'   => $stock_id
        ));
    } else {
        $sth->execute(array(
            ':sale_id' => $sale_id
        ));
    }

    return $sth->fetchAll();
}

function get_movings_out_data ($moving_id)
{
    global $dbh;

    $sql = 'SELECT stock_id, moving_out_quantity
            FROM movings_out
            WHERE moving_id = :moving_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':moving_id' => $moving_id));

    $array  = $sth->fetchAll();
    $result = make_array($array, 'stock_id', 'moving_out_quantity');

    return $result;
}

function get_movings_in_data ($moving_id)
{
    global $dbh;

    $sql = 'SELECT stock_id, moving_in_quantity
            FROM movings_in
            WHERE moving_id = :moving_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':moving_id' => $moving_id));

    $array  = $sth->fetchAll();
    $result = make_array($array, 'stock_id', 'moving_in_quantity');

    return $result;
}


/**
 * #############################################################################
 * ### INCOME functions                                                      ###
 * #############################################################################
 */

function get_names ($class_id)
{
    global $dbh;

    $sql = 'SELECT name_id, name_alias
            FROM names
            WHERE class_id = :class_id
            ORDER BY name_alias';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':class_id' => $class_id));

    $array  = $sth->fetchAll();

    $result_array = make_array($array, 'name_id', 'name_alias');

    return $result_array;
}

// function get_supplier_data ()
// {
//     global $dbh; // supplier_city, supplier_alias, supplier_address, supplier_phone

//     $sql = 'SELECT supplier_id, supplier_city, supplier_alias, supplier_address, supplier_phone
//             FROM suppliers
//             ORDER BY supplier_id';

//     $sth = $dbh->prepare($sql);

//     $sth->execute();

//     $array  = $sth->fetchAll();

//     return $array;
// }

function get_location_id ($product_id, $stock_id)
{
    global $dbh;

    $sql = 'SELECT location_id
            FROM locations
            WHERE product_id = :product_id
                AND stock_id = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':product_id' => $product_id,
        ':stock_id'   => $stock_id
    ));

    $array = $sth->fetch();

    return $array['location_id'];
}



/**
 * #############################################################################
 * ### OUTCOME functions                                                     ###
 * #############################################################################
 */

function get_names_id ($class_id)
{
    global $dbh;

    $sql = 'SELECT name_id
            FROM names
            WHERE class_id = :class_id
            ORDER BY name_alias';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':class_id' => $class_id));

    $result_array  = $sth->fetchAll();

    return $result_array;
}

function get_locations_product_id ($stock_id)
{
    global $dbh;

    $sql = 'SELECT product_id
            FROM locations
            WHERE stock_id = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':stock_id' => $stock_id));

    return $sth->fetchAll();
}

function get_name_id ($product_id)
{
    global $dbh;

    $sql = 'SELECT name_id
            FROM products
            WHERE product_id = :product_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':product_id' => $product_id));

    $result_array  = $sth->fetch();

    return $result_array['name_id'];
}

function get_name_alias ($name_id)
{
    global $dbh;

    $sql = 'SELECT name_alias
            FROM names
            WHERE name_id = :name_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':name_id' => $name_id));

    $result_array  = $sth->fetch();

    return $result_array['name_alias'];
}













function prepare_range ($date_range)
{
    switch ($date_range) {

        case 'w':
            $range = '>= DATE_SUB(CURDATE(), INTERVAL 7 DAY)';
            break;

        case 'm':
            $range = '>= DATE_SUB(CURDATE(), INTERVAL 30 DAY)';
            break;

        case 'd':
        default:
            $range = '= CURDATE()';
            break;

    }

    return $range;
}

function get_initial_stock ()
{
    global $dbh;

    $sql = 'SELECT stock_id, stock_alias
            FROM stocks
            ORDER BY stock_order';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    return $sth->fetchAll();
}

function get_initial_moving ($date_range)
{
    global $dbh;

    $range = prepare_range($date_range);

    $sql = 'SELECT moving_id, product_id
            FROM movings
            WHERE moving_date ' . $range;

    $sth = $dbh->prepare($sql);

    $sth->execute();

    return $sth->fetchAll();
}

function get_main_sale_data ($date_range, $stock_id)
{
    global $dbh;

    $range = prepare_range($date_range);

    $sql = 'SELECT sale_quantity, sale_amount, sale_discount
            FROM sales
            WHERE stock_id = :stock_id
                AND sale_date ' . $range;

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':stock_id' => $stock_id));

    return $sth->fetchAll();
}

function get_main_moving_in_quantity ($moving_id, $stock_id)
{
    global $dbh;

    $sql = 'SELECT moving_in_quantity
            FROM movings_in
            WHERE moving_id = :moving_id
                AND stock_id = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':moving_id' => $moving_id,
        ':stock_id' => $stock_id
    ));

    $result_array  = $sth->fetch();

    return $result_array['moving_in_quantity'];
}

function get_main_moving_out_quantity ($moving_id, $stock_id)
{
    global $dbh;

    $sql = 'SELECT moving_out_quantity
            FROM movings_out
            WHERE moving_id = :moving_id
                AND stock_id = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':moving_id' => $moving_id,
        ':stock_id' => $stock_id
    ));

    $result_array  = $sth->fetch();

    return $result_array['moving_out_quantity'];
}

function get_main_product_price ($product_id)
{
    global $dbh;

    $sql = 'SELECT product_price
            FROM products
            WHERE product_id = :product_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':product_id' => $product_id
    ));

    $result_array  = $sth->fetch();

    return $result_array['product_price'];
}

function get_main_location_stock_data ($stock_id)
{
    global $dbh;

    $sql = 'SELECT product_id, location_quantity
            FROM locations
            WHERE stock_id = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':stock_id' => $stock_id));

    return $sth->fetchAll();
}








function get_footer_sale_data ($date_range)
{
    global $dbh;

    $range = prepare_range($date_range);

    $sql = 'SELECT sale_quantity, sale_discount, sale_amount
            FROM sales
            WHERE sale_date ' . $range;

    $sth = $dbh->prepare($sql);

    $sth->execute();

    return $sth->fetchAll();
}

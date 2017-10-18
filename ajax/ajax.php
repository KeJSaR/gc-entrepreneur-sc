<?php

error_reporting(E_ALL);

define('SC_SAFETY_CONST', TRUE);
define('DS', DIRECTORY_SEPARATOR);

$raw_root_dir = explode(DS, __DIR__);
array_pop($raw_root_dir);
$root_dir = implode(DS, $raw_root_dir);

define('SC_ROOT_DIR', $root_dir . DS);

require_once SC_ROOT_DIR . DS . 'config' . DS . 'defines.php';
require_once SC_ROOT_DIR . DS . 'config' . DS . 'config.php';

require_once (SC_MODEL_DIR . 'db_conn.php');

$ajax_html;

function make_ajax_array ($db_array, $db_key, $db_value)
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

/*
################################################################################
### INCOME                                                                   ###
################################################################################
*/

function ajax_income_product_price ($post_name_id)
{
    global $dbh;

    $sql = 'SELECT product_id, product_price
            FROM products
            WHERE name_id = :name_id
            ORDER BY product_price';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':name_id' => $post_name_id));

    $temp_prices_array = $sth->fetchAll();

    $product_prices_array = make_ajax_array($temp_prices_array, 'product_id', 'product_price');

    $html = '';

    foreach ($product_prices_array as $product_id => $product_price) {
        $html .= '<span data-product-id="' . $product_id . '">'
                . $product_price
                . '</span>' . BR;
    }

    return $html;
}

if ( isset($_POST['name_id']) && isset($_POST['income']) ) {
    $ajax_html = ajax_income_product_price($_POST['name_id']);
    echo $ajax_html;
}

// #############################################################################

function ajax_income_product_data ($post_product_id)
{
    global $dbh;

    $sql = 'SELECT *
            FROM products
            WHERE product_id = :product_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':product_id' => $post_product_id));

    $product_array = $sth->fetch();

    // #################################################

    $sql = 'SELECT *
            FROM locations
            WHERE product_id = :product_id
            ORDER BY stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':product_id' => $post_product_id));

    $location_data = $sth->fetchAll();

    // get products we have from database
    $locations_array = array(
        'locations' => $location_data
    );

    // #################################################

    $result_array = array_merge($product_array, $locations_array);
    $json_array   = json_encode($result_array);

    return $json_array;
}

if ( isset($_POST['product_id']) && isset($_POST['income']) ) {
    $ajax_html = ajax_income_product_data($_POST['product_id']);
    echo $ajax_html;
}

/*
################################################################################
### OUTCOME / SELL                                                           ###
################################################################################
*/

function get_product_id_from_locations ($stock_id)
{
    global $dbh;

    $sql = 'SELECT product_id
            FROM locations
            WHERE stock_id = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':stock_id' => $stock_id));

    return $sth->fetchAll();
}

function get_product_id_from_products ($name_id)
{
    global $dbh;

    $sql = 'SELECT product_id
            FROM products
            WHERE name_id = :name_id
            ORDER BY product_price';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':name_id' => $name_id));

    return $sth->fetchAll();
}

function get_product_price_from_products ($product_id)
{
    global $dbh;

    $sql = 'SELECT product_price
            FROM products
            WHERE product_id = :product_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':product_id' => $product_id));

    $result_array = $sth->fetch();

    return $result_array['product_price'];
}

function get_product_quantity ($product_id)
{
    global $dbh;

    $sql = 'SELECT product_quantity
            FROM products
            WHERE product_id = :product_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':product_id' => $product_id));

    $array = $sth->fetch();

    return $array['product_quantity'];
}

function get_location_quantity ($product_id, $stock_id)
{
    global $dbh;

    $sql = 'SELECT location_quantity
            FROM locations
            WHERE product_id = :product_id
                AND stock_id = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':product_id' => $product_id,
        ':stock_id'   => $stock_id
    ));

    $array = $sth->fetch();

    return $array['location_quantity'];
}

function ajax_outcome_product_price ($post_name_id, $post_stock_id)
{

    $temp_prices_array = get_product_id_from_products($post_name_id);

    $name_product_id_array = make_ajax_array($temp_prices_array, FALSE, 'product_id');


    $temp_locations_array = get_product_id_from_locations($post_stock_id);

    $stock_product_id_array  = [];

    foreach ($temp_locations_array as $v) {
        array_push($stock_product_id_array, $v['product_id']);
    }

    $result_product_id_array  = [];

    foreach ($name_product_id_array as $product_id) {
        if (in_array($product_id, $stock_product_id_array)) {
            array_push($result_product_id_array, $product_id);
        }
    }

    $product_prices_array = [];

    foreach ($result_product_id_array as $product_id) {
        $product_price = get_product_price_from_products($product_id);
        $product_prices_array[$product_id] = $product_price;
    }


    $html = '';

    foreach ($product_prices_array as $product_id => $product_price) {
        $html .= '<span';
        if (count($product_prices_array) == 1) {
            $html .= ' id="the-price"';
        }
        $html .= ' data-product-id="' . $product_id . '">'
                . $product_price
                . '</span>' . BR;
    }

    return $html;
}

if ( isset($_POST['name_id']) && isset($_POST['stock_id']) && isset($_POST['sale']) ) {
    $ajax_html = ajax_outcome_product_price($_POST['name_id'], $_POST['stock_id']);
    echo $ajax_html;
}


function ajax_outcome_quantity_data ($product_id, $stock_id)
{
    $product_quantity  = get_product_quantity ($product_id);
    $location_quantity = get_location_quantity ($product_id, $stock_id);

    $result_array = array(
        'product_quantity'  => $product_quantity,
        'location_quantity' => $location_quantity
    );

    $json_array = json_encode($result_array);

    return $json_array;
}

if ( isset($_POST['product_id']) && isset($_POST['stock_id']) && isset($_POST['sale']) ) {
    $ajax_html = ajax_outcome_quantity_data($_POST['product_id'], $_POST['stock_id']);
    echo $ajax_html;
}

/*
################################################################################
### OUTCOME / MOVE                                                           ###
################################################################################
*/

function ajax_move_product_price ($post_name_id)
{
    global $dbh;

    $sql = 'SELECT product_id, product_price
            FROM products
            WHERE name_id = :name_id
            ORDER BY product_price';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':name_id' => $post_name_id));

    $temp_prices_array = $sth->fetchAll();

    $product_prices_array = make_ajax_array($temp_prices_array, 'product_id', 'product_price');

    $html = '';

    foreach ($product_prices_array as $product_id => $product_price) {
        $html .= '<span data-product-id="' . $product_id . '">'
                . $product_price
                . '</span>' . BR;
    }

    return $html;
}

if ( isset($_POST['name_id']) && isset($_POST['move']) ) {
    $ajax_html = ajax_move_product_price($_POST['name_id']);
    echo $ajax_html;
}

// #############################################################################

function ajax_move_product_data ($post_product_id)
{
    global $dbh;

    $sql = 'SELECT *
            FROM products
            WHERE product_id = :product_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':product_id' => $post_product_id));

    $product_array = $sth->fetch();

    //var_dump($product_array);

    // #################################################

    $sql = 'SELECT *
            FROM locations
            WHERE product_id = :product_id
            ORDER BY stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':product_id' => $post_product_id));

    $location_data = $sth->fetchAll();

    // get products we have from database
    $locations_array = array(
        'locations' => $location_data
    );

    // #################################################

    $result_array = array_merge($product_array, $locations_array);
    $json_array   = json_encode($result_array);

    return $json_array;
}

if ( isset($_POST['product_id']) && isset($_POST['move']) ) {
    $ajax_html = ajax_move_product_data($_POST['product_id']);
    echo $ajax_html;
}

/*
################################################################################
### OUTCOME / SUPPLIER                                                       ###
################################################################################
*/

function ajax_get_supplier_data ($supplier_id)
{
    global $dbh;

    $sql = 'SELECT supplier_city, supplier_alias, supplier_phone, supplier_address, supplier_comment
            FROM suppliers
            WHERE supplier_id = :supplier_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':supplier_id' => $supplier_id
    ));

    $array = $sth->fetch();

    return json_encode($array);
}

if ( isset($_POST['supplier_id']) && isset($_POST['suppliers']) ) {
    $ajax_html = ajax_get_supplier_data($_POST['supplier_id']);
    echo $ajax_html;
}

/*
################################################################################
### SETTINGS / STOCKS                                                        ###
################################################################################
*/

function ajax_get_stock_id ($stock_order)
{
    global $dbh;

    $sql = 'SELECT stock_id
            FROM stocks
            WHERE stock_order = :stock_order';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':stock_order' => $stock_order));

    $result_array = $sth->fetch();

    return $result_array['stock_id'];
}

function ajax_get_stock_order_max ()
{
    global $dbh;

    $sql = 'SELECT stock_order
            FROM stocks';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    $stock_order_array = $sth->fetchAll();

    $arr = [];

    foreach ($stock_order_array as $value) {
        array_push($arr, $value['stock_order']);
    }

    $max = max($arr);

    return $max;
}

function ajax_update_stock_order ($stock_id, $stock_order)
{
    global $dbh;

    $sql = 'UPDATE stocks
            SET stock_order = :stock_order
            WHERE stock_id = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':stock_id'    => $stock_id,
        ':stock_order' => $stock_order
    ));
}

function ajax_move_stock ($stock_order, $move)
{
    $stock_id_curr = ajax_get_stock_id($stock_order);

    if ($move == 'up') {

        $prev = $stock_order - 1;

        if ($prev <= 0) {
            die;
        }

        $stock_id_prev = ajax_get_stock_id($prev);

        ajax_update_stock_order($stock_id_curr, $prev);
        ajax_update_stock_order($stock_id_prev, $stock_order);

    }

    if ($move == 'down') {

        $max  = ajax_get_stock_order_max();
        $next = $stock_order + 1;

        if ($next > $max) {
            die;
        }

        $stock_id_next = ajax_get_stock_id($next);

        ajax_update_stock_order($stock_id_curr, $next);
        ajax_update_stock_order($stock_id_next, $stock_order);

    }


}

if ( isset($_POST['stock_order']) && isset($_POST['stocks']) ) {

    // echo 'stock_order: ' . $_POST['stock_order'] . '; stocks: ' . $_POST['stocks'] . '; move: ' . $_POST['move'] . ';';
    ajax_move_stock($_POST['stock_order'], $_POST['move']);
}

/*
################################################################################
################################################################################
################################################################################
*/





if ( isset($_POST['sale_stock_id']) && isset($_POST['sale_product_id'])) {

    // prepare array of options for query
    $query_options = array(
            'select_options' => 'location_quantity',
            'from_options'   => 'locations',
            'where_options'  => array(
                'product_id' => $_POST['sale_product_id'],
                'stock_id'   => $_POST['sale_stock_id']
            )
    );

    // get products we have from database
    $locations_array = select_query ($query_options, 'fetch');
    $location_quantity = $locations_array['location_quantity'];

    // prepare array of options for query
    $query_options = array(
            'select_options' => 'product_quantity',
            'from_options'   => 'products',
            'where_options'  => array( 'product_id' => $_POST['sale_product_id'] )
    );

    // get products we have from database
    $products_array = select_query ($query_options, 'fetch');
    $product_quantity = $products_array['product_quantity'];

    $result_array = array(
        'location_quantity' => $location_quantity,
        'product_quantity'  => $product_quantity
    );
    $json_array = json_encode($result_array);

    echo $json_array;

}

if ( isset($_POST['change_new_price']) && isset($_POST['change_product_id'])) {

    $product_price = $_POST['change_new_price'];
    $product_id    = $_POST['change_product_id'];

    // set new product data
    $table  = 'products';
    $params = array(
        'set_options'   => array(
            'product_price'    => $product_price
        ),
        'where_options' => array(
            'product_id' => $product_id
        )
    );
    update($table, $params);

    $query_options = array(
            'select_options' => 'product_price',
            'from_options'   => 'products',
            'where_options'  => array( 'product_id' => $product_id )
    );

    // get products we have from database
    $products_array = select_query ($query_options, 'fetch');
    $new_price      = $products_array['product_price'];

    echo $new_price;

}

if ( isset($_POST['change_new_name']) && isset($_POST['change_product_id'])) {

    $name_alias = $_POST['change_new_name'];
    $product_id    = $_POST['change_product_id'];

    $query_options = array(
            'select_options' => 'name_id',
            'from_options'   => 'products',
            'where_options'  => array( 'product_id' => $product_id )
    );

    // get products we have from database
    $products_array = select_query ($query_options, 'fetch');
    $name_id        = $products_array['name_id'];

    // set new product data
    $table  = 'names';
    $params = array(
        'set_options'   => array(
            'name_alias'    => $name_alias
        ),
        'where_options' => array(
            'name_id' => $name_id
        )
    );
    update($table, $params);

    $query_options = array(
            'select_options' => 'name_alias',
            'from_options'   => 'names',
            'where_options'  => array( 'name_id' => $name_id )
    );
    // get products we have from database
    $products_array = select_query ($query_options, 'fetch');
    $name_alias     = $products_array['name_alias'];

    echo $name_alias;

}

if ( isset($_POST['change_new_code']) && isset($_POST['change_product_id'])) {

    $product_code = $_POST['change_new_code'];
    $product_id   = $_POST['change_product_id'];

    // set new product data
    $table  = 'products';
    $params = array(
        'set_options'   => array(
            'product_code'    => $product_code
        ),
        'where_options' => array(
            'product_id' => $product_id
        )
    );
    update($table, $params);

    $query_options = array(
            'select_options' => 'product_code',
            'from_options'   => 'products',
            'where_options'  => array( 'product_id' => $product_id )
    );
    // get products we have from database
    $products_array = select_query ($query_options, 'fetch');
    $product_code   = $products_array['product_code'];

    echo $product_code;

}







function correct_sales ()
{
    global $dbh;

    $sql = 'SELECT sale_id, sale_discount
            FROM sales';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    return $sth->fetchAll();
}

function update_correct_sales ($sale_id, $sale_discount)
{
    global $dbh;

    $sql = 'UPDATE sales
            SET sale_discount = :sale_discount
            WHERE sale_id = :sale_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':sale_discount' => $sale_discount,
        ':sale_id' => $sale_id
    ));
}


$raw_sales_array = correct_sales();

$sales_array_to_correct = make_array($raw_sales_array, 'sale_id', 'sale_discount');

foreach ($sales_array_to_correct as $key => $value) {

    $sale_dc_corr = (int)$value;

    if ($sale_dc_corr < 0) {
        $dc = 0 - $sale_dc_corr;
        update_correct_sales($key, $dc);

        echo '$sale_id: ';
        var_dump($key);
        echo '$sale_discount: ';
        var_dump($dc);
    }

}

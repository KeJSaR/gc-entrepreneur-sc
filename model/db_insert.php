<?php

defined('SC_SAFETY_CONST') or die;

function set_new_product ($name_id, $product_price, $product_quantity,
    $product_amount, $product_comment
) {
    global $dbh;

    $sql = 'INSERT INTO products
                (name_id,
                 product_price,
                 product_quantity,
                 product_amount,
                 product_comment)
            VALUES
                (:name_id,
                 :product_price,
                 :product_quantity,
                 :product_amount,
                 :product_comment)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':name_id'          => $name_id,
        ':product_price'    => $product_price,
        ':product_quantity' => $product_quantity,
        ':product_amount'   => $product_amount,
        ':product_comment'  => $product_comment
    ));
}

function set_purchase ($product_id, $purchase_price, $purchase_date,
    $supplier_id, $purchase_quantity, $purchase_amount,
    $purchase_comment
) {
    global $dbh;

    $sql = 'INSERT INTO purchases
                (product_id,
                 purchase_price,
                 purchase_date,
                 supplier_id,
                 purchase_quantity,
                 purchase_amount,
                 purchase_comment)
            VALUES
                (:product_id,
                 :purchase_price,
                 :purchase_date,
                 :supplier_id,
                 :purchase_quantity,
                 :purchase_amount,
                 :purchase_comment)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':product_id'        => $product_id,
        ':purchase_price'    => $purchase_price,
        ':purchase_date'     => $purchase_date,
        ':supplier_id'       => $supplier_id,
        ':purchase_quantity' => $purchase_quantity,
        ':purchase_amount'   => $purchase_amount,
        ':purchase_comment'  => $purchase_comment
    ));
}

function set_income ($purchase_id, $stock_id, $income_quantity)
{
    global $dbh;

    $sql = 'INSERT INTO incomes
                (purchase_id,
                 stock_id,
                 income_quantity)
            VALUES
                (:purchase_id,
                 :stock_id,
                 :income_quantity)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':purchase_id'     => $purchase_id,
        ':stock_id'        => $stock_id,
        ':income_quantity' => $income_quantity
    ));
}

function set_location ($product_id, $stock_id, $location_quantity)
{
    global $dbh;

    $sql = 'INSERT INTO locations
                (product_id,
                 stock_id,
                 location_quantity)
            VALUES
                (:product_id,
                 :stock_id,
                 :location_quantity)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':product_id'        => $product_id,
        ':stock_id'          => $stock_id,
        ':location_quantity' => $location_quantity
    ));
}

function set_sale ($product_id, $stock_id, $sale_price, $sale_quantity,
    $sale_discount, $sale_amount, $sale_remainder, $sale_date, $sale_comment
) {
    global $dbh;

    $sql = 'INSERT INTO sales
                (product_id,
                stock_id,
                sale_price,
                sale_quantity,
                sale_discount,
                sale_amount,
                sale_remainder,
                sale_date,
                sale_comment)
            VALUES
                (:product_id,
                :stock_id,
                :sale_price,
                :sale_quantity,
                :sale_discount,
                :sale_amount,
                :sale_remainder,
                :sale_date,
                :sale_comment)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':product_id'     => $product_id,
        ':stock_id'       => $stock_id,
        ':sale_price'     => $sale_price,
        ':sale_quantity'  => $sale_quantity,
        ':sale_discount'  => $sale_discount,
        ':sale_amount'    => $sale_amount,
        ':sale_remainder' => $sale_remainder,
        ':sale_date'      => $sale_date,
        ':sale_comment'   => $sale_comment
    ));
}

function set_movings ($product_id, $moving_date, $moving_comment)
{
    global $dbh;

    $sql = 'INSERT INTO movings
                (product_id,
                 moving_date,
                 moving_comment)
            VALUES
                (:product_id,
                 :moving_date,
                 :moving_comment)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':product_id'      => $product_id,
        ':moving_date'     => $moving_date,
        ':moving_comment'  => $moving_comment
    ));
}

function set_movings_out ($moving_id, $stock_id, $moving_out_quantity)
{
    global $dbh;

    $sql = 'INSERT INTO movings_out
                (moving_id,
                 stock_id,
                 moving_out_quantity)
            VALUES
                (:moving_id,
                 :stock_id,
                 :moving_out_quantity)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':moving_id'           => $moving_id,
        ':stock_id'            => $stock_id,
        ':moving_out_quantity' => $moving_out_quantity
    ));
}

function set_movings_in ($moving_id, $stock_id, $moving_in_quantity)
{
    global $dbh;

    $sql = 'INSERT INTO movings_in
                (moving_id,
                 stock_id,
                 moving_in_quantity)
            VALUES
                (:moving_id,
                 :stock_id,
                 :moving_in_quantity)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':moving_id'          => $moving_id,
        ':stock_id'           => $stock_id,
        ':moving_in_quantity' => $moving_in_quantity
    ));
}

function set_class ($class_parent_id, $class_alias)
{
    global $dbh;

    $sql = 'INSERT INTO classes
                (class_parent_id,
                 class_alias)
            VALUES
                (:class_parent_id,
                 :class_alias)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':class_parent_id' => $class_parent_id,
        ':class_alias'     => $class_alias
    ));
}

function set_name ($class_id, $name_alias)
{
    global $dbh;

    $sql = 'INSERT INTO names
                (class_id,
                 name_alias)
            VALUES
                (:class_id,
                 :name_alias)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':class_id'   => $class_id,
        ':name_alias' => $name_alias
    ));
}

function set_new_supplier ($supplier_city, $supplier_alias, $supplier_phone,
    $supplier_address, $supplier_comment
) {
    global $dbh;

    $sql = 'INSERT INTO suppliers
                (supplier_city,
                 supplier_alias,
                 supplier_phone,
                 supplier_address,
                 supplier_comment)
            VALUES
                (:supplier_city,
                 :supplier_alias,
                 :supplier_phone,
                 :supplier_address,
                 :supplier_comment)';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':supplier_city'    => $supplier_city,
        ':supplier_alias'   => $supplier_alias,
        ':supplier_phone'   => $supplier_phone,
        ':supplier_address' => $supplier_address,
        ':supplier_comment' => $supplier_comment
    ));
}

function set_new_stock ($stock_alias)
{
    global $dbh;

    $sql = 'SELECT stock_order
            FROM stocks';

    $sth = $dbh->prepare($sql);

    $sth->execute();

    $stock_order_array = $sth->fetchAll();

    if (empty($stock_order_array)) {

        $stock_order = 1;

    } else {

        $arr = [];

        foreach ($stock_order_array as $value) {
            array_push($arr, $value['stock_order']);
        }

        $stock_order = max($arr) + 1;

    }

    $sql = 'INSERT INTO stocks
                (
                 stock_alias,
                 stock_order
                )
            VALUES
                (
                 :stock_alias,
                 :stock_order
                )';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':stock_alias' => $stock_alias,
        ':stock_order' => $stock_order
    ));
}

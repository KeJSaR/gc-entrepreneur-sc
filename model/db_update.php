<?php

defined('SC_SAFETY_CONST') or die;

function update_correct_amount($corr_id, $corr_amount)
{
    global $dbh;

    $sql = 'UPDATE products
            SET product_amount = :corr_amount
            WHERE product_id = :corr_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':corr_amount' => $corr_amount,
        ':corr_id' => $corr_id
    ));
}

function update_theme ($theme)
{
    global $dbh;

    $sql = 'UPDATE options
            SET option_theme = :theme
            WHERE option_id = 0';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(':theme' => $theme));
}

function update_product ($product_id, $product_quantity, $product_amount)
{
    global $dbh;

    $sql = 'UPDATE products
            SET product_quantity = :product_quantity,
                product_amount   = :product_amount
            WHERE product_id = :product_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':product_id'       => $product_id,
        ':product_quantity' => $product_quantity,
        ':product_amount'   => $product_amount
    ));
}

function update_location ($location_quantity, $product_id, $stock_id)
{
    global $dbh;

    $sql = 'UPDATE locations
            SET location_quantity = :location_quantity
            WHERE product_id = :product_id
                AND stock_id = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':location_quantity' => $location_quantity,
        ':product_id'        => $product_id,
        ':stock_id'          => $stock_id
    ));
}

function update_location_by_id ($location_quantity, $location_id)
{
    global $dbh;

    $sql = 'UPDATE locations
            SET location_quantity = :location_quantity
            WHERE location_id = :location_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':location_quantity' => $location_quantity,
        ':location_id'       => $location_id
    ));
}

function update_class ($class_alias, $class_id)
{
    global $dbh;

    $sql = 'UPDATE classes
            SET class_alias = :class_alias
            WHERE class_id = :class_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':class_alias' => $class_alias,
        ':class_id'    => $class_id
    ));
}

function update_name ($name_alias, $name_id)
{
    global $dbh;

    $sql = 'UPDATE names
            SET name_alias = :name_alias
            WHERE name_id  = :name_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':name_alias' => $name_alias,
        ':name_id'    => $name_id
    ));
}

function update_supplier ($supplier_id, $supplier_city, $supplier_alias,
            $supplier_phone, $supplier_address, $supplier_comment
) {
    global $dbh;

    $sql = 'UPDATE suppliers
            SET supplier_city    = :supplier_city,
                supplier_alias   = :supplier_alias,
                supplier_phone   = :supplier_phone,
                supplier_address = :supplier_address,
                supplier_comment = :supplier_comment
            WHERE supplier_id    = :supplier_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':supplier_city'    => $supplier_city,
        ':supplier_alias'   => $supplier_alias,
        ':supplier_phone'   => $supplier_phone,
        ':supplier_address' => $supplier_address,
        ':supplier_comment' => $supplier_comment,
        ':supplier_id'      => $supplier_id
    ));
}

function update_stock ($stock_id, $stock_alias)
{
    global $dbh;

    $sql = 'UPDATE stocks
            SET stock_alias = :stock_alias
            WHERE stock_id  = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':stock_id'    => $stock_id,
        ':stock_alias' => $stock_alias
    ));
}

function update_stock_order ($stock_id, $stock_order)
{
    global $dbh;

    $sql = 'UPDATE stocks
            SET stock_order = :stock_order
            WHERE stock_id  = :stock_id';

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':stock_id'    => $stock_id,
        ':stock_order' => $stock_order
    ));
}

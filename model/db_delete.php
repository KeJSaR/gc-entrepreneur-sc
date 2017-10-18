<?php

defined('SC_SAFETY_CONST') or die;

function delete_location ($product_id, $stock_id)
{
    global $dbh;

    $sql = "DELETE FROM locations
            WHERE product_id = :product_id
                AND stock_id = :stock_id";

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':product_id' => $product_id,
        ':stock_id'   => $stock_id
    ));
}

function delete_class ($class_id)
{
    global $dbh;

    $sql = "DELETE FROM classes
            WHERE class_id = :class_id";

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':class_id' => $class_id
    ));
}

function delete_name ($name_id)
{
    global $dbh;

    $sql = "DELETE FROM names
            WHERE name_id = :name_id";

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':name_id' => $name_id
    ));
}

function delete_supplier ($supplier_id)
{
    global $dbh;

    $sql = "DELETE FROM suppliers
            WHERE supplier_id = :supplier_id";

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':supplier_id' => $supplier_id
    ));
}

function delete_stock ($stock_id)
{
    global $dbh;

    $sql = "DELETE FROM stocks
            WHERE stock_id = :stock_id";

    $sth = $dbh->prepare($sql);

    $sth->execute(array(
        ':stock_id' => $stock_id
    ));
}

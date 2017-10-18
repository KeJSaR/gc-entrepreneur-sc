<?php

defined('SC_SAFETY_CONST') or die;

function make_name_alias_html ($name_aliase_array)
{
    $html = '';

    foreach ($name_aliase_array as $name_id => $name_alias) {
        $html .= '<option value="' . $name_id . '">' . $name_alias . '</option>' . BR;
    }

    return $html;
}

function make_supplier_alias_html ()
{
    $suppliers_array = get_suppliers(); // supplier_city, supplier_alias, supplier_address, supplier_phone
    $html = '';

    foreach ($suppliers_array as $value) {
        $html .= '    <option value="' . $value['supplier_id'] . '">';

        if ($value['supplier_city']) {
            $html .= $value['supplier_city'];
        }

        if ($value['supplier_alias']) {
            if ($value['supplier_city']) {
                $html .= ', ';
            }
            $html .= $value['supplier_alias'];
        }

        if ($value['supplier_address']) {
            if ($value['supplier_city'] || $value['supplier_alias']) {
                $html .= ', ';
            }
            $html .= $value['supplier_address'];
        }

        if ($value['supplier_phone']) {
            if ($value['supplier_city'] || $value['supplier_alias'] || $value['supplier_address']) {
                $html .= ',';
            }
            $html .= ' тел.: ' . $value['supplier_phone'] . ';';
        }

        $html .= '</option>' . "\n";
    }

    return $html;
}

function make_stock_list_html ($stock_array)
{
    $html = '';

    foreach ($stock_array as $stock_id => $stock_alias) {
        $html .= '<div id="' . $stock_id . '" class="form-group">' . BR;

        $html .= '<label class="col-md-4 control-label">'
                    . $stock_alias . '</label>' . BR;

        $html .= '<div class="col-md-4">' . BR;

        $html .= '<input id="location_old_' . $stock_id
                    . '" name="location_old_' . $stock_id
                    . '" type="text" class="form-control text-center location-old" '
                    . 'placeholder="--">' . BR;

        $html .= '</div>' . BR;

        $html .= '<div class="col-md-4">' . BR;

        $html .= '<input id="location_new_' . $stock_id
                    . '" name="location_new_' . $stock_id
                    . '" type="text" class="form-control text-center location-new" '
                    . 'placeholder="--">' . BR;

        $html .= '</div>' . BR;

        $html .= '</div>' . BR;
    }

    return $html;
}

?>
<div id="income" class="col-md-12">

    <form action="<?php echo SC_URL; ?>?page=income" method="post">

        <div class="row">
            <div id="sub-nav-holder" class="col-md-10 col-md-offset-1">

                <div id="sub-nav">

                    <div class="stock left">

                        <div data-edit-path="classes" class="button">Категории</div>
                        <div data-edit-path="names" class="button">Товары</div>
                        <div data-edit-path="suppliers" class="button">Поставщики</div>
                        <div data-edit-path="stocks" class="button">Склады</div>

                    </div>

                </div><!-- end of sub-nav -->

            </div>
        </div>

        <div class="row">

            <!-- ### LEFT COLUMN ### -->
            <div class="col-md-2 col-md-offset-1">

<!-- ### CLASSES TREE ###################################################### -->
<?php require_once(SC_PARTS_DIR . 'aside.php'); ?>
<!-- end of classes tree -->

            </div><!-- end of left column -->

            <!-- ### MIDDLE COLUMN ### -->
            <div class="col-md-5<?php if (!$current_section) echo ' hidden'; ?>">

                <div id="section-content">

                    <div class="form-horizontal">

<!-- ### NAME ALIAS ######################################################## -->
<div id="name-alias" class="form-group">

    <label for="name-alias-old" class="col-md-3 control-label">Имя товара:</label>

    <div class="col-md-9">

        <select id="name-alias-old" name="name_alias_old" class="form-control">
            <option>Выберите имя товара</option>
<?php if (isset($name_aliase_array)) echo make_name_alias_html($name_aliase_array); ?>
        </select>

        <input id="name-id" name="name_id" type="text" class="hidden" value="">

    </div>

</div><!-- ### end of name alias ### -->

<!-- ### SUPPLIER ALIAS #################################################### -->
<div id="supplier-alias" class="form-group">

    <label for="supplier-id" class="col-md-3 control-label">Поставщик:</label>

    <div class="col-md-9">

        <!-- displayed form -->
        <select id="supplier-id" name="supplier_id" class="form-control">
            <option value="choose">Выберите поставщика</option>
<?php echo make_supplier_alias_html(); ?>
        </select>

    </div>

</div><!-- ### end of supplier alias ### -->

<div class="form-group">

    <!-- product-price block -->
    <label class="col-md-3 control-label">Цена:</label>
    <div class="col-md-2">
        <input id="product-price" name="product_price"
            type="text" class="form-control"
            placeholder="--">

        <input id="product-id" name="product_id" type="text"
            class="hidden" value="">
    </div>

    <div class="col-md-7">
        <p id="prices"></p>
    </div>

</div>

<hr>

<div class="form-group">

    <!-- purchase-price block -->
    <label class="col-md-3 control-label">Цена закупки:</label>
    <div class="col-md-2">
        <input id="purchase-price" name="purchase_price"
            type="text" class="form-control"
            placeholder="--">
    </div>
    <div class="col-md-2">
        <input id="purchase-amount" name="purchase_amount"
            type="text" class="form-control"
            placeholder="--" readonly="readonly">
    </div>

    <!-- purchase-date block -->
    <label class="col-md-2 control-label">Дата закупки:</label>
    <div class="col-md-3">
        <input id="purchase-date" name="purchase_date"
            type="text" class="form-control text-center"
            placeholder="гггг-мм-дд">
    </div>

</div>

<hr>

<div id="product-comment" class="form-group">

    <label class="col-md-2 col-md-offset-1 control-label">Комментарий к товару:</label>
    <div class="col-md-9">
        <textarea id="product_comment" name="product_comment"
                class="form-control" rows="2"></textarea>
    </div>

</div>

<div id="purchase-comment" class="form-group">

    <label class="col-md-2 col-md-offset-1 control-label">Комментарий к закупке:</label>
    <div class="col-md-9">
        <textarea id="purchase_comment" name="purchase_comment"
                class="form-control" rows="2"></textarea>
    </div>

</div>

<hr>

<div class="form-group">

    <label id="purchase-info" class="col-md-6 control-label">
        <span id="purchase-quantity-info">--</span> шт. по
        <span id="purchase-price-info">--</span> руб. на сумму
        <span id="purchase-amount-info">--</span> руб.
    </label>

    <!-- submit block -->
    <div class="col-md-3 new">
        <button id="purchase-submit" type="submit" class="btn btn-primary btn-block">Добавить</button>
    </div>

</div><!-- end of form-group -->

                    </div><!-- end of form-horizontal -->

                </div><!-- end of section-content -->

            </div><!-- end of middle column -->


            <!-- ### RIGHT COLUMN ### -->
            <div class="col-md-3<?php if (!$current_section) echo ' hidden'; ?>">

                <div id="location">

                    <div class="form-horizontal">
<!--
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
 -->
<?php echo make_stock_list_html($stock_array); ?>

<hr>

<div class="form-group">

    <!-- product-quantity block -->
    <label class="col-md-4 control-label">Всего:</label>
    <div class="col-md-4">
        <input id="product-quantity" name="product_quantity"
            type="text" class="form-control text-center"
            placeholder="--" readonly="readonly">
    </div>
    <div class="col-md-4">
        <input id="purchase-quantity" name="purchase_quantity"
            type="text" class="form-control text-center"
            placeholder="--" readonly="readonly">
    </div>

</div>

<!--
                            </div>
                        </div>
 -->
                    </div>

                </div>

            </div><!-- end of right column -->

        </div><!-- end of row -->

    </form>

</div>

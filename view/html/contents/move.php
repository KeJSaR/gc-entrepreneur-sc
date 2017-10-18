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

if (isset($name_aliase_array)) {
    $n = count($name_aliase_array);
    if ($n > 0) {
        $name_alias_html = make_name_alias_html($name_aliase_array);
    } else {
        $name_alias_html = '';
    }
};

function make_stock_list_html ($stock_array)
{
    $html = '';

    foreach ($stock_array as $stock_id => $stock_alias) {

        $html .= '<div id="' . $stock_id . '" class="form-group stocks-list">' . BR . BR;

        $html .= '<label class="col-md-4 control-label">' . $stock_alias . '</label>' . BR . BR;

        $html .= '<div class="col-md-4 old">' . BR;
        $html .= '<div class="input-group">' . BR;
        $html .= '<span class="input-group-addon">' . BR;
        $html .= '<input type="radio" class="stock-radio" name="stock_radio" value="' . $stock_id . '">' . BR;
        $html .= '</span>' . BR;
        $html .= '<input type="text" class="form-control text-center input-old" name="old_stock_' . $stock_id . '"  placeholder="--" data-quantity="0" readonly="readonly">' . BR;
        $html .= '</div>' . BR;
        $html .= '</div>' . BR . BR;

        $html .= '<div class="col-md-4 new">' . BR;
        $html .= '<input type="text" class="form-control text-center input-new" name="new_stock_' . $stock_id . '"  placeholder="--" readonly="readonly">' . BR;
        $html .= '</div>' . BR . BR;

        $html .= '</div>' . BR . BR;

    }

    return $html;
}

$stock_list_html = make_stock_list_html($stock_array);

?>
            <!-- ### MIDDLE COLUMN ### -->
<?php if (isset($name_aliase_array) && $name_alias_html == '') : ?>
            <div class="col-md-5">
                <h4 class="text-center">Товар указанной категории отсутствует.</h4>
            </div>
<?php endif; ?>

            <div class="col-md-5<?php if (!$current_section || ($name_alias_html == '')) echo ' hidden'; ?>">

                <div id="section-content">

                    <div class="form-horizontal">

<!-- ### NAME ALIAS ######################################################## -->
<div id="name-alias" class="form-group">

    <label for="name-alias-old" class="col-md-3 control-label">Имя товара:</label>

    <div class="col-md-9">

        <select id="name-alias-old" name="name_alias_old" class="form-control">
            <option>Выберите имя товара</option>
<?php if (isset($name_aliase_array)) echo $name_alias_html; ?>
        </select>

        <input id="name-id" name="name_id" type="text" class="hidden" value="">

    </div>

</div><!-- ### end of name alias ### -->

<!-- ### PRICES HOLDER ##################################################### -->
<div id="prices-holder" class="form-group">

    <label class="col-md-3 control-label">Цена:</label>

    <div class="col-md-2">

        <input id="product-price" name="product_price" type="text" class="form-control" placeholder="--">

    </div>

    <div class="col-md-7">

        <p id="prices"></p>

        <input id="product-id" name="product_id" type="text" class="hidden" value="">

    </div>

</div><!-- ### end of prices holder ### -->

<hr>

<div class="form-group">

    <!-- moving-date block -->
    <label class="col-md-3 control-label">Дата:</label>
    <div class="col-md-2">
        <input id="moving-date" name="moving_date"
            type="text" class="form-control"
            placeholder="гггг-мм-дд">
    </div>

</div>

<div id="moving-comment" class="form-group">

    <!-- purchase-comment block -->
    <label class="col-md-3 control-label">Комментарий к перемещению:</label>

    <div class="col-md-9">
        <textarea name="moving_comment" class="form-control" rows="2"></textarea>
    </div>

</div>

<hr>

                    </div><!-- end of form-horizontal -->

                    <div class="form-group">

                        <!-- submit block -->
                        <div class="col-md-offset-3 col-md-4">
                            <button id="move-submit" type="submit" class="btn btn-primary btn-block">Переместить товар</button>
                        </div>

                        <!-- reset block -->
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-default btn-block">Очистить поля формы</button>
                        </div>

                    </div>

                </div>

            </div><!-- end of middle column -->

            <!-- ### RIGHT COLUMN ### -->
            <div class="col-md-3<?php if (!$current_section) echo ' hidden'; ?>">
                <div class="form-horizontal">
<!-- ### LOCATION ########################################################## -->

<?php echo $stock_list_html; ?>

<!-- end of location -->

                    <hr>

                    <div class="form-group stocks-list">
                        <!-- moving-quantity block -->
                        <label class="col-md-4 control-label">Всего:</label>

                        <!-- product-quantity block -->
                        <div class="col-md-4">
                            <input id="product-quantity" name="product_quantity"
                                type="text" class="form-control"
                                placeholder="--" readonly="readonly">
                        </div>

                        <div class="col-md-4">
                            <input id="moving-quantity" name="moving_quantity"
                                type="text" class="form-control"
                                placeholder="--" readonly="readonly">
                        </div>

                    </div>


                </div>
            </div><!-- end of right column -->

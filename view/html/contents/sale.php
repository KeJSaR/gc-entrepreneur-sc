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

?>
            <!-- ### MIDDLE COLUMN ### -->
<?php if (isset($name_aliase_array) && $name_alias_html == '') : ?>
            <div class="col-md-5">
                <h4 class="text-center">Товар указанной категории отсутствует на выбранном складе.</h4>
            </div>
<?php endif; ?>

            <div class="col-md-5<?php if (!$current_section || !$current_stock || ($name_alias_html == '')) echo ' hidden'; ?>">

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

    <div class="col-md-9">

        <p id="prices"></p>

        <input id="product-id" name="product_id" type="text" class="hidden" value="">

        <input id="product-price" name="product_price" type="text" class="hidden" value="">

        <input id="stock-id" name="stock_id" type="text" class="hidden" value="">

    </div>

</div><!-- ### end of prices holder ### -->

<div class="form-group">

    <!-- product-quantity block -->
    <label class="col-md-3 control-label">В наличии:</label>

    <div class="col-md-3">
        <input id="product-quantity" name="product_quantity" type="text" class="form-control text-center"
            placeholder="--" data-pquant="" readonly="readonly">
    </div>

    <!-- location-quantity block -->
    <label class="col-md-3 control-label">На складе:</label>

    <div class="col-md-3">
        <input id="location-quantity" name="location_quantity" type="text" class="form-control text-center"
            placeholder="--" data-lquant="" readonly="readonly">
    </div>

</div>

<div class="form-group">

    <!-- sale-price block -->
    <label class="col-md-3 control-label">Цена продажи:</label>

    <div class="col-md-3">
        <input id="sale-price" name="sale_price" type="text" class="form-control text-center"
            placeholder="--">
    </div>

    <!-- sale-amount block -->
    <label class="col-md-3 control-label">Сумма:</label>

    <div class="col-md-3">
        <input id="sale-amount" name="sale_amount" type="text" class="form-control text-center"
            placeholder="--" readonly="readonly">
    </div>

</div>

<div class="form-group">

    <!-- sale-quantity block -->
    <label class="col-md-3 control-label">Количество:</label>

    <div class="col-md-3">
        <input id="sale-quantity" name="sale_quantity" type="text" class="form-control text-center"
            placeholder="--">
    </div>

    <!-- sale-date block -->
    <label class="col-md-3 control-label">Дата продажи:</label>

    <div class="col-md-3">
        <input id="sale-date" name="sale_date" type="text" class="form-control text-center"
            placeholder="гггг-мм-дд">
    </div>

</div>

<div class="form-group">
    <div class="col-md-9 col-md-offset-3">
        <div class="checkbox">
            <label>
                <input id="write-off" name="write_off"
                        type="checkbox" value="0"> Списание товара
            </label>
        </div>
    </div>
</div>

<div id="sale-comment-block" class="form-group">

    <label class="col-md-3 control-label">Комментарий к продаже:</label>
    <div class="col-md-9">
        <textarea id="sale-comment" name="sale_comment"
                class="form-control" rows="2"></textarea>
    </div>

</div>

                    </div><!-- end of form-horizontal -->

                    <div class="form-group">

                        <!-- submit block -->
                        <div class="col-md-offset-3 col-md-4">
                            <button id="sale-submit" type="submit" class="btn btn-primary btn-block">Продать товар</button>
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


            </div><!-- end of right column -->

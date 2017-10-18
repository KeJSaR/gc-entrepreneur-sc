<?php

defined('SC_SAFETY_CONST') or die;

function make_supplier_alias_html ()
{
    $suppliers_array = get_suppliers(); // supplier_city, supplier_alias, supplier_address, supplier_phone
    $html = '';

    foreach ($suppliers_array as $value) {
        $html .= '<div class="radio">' . BR;
        $html .= '<label>' . BR;

        $html .= '<input type="radio" name="name_alias" value="' . $value['supplier_id'] . '">' . BR;

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
            $html .= ' тел.: ' . $value['supplier_phone'] . ';' . BR;
        }

        $html .= '</label>' . BR;
        $html .= '</div>' . BR;

    }

    return $html;
}

?>


<div class="row">

    <div id="sub-nav-holder" class="col-md-10 col-md-offset-1">

        <div id="sub-nav">

            <div class="sub left">

                <div data-sub-page="classes"
                        class="button<?php if ($sub_page == 'classes')   echo ' active'; ?>">Категории</div>
                <div data-sub-page="names"
                        class="button<?php if ($sub_page == 'names')     echo ' active'; ?>">Товары</div>
                <div data-sub-page="suppliers"
                        class="button<?php if ($sub_page == 'suppliers') echo ' active'; ?>">Поставщики</div>
                <div data-sub-page="stocks"
                        class="button<?php if ($sub_page == 'stocks')    echo ' active'; ?>">Склады</div>

            </div>

        </div><!-- end of sub-nav -->

    </div>

</div>
<div class="row">

    <!-- ### LEFT COLUMN ### -->
    <div class="col-md-3 col-md-offset-1">
        <div id="aside-content">

<?php echo make_supplier_alias_html(); ?>

        </div>
    </div><!-- end of left column -->

    <!-- ### MIDDLE COLUMN ### -->
    <div class="col-md-7">

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Добавление поставщика
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">

                        <form action="<?php echo SC_URL; ?>?page=settings&sub=suppliers" method="post" class="form-horizontal">

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="new-city">Город:</label>
                                <div class="col-md-2">
                                    <input class="form-control" type="text" name="new_city" id="new-city" placeholder="...">
                                </div>
                                <label class="col-md-2 control-label" for="new-alias">Имя / Название:</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="new_alias" id="new-alias" placeholder="...">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="new-phone">Телефон:</label>
                                <div class="col-md-2">
                                    <input class="form-control" type="text" name="new_phone" id="new-phone" placeholder="...">
                                </div>
                                <label class="col-md-2 control-label" for="new-address">Адрес:</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="new_address" id="new-address" placeholder="...">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="new-comment">Комментарий:</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="new_comment" id="new-comment" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">
                                    <button type="submit" class="btn btn-default">Добавить</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Изменение поставщика
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">

                        <form action="<?php echo SC_URL; ?>?page=settings&sub=suppliers" method="post" class="form-horizontal">

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="update-city">Город:</label>
                                <div class="col-md-2">
                                    <input class="form-control" type="text" name="update_city" id="update-city" placeholder="...">
                                </div>
                                <label class="col-md-2 control-label" for="update-alias">Имя / Название:</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="update_alias" id="update-alias" placeholder="...">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="update-phone">Телефон:</label>
                                <div class="col-md-2">
                                    <input class="form-control" type="text" name="update_phone" id="update-phone" placeholder="...">
                                </div>
                                <label class="col-md-2 control-label" for="update-address">Адрес:</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="update_address" id="update-address" placeholder="...">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="update-comment">Комментарий:</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="update_comment" id="update-comment" rows="2"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <input class="hidden" type="checkbox" name="update_supplier_id" id="update-supplier-id" value="0" checked="checked">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">
                                    <button type="submit" class="btn btn-default">Изменить</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Удаление поставщика
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">

                        <form action="<?php echo SC_URL; ?>?page=settings&sub=suppliers" method="post" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <p>Внимение! Будут удалены все товары, числящиеся в базе данных как купленные у данного поставщика, а также все записи в истории операций.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12" for="delete-supplier-id">Удалить поставщика: <span id="delete-supplier-alias">не указано</span></label>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input class="form-control hidden" type="checkbox" name="delete_supplier_id" id="delete-supplier-id" value="0" checked="checked">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-danger">Удалить</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div><!-- end of middle column -->

</div>

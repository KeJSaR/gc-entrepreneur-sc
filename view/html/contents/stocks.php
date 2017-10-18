<?php

defined('SC_SAFETY_CONST') or die;

function make_stock_alias_html ()
{
    $stocks_array = get_stocks();
    $html = '';

    foreach ($stocks_array as $value) {
        $html .= '<div class="radio">' . BR;
        $html .= '<label>' . BR;

        $html .= '<input type="radio" name="stock_alias" value="' . $value['stock_id'] . '">' . BR;

        $html .= '<span data-stock-order="' . $value['stock_order'] . '">';
        $html .= $value['stock_alias'];
        $html .= '</span>';

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
        <div id="aside-content" class="stock-movings">

<?php echo make_stock_alias_html(); ?>

        </div>
    </div><!-- end of left column -->

    <!-- ### MIDDLE COLUMN ### -->
    <div class="col-md-7">

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Добавление склада
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">

                        <form action="<?php echo SC_URL; ?>?page=settings&sub=stocks" method="post" class="form-horizontal">

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="new-stock">Название склада:</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="new_stock" id="new-stock" placeholder="...">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">
                                    <button type="submit" class="btn btn-default">Добавить</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div id="test-block" class="col-md-10 col-md-offset-2">

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
                            Изменение названия склада
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">

                        <form action="<?php echo SC_URL; ?>?page=settings&sub=stocks" method="post" class="form-horizontal">

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="update-stock-alias">Изменить название:</label>
                                <div class="col-md-2">
                                    <input class="form-control" type="text" name="update_stock_alias" id="update-stock-alias" placeholder="...">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input class="hidden" type="checkbox" name="update_stock_id" id="update-stock-id" value="0" checked="checked">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 text-center">
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
                            Удаление склада
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">

                        <form action="<?php echo SC_URL; ?>?page=settings&sub=stocks" method="post" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <p>Внимение! Будут удалены все товары, числящиеся в базе данных на данном складе, а также все записи в истории операций с ним связанные.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12" for="delete-stock-id">Удалить склад: <span id="delete-stock-alias">не указано</span></label>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input class="hidden" type="checkbox" name="delete_stock_id" id="delete-stock-id" value="0" checked="checked">
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

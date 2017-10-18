<?php

defined('SC_SAFETY_CONST') or die;

function make_name_alias_html ($name_aliase_array)
{
    $html = '';

    foreach ($name_aliase_array as $name_id => $name_alias) {
        $html .= '<div class="radio">' . BR;
        $html .= '<label>' . BR;
        $html .= '<input type="radio" name="name_alias" value="' . $name_id . '">' . BR;
        $html .= $name_alias . BR;
        $html .= '</label>' . BR;
        $html .= '</div>' . BR;
    }

    return $html;
}

if (isset($name_aliase_array)) {
    $name_alias_html = make_name_alias_html($name_aliase_array);
};

?>

<!-- <form action="<?php echo SC_URL; ?>?page=settings&sub=<?php echo $sub_page; ?>" method="post"> -->
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
    <div class="col-md-2 col-md-offset-1">

<!-- ### CLASSES TREE ###################################################### -->
<?php require_once(SC_PARTS_DIR . 'aside.php'); ?>
<!-- end of classes tree -->

    </div><!-- end of left column -->

<?php if ($current_section) : ?>

    <!-- ### MIDDLE COLUMN ### -->
    <div class="col-md-2">
        <div id="section-content">

<?php echo $name_alias_html; ?>

        </div>
    </div><!-- end of middle column -->

    <!-- ### RIGHT COLUMN ### -->
    <div class="col-md-6">

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Добавление наименования
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">

                        <form action="<?php echo SC_URL; ?>?page=settings&section=<?php echo $current_section; ?>&sub=names" method="post">
                            <div class="form-group">
                                <label for="parent-class">Родительская категория: <span id="parent-class-name">не указана</span></label>
                                <input type="checkbox" name="parent_class" id="parent-class" value="0" checked="checked" class="hidden">
                            </div>
                            <div class="form-group">
                                <label for="new-name">Новое наименование:</label>
                                <input type="text" name="new_name" id="new-name" class="form-control" placeholder="Введите новое наименование">
                            </div>
                            <button type="submit" class="btn btn-default">Добавить</button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Изменение наименования
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">

                        <form action="<?php echo SC_URL; ?>?page=settings&section=<?php echo $current_section; ?>&sub=names" method="post">
                            <div class="form-group">
                                <label for="change-name">Старое наименование: <span id="change-name-alias">не указано</span></label>
                                <input type="checkbox" name="change_name" id="change-name" value="0" checked="checked" class="hidden">
                            </div>
                            <div class="form-group">
                                <label for="new-name-alias">Новое наименование:</label>
                                <input type="text" name="new_name_alias" id="new-name-alias" class="form-control" placeholder="Введите новое наименование">
                            </div>
                            <button type="submit" class="btn btn-default">Изменить</button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Удаление наименования
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">

                        <p>Внимение! Будут удалены все товары, числящиеся в базе данных под данным наименованием, а также все записи в истории операций.</p>
                        <form action="<?php echo SC_URL; ?>?page=settings&section=<?php echo $current_section; ?>&sub=names" method="post">
                            <div class="form-group">
                                <label for="delete-name">Удалить наименование: <span id="delete-name-alias">не указано</span></label>
                                <input type="checkbox" name="delete_name" id="delete-name" value="0" checked="checked" class="hidden">
                            </div>
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div><!-- end of right column -->

<?php endif; ?>

</div>

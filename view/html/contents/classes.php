<?php

defined('SC_SAFETY_CONST') or die;

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
    <div class="col-md-2 col-md-offset-1">

<!-- ### CLASSES TREE ###################################################### -->
<?php require_once(SC_PARTS_DIR . 'aside.php'); ?>
<!-- end of classes tree -->

    </div><!-- end of left column -->

    <!-- ### MIDDLE COLUMN ### -->
    <div class="col-md-8">

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Добавление категории
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">

                        <p>Выберите родительскую категорию в левой колонке, затем введите в поле имя новой категории.</p>
                        <p>Если родительская категория останется невыбранной, новая категория будет создана в качестве родительской категории первого уровня.</p>
                        <form action="<?php echo SC_URL; ?>?page=settings&section=<?php echo $current_section; ?>&sub=classes" method="post">
                            <div class="form-group">
                                <label for="parent-class">Родительская категория: <span id="parent-class-name">не указана</span></label>
                                <input type="checkbox" name="parent_class" id="parent-class" value="0" checked="checked" class="hidden">
                            </div>
                            <div class="form-group">
                                <label for="new-class">Новая категория:</label>
                                <input type="text" name="new_class" id="new-class" class="form-control" placeholder="Введите имя новой категории">
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
                            Изменение категории
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">

                        <p>Выберите категорию в левой колонке, которую хотите изменить, введите другое название в форму ниже и нажмите на кнопку изменения.</p>
                        <form action="<?php echo SC_URL; ?>?page=settings&section=<?php echo $current_section; ?>&sub=classes" method="post">
                            <div class="form-group">
                                <label for="change-class">Изменить категорию: <span id="change-class-name">не указано</span></label>
                                <input type="checkbox" name="change_class" id="change-class" value="0" checked="checked" class="hidden">
                            </div>
                            <div class="form-group">
                                <label for="new-class-name">Новое имя категории:</label>
                                <input type="text" name="new_class_name" id="new-class-name" class="form-control" placeholder="Введите новое имя для указанной категории">
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
                            Удаление категории
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">

                        <p>Выберите категорию в левой колонке, которую хотите удалить, затем нажмите на кнопку удаления.</p>
                        <p>Если Вы отметите для удаления категорию, которая является родительской (содержит другие категории), то будут удалены и данная категория, и все дочерние по отношению к ней категории.</p>
                        <form action="<?php echo SC_URL; ?>?page=settings&section=<?php echo $current_section; ?>&sub=classes" method="post">
                            <div class="form-group">
                                <label for="delete-class">Удалить категорию: <span id="delete-class-name">не указано</span></label>
                                <input type="checkbox" name="delete_class" id="delete-class" value="0" checked="checked" class="hidden">
                            </div>
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div><!-- end of middle column -->

</div>

<?php

defined('SC_SAFETY_CONST') or die;

?>

<div id="settings" class="col-md-12">

<?php

if ($sub_page == 'classes') {

    require_once(SC_CONTENTS_DIR . 'classes.php');

} else if ($sub_page == 'names') {

    require_once(SC_CONTENTS_DIR . 'names.php');

} else if ($sub_page == 'suppliers') {

    require_once(SC_CONTENTS_DIR . 'suppliers.php');

} else if ($sub_page == 'stocks') {

    require_once(SC_CONTENTS_DIR . 'stocks.php');

} else {

?>
<div class="row">
    <div class="col-md-4 col-md-offset-2">
        <a href="<?php echo SC_URL; ?>?page=settings&sub=classes" class="settings-navigation">Категории</a>
    </div>
    <div class="col-md-4">
        <a href="<?php echo SC_URL; ?>?page=settings&sub=names" class="settings-navigation">Товары</a>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-2">
        <a href="<?php echo SC_URL; ?>?page=settings&sub=suppliers" class="settings-navigation">Поставщики</a>
    </div>
    <div class="col-md-4">
        <a href="<?php echo SC_URL; ?>?page=settings&sub=stocks" class="settings-navigation">Склады</a>
    </div>
</div>
<?php

}

?>

</div>





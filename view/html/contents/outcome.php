<?php

defined('SC_SAFETY_CONST') or die;

?>

<div id="outcome" class="col-md-12">

    <form action="<?php echo SC_URL; ?>?page=outcome&sub=<?php echo $sub_page; ?>" method="post">

        <div class="row">
            <div id="sub-nav-holder" class="col-md-10 col-md-offset-1">

                <div id="sub-nav">

<?php require_once(SC_PARTS_DIR . 'sub-nav.php'); ?>

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
<?php

if ($sub_page == 'sale') {

    require_once(SC_CONTENTS_DIR . 'sale.php');

}

if ($sub_page == 'move') {

    require_once(SC_CONTENTS_DIR . 'move.php');

}

?>
        </div>
    </form>

</div>

<?php

defined('SC_SAFETY_CONST') or die;

?>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Title -->
        <title>Складской учёт</title>
        <meta name="description" content="">

        <!-- CSS -->
        <link rel="stylesheet" href="view/css/bootstrap.min.css">
<?php if (isset($print) && $print == 1) : ?>
        <link rel="stylesheet" href="view/css/print.css">
<?php else : ?>
        <link rel="stylesheet" href="view/css/jquery.mCustomScrollbar.min.css">
        <link rel="stylesheet" href="view/css/font-awesome.min.css">
        <link rel="stylesheet" href="view/css/bootstrap.min.css">
        <link rel="stylesheet" href="view/css/bootstrap-datepicker3.min.css">
        <link rel="stylesheet" href="view/css/style.css">
        <link rel="stylesheet" href="view/css/fonts.css">
        <link rel="stylesheet" href="view/css/<?php echo $theme; ?>.css">
<?php endif;

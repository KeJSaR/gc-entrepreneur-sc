$(function(){

    function setBlocksSize () {

        // get height and width of browser viewport
        var h = $(window).height();
        var w = $(window).width();

        // set content size
        $("body").height(h - 60);

        $("#content").height(h - 90);

        $("#sub-nav").width(function() {
            return $("#sub-nav-holder").width();
        });

        $("#aside-content").height(h - 170);

        $("#section-content").height(h - 170);

        if (w < 1500) {
            $("#main-nav span").hide();
        } else {
            $("#main-nav span").show();
        }

    }

    function customScrollbar (theme) {

        switch (theme) {
            case "dark":
                var themeName = ["light-thick", "dark-thick"];
                break;
            case "night":
                var themeName = ["light-thick", "light-thick"];
                break;
            case "light":
            default:
                var themeName = ["dark-thick", "dark-thick"];
                break;
        }

        $("#aside-content").mCustomScrollbar({
            theme: themeName[0],
            scrollButtons:{ enable: true }
        });
        $("#section-content").mCustomScrollbar({
            theme: themeName[1],
            scrollButtons:{ enable: true }
        });
    }

    $(window).load(function(){
        var theme = $(".theme-chooser.active i").attr("id");
        setBlocksSize();
        customScrollbar(theme);
    });

    $(window).resize(function() {
        setBlocksSize();
    });

    $("#aside-content p").click(function() {

        var i  = $(this).children("i");

        if ($(this).hasClass("active")) {
            $("#aside-content p").removeClass("active");
        } else {
            $("#aside-content p").removeClass("active");
            $(this).addClass("active");
        }

        if (i.hasClass("fa-square-o")) {
            $("#aside-content p").children("i").removeClass("fa-check-square-o");
            $("#aside-content p").children("i").addClass("fa-square-o");
            i.removeClass("fa-square-o");
            i.addClass("fa-check-square-o");
        } else {
            i.removeClass("fa-check-square-o");
            i.addClass("fa-square-o");
        }

    });

    $("#main-nav li").click(function() {
        $("#main-nav li").removeClass("active");
        $(this).addClass("active");
    });

// #############################################################################
// #############################################################################
// #############################################################################

/*
 * ### PREPARE PAGE ############################################################
 */

    $('[data-toggle="tooltip"]').tooltip();

    /*
     * DATEPICKER
     */
    $('#purchase-date').datepicker({
        format: "yyyy-mm-dd",
        weekStart: 1,
        todayBtn: "linked",
        language: "ru",
        autoclose: true
    });
    // end of datepicker

    /*
     * CURRENT DATE
     */
    function Date_toYMD() {
        var dt = new Date();
        var year, month, day;
        year = String(dt.getFullYear());
        month = String(dt.getMonth() + 1);
        if (month.length == 1) {
            month = "0" + month;
        }
        day = String(dt.getDate());
        if (day.length == 1) {
            day = "0" + day;
        }
        return year + "-" + month + "-" + day;
    }

    var str = Date_toYMD();

    $("#purchase-date").val(str);
    // end of current date

    $("input.location-old").attr("readonly", "readonly");
    $("input.location-new").attr("readonly", "readonly");

    $("input.location-new").removeAttr("readonly");

    /*
     * SUPPLIER ALIAS
     */

    // end of supplier alias

    /*
     * PRODUCT ALIAS
     */

    // end of product alias

// ### end of prepare page

/*
 * ### AJAX ####################################################################
 */

    $("#name-alias-old").click(function() {

        $("#prices span").remove();

        var name_id = $(this).val();
        $("#name-id").val(name_id);

        $.ajax({

            method: "POST",
            url: "ajax/ajax.php",
            data: {
                name_id: name_id,
                income: true
            }

        }).done(function(html) {

            $("#prices").append(html);

            $("#prices span").click(function(){

                $(".location-old").val("");

                var prodId = $(this).attr('data-product-id');
                var prodPrice = $(this).html();

                $("#product-price").attr("value", prodId);
                $("#product-price").val(prodPrice);

                $("#product-id").val(prodId);

                $.ajax({

                    method: "POST",
                    url: "ajax/ajax.php",
                    data: {
                        product_id: prodId,
                        income: true
                    }

                }).done(function(html) {

                    var obj = JSON.parse(html);

                    $("#product_comment").val(obj.product_comment);

                    $("#product-quantity").val(obj.product_quantity);

                    var locations = obj.locations;

                    $.each(locations, function(key, value) {

                        $("#location_old_" + value['stock_id']).attr("data-quantity", value['location_quantity']);
                        $("#location_old_" + value['stock_id']).text(value['location_quantity']);
                        $("#location_old_" + value['stock_id']).attr("value", value['location_quantity']);
                        $("#location_old_" + value['stock_id']).val(value['location_quantity']);

                    });
                });
            });
        });
    });

    $( "#product-price" ).keyup(function() {

        $( "#prices span" ).each(function( index ) {

            var prodPrice = $("#product-price").val(),
                prodId    = $( this ).data( "productId" ),
                old_price = $( this ).text();

            if ( prodPrice == old_price ) {
                $("#product-price").attr("value", prodId);
                $("#product-price").val(prodPrice);

                $("#product-id").val(prodId);

                $.ajax({

                    method: "POST",
                    url: "ajax/ajax.php",
                    data: {
                        product_id: prodId,
                        income: true
                    }

                }).done(function(html) {

                    var obj = JSON.parse(html);

                    $("#product_comment").val(obj.product_comment);

                    $("#product-quantity").val(obj.product_quantity);

                    var locations = obj.locations;

                    $.each(locations, function(key, value) {

                        $("#location_old_" + value['stock_id']).attr("data-quantity", value['location_quantity']);
                        $("#location_old_" + value['stock_id']).text(value['location_quantity']);
                        $("#location_old_" + value['stock_id']).attr("value", value['location_quantity']);
                        $("#location_old_" + value['stock_id']).val(value['location_quantity']);

                    });
                });
            }
        });
    });

    $("#purchase-price").keyup(function() {

        var price = $("#purchase-price").val();
        var quant = $("#purchase-quantity").val();

        if (isNaN(price * quant) || (price * quant) == 0) {
            $("#purchase-amount").val("--");
            $("#purchase-amount-info").text("--");
        } else {
            $("#purchase-amount").val(price * quant);
            $("#purchase-amount-info").text(price * quant);
        }

        $("#purchase-price-info").text(price);
    });

    $("#purchase-quantity").change(function() {

        var price = $("#purchase-price").val();
        var quant = $("#purchase-quantity").val();

        if (isNaN(price * quant) || (price * quant) == 0) {
            $("#purchase-amount").val("--");
            $("#purchase-amount-info").text("--");
        } else {
            $("#purchase-amount").val(price * quant);
            $("#purchase-amount-info").text(price * quant);
        }
    });

    $("input.location-new").keyup(function(){

        var moving_quantity = 0;
        var new_quantity = parseInt($(this).val());
        if (isNaN(new_quantity)) {
            new_quantity = parseInt(0);
        }

        $(this).attr("value", new_quantity);

        var new_stock_id = $(this).parent().parent().attr("id");
        var old_quantity = $("#" + new_stock_id + " input.location-old").attr("data-quantity");
        old_quantity = parseInt(old_quantity);
        if (isNaN(old_quantity)) {
            old_quantity = parseInt(0);
        }
        if ((old_quantity + new_quantity) == 0) {
            $("#" + new_stock_id + " input.location-old").attr("value", "");
            $("#" + new_stock_id + " input.location-old").val("");
            $("#" + new_stock_id + " input.location-old").text("");
        } else {
            $("#" + new_stock_id + " input.location-old").attr("value", old_quantity + new_quantity);
            $("#" + new_stock_id + " input.location-old").val(old_quantity + new_quantity);
            $("#" + new_stock_id + " input.location-old").text(old_quantity + new_quantity);
        }

        $("input.location-new").each(function() {
            var temp_quantity = parseInt($(this).val());
            if (isNaN(temp_quantity)) {
                temp_quantity = parseInt(0);
            }
            moving_quantity += temp_quantity;
        });

        if (moving_quantity == 0) {
            $("#purchase-quantity").attr("value", "--");
            $("#purchase-quantity").text("--");
            $("#purchase-quantity").val("--");

            $("#purchase-quantity-info").text("");

        } else {
            $("#purchase-quantity").attr("value", moving_quantity);
            $("#purchase-quantity").text(moving_quantity);
            $("#purchase-quantity").val(moving_quantity);

            $("#purchase-quantity-info").text(moving_quantity);

        }

        $("#purchase-quantity").change();

    });

    // $("#location input.location-old[name=2]").val("33");

    $(window).unload(function() {
        $("input").prop("checked", false);
        $("input").val("");
    });

    $("#purchase-submit").click(function(event) {

        var supplier_id = $("#supplier-id").val();

        var price = $("#purchase-price").val();
        price = Number(price);

        var productPrice = $("#product-price").val();
        productPrice = Number(price);

        var quant = $("#purchase-quantity").val();
        quant = Number(quant);

        if (supplier_id == 'choose' || supplier_id == '') {
            event.preventDefault();
            alert("Не указан поставщик!");
        }

        if (quant == 0) {
            event.preventDefault();
            alert("Не указано количество купленного товара!");
        }

        if (price == 0) {
            event.preventDefault();
            alert("Не указана цена закупки!");
        }

        if (productPrice == 0) {
            event.preventDefault();
            alert("Не указана цена продажи!");
        }

    });

// #############################################################################
// #############################################################################
// #############################################################################

    /**
     * #########################################################################
     * Change LOCATION #########################################################
     * #########################################################################
     */

    function getRequestUrl () {
        var url = window.location.hostname + window.location.pathname;
        return url;
    }

    function getRequestPage () {
        var page = $("#main-nav li.active .button").data("mainNav");
        return page;
    }

    function getRequestClass () {
        var classId = $("#aside-content p.active").data("classId");
        return classId;
    }

    function changeLocation (classId, theme) {

        var url  = getRequestUrl();
        var page = getRequestPage();

        // $("#blank-screen").fadeIn(200, function () {
        $( "body" ).fadeOut( 400, function () {

            var uri = "https://" + url + "?page=" + page;

                if (typeof classId !== 'undefined' && classId !== 'all') {
                    uri += "&section=" + classId;
                }

                if (typeof theme !== 'undefined') {
                    uri += "&theme=" + theme;
                }

            window.location = uri;
        });

    }

    $("#main-nav .button").click(function() {
        var url  = getRequestUrl();
        var page = $(this).data("mainNav");

        // $("#blank-screen").fadeIn(200, function () {
        $( "body" ).fadeOut( 400, function () {
            var uri = "https://" + url + "?page=" + page;
            if (page == 'outcome') {
                uri += "&sub=sale";
            }
            window.location = uri;
        });

    });

    $("#aside-content p").click(function(){
        var classId = getRequestClass();
        var theme;

        changeLocation(classId, theme);
    });

    $(".theme-chooser").click(function(){
        var classId = getRequestClass();
        var theme   = $(this).data("theme");

        changeLocation(classId, theme);
    });

    $("#sub-nav .stock .button").click(function() {
        var subPage = $( this ).data( "editPath" );
        var url     = getRequestUrl();

        $( "body" ).fadeOut( 400, function () {
            var uri = "https://" + url + "?page=settings&sub=" + subPage;
            window.location = uri;
        });
    });

});

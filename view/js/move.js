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

    $("#sub-nav a").click(function() {
        $("#sub-nav a").removeClass("active");
        $(this).addClass("active");
    });

// #############################################################################
// #############################################################################
// #############################################################################

    $('[data-toggle="tooltip"]').tooltip();

    $('#moving-date').datepicker({
        format: "yyyy-mm-dd",
        weekStart: 1,
        todayBtn: "linked",
        language: "ru",
        autoclose: true
    });

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

    $("#moving-date").val(str);

    /*
     * end of current date
     */

    function addValue(object, value){
        object.val(value);
        object.attr("value", value);
        object.text(value);
    }

    function clearValue(object){
        object.val("");
        object.attr("value", "");
        object.text("");
    }

    /*
     * ### AJAX ################################################################
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
                move: true
            }

        }).done(function(html) {

            $("#prices").append(html);

            $("#prices span").click(function(){

                var prodId    = $(this).attr('data-product-id');
                var prodPrice = $(this).html();

                $("#product-price").attr("value", prodId);
                $("#product-price").val(prodPrice);

                $("#product-id").val(prodId);

                $.ajax({

                    method: "POST",
                    url: "ajax/ajax.php",
                    data: {
                        product_id: prodId,
                        move: true
                    }

                }).done(function(html) {

                    clearValue( $("input.input-old") );
                    clearValue( $("input.input-new") );

                    var obj = JSON.parse(html);

                    $("#product-quantity").val(obj["product_quantity"]);

                    var locations = obj.locations;

                    $.each(locations, function(key, value) {

                        $("#" + value['stock_id'] + " input.input-old").val(value['location_quantity']);
                        $("#" + value['stock_id'] + " input.input-old").attr("value", value['location_quantity']);
                        $("#" + value['stock_id'] + " input.input-old").attr("data-quantity", value['location_quantity']);

                    });

                    $.each($("input.input-old"), function() {

                        var temp_data_quantity = parseInt($(this).attr("data-quantity"));
                        if (isNaN(temp_data_quantity)) {
                            $(this).attr("data-quantity", "0")
                        }

                    });

                    $("input.stock-radio").click(function() {
                        var input = $(this).prop("checked", true);
                        var form_group = input.parent().parent().parent().parent();
                        var stock_id = form_group.attr("id");

                        var base_quantity = $("#" + stock_id + " input.input-old").val();

                        if ($.isNumeric(base_quantity)) {

                            $("input.input-old").removeClass("bg-danger");
                            $("input.input-new").removeAttr("readonly");
                            $("#" + stock_id + " input.input-old").addClass("bg-danger");
                            $("#" + stock_id + " input.input-new").attr("readonly", "readonly");

                            $("#" + stock_id + " input.input-old").attr("data-quantity", base_quantity);
                            $("#" + stock_id + " input.input-old").attr("id", "base-remainder");

                            $("input.input-new").keyup(function(){

                                var moving_quantity = 0;
                                var new_quantity = parseInt($(this).val());
                                if (isNaN(new_quantity)) {
                                    new_quantity = parseInt(0);
                                }

                                $(this).attr("value", new_quantity);

                                var new_stock_id = $(this).parent().parent().attr("id");
                                var old_quantity = $("#" + new_stock_id + " input.input-old").attr("data-quantity");
                                old_quantity = parseInt(old_quantity);
                                if (isNaN(old_quantity)) {
                                    old_quantity = parseInt(0);
                                }
                                if ((old_quantity + new_quantity) == 0) {
                                    $("#" + new_stock_id + " input.input-old").attr("value", "--");
                                    $("#" + new_stock_id + " input.input-old").val("--");
                                    $("#" + new_stock_id + " input.input-old").text("--");
                                } else {
                                    $("#" + new_stock_id + " input.input-old").attr("value", old_quantity + new_quantity);
                                    $("#" + new_stock_id + " input.input-old").val(old_quantity + new_quantity);
                                    $("#" + new_stock_id + " input.input-old").text(old_quantity + new_quantity);
                                }

                                $("input.input-new").each(function() {
                                    var temp_quantity = parseInt($(this).val());
                                    if (isNaN(temp_quantity)) {
                                        temp_quantity = parseInt(0);
                                    }
                                    moving_quantity += temp_quantity;
                                });

                                base_remainder = base_quantity - moving_quantity;

                                if (base_remainder < 0) {

                                    alert("Вы не можете переместить больше товара, чем есть!");

                                    $("input.input-old").each(function() {
                                        $(this).attr("value", $(this).attr("data-quantity"));
                                        $(this).val($(this).attr("data-quantity"));
                                        $(this).text($(this).attr("data-quantity"));

                                        $("#moving-quantity").val("");
                                    });

                                    $("input.input-new").each(function() {
                                        $(this).attr("value", "");
                                        $(this).val("");
                                        $(this).text("");
                                    });

                                    return false;
                                }

                                $("#base-remainder").attr("value", base_remainder);
                                $("#base-remainder").text(base_remainder);
                                $("#base-remainder").val(base_remainder);

                                if (moving_quantity == 0) {
                                    $("#moving-quantity").attr("value", "--");
                                    $("#moving-quantity").text("--");
                                    $("#moving-quantity").val("--");
                                } else {
                                    $("#moving-quantity").attr("value", moving_quantity);
                                    $("#moving-quantity").text(moving_quantity);
                                    $("#moving-quantity").val(moving_quantity);
                                }

                            });

                        } else {
                            $(".stocks-list input").prop("checked", false);
                            alert("Товар отсутствует!");
                            return false;
                        }

                    });

                });

            });

        });

    });


    $(window).unload(function() {
        $("input").prop("checked", false);
        clearValue( $("input") );
        $("input.input-old").removeClass("bg-danger");
        $("input.input-new").removeAttr("readonly");
        $("#name-alias-old").val($("#name-alias-old option:first").val());
    });

    $("#move-submit").click(function(event) {

        var quant = $("#moving-quantity").val();
        quant = Number(quant);

        if (quant == 0) {
            event.preventDefault();
            alert("Не указано количество перемещаемого товара!");
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

    function getRequestSubPage () {
        var subPage = $("#sub-nav .sub .active").data("subPage");
        return subPage;
    }

    function changeLocation (classId, subPage, theme) {

        var url     = getRequestUrl();
        var page    = getRequestPage();

        // $("#blank-screen").fadeIn(200, function () {
        $("body").fadeOut(400, function () {

            var uri  = "https://" + url + "?page=" + page;

                if (typeof classId !== 'undefined'
                        && classId !== 'all') {
                    uri += "&section=" + classId;
                }

                if (typeof subPage   !== 'undefined'
                        && subPage   !== 'all') {
                    uri += "&sub="   + subPage;
                }

                if (typeof theme   !== 'undefined'
                        && theme   !== 'all') {
                    uri += "&theme="   + theme;
                }

            window.location = uri;
        });

    }

    $("#main-nav .button").click(function() {
        var url  = getRequestUrl();
        var page = $(this).data("mainNav");

        $("body").fadeOut(400, function () {
            var uri = "https://" + url + "?page=" + page;
            if (page == 'outcome') {
                uri += "&sub=move";
            }
            window.location = uri;
        });

    });

    $("#aside-content p").click(function(){
        var classId = getRequestClass();
        var subPage = getRequestSubPage();
        var theme;

        changeLocation(classId, subPage, theme);
    });

    $(".theme-chooser").click(function(){
        var classId = getRequestClass();
        var subPage = getRequestSubPage();
        var theme   = $(this).data("theme");

        changeLocation(classId, subPage, theme);
    });

});

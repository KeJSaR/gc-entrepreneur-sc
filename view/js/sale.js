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

    $('#sale-date').datepicker({
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

    $("#sale-date").val(str);

    /*
     * end of current date
     */


    /*
     * WRITING OFF
     */
    $("#write-off").prop("checked", false);

    $("#write-off").click(function() {

        if ($(this).is(":checked")) {

            $("#sale-price").attr("readonly", "readonly");
            $("#sale-submit").text("Списать товар");

            $("#sale-submit").removeClass("btn-primary");
            $("#sale-submit").addClass("btn-warning");

            $(this).attr("value", "1");
            $(this).val("1");
            $(this).prop("checked", true);

        } else {

            $("#sale-price").removeAttr("readonly");
            $("#sale-submit").text("Продать товар");

            $("#sale-submit").removeClass("btn-warning");
            $("#sale-submit").addClass("btn-primary");

            $(this).attr("value", "0");
            $(this).val("0");
            $(this).prop("checked", false);

        }
    });
    // end of writing off

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

    $("#name-alias-old").click(function(){

        $("#prices span").remove();

        clearValue( $("#sale-quantity") );
        clearValue( $("#location-quantity") );
        clearValue( $("#sale-price") );
        clearValue( $("#sale-amount") );
        clearValue( $("#product-quantity") );

        var name_id = $(this).val();
        $("#name-id").val(name_id);

        var stock_id = $( "#sub-nav .stock .active" ).data( "storeId" );
        addValue($("#stock-id"), stock_id);

        $.ajax({

            method: "POST",
            url: "ajax/ajax.php",
            data: {
                name_id: name_id,
                stock_id: stock_id,
                sale: true
            }

        }).done(function(html) {

            $("#prices").append(html);

            var sizeOfPrices = $("#prices span").length;

            $("#prices span").click(function(){

                $("#prices span").removeClass("active");
                $(this).addClass("active");

                var prodId    = $(this).attr('data-product-id'),
                    prodPrice = $(this).html();

                addValue($("#product-id"), prodId);
                addValue($("#product-price"), prodPrice);

                addValue($("#sale-price"), prodPrice);

                $.ajax({

                    method: "POST",
                    url: "ajax/ajax.php",
                    data: {
                        product_id: prodId,
                        stock_id: stock_id,
                        sale: true
                    }

                }).done(function(html) {

                    var obj = JSON.parse(html);

                    $("#location-quantity").val(obj.location_quantity);
                    $("#product-quantity").val(obj.product_quantity);

                    $("#location-quantity").attr("data-lquant", obj.location_quantity);
                    $("#product-quantity").attr("data-pquant", obj.product_quantity);

                });

            }); // end of name block

            if (sizeOfPrices == 1) {
                $("#the-price").click();
            }

        });

    });

    $("#sale-quantity").keyup(function(){
        var quant = $("#sale-quantity").val();
        quant = Number(quant);

        var price = $("#sale-price").val();
        price = Number(price);

        $("#sale-amount").val(price * quant);

        var l_quant = $("#location-quantity").attr("data-lquant");
        l_quant = Number(l_quant);

        var p_quant = $("#product-quantity").attr("data-pquant");
        p_quant = Number(p_quant);

        if ((l_quant - quant) >= 0) {

            $("#location-quantity").val(l_quant - quant);
            $("#product-quantity").val(p_quant - quant);

        } else {

            $("#sale-quantity").val("");
            $("#sale-quantity").attr("value", "");
            $("#sale-quantity").text("");

            var data_lquant = $("#location-quantity").attr("data-lquant");
            var data_pquant = $("#product-quantity").attr("data-pquant");

            $("#location-quantity").val(data_lquant);
            $("#location-quantity").attr("value", data_lquant);
            $("#location-quantity").text(data_lquant);
            $("#product-quantity").val(data_pquant);
            $("#product-quantity").attr("value", data_pquant);
            $("#product-quantity").text(data_pquant);

            alert("Вы не можете продать больше товара, чем есть на складе!");

        }

    });

    $("#sale-price").keyup(function(){
        var quant = $("#sale-quantity").val();
        var price = $("#sale-price").val();
        $("#sale-amount").val(price * quant);
    });

    $(window).unload(function() {
        $("#class-tree input").prop("checked", false);

        clearValue( $("input") );

        $("#name-alias-old").val($("#name-alias-old option:first").val());

        $("input").children('option').remove();
    });

    $("#sale-submit").click(function(event) {
        var quant = $("#sale-quantity").val();
        quant = Number(quant);

        var price = $("#sale-price").val();
        price = Number(price);

        if ($("#write-off").is(":checked")) {
            var write_off = 1;
        } else {
            var write_off = 0;
        }

        if (quant == 0) {
            event.preventDefault();
            alert("Не указано количество проданного товара!");
        }

        if (price == 0 && write_off == 0) {
            event.preventDefault();
            alert("Не указана цена по которой продали товар!");
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

    function getRequestStore () {
        var store = $("#sub-nav .stock .active").data("storeId");
        return store;
    }

    function changeLocation (storeId, classId, subPage, theme) {

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

                if (typeof storeId !== 'undefined'
                        && storeId !== 'all') {
                    uri += "&stock="   + storeId;
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
                uri += "&sub=sale";
            }
            window.location = uri;
        });

    });

    $("#sub-nav .stock .button").click(function() {
        var storeId = $(this).data("storeId");
        var classId = getRequestClass();
        var subPage = getRequestSubPage();
        var theme;

        changeLocation(storeId, classId, subPage, theme);
    });

    $("#aside-content p").click(function(){
        var storeId = getRequestStore();
        var classId = getRequestClass();
        var subPage = getRequestSubPage();
        var theme;

        changeLocation(storeId, classId, subPage, theme);
    });

    $(".theme-chooser").click(function(){
        var storeId = getRequestStore();
        var classId = getRequestClass();
        var subPage = getRequestSubPage();
        var theme   = $(this).data("theme");

        changeLocation(storeId, classId, subPage, theme);
    });

});

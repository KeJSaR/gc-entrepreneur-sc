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
                uri += "&outcome=sale";
            }
            window.location = uri;
        });

    });

    $("#sub-nav .sub .button").click(function() {
        var subPage = $( this ).data( "storeId" );
        var classId = getRequestClass();
        var theme;

        changeLocation(classId, subPage, theme);
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

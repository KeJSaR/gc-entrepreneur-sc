$(function(){

    function setBlocksSize () {

        // get height and width of browser viewport
        var h = $( window ).height();
        var w = $( window ).width();

        // set content size
        $( "body" ).height( h - 60 );

        $( "#content" ).height( h - 90 );

        $( "#sub-nav" ).width(function() {
            if ( w > 1200 ) {
                return w - 160;
            } else {
                return 1100;
            }
        });

        $( "aside" ).height( h - 167 );
        $( "#aside-content" ).height( h - 230 );
        if ( w < 1400 ) {
            $( "aside span" ).hide();
            $( "#aside-content" ).hide();
            $( "aside" ).css("width", "62px");
        } else {
            $( "aside span" ).show();
            $( "#aside-content" ).show();
            $( "aside" ).css("width", "300px");
        }

        $( "section" ).height( h - 167 );
        $( "#section-content" ).height( h - 167 );
        if ( w >= 1400 ) {
            $( "section" ).css("left", "350px");
            $( "section" ).width( w - 400 );
        } else if (w >= 1200) {
            $( "section" ).css("left", "112px");
            $( "section" ).width( w - 162 );
        } else {
            $( "section" ).css("left", "112px");
            $( "section" ).width( 1038 );
        }

        $( "footer" ).width(function() {
            if ( w > 1200 ) {
                return w - 100;
            } else {
                return 1100;
            }
        });

        if ( w < 1500 ) {
            $( "#main-nav span" ).hide();
        } else {
            $( "#main-nav span" ).show();
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

    function makeSectionHeader () {

        $( "#section-header tr" ).empty();

        $("#section-header").width( $("#section-content table").width() );

        $( "#section-content thead th" ).each(function( index ) {

            var w = $( this ).width(),
                t = $( this ).html(),
                css = 'style="width: ' + w + 'px;"',
                input = "<td " + css + ">" + t + "</td>";

            $( "#section-header tr" ).append( input );

        });
    }

    $(window).load(function(){
        var themeName = $( "footer .right a.active" ).attr( "id" );
        console.log(themeName);

        setBlocksSize();
        customScrollbar( themeName );
        makeSectionHeader();
    });

    $(window).resize(function() {
        setBlocksSize();
        makeSectionHeader();
    });

    $("#print").click(function(){
        var uri = window.location.href + "&print=1";
        window.location = uri;
    });

    $( "#aside-content p" ).click(function() {

        var i  = $( this ).children( "i" );

        if ( $( this ).hasClass( "active" ) ) {
            $( "#aside-content p" ).removeClass( "active" );
        } else {
            $( "#aside-content p" ).removeClass( "active" );
            $( this ).addClass( "active" );
        }

        if ( i.hasClass( "fa-square-o" ) ) {
            $( "#aside-content p" ).children( "i" ).removeClass( "fa-check-square-o" );
            $( "#aside-content p" ).children( "i" ).addClass( "fa-square-o" );
            i.removeClass( "fa-square-o" );
            i.addClass( "fa-check-square-o" );
        } else {
            i.removeClass( "fa-check-square-o" );
            i.addClass( "fa-square-o" );
        }

    });

    $( "#main-nav li" ).click(function() {
        $( "#main-nav li" ).removeClass( "active" );
        $( this ).addClass( "active" );
    });

    $( "#sub-nav a" ).click(function() {
        $( "#sub-nav a" ).removeClass( "active" );
        $( this ).addClass( "active" );
    });

    $('[data-toggle="tooltip"]').tooltip();

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
        var page = $( "#main-nav li.active .button" ).data( "mainNav" );
        return page;
    }

    function getRequestStore () {
        var store = $( "#sub-nav .stock .active" ).data( "storeId" );
        return store;
    }

    function getRequestRange () {
        var range = $( "#range .active" ).data( "range" );
        return range;
    }

    function getRequestClass () {
        var classId = $( "#aside-content p.active" ).data( "classId" );
        return classId;
    }

    function changeLocation (storeId, classId, range, theme) {

        var url     = getRequestUrl();
        var page    = getRequestPage();

        // $( "#blank-screen" ).fadeIn( 200, function () {
        $( "body" ).fadeOut( 400, function () {

            var uri  = "https://" + url + "?page=" + page;

                if (typeof storeId !== 'undefined'
                        && storeId !== 'all') {
                    uri += "&stock="   + storeId;
                }

                if (typeof classId !== 'undefined'
                        && classId !== 'all') {
                    uri += "&section=" + classId;
                }

                if (typeof range   !== 'undefined'
                        && range   !== 'all') {
                    uri += "&range="   + range;
                }

                if (typeof theme   !== 'undefined'
                        && theme   !== 'all') {
                    uri += "&theme="   + theme;
                }

            window.location = uri;
        });

    }

    $( "#main-nav .button" ).click(function() {
        var url  = getRequestUrl();
        var page = $( this ).data( "mainNav" );

        // $( "#blank-screen" ).fadeIn( 200, function () {
        $( "body" ).fadeOut( 400, function () {
            var uri = "https://" + url + "?page=" + page;
            if (page == 'outcome') {
                uri += "&sub=sale";
            }
            window.location = uri;
        });

    });

    $( "#sub-nav .stock .button" ).click(function() {
        var storeId = $( this ).data( "storeId" );
        var classId = getRequestClass();
        var range   = getRequestRange();
        var theme;

        changeLocation(storeId, classId, range, theme);
    });

    $( "#range .button" ).click(function() {
        var storeId = getRequestStore();
        var classId = getRequestClass();
        var range   = $( this ).data( "range" );
        var theme;

        changeLocation(storeId, classId, range, theme);
    });

    $( "#aside-content p" ).click(function(){
        var storeId = getRequestStore();
        var classId = getRequestClass();
        var range   = getRequestRange();
        var theme;

        changeLocation(storeId, classId, range, theme);
    });

    $( ".theme-chooser" ).click(function(){
        var storeId = getRequestStore();
        var classId = getRequestClass();
        var range   = getRequestRange();
        var theme   = $( this ).data( "theme" );

        changeLocation(storeId, classId, range, theme);
    });

});

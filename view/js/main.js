$(function(){

    function setBlocksSize () {

        // get height and width of browser viewport
        var h = $( window ).height();
        var w = $( window ).width();

        // set content size
        $( "body" ).height( h - 60 );

        $( "#content" ).height( h - 90 );

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

    $(window).load(function(){
        var themeName = $( "footer .right a.active" ).attr( "id" );
        setBlocksSize();
    });

    $(window).resize(function() {
        setBlocksSize();
    });

    $( "#main-nav li" ).click(function() {
        $( "#main-nav li" ).removeClass( "active" );
        $( this ).addClass( "active" );
    });

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

    function getRequestRange () {
        var range = $( "#range .active" ).data( "range" );
        return range;
    }

    function changeLocation (range, theme) {

        var url     = getRequestUrl();
        var page    = getRequestPage();

        // $( "#blank-screen" ).fadeIn( 200, function () {
        $( "body" ).fadeOut( 400, function () {

            var uri  = "https://" + url + "?page=" + page;

            if (typeof range   !== 'undefined'
                    && range   !== 'all') {
                uri += "&range="   + range;
            }

            if (typeof theme !== 'undefined') {
                uri += "&theme="   + theme;
            }

            window.location = uri;
        });

    }

    $( "#main-nav .button" ).click(function() {
        var url  = getRequestUrl();
        var page = $( this ).data( "mainNav" );

        $( "body" ).fadeOut( 400, function () {
            var uri = "https://" + url + "?page=" + page;
            if (page === 'outcome') {
                uri += "&sub=sale";
            }
            window.location = uri;
        });

    });

    $( "#range .button" ).click(function() {
        var range   = $( this ).data( "range" );
        var theme;

        changeLocation(range, theme);
    });

    $( ".theme-chooser" ).click(function(){
        var range = getRequestRange();
        var theme = $( this ).data( "theme" );

        changeLocation(range, theme);
    });

});

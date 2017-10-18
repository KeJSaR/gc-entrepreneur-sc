$(function(){

    print();

    var url = window.location.hostname + window.location.pathname;
    var uri = url.slice(0, -8);
    window.location = uri;

});

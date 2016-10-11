Loader = (function () {
    var that = {},
    
    showLoader = function(){
        $('#loader-background').show();
        $('#loaders').show();
    },
    
    hideLoader = function(){
        $('#loaders').hide();
        $('#loader-background').hide();
    };
    
    that.showLoader = showLoader;
    that.hideLoader = hideLoader;
    
    return that;
})();


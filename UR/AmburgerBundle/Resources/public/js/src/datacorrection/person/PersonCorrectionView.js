
PersonCorrection.PersonCorrectionView = (function(){
    var that = {},
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        $('#save-button').on("click", save);
        Collapsible.register();
        
        showLoader();
        
        return that;
    },

    showLoader = function(){
        Loader.showLoader();
    },
    
    hideLoader = function(){
        Loader.hideLoader();
    },
    
    save = function(){
        $(that).trigger('save');
    };


    that.init = init;
    that.showLoader = showLoader;
    that.hideLoader = hideLoader;

    return that;
})();


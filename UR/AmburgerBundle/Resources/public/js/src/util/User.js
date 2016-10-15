User = (function () {
    var that = {},
    
    init = function(){
        $('#user-settings').on("click", openSettings);
        $('#logout').on("click", logout);
        $('#back').on('click', back);
    },
    
    openSettings = function(){
       window.location.href = window.location.origin+"/settings";
    },
    
    logout = function(){
        //move to next step
        window.location.href = window.location.origin+"/logout";
    },
    
    back = function(){
        window.history.back();
    };
    
    that.init = init;
    
    return that;
})();



DuplicatePerson.DuplicatePersonView = (function(){
    var that = {},
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        $('#save-button').on("click", save);
        
        
        return that;
    },
    
    
    save = function(){
        $(that).trigger('save');
    };


    that.init = init;

    return that;
})();


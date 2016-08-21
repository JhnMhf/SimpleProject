
PersonCorrection.PersonCorrectionView = (function(){
    var that = {},
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        $('#save-button').on("click", save);
        $('.collapsible').on("click", collapse);
        return that;
    },
    
    
    save = function(){
        $(that).trigger('save');
    },
    
    collapse = function(args){
        console.log(args);
        
        var clickedContainer = extractClickedContainer(args.currentTarget.className);
        
        console.log(clickedContainer);
        
        var elements = $("."+clickedContainer);
        
        if(elements.hasClass('collapsed')){
            elements.removeClass('collapsed');
        } else{
            elements.addClass('collapsed');
        }
        
        
    },
    
    extractClickedContainer = function(classes){
        var classList = classes.split(/\s+/);
        for (i = 0; i < classList.length; i++) {
           if (classList[i].includes('-container')) {
               return classList[i];
           }
        }
    };


    that.init = init;

    return that;
})();


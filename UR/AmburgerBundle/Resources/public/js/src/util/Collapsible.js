Collapsible = (function () {
    var that = {},
    
    register = function(){
        $('.collapsible').on("click", collapse);
    },
    
    collapse = function(args){
        console.log(args);
        
        if(args.target.localName !== 'input' 
                && args.target.localName !== 'label' 
                && args.target.localName  !== 'select'
                 && args.target.localName  !== 'option'){             
            var clickedContainer = extractClickedContainer(args.currentTarget.className);

            console.log(clickedContainer);

            if(clickedContainer !== undefined){
                var elements = $("."+clickedContainer);

                if(elements.hasClass('collapsed')){
                    elements.removeClass('collapsed');
                } else{
                    elements.addClass('collapsed');
                }
            }
        }

    },
    
    extractClickedContainer = function(classes){
        var classList = classes.split(/\s+/);
        for (i = 0; i < classList.length; i++) {
           if (classList[i].includes('-container')) {
               return classList[i];
           }
        }
    }
    
    that.register = register;
    
    return that;
})();


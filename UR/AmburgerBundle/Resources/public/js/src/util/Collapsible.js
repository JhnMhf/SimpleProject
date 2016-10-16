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
                && args.target.localName  !== 'option'
                && args.target.localName !== 'button'){             
            var clickedContainer = extractClickedContainer(args.currentTarget.className);

            console.log(clickedContainer);

            if(clickedContainer !== undefined){
                var elements = $("."+clickedContainer);

                if(elements.hasClass('collapsed')){
                    elements.removeClass('collapsed');
                    var originHeight = elements.attr('origin-height');
                    
                    if(originHeight !== undefined && originHeight){
                        elements.height(originHeight);
                    }
                } else{
                    var height = elements.height();
                    elements.addClass('collapsed');
                    elements.attr('origin-height', height);
                    elements.attr('style', "");
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


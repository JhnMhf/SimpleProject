
End.EndController = (function(){
    var that = {},
    
    ajaxLoader = null,
   
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        ajaxLoader = End.AjaxLoader.init();
        $(ajaxLoader).on('correctionCompleted',onCorrectionIsCompleted );
        
        $('.end-correction').on('click', onCorrectionEndClicked);
        
        return that;
    },
    
    onCorrectionEndClicked = function(){
        ajaxLoader.completeCorrection();
    },
    
    onCorrectionIsCompleted= function(){
        console.log("Correction session was completed.");
        window.location.href = window.location.origin+"/correction/";
    };


    that.init = init;

    return that;
})();


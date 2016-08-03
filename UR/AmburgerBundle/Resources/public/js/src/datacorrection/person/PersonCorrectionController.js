
PersonCorrection.PersonCorrectionController = (function(){
    var that = {},
    
    /* Views */
    personCorrectionView = null,
    
    /* Controllers */
    ajaxLoader = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        
        ajaxLoader = PersonCorrection.AjaxLoader.init();
        
        $(ajaxLoader).on("personLoaded", onPersonLoaded);

        personCorrectionView = PersonCorrection.PersonCorrectionView.init();
        
        
        ajaxLoader.loadPersonToCorrect();
           
        return that;
    },
    
    onPersonLoaded = function(event, oldPerson,newPerson, finalPerson){
        console.log("OldPerson", oldPerson);
        console.log("NewPerson", newPerson);
        console.log("FinalPerson", finalPerson);
    };


    that.init = init;

    return that;
})();


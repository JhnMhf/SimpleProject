
RelativesCorrection.RelativesCorrectionController = (function(){
    var that = {},
    
    /* Views */
    relativesView = null,
    
    /* Controllers */
    ajaxLoader = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        
        ajaxLoader = RelativesCorrection.AjaxLoader.init();
        
        $(ajaxLoader).on("possibleRelativesLoaded", onPossibleRelativesLoaded);
        $(ajaxLoader).on("directRelativesLoaded", onDirectRelativesLoaded);
        $(ajaxLoader).on("personLoaded", onPersonLoaded);
        
        relativesView = RelativesCorrection.RelativesView.init();
        
        
        ajaxLoader.loadPerson();


        return that;
    },
    
    onPersonLoaded = function(event, data){
        ajaxLoader.loadPossibleRelatives();
        ajaxLoader.loadDirectRelatives();
        
        relativesView.setPersonData(data);
    }
    
    onPossibleRelativesLoaded = function (event, data){
        relativesView.displayPossibleRelatives(data['data']);
    },
    
    onDirectRelativesLoaded = function (event, data){
        relativesView.displayDirectRelatives(data['data']);
    };


    that.init = init;

    return that;
})();



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
        
        $(relativesView).on('relationUpdated',onRelationUpdated);
        $(relativesView).on('relationCreated',onRelationCreated);
        $(relativesView).on('relationRemoved',onRelationRemoved);
        
        
        ajaxLoader.loadPerson();

        Loader.showLoader();
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
    },
    
    onRelationUpdated = function(event, data){
        console.log('onRelationUpdated', data);
        ajaxLoader.updateRelationship(data);
    },
    
    onRelationCreated = function(event, data){
        console.log('onRelationCreated', data);
        ajaxLoader.createRelationship(data);
    },
    
    onRelationRemoved = function(event, data){
        console.log('onRelationRemoved', data);
        ajaxLoader.removeRelationship(data);
    };


    that.init = init;

    return that;
})();


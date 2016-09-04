
PersonCorrection.PersonCorrectionController = (function(){
    var that = {},
    
    /* Views */
    personCorrectionView = null,
    oldPersonView = null,
    newPersonView = null,
    finalPersonView = null,
    
    /* Controllers */
    ajaxLoader = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        
        ajaxLoader = PersonCorrection.AjaxLoader.init();
        
        $(ajaxLoader).on("personLoaded", onPersonLoaded);
        
        $(ajaxLoader).on("saveFinished", onSaveFinished);
        
        $(ajaxLoader).on("errorOccured", onErrorOccured);

        personCorrectionView = PersonCorrection.PersonCorrectionView.init();
        
        $(personCorrectionView).on("save", onSave);
        
        oldPersonView = PersonCorrection.OldPersonView.init();
        newPersonView = PersonCorrection.NewPersonView.init();
        finalPersonView = PersonCorrection.FinalPersonView.init();
        
        
        ajaxLoader.loadPersonToCorrect();
           
        return that;
    },
    
    onPersonLoaded = function(event, oldPerson,newPerson, finalPerson){
        console.log("OldPerson", oldPerson);
        oldPersonView.displayPerson(oldPerson);
        console.log("NewPerson", newPerson);
        newPersonView.displayPerson(newPerson);
        console.log("FinalPerson", finalPerson);
        finalPersonView.displayPerson(finalPerson);
        
        personCorrectionView.hideLoader();
    },
    
    onSave = function(){
        personCorrectionView.showLoader();
        var changedFinalPerson = finalPersonView.extractPersonData();
        
        console.log(changedFinalPerson);
        
        ajaxLoader.saveFinalPerson(changedFinalPerson);
    },
    
    onSaveFinished = function(){
        personCorrectionView.hideLoader();
        alert('Success');
        /*
        var currentUrl = window.location.href;
        var newUrl = currentUrl.replace("person", "relationships");
        window.location.href = newUrl;
        */
    },
    
        
    onErrorOccured = function(event, data){
        personCorrectionView.hideLoader();
        console.error(data)
    };


    that.init = init;

    return that;
})();


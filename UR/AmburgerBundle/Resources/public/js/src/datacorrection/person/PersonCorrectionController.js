
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
        
        $(ajaxLoader).on("weddingsLoaded", onWeddingsLoaded);
        
        $(ajaxLoader).on("saveFinished", onSaveFinished);
        
        $(ajaxLoader).on("weddingSaveFinished", onWeddingSaveFinished);
        
        $(ajaxLoader).on("errorOccured", onErrorOccured);

        personCorrectionView = PersonCorrection.PersonCorrectionView.init();
        
        $(personCorrectionView).on("save", onSave);
        
        oldPersonView = PersonCorrection.OldPersonView.init();
        newPersonView = PersonCorrection.NewPersonView.init();
        finalPersonView = PersonCorrection.FinalPersonView.init();
        
        
        ajaxLoader.loadPersonToCorrect();
        ajaxLoader.loadWeddingsToCorrect();
           
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
    
    onWeddingsLoaded = function(event, oldWeddings,newWeddings, finalWeddings){
        console.log("OldPerson", oldWeddings);
        oldPersonView.displayWeddings(oldWeddings);
        console.log("NewPerson", newWeddings);
        newPersonView.displayWeddings(newWeddings);
        console.log("FinalPerson", finalWeddings);
        finalPersonView.displayWeddings(finalWeddings);
    },
    
    onSave = function(){
        personCorrectionView.showLoader();

        var changedWeddings = finalPersonView.extractWeddingData();
        
        if(changedWeddings.length > 0){
            console.log(changedWeddings);
        
            ajaxLoader.saveWeddings(changedWeddings);
        } else {
            savePerson();
        }
    },
    
    onWeddingSaveFinished = function(){
        savePerson();
    },
    
    savePerson = function(){
        var changedFinalPerson = finalPersonView.extractPersonData();
        console.log(changedFinalPerson);
        ajaxLoader.saveFinalPerson(changedFinalPerson);
    }
    
    onSaveFinished = function(){
        personCorrectionView.hideLoader();
        alert('Success');
        var currentUrl = window.location.href;
        var newUrl = currentUrl.replace("person", "relatives");
        window.location.href = newUrl;
    },
    
        
    onErrorOccured = function(event, data){
        personCorrectionView.hideLoader();
        console.error(data)
    };


    that.init = init;

    return that;
})();


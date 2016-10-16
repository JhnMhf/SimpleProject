
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
        
        $(ajaxLoader).on("gndRequestFinished", onGNDRequestFinished);

        personCorrectionView = PersonCorrection.PersonCorrectionView.init();
        
        $(personCorrectionView).on("save", onSave);
        
        oldPersonView = PersonCorrection.OldPersonView.init();
        newPersonView = PersonCorrection.NewPersonView.init();
        finalPersonView = PersonCorrection.FinalPersonView.init();
        
        $(finalPersonView).on("sendGNDRequest", onSentGNDRequest);
        
        
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
        
        
        //removeUnusedRows();
        alignRowsOfTheSameType();
        collapse();
        personCorrectionView.hideLoader();
    },
    
    removeUnusedRows = function(){
        console.log("Removing unused rows");
        var higherRows = $('.higher-row');
        
        for(var i = 0; i < higherRows.length; i++){
            var childRows = $(higherRows[i]).find('.row');
            
            if(childRows.length == 0){
                $(higherRows[i]).hide();
            }
        }
    },
    
    alignRowsOfTheSameType = function(){
        console.log("Aligning rows of the same type");
        var listOfRowTypes = ["base-person-container", "birth-container", 
            "baptism-container", "death-container", "educations-container", 
            "honours-container","properties-container", "rank-container", 
            "religion-container", "residence-container", "road-of-life-container", 
            "status-container", "works-container", "wedding-container", "source-container"];
        
        for(var i = 0; i < listOfRowTypes.length; i++){
            var rowType = listOfRowTypes[i];
            
            var elements = $("."+rowType);
            
            console.log("Found ", elements.length, " for rowtype: ",rowType);
            
            var largest = 0;
            //find largest
            for(var j = 0; j < elements.length; j++){
                var height = $(elements[j]).height();
                console.log("Height: ", height);
                if(height > largest){
                    console.log("Bigger than largest: ", largest);
                    largest = height;
                }
            }
            
            //set all to the same height
            for(var j = 0; j < elements.length; j++){
                $(elements[j]).height(largest);
            }
        }
    },
    
    collapse = function(){
        console.log("Collapsing some rows");
        var listOfRowTypes = ["birth-container", 
            "baptism-container", "death-container", "educations-container", 
            "honours-container","properties-container", "rank-container", 
            "religion-container", "residence-container", "road-of-life-container", 
            "status-container", "works-container", "wedding-container", "source-container"];
        
        for(var i = 0; i < listOfRowTypes.length; i++){
            var rowType = listOfRowTypes[i];
            
            var elements = $("."+rowType);
           
            //collapse them
            for(var j = 0; j < elements.length; j++){
                var height =  $(elements[j]).height();
                $(elements[j]).attr('origin-height', height);
                $(elements[j]).addClass('collapsed');
                $(elements[j]).attr('style', "");
            }
        }
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
    },
    
    onSentGNDRequest = function(event, searchTerm){
        ajaxLoader.sendGNDRequest(searchTerm);
    },
    
    onGNDRequestFinished = function(event, result){
        finalPersonView.displayGNDResult(result['results']);
    };


    that.init = init;

    return that;
})();


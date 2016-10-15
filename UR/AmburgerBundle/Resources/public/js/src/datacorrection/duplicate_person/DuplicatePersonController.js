
DuplicatePerson.DuplicatePersonController = (function(){
    var that = {},
        
    /* Views */
    duplicatePersonView = null,
    
    /* Controllers */
    ajaxLoader = null,
    
    duplicatesData = null,
    
    personData = null,
    
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        ajaxLoader = DuplicatePerson.AjaxLoader.init();
        
        $(ajaxLoader).on("duplicatePersonsLoaded", onDuplicatePersonsLoaded);
        $(ajaxLoader).on("personDataLoaded", onPersonDataLoaded);
        $(ajaxLoader).on("mergeFinished", onMergeFinished);

        duplicatePersonView = DuplicatePerson.DuplicatePersonView.init();
        
        $(duplicatePersonView).on('mergeDuplicate', onMergeDuplicate);
        
        ajaxLoader.loadPersonData();
        ajaxLoader.loadDuplicatePersons();
        
        Loader.showLoader();

        return that;
    },
    
    onPersonDataLoaded = function(event, loadedPersonData){
        console.log("PersonData: ", loadedPersonData);
        personData = loadedPersonData;
        checkDisplay();
    },
    
    onDuplicatePersonsLoaded = function(event, loadedDuplicatePersons){
        console.log("DuplicatePersons: ", loadedDuplicatePersons);
        duplicatesData = loadedDuplicatePersons;
        checkDisplay();
    },
    
    onMergeDuplicate = function(event, duplicateId){
        ajaxLoader.triggerMergeDuplicate(duplicateId);
    },
    
    onMergeFinished = function(event, status){
        if(status == 200){
            duplicatePersonView.mergeFinished();
        } else if(status == 406){
            MessageHelper.showInfoMessage("Die derzeit ausgewählte Person, wurde als Duplikat erkannt und in eine andere Person gemerged. \n\
            Die Korrektursession ist somit beendet.", that, forwardToStart);
        }
    },
    
    forwardToStart = function(){
        window.location.href = window.location.origin+"/correction/";
    },
    
    checkDisplay = function(){
        if(duplicatesData  && personData){
            startDisplay();
        }
    },
    
    startDisplay = function(){
        duplicatePersonView.displayDuplicates(personData, duplicatesData);
    };


    that.init = init;

    return that;
})();


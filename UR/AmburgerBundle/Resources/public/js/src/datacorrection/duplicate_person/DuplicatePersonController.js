
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
        
        if(loadedDuplicatePersons['status'] == 200){
            duplicatesData = loadedDuplicatePersons['data'];
            checkDisplay();
        } else if (loadedDuplicatePersons['status'] == 204) {
            Loader.hideLoader();
            MessageHelper.showInfoMessage("Es wurden keine Duplikate gefunden.",false, that, "next");
        }
        
    },
    
    onMergeDuplicate = function(event, duplicateId){
        ajaxLoader.triggerMergeDuplicate(duplicateId);
    },
    
    onMergeFinished = function(event, status){
        if(status == 200){
            duplicatePersonView.mergeFinished();
        } else if(status == 406){
            MessageHelper.showInfoMessage("Die derzeit ausgewählte Person, wurde als Duplikat erkannt und in eine andere Person gemerged. \n\
            Die Korrektursession ist somit beendet.",false, that, "forwardToStart");
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
    
    next = function(){
        console.log("Forwarding to person");
        var currentUrl = window.location.href;
        var newUrl = currentUrl.replace("duplicate", "person");
        window.location.href = newUrl;
    },
    
    startDisplay = function(){
        duplicatePersonView.displayDuplicates(personData, duplicatesData);
    };


    that.init = init;
    that.forwardToStart = forwardToStart;
    that.next = next;

    return that;
})();


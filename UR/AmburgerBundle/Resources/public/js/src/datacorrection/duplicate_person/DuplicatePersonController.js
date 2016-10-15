
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

        duplicatePersonView = DuplicatePerson.DuplicatePersonView.init();
        
        $(duplicatePersonView).on("save", onSave);
        
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


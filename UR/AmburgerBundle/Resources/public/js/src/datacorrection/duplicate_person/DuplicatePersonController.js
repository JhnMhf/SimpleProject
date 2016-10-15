
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
    },
    
    onSave = function(event){
        console.log("Save triggered...");
        
        //@TODO: Save data to db? If it didn't already happen...
        
        //forward to next page
        var currentUrl = window.location.href;
        var newUrl = currentUrl.replace("duplicate", "person");
        window.location.href = newUrl;
    };


    that.init = init;

    return that;
})();


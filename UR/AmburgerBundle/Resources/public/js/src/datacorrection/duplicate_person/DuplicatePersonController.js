
DuplicatePerson.DuplicatePersonController = (function(){
    var that = {},
        
    /* Views */
    duplicatePersonView = null,
    
    /* Controllers */
    ajaxLoader = null,
    
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        ajaxLoader = DuplicatePerson.AjaxLoader.init();
        
        $(ajaxLoader).on("duplicatePersonsLoaded", onDuplicatePersonsLoaded);

        duplicatePersonView = DuplicatePerson.DuplicatePersonView.init();
        
        $(duplicatePersonView).on("save", onSave);
        
        ajaxLoader.loadDuplicatePersons();

        return that;
    },
    
    
    onDuplicatePersonsLoaded = function(event, duplicatePersons){
        console.log("DuplicatePersons: ", duplicatePersons);
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


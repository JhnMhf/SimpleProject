
PersonCorrection.FinalPersonView = (function(){
    var that = {},
    
    finalPersonData = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {

        return that;
    },
    
    displayPerson = function(personData){
        finalPersonData = personData;
    },
    
    extractPersonData = function(){
        return finalPersonData;
    };


    that.init = init;
    that.displayPerson = displayPerson;
    that.extractPersonData = extractPersonData;

    return that;
})();


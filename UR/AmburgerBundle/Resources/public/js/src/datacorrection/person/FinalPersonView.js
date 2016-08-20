
PersonCorrection.FinalPersonView = (function(){
    var that = {},
    
    finalPersonData = null,
    
    personViewGenerator = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        personViewGenerator = PersonCorrection.BasePersonViewGenerator.init();
        return that;
    },
    
    displayPerson = function(personData){
        finalPersonData = personData;
        personViewGenerator.displayPerson("#final", personData, true);
        
    },
    
    extractPersonData = function(){
        return finalPersonData;
    };


    that.init = init;
    that.displayPerson = displayPerson;
    that.extractPersonData = extractPersonData;

    return that;
})();


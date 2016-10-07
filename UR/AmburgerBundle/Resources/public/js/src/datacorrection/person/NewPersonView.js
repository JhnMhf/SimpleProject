
PersonCorrection.NewPersonView = (function(){
    var that = {},
    
    personViewGenerator = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        personViewGenerator = PersonCorrection.BasePersonViewGenerator.init();
        return that;
    },
    
    displayPerson = function(personData){
        personViewGenerator.displayPerson("#new", personData);
    },
    
    displayWeddings = function(weddingData){
        personViewGenerator.displayWeddings('#new', weddingData);
    };

    that.init = init;
    that.displayPerson = displayPerson;
    that.displayWeddings = displayWeddings;

    return that;
})();


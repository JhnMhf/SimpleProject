
PersonCorrection.OldPersonView = (function(){
    var that = {},
    
    oldPersonViewGenerator = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        oldPersonViewGenerator = PersonCorrection.OldPersonViewGenerator.init();
        return that;
    },
    
    displayPerson = function(personData){
        oldPersonViewGenerator.displayPerson("#old", personData);
    },
    
    displayWeddings = function(weddingData){
        oldPersonViewGenerator.displayWeddings('#old', weddingData);
    };


    that.init = init;
    that.displayPerson = displayPerson;
    that.displayWeddings = displayWeddings;

    return that;
})();


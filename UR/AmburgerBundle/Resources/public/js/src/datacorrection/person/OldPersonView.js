
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
    };


    that.init = init;
    that.displayPerson = displayPerson;

    return that;
})();



Detail.PersonView = (function(){
    var that = {},
    
    personViewGenerator = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        personViewGenerator = Detail.BasePersonViewGenerator.init();
        return that;
    },
    
    displayPerson = function(personData){
        setHeader(personData);
        personViewGenerator.displayPerson("#person-data", personData);
    },
    
    setHeader = function(personData){
        var name = personData['first_name']+" "+ personData['last_name'];
        $('h2#name-header').text(name);
    };

    that.init = init;
    that.displayPerson = displayPerson;

    return that;
})();


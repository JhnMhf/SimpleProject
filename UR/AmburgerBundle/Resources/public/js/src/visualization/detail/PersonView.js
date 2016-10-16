
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
        
        removeUnusedRows();
        
        Loader.hideLoader();
    },
    
    removeUnusedRows = function(){
        var higherRows = $('.higher-row');
        
        for(var i = 0; i < higherRows.length; i++){
            var childRows = $(higherRows[i]).find('.row');
            
            if(childRows.length == 0){
                $(higherRows[i]).hide();
            }
        }
    },
    
    setHeader = function(personData){
        var name = personData['first_name']+" "+ personData['last_name'];
        $('h2#name-header').text(name);
    };

    that.init = init;
    that.displayPerson = displayPerson;

    return that;
})();


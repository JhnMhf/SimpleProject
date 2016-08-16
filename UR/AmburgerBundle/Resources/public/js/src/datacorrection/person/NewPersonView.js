
PersonCorrection.NewPersonView = (function(){
    var that = {},
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {

        return that;
    },
    
    displayPerson = function(personData){
        displayBasePerson(personData);
    },
    
    displayBasePerson = function(personData){
        console.log(personData);
        var template = _.template($("script#basePerson").html());
        console.log("template", template);
        $("#new").append(template({
            oid:personData['oid'], 
            firstName:personData['first_name'],
            patronym:personData['patronym'],
            lastName:personData['last_name'],
            foreName:personData['fore_name'],
            birthName:personData['birth_name']
        }));
    };

    that.init = init;
    that.displayPerson = displayPerson;

    return that;
})();


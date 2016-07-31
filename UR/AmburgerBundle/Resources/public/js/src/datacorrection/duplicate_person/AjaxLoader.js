/* 
    An object used for retrieving information with ajax from the backend.
*/

DuplicatePerson.AjaxLoader = (function(){
    var that = {},


    /* 
        Initialises the object 
    */
    init = function() {


        return that;
    },
    
    loadDuplicatePersons = function(oid){
        $.ajax({
            type: "GET",
            url: ''+oid,
            dataType: 'json',
            data: {
            },
            success: function(data){
                //@TODO: Handle list of duplicate persons
                $(that).trigger("duplicatePersonsLoaded", []);
            },
            error: function(data){
                if(data.status == 200){
                    //data.responseText
                }
            }
        });
};

    that.init = init;
    that.loadCorrectedPerson = loadCorrectedPerson;
    return that;
})();
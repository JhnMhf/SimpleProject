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
            url: 'load',
            dataType: 'json',
            data: {
            },
            success: function(data){
                //@TODO: Handle json?
                console.log(data);
                $(that).trigger("duplicatePersonsLoaded", data['duplicate_persons']);
            },
            error: function(data){
                if(data.status == 200){
                    //data.responseText
                }
            }
        });
};

    that.init = init;
    that.loadDuplicatePersons = loadDuplicatePersons;
    return that;
})();
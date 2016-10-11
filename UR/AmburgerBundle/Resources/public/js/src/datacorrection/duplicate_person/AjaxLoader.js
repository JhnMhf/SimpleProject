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
    
    loadPersonData = function () {
        $.ajax({
            type: "GET",
            url: 'load/person',
            dataType: 'json'
        }).always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);

            $(that).trigger("personDataLoaded", [data]);
        });
    },
    
    loadDuplicatePersons = function(){
       $.ajax({
            type: "GET",
            url: 'load/duplicates',
            dataType: 'json'
        }).always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);

            $(that).trigger("duplicatePersonsLoaded", [data]);
        });
    };

    that.init = init;
    that.loadDuplicatePersons = loadDuplicatePersons;
    that.loadPersonData = loadPersonData;
    return that;
})();
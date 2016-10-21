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
            if(jqXHR.status == 200){
                 $(that).trigger("personDataLoaded", [data]);
            } else {
                MessageHelper.showServerCommunicationFailed();
            }

           
        });
    },
    
    loadDuplicatePersons = function(){
       $.ajax({
            type: "GET",
            url: 'load/duplicates',
            dataType: 'json'
        }).always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);
            
            if(jqXHR.status == 200 || jqXHR.status == 204){
                 $(that).trigger("duplicatePersonsLoaded", {status: jqXHR.status, data: data});
            } else {
                MessageHelper.showServerCommunicationFailed();
            }

           
        });
    },
    
    triggerMergeDuplicate = function(duplicateId){
        $.ajax({
            type: "POST",
            url: 'merge/'+duplicateId,
            dataType: 'json'
        }).always(function (data, textStatus, jqXHR) {
            if(jqXHR.status == 200 || jqXHR.status == 406){
                 $(that).trigger("mergeFinished", [jqXHR.status]);
            } else {
                MessageHelper.showServerCommunicationFailed();
            }
        });
    };

    that.init = init;
    that.loadDuplicatePersons = loadDuplicatePersons;
    that.loadPersonData = loadPersonData;
    that.triggerMergeDuplicate = triggerMergeDuplicate;
    return that;
})();

Detail.AjaxLoader = (function(){
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
            url: 'load',
            dataType: 'json'
        }).always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);
            
            if(jqXHR.status == 200){
                $(that).trigger("personLoaded", data);
            } else {
                MessageHelper.showServerCommunicationFailed();
            }

        });
    },
    
    loadLocationsForPerson = function () {
        $.ajax({
            type: "GET",
            url: 'locations',
            dataType: 'json'
        }).always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);

            if(jqXHR.status == 200){
                $(that).trigger("locationsLoaded", {'data':data});
            } else {
                MessageHelper.showServerCommunicationFailed();
            }

        });
    },
    
    loadRelationsForPerson= function(){
        $.ajax({
            type: "GET",
            url: 'relations',
            dataType: 'json'
        }).always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);
            
            if(jqXHR.status == 200){
                $(that).trigger("relationsLoaded", {'data':data});
            } else {
                MessageHelper.showServerCommunicationFailed();
            }

           
        });
    };

    that.init = init;
    that.loadLocationsForPerson = loadLocationsForPerson;
    that.loadPersonData = loadPersonData;
    that.loadRelationsForPerson = loadRelationsForPerson;
    return that;
})();
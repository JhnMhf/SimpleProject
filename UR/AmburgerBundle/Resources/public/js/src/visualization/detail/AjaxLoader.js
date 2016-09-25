
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

            $(that).trigger("personLoaded", data);
        });
    },
    
    loadLocationsForPerson = function () {
        $.ajax({
            type: "GET",
            url: 'locations',
            dataType: 'json'
        }).always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);

            $(that).trigger("locationsLoaded", {'data':data});
        });
    };

    that.init = init;
    that.loadLocationsForPerson = loadLocationsForPerson;
    that.loadPersonData = loadPersonData;
    return that;
})();
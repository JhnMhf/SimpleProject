/* 
 An object used for retrieving information with ajax from the backend.
 */

RelativesCorrection.AjaxLoader = (function () {
    var that = {},
            /* 
             Initialises the object 
             */
            init = function () {


                return that;
            },
            
            loadPerson = function(){
                $.ajax({
                    type: "GET",
                    url: 'load',
                    dataType: 'json'
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);

                    $(that).trigger("personLoaded", data);
                });
            },
    
            loadPossibleRelatives = function(){
                $.ajax({
                    type: "GET",
                    url: 'possible',
                    dataType: 'json'
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);

                    $(that).trigger("possibleRelativesLoaded", {data: data});
                });
            },
            
            loadDirectRelatives = function(){
                $.ajax({
                    type: "GET",
                    url: 'direct',
                    dataType: 'json'
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);

                    $(that).trigger("directRelativesLoaded", {data: data});
                });
            };
            

    that.init = init;
    that.loadPossibleRelatives = loadPossibleRelatives;
    that.loadDirectRelatives = loadDirectRelatives;
    that.loadPerson = loadPerson;
    
    return that;
})();
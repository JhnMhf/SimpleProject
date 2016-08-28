/* 
 An object used for retrieving information with ajax from the backend.
 */

PersonCorrection.AjaxLoader = (function () {
    var that = {},
            /* 
             Initialises the object 
             */
            init = function () {


                return that;
            },
            
            loadPersonToCorrect = function () {
                $.ajax({
                    type: "GET",
                    url: 'load',
                    dataType: 'json',
                    data: {
                    },
                    success: function (data) {

                    },
                    error: function (data) {
                        if (data.status == 200) {
                            //data.responseText
                        }
                    }
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);

                    $(that).trigger("personLoaded", [data['old'], data['new'], data['final']]);
                });
            },
            
            saveFinalPerson = function(finalPerson){
                //@TODO: Save
                $.ajax({
                    type: "POST",
                    url: 'save',
                    dataType: 'json',
                    data: JSON.stringify(finalPerson),
                    success: function (data) {
                        console.log('success', data);
                    },
                    error: function (data) {
                        console.log('error', data);
                        if (data.status == 200) {
                            //data.responseText
                        }
                    }
                }).always(function (data, textStatus, jqXHR) {
                    console.log('always', data);
                    
                    console.log(data.responseText);
                    //@TODO: Handle errors etc.?
                    $(that).trigger("saveFinished");
                });
            };

    that.init = init;
    that.loadPersonToCorrect = loadPersonToCorrect;
    that.saveFinalPerson = saveFinalPerson;
    
    return that;
})();
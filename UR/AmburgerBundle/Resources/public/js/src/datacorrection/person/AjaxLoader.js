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
                    console.log(data);
                    var oldPerson = PersonCorrection.PersonModel.init();
                    oldPerson.createFromJson(data['old']);

                    var newPerson = PersonCorrection.PersonModel.init();
                    newPerson.createFromJson(data['new']);

                    var finalPerson = PersonCorrection.PersonModel.init();
                    finalPerson.createFromJson(data['final']);

                    $(that).trigger("personLoaded", [oldPerson, newPerson, finalPerson]);
                });
            };

    that.init = init;
    that.loadPersonToCorrect = loadPersonToCorrect;
    return that;
})();
/* 
 An object used for retrieving information with ajax from the backend.
 */

Start.AjaxLoader = (function () {
    var that = {},
            /* 
             Initialises the object 
             */
            init = function () {


                return that;
            },
            nextPerson = function () {
                $.ajax({
                    type: "GET",
                    url: 'next',
                    dataType: 'json'
                })
                        .always(function (data, textStatus, jqXHR) {
                            if (jqXHR.status == "200") {
                                var id = data['id'];

                                $(that).trigger("nextPerson", [id]);

                            }
                        }).fail(function (jqXHR) {
                    if (jqXHR.status == "404") {
                        $(that).trigger("noNextPerson");
                    }
                });
            },
            startWork = function (id) {
                $.ajax({
                    type: "POST",
                    url: id
                })
                        .always(function (data, textStatus, jqXHR) {
                            $(that).trigger("workStarted", [jqXHR.status]);
                        }).fail(function (jqXHR) {
                    $(that).trigger("workStarted", [jqXHR.status]);
                });
            },
            checkPerson = function (id) {
                $.ajax({
                    type: "GET",
                    url: id + "/check",
                })
                        .done(function (data, textStatus, jqXHR) {
                            $(that).trigger("personChecked", [jqXHR.status]);
                        })
                        .fail(function (jqXHR) {
                            $(that).trigger("personChecked", [jqXHR.status]);
                        });
            };


    that.init = init;
    that.checkPerson = checkPerson;
    that.nextPerson = nextPerson;
    that.startWork = startWork;
    return that;
})();
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
            loadNextPerson = function () {
                console.log("loadNextPerson");
                $.ajax({
                    type: "GET",
                    url: 'next',
                    dataType: 'json'
                })
                        .always(function (data, textStatus, jqXHR) {
                            if (jqXHR.status == "200") {
                                var id = data['id'];

                                $(that).trigger("nextPerson", [id]);

                            } else if (jqXHR.status == "404") {
                                $(that).trigger("noNextPerson");
                            } else {
                                MessageHelper.showServerCommunicationFailed();
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
            checkPersonByID = function (id) {
                $.ajax({
                    type: "GET",
                    url: id + "/check",
                    beforeSend: function (request)
                    {
                        request.setRequestHeader("Type", 'ID');
                    },
                })
                        .done(function (data, textStatus, jqXHR) {
                            $(that).trigger("personChecked", [jqXHR.status]);
                        })
                        .fail(function (jqXHR) {
                            $(that).trigger("personChecked", [jqXHR.status]);
                        });
            },
            
            checkPersonByOID = function (id) {
                $.ajax({
                    type: "GET",
                    url: id + "/check",
                    beforeSend: function (request)
                    {
                        request.setRequestHeader("Type", 'OID');
                    },
                })
                        .done(function (data, textStatus, jqXHR) {
                            console.log("ID of person: ",jqXHR.getResponseHeader('ID'));
                            $(that).trigger("oidPersonChecked", {status: jqXHR.status, id: jqXHR.getResponseHeader('ID') });
                        })
                        .fail(function (jqXHR) {
                            console.log("ID of person: ",jqXHR.getResponseHeader('ID'));
                            $(that).trigger("oidPersonChecked", {status: jqXHR.status, id: jqXHR.getResponseHeader('ID') });
                        });
            };


    that.init = init;
    that.checkPersonByID = checkPersonByID;
    that.checkPersonByOID = checkPersonByOID;
    that.loadNextPerson = loadNextPerson;
    that.startWork = startWork;
    return that;
})();
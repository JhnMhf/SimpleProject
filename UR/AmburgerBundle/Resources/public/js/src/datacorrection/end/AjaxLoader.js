
End.AjaxLoader = (function(){
    var that = {},


    /* 
        Initialises the object 
    */
    init = function() {


        return that;
    },
    
    completeCorrection = function(){
        $.ajax({
            type: "GET",
            url: 'complete'
        }).always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);

            $(that).trigger("correctionCompleted", data);
        });
    };

    that.init = init;
    that.completeCorrection = completeCorrection;
    return that;
})();
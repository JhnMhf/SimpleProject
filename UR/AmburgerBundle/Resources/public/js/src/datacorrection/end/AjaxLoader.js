
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
            if(jqXHR.status == 200){
                 $(that).trigger("correctionCompleted", data);
            } else {
                MessageHelper.showServerCommunicationFailed();
            }

            
        });
    };

    that.init = init;
    that.completeCorrection = completeCorrection;
    return that;
})();
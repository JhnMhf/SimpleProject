/* 
    An object used for retrieving information with ajax from the backend.
*/

PersonCorrection.AjaxLoader = (function(){
    var that = {},


    /* 
        Initialises the object 
    */
    init = function() {


        return that;
    },
    
    loadCorrectedPerson = function(oid){
        $.ajax({
            type: "GET",
            url: ''+oid,
            dataType: 'json',
            data: {
            },
            success: function(data){
                var correctedPerson = PersonCorrection.PersonModel.init();
                correctedPerson.createFromJson(data);
                $(that).trigger("personLoaded", [correctedPerson]);
            },
            error: function(data){
                if(data.status == 200){
                    //data.responseText
                }
            }
        });
};

    that.init = init;
    that.loadCorrectedPerson = loadCorrectedPerson;
    return that;
})();
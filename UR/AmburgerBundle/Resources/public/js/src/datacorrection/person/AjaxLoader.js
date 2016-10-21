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
                    dataType: 'json'
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);
                    
                    if(jqXHR.status == 200){
                        $(that).trigger("personLoaded", [data['old'], data['new'], data['final']]);
                    } else {
                        MessageHelper.showServerCommunicationFailed();
                    }

                    
                });
            },
                     
            loadWeddingsToCorrect = function () {
                $.ajax({
                    type: "GET",
                    url: 'load/wedding',
                    dataType: 'json'
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);
                    
                    if(jqXHR.status == 200){
                        $(that).trigger("weddingsLoaded", [data['old'], data['new'], data['final']]);
                    } else {
                        MessageHelper.showServerCommunicationFailed();
                    }

                    
                });
            },
            
            saveFinalPerson = function(finalPerson){
                $.ajax({
                    type: "POST",
                    url: 'save',
                    dataType: 'json',
                    data: JSON.stringify(finalPerson)
                }).always(function (data, textStatus, jqXHR) {
                    console.log('always', data);
                    if(data.status == '202'){
                        //@TODO: Handle errors etc.?
                        $(that).trigger("saveFinished");
                    } else {
                        $(that).trigger("errorOccured", data);
                    }
                });
            },
            
            saveWeddings = function(weddings){
                $.ajax({
                    type: "POST",
                    url: 'save/wedding',
                    dataType: 'json',
                    data: JSON.stringify(weddings)
                }).always(function (data, textStatus, jqXHR) {
                    console.log('always', data);
                    if(data.status == '202'){
                        //@TODO: Handle errors etc.?
                        $(that).trigger("weddingSaveFinished");
                    } else {
                        $(that).trigger("errorOccured", data);
                    }
                });
            },
            
            sendGNDRequest = function(searchTerm){
                $.ajax({
                    type: "GET",
                    url: 'gnd/'+searchTerm,
                    dataType: 'json'
                }).always(function (data, textStatus, jqXHR) {
                    console.log('always', data);
                    
                    if(jqXHR.status == 200){
                        $(that).trigger("gndRequestFinished", {results: data });
                    } else {
                        MessageHelper.showServerCommunicationFailed();
                    }
                    
                    
                });
            };

    that.init = init;
    that.loadPersonToCorrect = loadPersonToCorrect;
    that.saveFinalPerson = saveFinalPerson;
    that.loadWeddingsToCorrect = loadWeddingsToCorrect;
    that.saveWeddings =saveWeddings;
    that.sendGNDRequest = sendGNDRequest;
    
    return that;
})();
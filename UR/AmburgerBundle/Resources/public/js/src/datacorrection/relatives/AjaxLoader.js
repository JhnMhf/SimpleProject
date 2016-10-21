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
                    if(jqXHR.status == 200){
                        $(that).trigger("personLoaded", data);
                    } else {
                        MessageHelper.showServerCommunicationFailed();
                    }
                    
                });
            },
    
            loadPossibleRelatives = function(){
                $.ajax({
                    type: "GET",
                    url: 'possible',
                    dataType: 'json'
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);
                    if(jqXHR.status == 200){
                         $(that).trigger("possibleRelativesLoaded", {data: data});
                    } else {
                        MessageHelper.showServerCommunicationFailed();
                    }
                   
                });
            },
            
            loadDirectRelatives = function(){
                $.ajax({
                    type: "GET",
                    url: 'direct',
                    dataType: 'json'
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);
                    if(jqXHR.status == 200){
                        $(that).trigger("directRelativesLoaded", {data: data});
                    } else {
                        MessageHelper.showServerCommunicationFailed();
                    }
                    
                });
            },
            
            createRelationship = function(data){
                $.ajax({
                    type: "PUT",
                    url: 'create',
                    data: JSON.stringify(data)
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);

                    if(jqXHR.status == 202){
                        
                    } else {
                        MessageHelper.showServerCommunicationFailed();
                    }
                    
                });
            },
            
            updateRelationship = function(data){
                $.ajax({
                    type: "POST",
                    url: 'update',
                    data: JSON.stringify(data)
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);

                    if(jqXHR.status == 202){
                        
                    } else {
                        MessageHelper.showServerCommunicationFailed();
                    }
                    
                    
                });
            },
            
            removeRelationship = function(data){
                $.ajax({
                    type: "POST",
                    url: 'remove',
                    data: JSON.stringify(data)
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);

                    if(jqXHR.status == 202){
                        
                    } else {
                        MessageHelper.showServerCommunicationFailed();
                    }
                    
                    
                });
            };
            

    that.init = init;
    that.loadPossibleRelatives = loadPossibleRelatives;
    that.loadDirectRelatives = loadDirectRelatives;
    that.loadPerson = loadPerson;
    that.createRelationship = createRelationship;
    that.updateRelationship = updateRelationship;
    that.removeRelationship = removeRelationship;
    
    return that;
})();
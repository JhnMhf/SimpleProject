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
            },
            
            createRelationship = function(data){
                $.ajax({
                    type: "PUT",
                    url: 'create',
                    data: JSON.stringify(data)
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);

                    
                    
                });
            },
            
            updateRelationship = function(data){
                $.ajax({
                    type: "POST",
                    url: 'update',
                    data: JSON.stringify(data)
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);

                    
                    
                });
            },
            
            removeRelationship = function(data){
                $.ajax({
                    type: "POST",
                    url: 'remove',
                    data: JSON.stringify(data)
                }).always(function (data, textStatus, jqXHR) {
                    console.log(data,textStatus, jqXHR);

                    
                    
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
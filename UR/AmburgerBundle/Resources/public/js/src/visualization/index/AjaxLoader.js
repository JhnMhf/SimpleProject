
Index.AjaxLoader = (function(){
    var that = {},


    /* 
        Initialises the object 
    */
    init = function() {


        return that;
    },
    
    search = function(queryData){
        var queryString = buildQueryString(queryData);
        
        console.log(queryString);
        
        $.ajax({
            type: "GET",
            url: 'search?'+queryString,
            dataType: 'json',
        })
        .always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);
    
            if(jqXHR.status == 200){
                $(that).trigger("idListLoaded", {'data':data});
            } else {
                MessageHelper.showServerCommunicationFailed();
            }

        });
    },
    
    loadPersonListData = function(ids){
        $.ajax({
            type: "POST",
            url: 'search/load',
            dataType: 'json',
            data: JSON.stringify(ids)
        })
        .always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);
    
            if(jqXHR.status == 200){
                $(that).trigger("loadedPersonData", {'persons': data});
            } else {
                MessageHelper.showServerCommunicationFailed();
            }
        });
    },
    
    loadInitialIdList = function(){
        $.ajax({
            type: "GET",
            url: 'visualization/ids/list/',
            dataType: 'json',
        })
        .always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);
    
            if(jqXHR.status == 200){
                $(that).trigger("idListLoadedAll", {'data':data});
            } else {
                MessageHelper.showServerCommunicationFailed();
            }

            
        });
    },
    
    buildQueryString = function(queryData){
        var queryString = "";
        
        var first = true;
        
        for (var key in queryData) {
            if (queryData.hasOwnProperty(key)) {
               console.log(key + " -> " + queryData[key]);
               
               if(queryData[key]){
                    if(!first){
                        queryString +="&";
                    } else {
                        first = false;
                    }

                    queryString +=key+"="+queryData[key];
               }
               
            }
        }
        
        return queryString;
    },
    
    loadLocationDataForAll = function(){
        $.ajax({
            type: "GET",
            url: 'visualization/locations/all/',
            dataType: 'json',
        })
        .always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);

            if(jqXHR.status == 200){
                $(that).trigger("locationsLoaded", {'data':data});
            } else {
                MessageHelper.showServerCommunicationFailed();
            }
            
        });
    },
    
    loadLocationsForPersonList= function(personList){
        $.ajax({
            type: "POST",
            url: 'visualization/locations/ids/',
            dataType: 'json',
            data: JSON.stringify(personList)
        })
        .always(function (data, textStatus, jqXHR) {
            console.log(data,textStatus, jqXHR);
    
            if(jqXHR.status == 200){
                $(that).trigger("locationsLoaded", {'data':data});
            } else {
                MessageHelper.showServerCommunicationFailed();
            }

        });
    };

    that.init = init;
    that.search = search;
    that.loadPersonListData = loadPersonListData;
    that.loadInitialIdList = loadInitialIdList;
    that.loadLocationDataForAll = loadLocationDataForAll;
    that.loadLocationsForPersonList = loadLocationsForPersonList;
    return that;
})();
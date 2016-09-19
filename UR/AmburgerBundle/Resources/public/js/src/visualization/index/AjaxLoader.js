
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

            $(that).trigger("searchResult", {'data':data});
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

           $(that).trigger("loadedPersonData", {'persons': data});
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
    };

    that.init = init;
    that.search = search;
    that.loadPersonListData = loadPersonListData;
    return that;
})();
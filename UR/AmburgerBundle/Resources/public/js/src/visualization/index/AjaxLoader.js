
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
            data: {
            },
            success: function(data){
                //@TODO: Handle json?
                console.log(data);
                $(that).trigger("searchResult");
            },
            error: function(data){
                if(data.status == 200){
                    //data.responseText
                }
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
    };

    that.init = init;
    that.search = search;
    return that;
})();
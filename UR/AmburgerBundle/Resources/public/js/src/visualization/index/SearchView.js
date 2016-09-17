
Index.SearchView = (function(){
    var that = {},
    
    simpleSearchMode = 'simple-search',
    
    extendedSearchMode = 'extended-search',

    activeMode = simpleSearchMode,

    /* 
        Initialises the object 
    */
    init = function() {
        
        $('#search-btn').on("click", searchButtonClicked);

        return that;
    },
    
    searchButtonClicked = function(){
        $(that).trigger('search', {'searchMode': activeMode, 'queryData': extractSearchData()});
    },
    
    extractSearchData = function(){
        if(activeMode == extendedSearchMode){
            return extractDataForExtendedMode();
        }
        return extractDataForSimpleMode();
    },
    
    extractDataForSimpleMode = function(){
        var data = {};
        
        data['searchQuery'] = $("."+simpleSearchMode + " input[name='simple-search']").val();
        data['onlyMainPersons'] = $("."+simpleSearchMode + " input[name='only-main-persons']").is(':checked');
        
        console.log("extractDataForSimpleMode", data);
        
        return data;
    },
    
    extractDataForExtendedMode = function(){
        var data = {};
        
        data['onlyMainPersons'] = $("."+extendedSearchMode + " input[name='only-main-persons']").is(':checked');
        
        console.log("extractDataForExtendedMode", data);
        
        return data;
    },
    
    switchActiveMode = function(){
        
    };
    
    
    that.init = init;
    return that;
})();
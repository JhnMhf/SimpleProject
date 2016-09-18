
Index.IndexController = (function(){
    var that = {},

    /* Views */
    searchView = {},
    mapView = {},
    personListView = {},
    
    /* AjaxLoader */
    ajaxLoader = {},

    /* 
        Initialises the object 
    */
    init = function() {
        
        searchView = Index.SearchView.init();
        
        $(searchView).on("search", onSearch);
        
        mapView = Index.MapView.init();
        personListView = Index.PersonListView.init();
        
        ajaxLoader = Index.AjaxLoader.init();
        $(searchView).on("searchResult", onSearchResult);

        return that;
    },
    
    onSearch = function(event, data){
        console.log("OnSearch: ", data);
        ajaxLoader.search(data);
    },
    
    onSearchResult = function(event,data){
         console.log("OnSearchResult");
    };
    
    
    that.init = init;
    return that;
})();

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

        return that;
    },
    
    onSearch = function(event, data){
        console.log("OnSearch: ", data['searchMode'], data['queryData']);
    };
    
    
    that.init = init;
    return that;
})();

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
        $(personListView).on('loadPersons', onLoadPersons);
        
        ajaxLoader = Index.AjaxLoader.init();
        $(ajaxLoader).on("searchResult", onSearchResult);
        $(ajaxLoader).on("loadedPersonData", onLoadedPersonData);

        return that;
    },
    
    onSearch = function(event, data){
        console.log("OnSearch: ", data);
        ajaxLoader.search(data);
    },
    
    onSearchResult = function(event,data){
         console.log("OnSearchResult: ", data);
         personListView.setPersonListIds(data['data']);
    },
    
    onLoadPersons = function(event, data){
        console.log("onLoadPersons: ", data);
        ajaxLoader.loadPersonListData(data['ids']);
    }
    
    onLoadedPersonData = function(event,data){
         console.log("onLoadedPersonData: ",data);
         personListView.displayPersonData(data['persons']);
    };
    
    
    that.init = init;
    return that;
})();
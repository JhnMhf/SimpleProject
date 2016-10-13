
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
    init = function(googleApiKey) {
        
        searchView = Index.SearchView.init();
        
        $(searchView).on("search", onSearch);
        
        mapView = Index.MapView.init(googleApiKey);
        personListView = Index.PersonListView.init();
        $(personListView).on('loadPersons', onLoadPersons);
        
        ajaxLoader = Index.AjaxLoader.init();
        $(ajaxLoader).on("idListLoaded", onIdListLoaded);
        $(ajaxLoader).on("idListLoadedAll", onIdListLoadedAll);
        $(ajaxLoader).on("loadedPersonData", onLoadedPersonData);
        $(ajaxLoader).on("locationsLoaded", onLocationsLoaded);
        
        Loader.showLoader();
        
        ajaxLoader.loadInitialIdList();

        return that;
    },
    
    onSearch = function(event, data){
        console.log("OnSearch: ", data);
        Loader.showLoader();
        ajaxLoader.search(data);
    },
    
    onIdListLoaded = function(event,data){
         console.log("onIdListLoaded: ", data);
         if(data['data'].length == 0){
            Loader.hideLoader();
            searchView.displayPopup("Es wurden keine Suchergebnisse gefunden.");
         } else {
            personListView.setPersonListIds(data['data']);
            ajaxLoader.loadLocationsForPersonList(data['data']);
         }

    },
    
    onIdListLoadedAll = function(event,data){
        console.log("onIdListLoadedAll: ", data);
        personListView.setPersonListIds(data['data']);
        ajaxLoader.loadLocationDataForAll();
        Loader.hideLoader();
    },
    
    onLocationsLoaded = function(event,data){
        console.log("onLocationsLoaded: ", data);
        mapView.setLocationsData(data['data']);
    },
    
    onLoadPersons = function(event, data){
        console.log("onLoadPersons: ", data);
        ajaxLoader.loadPersonListData(data['ids']);
    }
    
    onLoadedPersonData = function(event,data){
         console.log("onLoadedPersonData: ",data);
         personListView.displayPersonData(data['persons']);
         Loader.hideLoader();
    };
    
    
    that.init = init;
    return that;
})();
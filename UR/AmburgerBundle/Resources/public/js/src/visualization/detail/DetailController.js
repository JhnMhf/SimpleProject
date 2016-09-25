
Detail.DetailController = (function(){
    var that = {},

    /* Views */
    mapView = {},
    
    /* AjaxLoader */
    ajaxLoader = {},
    
    personView = {},
    
    personId = undefined,

    /* 
        Initialises the object 
    */
    init = function(googleApiKey) {
        
        mapView = Detail.MapView.init(googleApiKey);
        
        personView = Detail.PersonView.init();
        
        ajaxLoader = Detail.AjaxLoader.init();
        $(ajaxLoader).on("locationsLoaded", onLocationsLoaded);
        $(ajaxLoader).on("personLoaded", onPersonLoaded);
        
        ajaxLoader.loadPersonData();
        ajaxLoader.loadLocationsForPerson();
        
        return that;
    },
    
    onLocationsLoaded = function(event,data){
        console.log("onLocationsLoaded: ", data);
        mapView.setLocationsData(data['data']);
    },
    
    onPersonLoaded = function(event, data){
        console.log("onPersonLoaded: ", data);
        personView.displayPerson(data);
    };
    
    
    that.init = init;
    return that;
})();
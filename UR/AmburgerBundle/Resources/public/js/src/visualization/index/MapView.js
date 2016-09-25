
Index.MapView = (function(){
    var that = {},
    mapContainerId = 'map-display',

    map = {},
    heatmap = undefined,
    locationsData = {},
    
    googleApiKey = {},

    /* 
        Initialises the object 
    */
    init = function(newGoogleApiKey) {
        googleApiKey = newGoogleApiKey; 
        startMap();

        return that;
    },
    
    setLocationsData = function(newLocationsData){
        console.log("setLocationsData: ", newLocationsData);
        locationsData = newLocationsData;
        
        repositionMap();
        displayHeatMap();
    },
    
    startMap = function(){
      $('body').append('<script src="https://maps.googleapis.com/maps/api/js?key='+googleApiKey+'&callback=Index.MapView.initMap&libraries=visualization&language=de" async defer></script>');  
    },
    
    repositionMap = function(){
        if(locationsData.length > 0){
            var latitude = parseFloat(locationsData[0]['latitude']);
            var longitude = parseFloat(locationsData[0]['longitude']);
            map.setCenter(new google.maps.LatLng(latitude, longitude));
        }
    },
    
    displayHeatMap = function(){
        if(heatmap !== undefined){
            heatmap.setMap(null);
        }
        
        heatmap = new google.maps.visualization.HeatmapLayer({
          data: getPoints(),
          map: map
        });
    },
    
    getPoints = function(){
        var points = [];
        console.log(locationsData, locationsData.length);
        for(var i = 0; i < locationsData.length; i++){
            var latitude = parseFloat(locationsData[i]['latitude']);
            var longitude = parseFloat(locationsData[i]['longitude']);
            for(var j = 0; j < parseInt(locationsData[i]['count']); j++){
                points.push(new google.maps.LatLng(latitude, longitude));
            }
        }

        console.log(points);
        return points;
    },
    
    initMap = function() {
      map = new google.maps.Map(document.getElementById(mapContainerId), {
        center: {lat: 53.9, lng: 27.5667},
        zoom: 4,
        mapTypeId: 'roadmap' //alternative: satellite
      });
      

    };
    
    
    that.init = init;
    that.setLocationsData = setLocationsData;
    that.initMap = initMap;
    return that;
})();
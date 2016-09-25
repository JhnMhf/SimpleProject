
Detail.MapView = (function(){
    var that = {},
    mapContainerId = 'map-display',

    map = {},
    markers = [],
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
        displayMarkers();
    },
    
    startMap = function(){
      $('body').append('<script src="https://maps.googleapis.com/maps/api/js?key='+googleApiKey+'&callback=Detail.MapView.initMap&libraries=visualization&language=de" async defer></script>');  
    },
    
    repositionMap = function(){
        if(locationsData.length > 0){
            var latitude = parseFloat(locationsData[0]['latitude']);
            var longitude = parseFloat(locationsData[0]['longitude']);
            map.setCenter(new google.maps.LatLng(latitude, longitude));
        }
    },
    
    displayMarkers = function(){
        if(markers.length > 0){
            for(var i = 0; i < markers.length; i++){
                markers[i].setMap(null);
            }
        }
        
        markers = [];

        console.log(locationsData, locationsData.length);
        for(var i = 0; i < locationsData.length; i++){
            var latitude = parseFloat(locationsData[i]['latitude']);
            var longitude = parseFloat(locationsData[i]['longitude']);
            var latLng = new google.maps.LatLng(latitude, longitude);
            markers.push(new google.maps.Marker({
                position: latLng,
                map: map,
                title: locationsData[i]['name']
            }));
        }
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
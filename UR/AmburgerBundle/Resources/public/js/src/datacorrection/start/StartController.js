
Start.StartController = (function(){
    var that = {},
    
    /* Views */
    startView = null,
    
    /* Controllers */
    ajaxLoader = null,
    
    /* Variables */
    currentId = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        ajaxLoader = Start.AjaxLoader.init();
        
        $(ajaxLoader).on("personChecked", onPersonChecked);
        $(ajaxLoader).on("nextPerson", onNextPerson);
        $(ajaxLoader).on("noNextPerson", onNoNextPerson);
        $(ajaxLoader).on("workStarted", onWorkStarted);
        
        startView = Start.StartView.init();
        
        $(startView).on("nextPerson", nextPersonClicked);
        $(startView).on("selectedPerson", selectedPersonClicked);
        $(startView).on("startCorrectingNonetheless", startCorrectingNonetheless);
        
        return that;
    },
    
    nextPersonClicked = function(event){
        console.log("nextPersonClicked");
        ajaxLoader.nextPerson();
    },
    
    selectedPersonClicked = function(event, id){
        console.log("selectedPersonClicked", id);
        currentId = id;
        ajaxLoader.checkPerson(currentId);
    },
    
    onPersonChecked = function(event, responseCode){
        console.log("onPersonChecked", responseCode);
        if(responseCode == "200"){
            //everything alright
            ajaxLoader.startWork(currentId);
        } else if (responseCode == "300"){ 
            //already corrected
            startView.showAlreadyCorrectedMessage();
        } else if (responseCode == "404"){ 
            //oid does not exist
            startView.showErrorMessage("Die Person existiert nicht.");
        } else if (responseCode == "409"){ 
            // somebody is already working on it
            startView.showErrorMessage("Die Person wird im Moment bereits bearbeitet.");
        }
        
    },
    
    onNextPerson = function(event, id){
        console.log("onNextPerson", id);
        currentId = id;
        ajaxLoader.startWork(currentId);
    },
    
        
    onNoNextPerson = function(event){
        //display error that all persons already are completly corrected
        console.log("onNoNextPerson");
        startView.showErrorMessage("Es existieren keine weiteren Personen, die noch nicht korrigiert wurden.");
    },
    
    startCorrectingNonetheless = function(){
        console.log("startCorrectingNonetheless");
        ajaxLoader.startWork(currentId);
    },
    
    onWorkStarted = function(event, responseCode){
        console.log("onWorkStarted", responseCode);
        if(responseCode == "200"){
            var currentUrl = window.location.href;
            var newUrl = currentUrl.replace("start", "correction");
            
            if(newUrl.substr(newUrl.length - 1) !== "/"){
                newUrl += "/";
            }
            
            newUrl += currentId +"/duplicate/";
            
            //move to next step
            window.location.href = newUrl;
        } else { 
            //problem during work started
            startView.showErrorMessage("Es gab ein Problem während die Bearbeitung der Person gestartet werden sollte.");
        }
    };


    that.init = init;

    return that;
})();


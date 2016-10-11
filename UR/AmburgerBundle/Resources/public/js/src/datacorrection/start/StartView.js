
Start.StartView = (function(){
    var that = {},

    /* 
        Initialises the object and sets default values.
    */
    init = function() {
       
        $('#next-person-button').on("click", nextPerson);
        
        $('#selected-person-button').on("click", selectedPerson);
        
        return that;
    },
    
    nextPerson = function(){
        $(that).trigger('nextPerson');
    },
    
    selectedPerson = function(){
        var oid = $("#selected-id").val();
        
        $(that).trigger('selectedPerson', [oid]);
    },
    
    showErrorMessage = function(message){
        MessageHelper.showErrorMessage(message);
    },
    
    showAlreadyCorrectedMessage = function(){
        MessageHelper.showYesNoMessage('Die Person wurde bereits korrigiert. Soll die Korrektur trotzdem gestartet werden', 
        'Person wurde bereits korrigiert', that, "startCorrectingNonetheless");
    };


    that.init = init;
    that.showErrorMessage = showErrorMessage;
    that.showAlreadyCorrectedMessage = showAlreadyCorrectedMessage;


    return that;
})();


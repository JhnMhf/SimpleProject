
Start.StartView = (function(){
    var that = {},

    /* 
        Initialises the object and sets default values.
    */
    init = function() {
       
        $('#next-person-button').on("click", nextPerson);
        
        $('#selected-person-button').on("click", selectedPerson);
        
        $(window).on('keypress', onKeyPress);
        
        return that;
    },
    
    onKeyPress = function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        
        console.log("KeyPressed", code);
        //13 is enter
        if(code == 13) { 
           selectedPerson();
        }
    },
    
    nextPerson = function(){
        $(that).trigger('nextPerson');
    },
    
    selectedPerson = function(){
        var idValue = $("#selected-id").val();
        
        var idType = getIDType();
        
        if(idType == 'id'){
            $(that).trigger('selectedPersonByID', [idValue]);
        } else if(idType == 'oid'){
            $(that).trigger('selectedPersonByOID', [idValue]);
        }
       
    },
    
    showErrorMessage = function(message){
        MessageHelper.showErrorMessage(message);
    },
    
    showAlreadyCorrectedMessage = function(){
        MessageHelper.showYesNoMessage('Die Person wurde bereits korrigiert. Soll die Korrektur trotzdem gestartet werden', 
        'Person wurde bereits korrigiert', that, "startCorrectingNonetheless");
    },
    
    getIDType = function(){
        var selected = $("#id-type-container input[type='radio']:checked");
        if (selected.length > 0) {
            return selected.val();
        }
    };


    that.init = init;
    that.showErrorMessage = showErrorMessage;
    that.showAlreadyCorrectedMessage = showAlreadyCorrectedMessage;


    return that;
})();


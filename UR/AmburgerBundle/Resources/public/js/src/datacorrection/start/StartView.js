
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
        var oid = $("#selected-oid").val();
        
        $(that).trigger('selectedPerson', [oid]);
    },
    
    showErrorMessage = function(message){
        $('#dialog-error .message').text(message);
        
        $('#dialog-error').dialog();
    },
    
    showAlreadyCorrectedMessage = function(){
        $( "#dialog-already-corrected" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              "Ja": function() {
                $( this ).dialog( "close" );
                $(that).trigger("startCorrectingNonetheless");
              },
              "Nein": function() {
                $( this ).dialog( "close" );
              }
            }
          });
    };


    that.init = init;
    that.showErrorMessage = showErrorMessage;
    that.showAlreadyCorrectedMessage = showAlreadyCorrectedMessage;


    return that;
})();


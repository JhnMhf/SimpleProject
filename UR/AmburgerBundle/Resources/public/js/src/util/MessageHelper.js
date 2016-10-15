MessageHelper = (function () {
    var that = {},
    
    showInfoMessage = function(message, title,callbackObj, trigger){
        $('#dialog-info .message').text(message);
        
        if(title){
            $('#dialog-info').attr('title', title);
        }
        
        $( "#dialog-info" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              "Ok": function() {
                $( this ).dialog( "close" );
                if(callbackObj && trigger){
                    $(callbackObj).trigger(trigger);
                }
              }
            }
          });
    },
    
    showErrorMessage = function(message, title,callbackObj, trigger){
        $('#dialog-error .message').text(message);
        
        if(title){
            $('#dialog-error').attr('title', title);
        }
        
        $( "#dialog-error" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              "Ok": function() {
                $( this ).dialog( "close" );
                if(callbackObj && trigger){
                    $(callbackObj).trigger(trigger);
                }
              }
            }
          });
    },
    
    showYesNoMessage = function(message,title, callbackObj, yesTrigger, noTrigger){
        $('#dialog-yes-no .message').text(message);
        
        if(title){
            $('#dialog-yes-no').attr('title', title);
        }
        
        $( "#dialog-yes-no" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              "Ja": function() {
                $( this ).dialog( "close" );
                if(callbackObj && yesTrigger){
                    $(callbackObj).trigger(yesTrigger);
                }
              },
              "Nein": function() {
                $( this ).dialog( "close" );
                if(callbackObj && noTrigger){
                    $(callbackObj).trigger(noTrigger);
                }
              }
            }
          });
    };
    
    that.showErrorMessage = showErrorMessage;
    that.showInfoMessage = showInfoMessage;
    that.showYesNoMessage = showYesNoMessage;
    
    return that;
})();


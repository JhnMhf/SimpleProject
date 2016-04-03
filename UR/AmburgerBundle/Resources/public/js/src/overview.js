$('#load-btn').click(function (){
   var idType = getIDType();
   
   console.log("IDType: ", idType);
   
   var idValue = $("#id-value").val();
   
   console.log("IDValue: ", idValue);

   loadDataFromServer(idType, idValue);
});


function getIDType(){
    var selected = $("#idType-container input[type='radio']:checked");
    if (selected.length > 0) {
        return selected.val();
    }
}

function loadDataFromServer(idType, idValue){
    var url = "http://127.0.0.1:8000/new/json/" + idType + "/" + idValue;
    console.log("URL: ", url);
    
    $.ajax({
        url: url
    }).done(function( data ) {
        if ( console && console.log ) {
          console.log( "Data: ", data);
        }
        appendToDocument(JSON.stringify(data));
    });
}


function appendToDocument(data){
    console.log("Append data?");
    $('#person-data').html(data);
}
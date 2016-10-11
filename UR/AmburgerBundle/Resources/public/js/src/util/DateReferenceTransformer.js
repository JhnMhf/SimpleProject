DateReferenceTransformer = (function () {
    var that = {},
    
    dateReferenceToString = function(dateReference){
        if(typeof(dateReference) === 'undefined'){
            return "";
        }

        var dateReferenceString = "";

        for(var i = 0; i < dateReference.length; i++){
            if(i != 0){
                dateReferenceString += ",";
            }

            if(typeof(dateReference[i]['from']) !== 'undefined') {
                dateReferenceString += dateRangeToString(dateReference[i]);
            }else {
                dateReferenceString += dateToString(dateReference[i]);
            }
        }

        return dateReferenceString;
    },
            
    dateRangeToString = function(dateRangeObj){
       return dateToString(dateRangeObj['from']) + "-" + dateToString(dateRangeObj['to']); 
    },

    dateToString = function(dateObj){
       var dateString = "";

       if(dateObj['before_date']){
           dateString += "vor ";
       } 
       
       if(dateObj['after_date']){
           dateString += "nach ";
       } 

       if(typeof(dateObj['day']) !== 'undefined'){
           dateString += dateObj['day'];
       } 
       dateString += "/";

       if(typeof(dateObj['month']) !== 'undefined'){
           dateString += dateObj['month'];
       } 
       dateString += "/";

       if(typeof(dateObj['year']) !== 'undefined'){
           dateString += dateObj['year'];
       } 

       return dateString;
    };
    
    that.dateReferenceToString = dateReferenceToString;
    
    return that;
})();


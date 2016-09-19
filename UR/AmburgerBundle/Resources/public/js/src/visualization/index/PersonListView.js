
Index.PersonListView = (function(){
    var that = {},

    personListModel = {},

    /* 
        Initialises the object 
    */
    init = function() {
        personListModel = Index.PersonListModel.init();

        return that;
    },
    
    setPersonListIds = function(ids){
        personListModel.setIds(ids);
        triggerPersonLoad();
    },
    
    displayPersonData = function(personData){
        console.log('displayPersonData: ', personData);
        
        $('#personDataList').empty();
        
        for(var i = 0; i < personData.length; i++){
            displayPerson(personData[i]);
        }
    },
    
    displayPerson = function(person){
        console.log(person);
        var template = _.template($("script#personDataTemplate").html());

        var data = preparePersonData(person);

        $('#personDataList').append(template(data));
    },
    
    preparePersonData = function(person){
        var preparedData = [];
        
        preparedData['first_name'] = person['first_name']; 
        preparedData['patronym'] = person['patronym']; 
        preparedData['last_name'] = person['last_name'];
        preparedData['birth_date'] = dateReferenceToString(person['birth_date']);
        preparedData['baptism_date'] = dateReferenceToString(person['baptism_date']);
        preparedData['death_date'] = dateReferenceToString(person['death_date']);
        preparedData['funeral_date'] = dateReferenceToString(person['funeral_date']);
        preparedData['type'] = person['type'];
        preparedData['id'] = person['id'];
    
        return preparedData;
    },
    
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
            dateString += "-";
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
        
        if(dateObj['after_date']){
            dateString += "-";
        } 
        
        return dateString;
    },
    
    triggerPersonLoad = function(){
        var idsForCurrentPage = personListModel.getIdsForCurrentPage();
        console.log("IdsForCurrentPage: ", idsForCurrentPage)
        $(that).trigger('loadPersons', {'ids': idsForCurrentPage});
    };
    
    
    that.init = init;
    that.setPersonListIds = setPersonListIds;
    that.displayPersonData = displayPersonData;
    return that;
})();
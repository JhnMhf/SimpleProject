/* 
 An object used for retrieving information with ajax from the backend.
 */

RelativesCorrection.RelativesView = (function () {
    var that = {},
    
    dateReferenceTransformer = {},
    
    personData = undefined,
            /* 
             Initialises the object 
             */
            init = function () {
                dateReferenceTransformer = DateReferenceTransformer;
            
                return that;
            },
            
            displayDirectRelatives = function(data){
                console.log('displayDirectRelatives', data, personData);
                
                internalDisplayDirectRelatives(data, 'parents');
                internalDisplayDirectRelatives(data, 'children');
                internalDisplayDirectRelatives(data, 'siblings');
                internalDisplayDirectRelatives(data, 'marriagePartners');
                
            },
            
            internalDisplayDirectRelatives = function(data, identifier){
                for(var i = 0; i < data[identifier].length; i++){
                    var template = _.template($("script#directRelationTemplate").html());
                    
                    var relativeReference = data[identifier][i]['person'];

                    var templateData = [];
                    templateData['person_first_name'] = personData['first_name'];
                    templateData['person_last_name'] = personData['last_name'];
                    templateData['person_patronym'] = personData['patronym'];
                    templateData['person_birth_date'] = extractBirthDate(personData);
                    templateData['person_baptism_date'] = extractBaptismDate(personData);
                    templateData['person_death_date'] = extractDeathDate(personData);
                    templateData['person_funeral_date'] = extractFuneralDate(personData);
                    
                    
                    templateData['relative_first_name'] = relativeReference['first_name'];
                    templateData['relative_last_name'] = relativeReference['last_name'];
                    templateData['relative_patronym'] = relativeReference['patronym'];
                    templateData['relative_birth_date'] = extractBirthDate(relativeReference);
                    templateData['relative_baptism_date'] = extractBaptismDate(relativeReference);
                    templateData['relative_death_date'] = extractDeathDate(relativeReference);
                    templateData['relative_funeral_date'] = extractFuneralDate(relativeReference);


                    $(".existing-relations-container").append(template(templateData));
                }
            },
            
            extractBirthDate = function(data){
                if(data['birth']){
                  return dateReferenceTransformer.dateReferenceToString(data['birth']['birth_date']);
                }
                return "";
            },
            
            extractBaptismDate = function(data){
                if(data['baptism']){
                  return dateReferenceTransformer.dateReferenceToString(data['baptism']['baptism_date']);
                }
                return "";
            },
            
            extractDeathDate = function(data){
                if(data['death']){
                  return dateReferenceTransformer.dateReferenceToString(data['death']['death_date']);
                }
                return "";
            },
            
            extractFuneralDate = function(data){
                if(data['death']){
                  return dateReferenceTransformer.dateReferenceToString(data['death']['funeral_date']);
                }
                return "";
            },
            
            displayPossibleRelatives = function(data){
                console.log('displayPossibleRelatives', data);
            },
            
            setPersonData = function(data){
                personData = data;
            };
            

    that.init = init;
    that.displayDirectRelatives = displayDirectRelatives;
    that.displayPossibleRelatives = displayPossibleRelatives;
    that.setPersonData = setPersonData;
    
    return that;
})();
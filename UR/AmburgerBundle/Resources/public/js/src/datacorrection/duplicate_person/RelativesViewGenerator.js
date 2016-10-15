
DuplicatePerson.RelativesViewGenerator = (function(){
    var that = {},
    dateReferenceTransformer = {},
    
    /* 
     Initialises the object 
     */
    init = function () {
        dateReferenceTransformer = DateReferenceTransformer;

        return that;
    },
    
    generateTemplate = function(relativesData){
        var data = {};
        
        data['parents'] = generateRelativesTemplate(relativesData['parents'], 'Eltern');
        data['children'] = generateRelativesTemplate(relativesData['children'], 'Kind');
        data['siblings'] = generateRelativesTemplate(relativesData['siblings'], 'Geschwister');
        data['partners'] = generateRelativesTemplate(relativesData['marriagePartners'], 'Ehepartner');

        var template = _.template($("script#fullRelativesTemplate").html());
        
        return template(data);
    },
    
    generateRelativesTemplate = function(relativeData, relationType){
       var fullTemplateForIdentifier = "";
       for(var i = 0; i < relativeData.length; i++){
           var relativeReference = relativeData[i]['person'];
           fullTemplateForIdentifier += getPersonDataTemplate(relativeReference, relationType);
        }
        
        return fullTemplateForIdentifier;
    },
    
    getPersonDataTemplate = function(data, relationType){
        var template = _.template($("script#personDataTemplate").html());

        var templateData = [];

        templateData['id'] = data['ID'];
        templateData['relation_type'] = relationType;
        templateData['first_name'] = data['first_name'];
        templateData['last_name'] = data['last_name'];
        templateData['patronym'] = data['patronym'];
        templateData['gender'] = extractGender(data);
        templateData['birth_date'] = extractBirthDate(data);
        templateData['baptism_date'] = extractBaptismDate(data);
        templateData['death_date'] = extractDeathDate(data);
        templateData['funeral_date'] = extractFuneralDate(data);
        templateData['job'] = extractJob(data);
        templateData['job_class'] = extractJobClass(data);
        templateData['nation'] = extractNation(data);
        templateData['educations'] = extractEducations(data);

        return template(templateData);
    },
    
    extractGender = function(data){
      switch(data['gender']){
            case 0:
                return "keine Angabe";
            case 1:
                return "männlich";
            case 2:
                return "weiblich";
        }
        return "";

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

    extractJob = function(data){
        if(data['job']){
            return data['job']['label'];
        }
        return "";
    },

    extractJobClass = function(data){
        if(data['job_class']){
            return data['job_class']['label'];
        }
        return "";
    },

    extractNation = function(data){
        if(data['nation']){
            return data['nation']['name'];
        }
        return "";
    },

    extractEducations = function(data){
        if(data['educations']){
            var educationsString = "";
            for(var i = 0; i < data['educations'].length; i++){
                if(data['educations'][i]['label'] !== undefined){
                    if(educationsString !== ""){
                        educationsString += ",";
                    }
                    
                    educationsString += data['educations'][i]['label'];
                }
            }

            return educationsString;
        }

        return "";
    };
   

    that.init = init;
    that.generateTemplate = generateTemplate;

    return that;
})();


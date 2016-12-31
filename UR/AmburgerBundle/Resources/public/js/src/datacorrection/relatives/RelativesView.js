/* 
 An object used for retrieving information with ajax from the backend.
 */

RelativesCorrection.RelativesView = (function () {
    var that = {},
    
    dateReferenceTransformer = {},
    
    personData = undefined,
    personDataTemplate = undefined,
    /* 
     Initialises the object 
     */
    init = function () {
        dateReferenceTransformer = DateReferenceTransformer;

        return that;
    },
     
    /* interaction */
    onEdit = function(){
        console.log('onEdit',$(this));
        if($(this).parent().parent().hasClass('relation-disabled')){
            $(this).parent().parent().removeClass('relation-disabled');
            $(this).parent().find('select').attr('disabled', false);
        } else {
           $(this).parent().parent().addClass('relation-disabled');
            $(this).parent().find('select').attr('disabled', true);
        }
        
    },
    
    onAccept = function(){
        console.log('onAccept',$(this));
        var rowContainer = $(this).closest('.row');
        
        console.log(rowContainer);
        
        var data = extractRelationData(rowContainer);
        
        console.log(data);
        
        if(data['currentRelation'] == ""){
            //@TODO: show box that this is not possible
            return;
        }
        
        var relationsContainer = $(this).closest('.container');
        
        if($(relationsContainer).hasClass('existing-relations-container')){
            if(data['currentRelation'] == data['originRelation']){
                //@TODO: the same relationtype? show error
                return;
            }
            
            $(that).trigger('relationUpdated', data);
            
            //overwrite origin relation
            $(rowContainer).find('input[name="relationType"]').val(data['currentRelation']);
        } else {
            $(that).trigger('relationCreated', data);
        }
        
    },
    
    onRemove = function(){
        console.log('onRemove',$(this));
        var rowContainer = $(this).closest('.row');
        
        console.log(rowContainer);
        
        var relationsContainer = $(this).closest('.container');
        
        if($(relationsContainer).hasClass('existing-relations-container')){
            var data = extractRelationData(rowContainer);

            console.log(data);

            $(that).trigger('relationRemoved', data);
        }
        
        $(rowContainer).hide();
    },
    
    extractRelationData = function(rowContainer){
        var data = {};
        data['personId'] = $(rowContainer).find('input[name="personID"]').val();
        data['relativeId'] = $(rowContainer).find('input[name="relativeID"]').val();
        data['personGender'] = $(rowContainer).find('input[name="personGender"]').val();
        data['relativeGender'] = $(rowContainer).find('input[name="relativeGender"]').val();
        data['originRelation'] = $(rowContainer).find('input[name="relationType"]').val();
        data['currentRelation'] = $(rowContainer).find('select[name="selectRelationType"] option:selected').attr('name');
        
        return data;
    }
    
    /* collapsible */
    

    /* inserting data */
    displayDirectRelatives = function(data){
        console.log('displayDirectRelatives', data, personData);

        internalDisplayDirectRelatives(data, 'parents');
        internalDisplayDirectRelatives(data, 'children');
        internalDisplayDirectRelatives(data, 'siblings');
        internalDisplayDirectRelatives(data, 'marriagePartners');

        $('#existing-relations > .container .edit-relation').on('click', onEdit);
        $('#existing-relations > .container .remove-relation').on('click', onRemove);
        $('#existing-relations > .container .accept-relation').on('click', onAccept);
    },

    displayPossibleRelatives = function(data){
        console.log('displayPossibleRelatives', data);
        internalDisplayPossibleRelatives(data);
        
        $('#possible-relations > .container .remove-relation').on('click', onRemove);
        $('#possible-relations > .container .accept-relation').on('click', onAccept);
        
        Loader.hideLoader();
    },

    internalDisplayDirectRelatives = function(data, identifier){
        for(var i = 0; i < data[identifier].length; i++){
            var template = _.template($("script#directRelationTemplate").html());

            var relativeReference = data[identifier][i]['person'];

            var templateData = [];

            templateData['personId'] = personData['id'];
            templateData['relativeId'] = relativeReference['id'];
            templateData['personGender'] = personData['gender'];
            templateData['relativeGender'] = relativeReference['gender'];
            
            templateData['relation'] = getRelationshipTestBasedOnIdentifier(identifier);
            templateData['relation_comment'] = extractComment(data[identifier][i]['relation']);

            templateData['personData'] = personDataTemplate;

            templateData['relativeData'] = getPersonDataTemplate(relativeReference);

            $(".existing-relations-container").append(template(templateData));
        }
    },
    
    extractComment = function(relation){
        console.log(relation);
        
        if(relation['comment'] !== undefined){
            return relation['comment'];
        } else if(relation['is_parent_comment'] !== undefined){
            return relation['is_parent_comment'];
        } else if(relation['is_parent_in_law_comment'] !== undefined){
            return relation['is_parent_in_law_comment'];
        } else if(relation['is_sibling_comment'] !== undefined){
            return relation['is_sibling_comment'];
        } else if(relation['is_grandparent_comment'] !== undefined){
            return relation['is_grandparent_comment'];
        } else if(relation['wedding_comment'] !== undefined){
            return relation['wedding_comment'];
        } else if(relation['marriage_comment'] !== undefined){
            return relation['marriage_comment'];
        } 
        
        return "";
    },

    getRelationshipTestBasedOnIdentifier = function(identifier){
        switch(identifier){
            case 'parents':
                return "parent";
            case 'children':
                return "child";
            case 'siblings':
                return "sibling";
            case 'marriagePartners':
                return "marriagePartner";
        }
        return "";
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
    },

    internalDisplayPossibleRelatives = function(data){
        for(var i = 0; i < data.length; i++){
            var template = _.template($("script#possibleRelationTemplate").html());

            var relativeReference = data[i];

            var templateData = [];
            templateData['personId'] = personData['id'];
            templateData['relativeId'] = relativeReference['id'];
            templateData['personGender'] = personData['gender'];
            templateData['relativeGender'] = relativeReference['gender'];

            templateData['personData'] = personDataTemplate;

            templateData['relativeData'] = getPersonDataTemplate(relativeReference);

            templateData['relation_suggestion'] = extractRelationSuggestion(relativeReference);

            $(".possible-relations-container").append(template(templateData));
        }
    },

    extractRelationSuggestion = function(relativeReference){
        var suggestion = "";
        if(personData['birth'] && relativeReference['birth']){
            suggestion = relationSuggestionBasedOnDate(personData['birth']['birth_date'],relativeReference['birth']['birth_date']);
        }

        if(suggestion == "" && personData['baptism'] && relativeReference['baptism']){
            suggestion = relationSuggestionBasedOnDate(personData['baptism']['baptism_date'],relativeReference['baptism']['baptism_date']);
        }

        return suggestion;  
    },

    relationSuggestionBasedOnDate = function(personDate, relativeDate){
        var personYears = extractDateYears(personDate);
        var relativeYears = extractDateYears(relativeDate);

        if(personYears.length == 0 || relativeYears.length == 0){
            return "";
        }

        if(personYears[0] < relativeYears[0]){
            var diff = relativeYears[0] - personYears[0];

            if(diff < 20){
                return "sibling";
            } else if(diff < 50){
                return "child";
            } else {
                return "";
            }
        } else if(personYears[0] > relativeYears[0]){
            var diff = personYears[0] - relativeYears[0];

            if(diff < 20){
                return "sibling";
            } else if(diff < 50){
                return "parent";
            } else {
                return "";
            }
        } else {
            return "sibling";
        }

        return "";
    },

    extractDateYears = function(dateReference){
        if(typeof(dateReference) === 'undefined'){
            return [];
        }

        var years = [];

        for(var i = 0; i < dateReference.length; i++){
            if(typeof(dateReference[i]['from']) !== 'undefined') {
                if(dateReference[i]['from']['year'] && years.indexOf(dateReference[i]['from']['year']) == -1){
                    years.push(dateReference[i]['from']['year']);
                }

                if(dateReference[i]['to']['year'] && years.indexOf(dateReference[i]['to']['year']) == -1){
                    years.push(dateReference[i]['to']['year']);
                }
            }else {
                if(dateReference[i]['year'] && years.indexOf(dateReference[i]['year']) == -1){
                    years.push(dateReference[i]['year']);
                }
            }
        }

        return years;
    },

    getPersonDataTemplate = function(data){
        var template = _.template($("script#personDataTemplate").html());

        var templateData = [];

        templateData['id'] = data['ID'];
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

    setPersonData = function(data){
        personData = data;

        personDataTemplate = getPersonDataTemplate(personData);
    };


    that.init = init;
    that.displayDirectRelatives = displayDirectRelatives;
    that.displayPossibleRelatives = displayPossibleRelatives;
    that.setPersonData = setPersonData;
    
    return that;
})();
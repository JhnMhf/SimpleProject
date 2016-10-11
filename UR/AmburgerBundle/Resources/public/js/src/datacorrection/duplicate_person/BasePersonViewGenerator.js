
DuplicatePerson.BasePersonViewGenerator = (function(){
    var that = {},
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {

        return that;
    },
    
    generateTemplate = function(personData){
        var enabled = false;
        
        var template = "";
        
        console.log("Building person for:",personData, enabled);
        template += generateTemplateForBasePerson(personData, enabled);
        
        if(personData['baptism'] !== undefined){
            template += generateTemplateForBaptism(personData['baptism'], enabled);
        }
        
        
        if(personData['birth'] !== undefined){
            template +=  generateTemplateForBirth(personData['birth'], enabled);
        }
        
        if(personData['death'] !== undefined){
            template += generateTemplateForDeath(personData['death'] , enabled);
        }
        
        template += generateTemplateForEducations(personData['educations'], enabled);
        template += generateTemplateForHonours(personData['honours'], enabled);
        template += generateTemplateForProperties(personData['properties'], enabled);
        template += generateTemplateForRanks(personData['ranks'], enabled);
        template += generateTemplateForReligion(personData['religions'], enabled);
        template += generateTemplateForResidence(personData['residences'], enabled);
        template += generateTemplateForRoadOfLife(personData['road_of_life'], enabled);
        template += generateTemplateForSource(personData['sources'], enabled);
        template += generateTemplateForStatus(personData['stati'], enabled);
        template += generateTemplateForWorks(personData['works'], enabled);
        
        return template;
    },
    
    generateTemplateForBasePerson = function(personData, enabled){
        var template = _.template($("script#basePerson").html());

        var data = personData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
        
    generateTemplateForBaptism = function(baptismData, enabled){
        var template = _.template($("script#baptism").html());

        var data = baptismData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
    
    generateTemplateForBirth = function(birthData, enabled){
        var template = _.template($("script#birth").html());

        var data = birthData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
    
    generateTemplateForDeath = function(deathData, enabled){
        var template = _.template($("script#death").html());

        var data = deathData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
       return template(data);
    },
    
    generateTemplateForEducations = function(educationsData, enabled){
        var template = _.template($("script#education").html());

        var data = [];
        
        data['educations'] = educationsData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
    
    generateTemplateForHonours = function(honoursData, enabled){
        var template = _.template($("script#honour").html());

        var data = [];
        
        data['honours'] = honoursData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
    
    generateTemplateForProperties = function(propertiesData, enabled){
        var template = _.template($("script#property").html());

        var data = [];
        
        data['properties'] = propertiesData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
    
    generateTemplateForRanks = function(ranksData, enabled){
        var template = _.template($("script#rank").html());

        var data = [];
        
        data['ranks'] = ranksData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
       
    generateTemplateForReligion = function(religionData, enabled){
        var template = _.template($("script#religion").html());

        var data = [];
        
        data['religions'] = religionData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
    
    generateTemplateForResidence = function(residenceData, enabled){
        var template = _.template($("script#residence").html());

        var data = [];
        
        data['residences'] = residenceData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
    
    generateTemplateForRoadOfLife = function(roadOfLifeData, enabled){
        var template = _.template($("script#roadOfLife").html());

        var data = [];
        
        data['roadOfLifes'] = roadOfLifeData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
    
    generateTemplateForSource = function(sourceData, enabled){
        var template = _.template($("script#source").html());

        var data = [];
        
        data['sources'] = sourceData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
    
    generateTemplateForStatus = function(statusData, enabled){
        var template = _.template($("script#status").html());

        var data = [];
        
        data['stati'] = statusData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    },
    
    generateTemplateForWorks = function(worksData, enabled){
        var template = _.template($("script#works").html());

        var data = [];
        
        data['works'] = worksData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        return template(data);
    };

    that.init = init;
    that.generateTemplate = generateTemplate;

    return that;
})();


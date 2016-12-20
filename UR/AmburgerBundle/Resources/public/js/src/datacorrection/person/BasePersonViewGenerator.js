
PersonCorrection.BasePersonViewGenerator = (function(){
    var that = {},
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {

        return that;
    },
    
    //@TODO: Add information about wedding partner
    displayWeddings = function(insertId, weddingData, enabled){
        if(enabled === undefined){
            enabled = false;
        }

        console.log("Building weddings for:",insertId, weddingData, enabled);
        
        var template = _.template($("script#wedding").html());

        var data = [];
        
        data['weddings'] = extractWeddingData(weddingData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .wedding-container").append(template(data));
    },
    
    extractWeddingData = function(weddingData){
        var weddingDataArray = weddingData['weddings'];
        var personDataArray = weddingData['personData'];
        
        
        for(var i = 0; i < weddingDataArray.length; i++){
            var weddingDataObj = weddingDataArray[i];
            var personDataObj = personDataArray[i];
            
            weddingDataObj['first_name'] = personDataObj['first_name'];
            weddingDataObj['last_name'] = personDataObj['last_name'];
        }
        
        
        return weddingDataArray;
    },
    
    displayPerson = function(insertId, personData, enabled){
        if(enabled === undefined){
            enabled = false;
        }
        
        console.log("Building person for:",insertId, personData, enabled);
        displayBasePerson(insertId,personData, enabled);
        
        displayBaptism(insertId, personData['baptism'], enabled);
        
        if(personData['birth'] !== undefined){
            displayBirth(insertId, personData['birth'], enabled);
        }
        
        if(personData['death'] !== undefined){
            displayDeath(insertId, personData['death'] , enabled);
        }
        
        displayEducations(insertId, personData['educations'], enabled);
        displayHonours(insertId, personData['honours'], enabled);
        displayProperties(insertId, personData['properties'], enabled);
        displayRanks(insertId, personData['ranks'], enabled);
        displayReligion(insertId, personData['religions'], enabled);
        displayResidence(insertId, personData['residences'], enabled);
        displayRoadOfLife(insertId, personData['road_of_life'], enabled);
        displaySource(insertId, personData['sources'], enabled);
        displayStatus(insertId, personData['stati'], enabled);
        displayWorks(insertId, personData['works'], enabled);
    },
    
    displayBasePerson = function(insertId, personData, enabled){
        var template = _.template($("script#basePerson").html());

        var data = personData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId + " .base-person-container").append(template(data));
    },
        
    displayBaptism = function(insertId, baptismData, enabled){
        var template = _.template($("script#baptism").html());

        var data = [];

        if(baptismData !== undefined){
            data = baptismData;
        }

        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId + " .baptism-container").append(template(data));
    },
    
    displayBirth = function(insertId, birthData, enabled){
        var template = _.template($("script#birth").html());

        var data = birthData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId + " .birth-container").append(template(data));
    },
    
    displayDeath = function(insertId, deathData, enabled){
        var template = _.template($("script#death").html());

        var data = deathData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .death-container").append(template(data));
    },
    
    displayEducations = function(insertId, educationsData, enabled){
        var template = _.template($("script#education").html());

        var data = [];
        
        data['educations'] = educationsData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .educations-container").append(template(data));
    },
    
    displayHonours = function(insertId, honoursData, enabled){
        var template = _.template($("script#honour").html());

        var data = [];
        
        data['honours'] = honoursData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .honours-container").append(template(data));
    },
    
    displayProperties = function(insertId, propertiesData, enabled){
        var template = _.template($("script#property").html());

        var data = [];
        
        data['properties'] = propertiesData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .properties-container").append(template(data));
    },
    
    displayRanks = function(insertId, ranksData, enabled){
        var template = _.template($("script#rank").html());

        var data = [];
        
        data['ranks'] = ranksData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .rank-container").append(template(data));
    },
       
    displayReligion = function(insertId, religionData, enabled){
        var template = _.template($("script#religion").html());

        var data = [];
        
        data['religions'] = religionData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .religion-container").append(template(data));
    },
    
    displayResidence = function(insertId, residenceData, enabled){
        var template = _.template($("script#residence").html());

        var data = [];
        
        data['residences'] = residenceData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .residence-container").append(template(data));
    },
    
    displayRoadOfLife = function(insertId, roadOfLifeData, enabled){
        var template = _.template($("script#roadOfLife").html());

        var data = [];
        
        data['roadOfLifes'] = roadOfLifeData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .road-of-life-container").append(template(data));
    },
    
    displaySource = function(insertId, sourceData, enabled){
        var template = _.template($("script#source").html());

        var data = [];
        
        data['sources'] = sourceData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .source-container").append(template(data));
    },
    
    displayStatus = function(insertId, statusData, enabled){
        var template = _.template($("script#status").html());

        var data = [];
        
        data['stati'] = statusData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .status-container").append(template(data));
    },
    
    displayWorks = function(insertId, worksData, enabled){
        var template = _.template($("script#works").html());

        var data = [];
        
        data['works'] = worksData;
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .works-container").append(template(data));
    };

    that.init = init;
    that.displayPerson = displayPerson;
    that.displayWeddings = displayWeddings;

    return that;
})();


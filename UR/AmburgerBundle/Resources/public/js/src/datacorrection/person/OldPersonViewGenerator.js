
PersonCorrection.OldPersonViewGenerator = (function(){
    var that = {},
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {

        return that;
    },
    
    displayPerson = function(insertId, personData, enabled){
        if(enabled === undefined){
            enabled = false;
        }
        
        console.log("Building person for:",insertId, personData, enabled);
        displayBasePerson(insertId,personData, enabled);

        if(personData['herkunft'] !== undefined){
            displayBaptism(insertId, personData['herkunft'], enabled);
        }
        
        if(personData['herkunft'] !== undefined){
            displayBirth(insertId, personData['herkunft'], enabled);
        }
        
        if(personData['tod'] !== undefined){
            displayDeath(insertId, personData['tod'] , enabled);
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
        var template = _.template($("script#basePerson-old").html());

        var data = extractPersonDataForBasePerson(personData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId + " .base-person-container").append(template(data));
    },
    
    extractPersonDataForBasePerson = function(personData){
        var data = [];
        
        data['oid'] = personData['oid'];
        data['first_name'] = personData['person']['vornamen'];
        data['patronym'] = personData['person']['russ_vornamen'];
        data['last_name'] = personData['person']['name'];
        data['fore_name'] = personData['person']['rufname'];
        data['birth_name'] = personData['person']['geburtsname'];
        data['gender'] = personData['person']['geschlecht'];
        data['job'] = personData['person']['beruf'];
        data['born_in_marriage'] = personData['person']['ehelich'];
        data['jobclass'] = personData['person']['berufsklasse'];
        data['nation'] = personData['person']['ursp_nation'];
        data['comment'] = personData['person']['kommentar'];
        
        return data;
    },
        
    displayBaptism = function(insertId, baptismData, enabled){
        var template = _.template($("script#baptism-old").html());

        var data = extractPersonDataForBaptism(baptismData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId + " .baptism-container").append(template(data));
    },
    
    extractPersonDataForBaptism = function(baptismData){
        var data = [];
        
        data['baptism_location'] = baptismData['taufort'];
        data['baptism_date'] = baptismData['getauft'];
        
        return data;
    },
    
    displayBirth = function(insertId, birthData, enabled){
        var template = _.template($("script#birth-old").html());

        var data = extractPersonDataForBirth(birthData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId + " .birth-container").append(template(data));
    },
    
    extractPersonDataForBirth = function(birthData){
        var data = [];
        
        data['origin_location'] = birthData['herkunftsort'];
        data['origin_territory'] = birthData['herkunftsterritorium'];
        data['origin_country'] = birthData['herkunftsland'];
        data['birth_location'] = birthData['geburtsort'];
        data['birth_territory'] = birthData['geburtsterritorium'];
        data['birth_country'] = birthData['geburtsland'];
        data['birth_date'] = birthData['geboren'];
        data['comment'] = birthData['kommentar'];
        
        return data;
    },
    
    
    displayDeath = function(insertId, deathData, enabled){
        var template = _.template($("script#death-old").html());

        var data = extractPersonDataForDeath(deathData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .death-container").append(template(data));
    },
    
    extractPersonDataForDeath = function(deathData){
        var data = [];
        
        data['death_location'] = deathData['todesort'];
        data['death_date'] = deathData['gestorben'];        
        data['cause_of_death'] = deathData['todesursache'];        
        data['death_territory'] = deathData['todesterritorium'];        
        data['graveyard'] = deathData['friedhof'];
        data['funeral_location'] = deathData['begräbnisort'];
        data['funeral_date'] = deathData['begraben'];
        data['death_country'] = deathData['todesland'];
        data['comment'] = deathData['kommentar'];
        
        return data;
    },
    
    displayEducations = function(insertId, educationsData, enabled){
        var template = _.template($("script#education-old").html());

        var data = [];
        
        data['educations'] = extractPersonDataForEducations(educationsData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .educations-container").append(template(data));
    },
     
    extractPersonDataForEducations = function(educationsData){
        var data = [];
        

        
        return data;
    },
    
    displayHonours = function(insertId, honoursData, enabled){
        var template = _.template($("script#honour-old").html());

        var data = [];
        
        data['honours'] = extractPersonDataForHonours(honoursData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .honours-container").append(template(data));
    },
     
    extractPersonDataForHonours = function(honoursData){
        var data = [];
        

        
        return data;
    },
    
    displayProperties = function(insertId, propertiesData, enabled){
        var template = _.template($("script#property-old").html());

        var data = [];
        
        data['properties'] = extractPersonDataForProperties(propertiesData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .properties-container").append(template(data));
    },
    
    extractPersonDataForProperties = function(propertiesData){
        var data = [];
        

        
        return data;
    },
    
    displayRanks = function(insertId, ranksData, enabled){
        var template = _.template($("script#rank-old").html());

        var data = [];
        
        data['ranks'] = extractPersonDataForRanks(ranksData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .rank-container").append(template(data));
    },
    
    extractPersonDataForRanks = function(ranksData){
        var data = [];
        

        
        return data;
    },
       
    displayReligion = function(insertId, religionData, enabled){
        var template = _.template($("script#religion-old").html());

        var data = [];
        
        data['religions'] = extractPersonDataForReligion(religionData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .religion-container").append(template(data));
    },
    
    extractPersonDataForReligion = function(religionData){
        var data = [];
        

        
        return data;
    },
    
    displayResidence = function(insertId, residenceData, enabled){
        var template = _.template($("script#residence-old").html());

        var data = [];
        
        data['residences'] = extractPersonDataForResidence(residenceData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .residence-container").append(template(data));
    },
    
    extractPersonDataForResidence = function(residenceData){
        var data = [];
        

        
        return data;
    },
    
    displayRoadOfLife = function(insertId, roadOfLifeData, enabled){
        var template = _.template($("script#roadOfLife-old").html());

        var data = [];
        
        data['roadOfLifes'] = extractPersonDataForRoadOfLife(roadOfLifeData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .road-of-life-container").append(template(data));
    },
    
    extractPersonDataForRoadOfLife = function(roadOfLifeData){
        var data = [];
        

        
        return data;
    },
    
    displaySource = function(insertId, sourceData, enabled){
        var template = _.template($("script#source").html());

        var data = [];
        
        data['sources'] = extractPersonDataForSource(sourceData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .source-container").append(template(data));
    },
    
    extractPersonDataForSource = function(sourceData){
        var data = [];
        

        
        return data;
    },
    
    displayStatus = function(insertId, statusData, enabled){
        var template = _.template($("script#status-old").html());

        var data = [];
        
        data['stati'] = extractPersonDataForStatus(statusData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .status-container").append(template(data));
    },
    
    extractPersonDataForStatus = function(statusData){
        var data = [];
        

        
        return data;
    },
    
    displayWorks = function(insertId, worksData, enabled){
        var template = _.template($("script#works-old").html());

        var data = [];
        
        data['works'] = extractPersonDataForWorks(worksData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .works-container").append(template(data));
    },
    
    extractPersonDataForWorks = function(worksData){
        var data = [];
        

        
        return data;
    };

    that.init = init;
    that.displayPerson = displayPerson;

    return that;
})();


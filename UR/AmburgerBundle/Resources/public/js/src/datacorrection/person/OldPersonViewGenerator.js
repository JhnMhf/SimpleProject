
PersonCorrection.OldPersonViewGenerator = (function(){
    var that = {},
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {

        return that;
    },
    
    displayWeddings = function(insertId, weddingData, enabled){
        if(enabled === undefined){
            enabled = false;
        }
        console.log("Building weddings for:",insertId, weddingData, enabled);
        
        var template = _.template($("script#wedding-old").html());

        var data = [];
        
        data['weddings'] = extractWeddingData(weddingData);
        
        if(enabled){
            data['enabled'] = true;
        }
        
        $(insertId  + " .wedding-container").append(template(data));
    },
    
    extractWeddingData = function(weddingData){
        var data = [];
        
        for(var i = 0; i < weddingData.length; i++){
            var weddingElement = {};
            
            weddingElement['last_name'] = weddingData[i]['name'];
            weddingElement['first_name'] = weddingData[i]['vornamen'];
            weddingElement['wedding_order'] = weddingData[i]['order'];
            weddingElement['wedding_territory'] = weddingData[i]['hochzeitsterritorium'];
            weddingElement['wedding_location'] = weddingData[i]['hochzeitsort'];
            weddingElement['wedding_date'] = weddingData[i]['hochzeitstag'];
            weddingElement['banns_date'] = weddingData[i]['aufgebot'];
            weddingElement['breakup_reason'] = weddingData[i]['gelöst'];
            weddingElement['breakup_date'] = weddingData[i]['auflösung'];
            weddingElement['marriage_comment'] = weddingData[i]['verheiratet'];
            weddingElement['before_after'] = weddingData[i]['vorher-nachher'];
            
            data[i] = weddingElement;
        }
        
        return data;
    },
    
    displayPerson = function(insertId, personData, enabled){
        if(enabled === undefined){
            enabled = false;
        }
        
        console.log("Building person for:",insertId, personData, enabled);
        displayBasePerson(insertId,personData, enabled);

        if(personData['herkunft'] !== undefined){
            displayBaptism(insertId, personData['herkunft'], enabled);
            displayBirth(insertId, personData['herkunft'], enabled);
        }
        
        if(personData['tod'] !== undefined){
            displayDeath(insertId, personData['tod'] , enabled);
        }
        displayEducations(insertId, personData['ausbildung'], enabled);
        displayHonours(insertId, personData['ehre'], enabled);
        displayProperties(insertId, personData['eigentum'], enabled);
        displayRanks(insertId, personData['rang'], enabled);
        displayReligion(insertId, personData['religion'], enabled);
        
        if(personData['wohnung'] !== undefined){
            displayResidence(insertId, personData['wohnung'], enabled);
        }
        
        displayRoadOfLife(insertId, personData['lebensweg'], enabled);
        displaySource(insertId, personData['quellen'], enabled);
        displayStatus(insertId, personData['status'], enabled);
        displayWorks(insertId, personData['werke'], enabled);
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
        
        for(var i = 0; i < educationsData.length; i++){
            var educationElement = [];
            
            educationElement['label'] = educationsData[i]['ausbildung'];
            educationElement['country'] = educationsData[i]['land'];
            educationElement['territory'] = educationsData[i]['territorium'];
            educationElement['location'] = educationsData[i]['ort'];
            educationElement['from_date'] = educationsData[i]['von-ab'];
            educationElement['to_date'] = educationsData[i]['bis'];
            educationElement['proven_date'] = educationsData[i]['belegt'];
            educationElement['graduation_label'] = educationsData[i]['bildungsabschluss'];
            educationElement['graduation_date'] = educationsData[i]['bildungsabschlussdatum'];
            educationElement['graduation_location'] = educationsData[i]['bildungsabschlussort'];
            educationElement['comment'] = educationsData[i]['kommentar'];
            
            data[i] = educationElement;
        }
        
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
        
        for(var i = 0; i < honoursData.length; i++){
            var honoursElement = [];
            
            honoursElement['label'] = honoursData[i]['ehren'];
            honoursElement['country'] = honoursData[i]['land'];
            honoursElement['territory'] = honoursData[i]['territorium'];
            honoursElement['location'] = honoursData[i]['ort'];
            honoursElement['from_date'] = honoursData[i]['von-ab'];
            honoursElement['to_date'] = honoursData[i]['bis'];
            honoursElement['proven_date'] = honoursData[i]['belegt'];
            honoursElement['comment'] = honoursData[i]['kommentar'];
            
            data[i] = honoursElement;
        }
        
        
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
        
        for(var i = 0; i < propertiesData.length; i++){
            var propertiesElement = [];
            
            propertiesElement['label'] = propertiesData[i]['besitz'];
            propertiesElement['country'] = propertiesData[i]['land'];
            propertiesElement['territory'] = propertiesData[i]['territorium'];
            propertiesElement['location'] = propertiesData[i]['ort'];
            propertiesElement['from_date'] = propertiesData[i]['von-ab'];
            propertiesElement['to_date'] = propertiesData[i]['bis'];
            propertiesElement['proven_date'] = propertiesData[i]['belegt'];
            propertiesElement['comment'] = propertiesData[i]['kommentar'];
            
            data[i] = propertiesElement;
        }
        
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
        
        for(var i = 0; i < ranksData.length; i++){
            var ranksElement = [];
            
            ranksElement['label'] = ranksData[i]['rang'];
            ranksElement['class'] = ranksData[i]['rangklasse'];
            ranksElement['country'] = ranksData[i]['land'];
            ranksElement['territory'] = ranksData[i]['territorium'];
            ranksElement['location'] = ranksData[i]['ort'];
            ranksElement['from_date'] = ranksData[i]['von-ab'];
            ranksElement['to_date'] = ranksData[i]['bis'];
            ranksElement['proven_date'] = ranksData[i]['belegt'];
            ranksElement['comment'] = ranksData[i]['kommentar'];
            
            data[i] = ranksElement;
        }
        
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
        
        for(var i = 0; i < religionData.length; i++){
            var religionElement = [];
            
            religionElement['name'] = religionData[i]['konfession'];
            religionElement['change_of_religion'] = religionData[i]['konversion'];
            religionElement['from_date'] = religionData[i]['von-ab'];
            religionElement['to_date'] = religionData[i]['bis'];
            religionElement['comment'] = religionData[i]['kommentar'];
            
            data[i] = religionElement;
        }
        
        
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
        

        for(var i = 0; i < residenceData.length; i++){
            var residenceElement = [];
            
            residenceElement['residence_country'] = residenceData[i]['wohnland'];
            residenceElement['residence_territory'] = residenceData[i]['wohnterritorium'];
            residenceElement['residence_location'] = residenceData[i]['wohnort'];
            
            data[i] = residenceElement;
        }
        
        
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
        
        for(var i = 0; i < roadOfLifeData.length; i++){
            var roadOfLifeElement = [];
            
            roadOfLifeElement['origin_country'] = roadOfLifeData[i]['stammland'];
            roadOfLifeElement['origin_territory'] = roadOfLifeData[i]['stammterritorium'];
            roadOfLifeElement['job'] = roadOfLifeData[i]['beruf'];
            roadOfLifeElement['country'] = roadOfLifeData[i]['land'];
            roadOfLifeElement['territory'] = roadOfLifeData[i]['territorium'];
            roadOfLifeElement['location'] = roadOfLifeData[i]['ort'];
            roadOfLifeElement['from_date'] = roadOfLifeData[i]['von-ab'];
            roadOfLifeElement['to_date'] = roadOfLifeData[i]['bis'];
            roadOfLifeElement['proven_date'] = roadOfLifeData[i]['belegt'];
            roadOfLifeElement['comment'] = roadOfLifeData[i]['kommentar'];
            
            data[i] = roadOfLifeElement;
        }
        
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
        

        for(var i = 0; i < sourceData.length; i++){
            var sourceElement = [];
            
            sourceElement['label'] = sourceData[i]['bezeichnung'];
            sourceElement['place_of_discovery'] = sourceData[i]['fundstelle'];
            sourceElement['remark'] = sourceData[i]['bemerkung'];
            sourceElement['comment'] = sourceData[i]['kommentar'];
            
            data[i] = sourceElement;
        }
        
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
        

        for(var i = 0; i < statusData.length; i++){
            var statusElement = [];
            
            statusElement['label'] = statusData[i]['stand'];
            statusElement['country'] = statusData[i]['land'];
            statusElement['territory'] = statusData[i]['territorium'];
            statusElement['location'] = statusData[i]['ort'];
            statusElement['from_date'] = statusData[i]['von-ab'];
            statusElement['to_date'] = statusData[i]['bis'];
            statusElement['proven_date'] = statusData[i]['belegt'];
            statusElement['comment'] = statusData[i]['kommentar'];
            
            data[i] = statusElement;
        }
        
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

        for(var i = 0; i < worksData.length; i++){
            var worksElement = [];
            
            worksElement['label'] = worksData[i]['werke'];
            worksElement['country'] = worksData[i]['land'];
            worksElement['territory'] = worksData[i]['territorium'];
            worksElement['location'] = worksData[i]['ort'];
            worksElement['from_date'] = worksData[i]['von-ab'];
            worksElement['to_date'] = worksData[i]['bis'];
            worksElement['proven_date'] = worksData[i]['belegt'];
            worksElement['comment'] = worksData[i]['kommentar'];
            
            data[i] = worksElement;
        }
        
        return data;
    };

    that.init = init;
    that.displayPerson = displayPerson;
    that.displayWeddings = displayWeddings;

    return that;
})();


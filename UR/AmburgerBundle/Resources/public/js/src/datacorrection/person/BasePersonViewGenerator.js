
PersonCorrection.BasePersonViewGenerator = (function(){
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
        if(personData['baptism'] !== undefined){
            displayBaptism(insertId, personData['baptism'], enabled);
        }
        
        if(personData['baptism'] !== undefined){
            displayBaptism(insertId, personData['baptism'], enabled);
        }
        
        if(personData['birth'] !== undefined){
            displayBirth(insertId, personData['birth'], enabled);
        }
        
        if(personData['death'] !== undefined){
            displayDeath(insertId, personData['death'] , enabled);
        }
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

        var data = baptismData;
        
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
    };

    that.init = init;
    that.displayPerson = displayPerson;

    return that;
})();


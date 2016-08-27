
PersonCorrection.FinalPersonView = (function(){
    var that = {},
    
    finalPersonData = null,
    
    personViewGenerator = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        personViewGenerator = PersonCorrection.BasePersonViewGenerator.init();
        return that;
    },
    
    displayPerson = function(personData){
        finalPersonData = personData;
        personViewGenerator.displayPerson("#final", personData, true);
        
    },
    
    extractPersonData = function(){
        console.log("basePerson", extractBasePerson());
        console.log("baptism", extractBaptism());
        console.log("birth", extractBirth());
        console.log("death", extractDeath());
        console.log("educations", extractEducations());
        return finalPersonData;
    },
    
    extractBasePerson = function(){
        var baseIdentifier = '#final .base-person-container';
        
        var person = {};
        person['first_name'] = $(baseIdentifier + ' input[name="firstName"]').val();
        person['patronym'] = $(baseIdentifier + ' input[name="patronym"]').val();
        person['last_name'] = $(baseIdentifier + ' input[name="lastName"]').val();
        person['fore_name'] = $(baseIdentifier + ' input[name="foreName"]').val();
        person['birth_name'] = $(baseIdentifier + ' input[name="birthName"]').val();
        person['gender'] = parseInt($(baseIdentifier + ' select[name="gender"] option:selected').val());
        person['gender_comment'] = $(baseIdentifier + ' input[name="genderComment"]').val();
        person['born_in_marriage'] = $(baseIdentifier + ' input[name="bornInMarriage"]').val();
        
        var jobObj = extractJobObj(baseIdentifier);
        
        if(jobObj !== undefined){
            person['job'] = jobObj;
        }
                
        var jobClassObj = extractJobClassObj(baseIdentifier);
        
        if(jobClassObj !== undefined){
            person['job_class'] = jobClassObj;
        }
        
        
        var nationObj = extractNationObj(baseIdentifier);
        
        if(nationObj !== undefined){
            person['nation'] = nationObj;
        }
        
        person['comment'] = $(baseIdentifier + ' input[name="comment"]').val();


        
        return person;
    },
    
    extractBaptism = function(){
        var baseIdentifier = '#final .baptism-container';
        
        var baptism = {};
        

        baptism['baptism_location'] = extractLocationObj(baseIdentifier);
        baptism['baptism_date'] = extractDateReferenceObj(baseIdentifier);

        return baptism;
    },
    
    extractBirth = function(){
        var baseIdentifier = '#final .birth-container';
        
        var birth = {};

        birth['origin_country'] = extractCountryObj(baseIdentifier + ' .origin-country' );
        birth['origin_territory'] = extractTerritoryObj(baseIdentifier + ' .origin-territory' );
        birth['origin_location'] = extractLocationObj(baseIdentifier + ' .origin-location' );
        birth['birth_country'] = extractCountryObj(baseIdentifier + ' .country' );
        birth['birth_territory'] = extractTerritoryObj(baseIdentifier + ' .territory' );
        birth['birth_location'] = extractLocationObj(baseIdentifier + ' .location' );
        birth['birth_date'] = extractDateReferenceObj(baseIdentifier + ' .birth-date' );
        birth['comment'] = $(baseIdentifier + ' input[name="comment"]').val();
        
        return birth;
    },
    
    extractDeath = function(){
        var baseIdentifier = '#final .death-container';
        
        var death = {};
        
        death['death_country'] = extractCountryObj(baseIdentifier + ' .country' );
        death['death_territory'] = extractTerritoryObj(baseIdentifier + ' .territory' );
        death['death_location'] = extractLocationObj(baseIdentifier + ' .location' );
        death['death_date'] = extractDateReferenceObj(baseIdentifier + ' .death-date' );
        death['cause_of_death'] = $(baseIdentifier + ' input[name="causeOfDeath"]').val();
        death['graveyard'] = $(baseIdentifier + ' input[name="graveyard"]').val();
        death['funeral_location'] = extractLocationObj(baseIdentifier + ' .funeral-location' );
        death['funeral_date'] = extractDateReferenceObj(baseIdentifier + ' .funeral-date' );
        death['comment'] = $(baseIdentifier + ' input[name="comment"]').val();
        
        return death;
    },
    
    extractEducations = function(){
        var baseIdentifier = '#final .educations-container';
        
        var educations = {};
        
        var educationRows = $(baseIdentifier + ' .education-row');
        
        for(var i = 0; i < educationRows.length; i++){
            
            educations[i]= extractEducationObj(educationRows[i]);
        }
        
        return educations;
    },
    
    extractEducationObj = function(element){
        console.log("Education: ", element, $(element));
        
        var $element = $(element);
        
        var educationObj = {};
        
        educationObj['label'] = $element.find('input[name="label"]').val();
        educationObj['country'] = extractCountryObj($element.find('.country'));
        educationObj['territory'] = extractTerritoryObj($element.find('.territory'));
        educationObj['location'] = extractLocationObj($element.find('.location'));
        educationObj['from_date'] = extractDateReferenceObj($element.find('.from-date'));
        educationObj['to_date'] = extractDateReferenceObj($element.find('.to-date'));
        educationObj['proven_date'] = extractDateReferenceObj($element.find('.proven-date'));
        educationObj['graduation_label'] = $element.find('input[name="graduationLabel"]').val();
        educationObj['graduation_location'] = extractLocationObj($element.find('.graduation-location'));
        educationObj['graduation_date'] = extractDateReferenceObj($element.find('.graduation-date'));
        educationObj['comment'] = $element.find('input[name="comment"]').val();
        
        return educationObj;
    },
    
    extractNationObj = function(identifier) {
        if($.type(identifier) === "string"){
            //if it is an identifier
            if($(identifier + " .nation-container ").length > 0){
                var nationObj = {};

                nationObj['name'] = $(identifier + ' .nation-container input[name="name"]').val();
                nationObj['comment'] = $(identifier + ' .nation-container input[name="comment"]').val();

                return nationObj;
            }
        } else{
            //if it is an element
            if($(identifier).find('.nation-container').length > 0){
                var nationObj = {};

                nationObj['name'] = $(identifier).find('.nation-container input[name="name"]').val();
                nationObj['comment'] = $(identifier).find('.nation-container input[name="comment"]').val();

                return nationObj;
            }
        }
        
    },
    
    extractJobObj = function(identifier) {
        if($.type(identifier) === "string"){
            if($(identifier + " .job-container ").length > 0){
                var jobObj = {};

                jobObj['label'] = $(identifier + ' .job-container input[name="label"]').val();
                jobObj['comment'] = $(identifier + ' .job-container input[name="comment"]').val();

                return jobObj;
            }
        }else {
            if($(identifier).find('.job-container').length > 0){
                var jobObj = {};

                jobObj['label'] = $(identifier).find('.job-container input[name="label"]').val();
                jobObj['comment'] = $(identifier).find('.job-container input[name="comment"]').val();

                return jobObj;
            }
        }
    },
    
    extractJobClassObj = function(identifier) {
        if($.type(identifier) === "string"){
            if($(identifier + " .job-class-container ").length > 0){
                var jobObj = {};

                jobObj['label'] = $(identifier + ' .job-class-container input[name="label"]').val();
                jobObj['comment'] = $(identifier + ' .job-class-container input[name="comment"]').val();

                return jobObj;
            }
        }else {
            if($(identifier).find('.country-container').length > 0){
                var jobObj = {};

                jobObj['label'] = $(identifier).find('.job-class-container input[name="label"]').val();
                jobObj['comment'] = $(identifier).find('.job-class-container input[name="comment"]').val();

                return jobObj;
            }
        }
    },
    
    extractDateReferenceObj = function(identifier) {
        if($.type(identifier) === "string"){
            //if it is an identifier
            var children = $(identifier + " .date-reference-container").children();

            console.log("DateReferences: ", children);

            if(children.length > 0){
                var dateReferenceArray = [];

                for(var i = 0; i < children.length; i++){
                    var childElement = children[i];

                    if(childElement.className.indexOf('date-range-container') !== -1){
                        //date range found
                        dateReferenceArray[i] = extractDateRangeObj(childElement);
                    } else {
                        //normal date found
                        dateReferenceArray[i] = extractDateObj(childElement);
                    }
                }

                return dateReferenceArray;
            }
        } else {
           var children = $(identifier).children(".date-reference-container");

            console.log("DateReferences: ", children);
            
            //if it is an element
            if(children.length > 0){
                var dateReferenceArray = [];

                for(var i = 0; i < children.length; i++){
                    var childElement = children[i];

                    if(childElement.className.indexOf('date-range-container') !== -1){
                        //date range found
                        dateReferenceArray[i] = extractDateRangeObj(childElement);
                    } else {
                        //normal date found
                        dateReferenceArray[i] = extractDateObj(childElement);
                    }
                }

                return dateReferenceArray;
            }
        }
    },
    
    extractDateRangeObj = function(element) {
        console.log("DateRange: ", element, $(element));
        
        var $element = $(element);
        var children = $element.children();
        
        var dateRangeObj = {};
        
        dateRangeObj['from'] = extractDateObj(children[0]);
        dateRangeObj['to'] = extractDateObj(children[1]);
        
        return dateRangeObj;
    },
        
    extractDateObj = function(element) {
        console.log("Date: ", element, $(element));
        
        var $element = $(element);
        
        var dateObj = {};
        
        dateObj['day'] = $element.find('input[name="day"]').val();
        dateObj['month'] = $element.find('input[name="month"]').val();
        dateObj['year'] = $element.find('input[name="year"]').val();
        dateObj['before_date'] = $element.find('input[name="beforeDate"]').val();
        dateObj['after_date'] = $element.find('input[name="afterDate"]').val();
        dateObj['comment'] = $element.find('input[name="comment"]').val();
        
        return dateObj;
    },
    
    extractCountryObj = function(identifier) { 
        if($.type(identifier) === "string"){
            //if it is an identifier
            if($(identifier + " .country-container").length > 0){
                var countryObj = {};

                countryObj['name'] = $(identifier + ' .country-container input[name="name"]').val();
                countryObj['comment'] = $(identifier + ' .country-container input[name="comment"]').val();

                return countryObj;
            }
        } else {
            if($(identifier).find('.country-container').length > 0){
                var countryObj = {};

                countryObj['name'] = $(identifier).find('.country-container input[name="name"]').val();
                countryObj['comment'] = $(identifier).find('.country-container input[name="comment"]').val();

                return countryObj;
            }
        }
    },
    
    extractTerritoryObj = function(identifier) {
        if($.type(identifier) === "string"){
            //if it is an identifier
            if($(identifier + " .territory-container").length > 0){
                var territoryObj = {};

                territoryObj['name'] = $(identifier + ' .territory-container input[name="name"]').val();
                territoryObj['comment'] = $(identifier + ' .territory-container input[name="comment"]').val();

                return territoryObj;
            }
        }else {
            if($(identifier).find('.territory-container').length > 0){
                var territoryObj = {};

                territoryObj['name'] = $(identifier).find('.territory-container input[name="name"]').val();
                territoryObj['comment'] = $(identifier).find('.territory-container input[name="comment"]').val();

                return territoryObj;
            }
        }
    },
    
    extractLocationObj = function(identifier) {
        if($.type(identifier) === "string"){
            //if it is an identifier
            if($(identifier + " .location-container").length > 0){
                var locationObj = {};

                locationObj['name'] = $(identifier + ' .location-container input[name="name"]').val();
                locationObj['comment'] = $(identifier + ' .location-container input[name="comment"]').val();

                return locationObj;
            }
        }else {
            if($(identifier).find('.location-container').length > 0){
                var locationObj = {};

                locationObj['name'] = $(identifier).find('.location-container input[name="name"]').val();
                locationObj['comment'] = $(identifier).find('.location-container input[name="comment"]').val();

                return locationObj;
            }
        }
    };


    that.init = init;
    that.displayPerson = displayPerson;
    that.extractPersonData = extractPersonData;

    return that;
})();


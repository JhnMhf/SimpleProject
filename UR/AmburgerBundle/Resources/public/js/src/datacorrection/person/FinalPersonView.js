function DateException(message) {
   this.message = message;
   this.name = "DateException";
}


PersonCorrection.FinalPersonView = (function () {
            var that = {},
            personViewGenerator = null,
            
            $gndRequestContainer = null,
            
            gndRequestSearchTerm = null,
            
            /* 
             Initialises the object and sets default values.
             */
            init = function () {
                personViewGenerator = PersonCorrection.BasePersonViewGenerator.init();
                return that;
            },
            displayPerson = function (personData) {
                personViewGenerator.displayPerson("#final", personData, true);
                registerOnGNDButtons();
                registerDateCheckboxListener();
            },
            displayWeddings = function (weddingData) {
                personViewGenerator.displayWeddings("#final", weddingData, true);
                registerOnGNDButtons();
                registerDateCheckboxListener();
            },
            
            registerOnGNDButtons = function(){
                $('button.gnd-request').off('click');
                $('button.gnd-request').on('click', onGNDButtonClicked);
            },
            
            registerDateCheckboxListener = function(){
                $('.date-container input[name="beforeDate"]').off('change');
                $('.date-container input[name="afterDate"]').off('change');
                $('.date-container input[name="beforeDate"]').on('change', onBeforeDateCheckboxChanged);
                $('.date-container input[name="afterDate"]').on('change', onAfterDateCheckboxChanged);
            },
            
            onAfterDateCheckboxChanged = function(){
                console.log("AfterDate changed: ", $(this));
                if($(this).is(':checked')){
                    console.log("Checkbox is checked, so we need to find the matching BeforeDate");
                    var beforeDate = $(this).parent().find('input[name="beforeDate"]');
                    console.log("BeforeDate: ", beforeDate);
                    if($(beforeDate).is(':checked')){
                        console.log("Removing checked from beforeDate");
                        $(beforeDate).removeAttr("checked");
                    }
                }
            },
            
            onBeforeDateCheckboxChanged = function(){
                console.log("BeforeDate changed: ", $(this));
                if($(this).is(':checked')){
                    console.log("Checkbox is checked, so we need to find the matching AfterDate");
                    var afterDate = $(this).parent().find('input[name="afterDate"]');
                    console.log("BeforeDate: ", afterDate);
                    if($(afterDate).is(':checked')){
                        console.log("Removing checked from afterDate");
                        $(afterDate).removeAttr("checked");
                    }
                }
            },
            
            onGNDButtonClicked = function(){
                console.log('GND Button clicked: ', $(this));
                
                $gndRequestContainer = $($(this).parent());
                gndRequestSearchTerm = $gndRequestContainer.find("input[name='name']").val();
                
                console.log('Searching for ', gndRequestSearchTerm,' from ' , $gndRequestContainer);
                
                $(that).trigger('sendGNDRequest', gndRequestSearchTerm);
            },
            
            displayGNDResult = function(result){
                console.log('GND Result: ', result);
                
                prepareGNDPopup(result);
                
                displayGNDPopup();
            },
            
            prepareGNDPopup = function(result){
                var template = _.template($("script#gnd-popup-template").html());

                var data = [];

                data['suggestions'] = result;

                $("#dialog-gnd").html(template(data));
                
                $('.gnd-accept').on('click', onAcceptGNDSuggestion);
            },
            
            displayGNDPopup = function(){
                $( "#dialog-gnd" ).dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    dialogClass: 'dialog-gnd-window',
                    buttons: {
                      "Abbrechen": function() {
                        $( this ).dialog( "close" );
                      }
                    }
                });
            },
            
            onAcceptGNDSuggestion = function(){
                $( "#dialog-gnd" ).dialog('close');
                
                
                console.log($(this).parent().parent());
                console.log($(this).parent().parent().find('td.gnd-value'));
                
                var acceptedSuggestion = $(this).parent().parent().find('td.gnd-value').html();
                
                console.log('Accepted Suggestion: ', acceptedSuggestion);
                
                $gndRequestContainer.find("input[name='name']").val(acceptedSuggestion);
            }
            
            extractWeddingData = function(){
              var weddingData = extractArrayData('wedding','wedding-container','wedding-row')
              
              console.log("Extracted WeddingData: ", weddingData);
              
              return weddingData;
            },
            
            extractPersonData = function () {
                var basePerson = extractBasePerson();
                basePerson['baptism'] = extractBaptism();
                basePerson['birth'] = extractBirth();
                basePerson['death'] = extractDeath();
                basePerson['educations'] = extractArrayData('education','educations-container','education-row');
                basePerson['honours'] = extractArrayData('honour','honours-container','honour-row');
                basePerson['properties'] = extractArrayData('property','properties-container','property-row');
                basePerson['ranks'] = extractArrayData('rank','rank-container','rank-row');
                basePerson['religions'] = extractArrayData('religion','religion-container','religion-row');
                basePerson['residences'] = extractArrayData('residence','residence-container','residence-row');
                basePerson['road_of_life'] = extractArrayData('road_of_life','road-of-life-container','road-of-life-row');
                basePerson['sources'] = extractArrayData('source','source-container','source-row');
                basePerson['stati'] = extractArrayData('status','status-container','status-row');
                basePerson['works'] = extractArrayData('works','works-container','works-row');

                console.log("extractedPerson", basePerson);
                console.log('json', JSON.stringify(basePerson));

                return basePerson;
            },
            extractBasePerson = function () {
                var baseIdentifier = '#final .base-person-container';

                var person = {};
                person['id'] = parseInt($(baseIdentifier + ' input[name="id"]').val());
                person['oid'] = parseInt($(baseIdentifier + ' input[name="oid"]').val());
                person['first_name'] = $(baseIdentifier + ' input[name="firstName"]').val();
                person['patronym'] = $(baseIdentifier + ' input[name="patronym"]').val();
                person['last_name'] = $(baseIdentifier + ' input[name="lastName"]').val();
                person['fore_name'] = $(baseIdentifier + ' input[name="foreName"]').val();
                person['birth_name'] = $(baseIdentifier + ' input[name="birthName"]').val();
                person['gender'] = parseInt($(baseIdentifier + ' select[name="gender"] option:selected').val());
                person['gender_comment'] = $(baseIdentifier + ' input[name="genderComment"]').val();
                person['born_in_marriage'] = $(baseIdentifier + ' input[name="bornInMarriage"]').val();

                var jobObj = extractJobObj(baseIdentifier);

                if (jobObj !== undefined) {
                    person['job'] = jobObj;
                }

                var jobClassObj = extractJobClassObj(baseIdentifier);

                if (jobClassObj !== undefined) {
                    person['job_class'] = jobClassObj;
                }


                var nationObj = extractNationObj(baseIdentifier);

                if (nationObj !== undefined) {
                    person['nation'] = nationObj;
                }

                person['comment'] = $(baseIdentifier + ' input[name="comment"]').val();



                return person;
            },
            extractBaptism = function () {
                var baseIdentifier = '#final .baptism-container';

                var baptism = {};

                baptism['id'] = parseInt($(baseIdentifier + ' input[name="id"]').val());
                baptism['baptism_location'] = extractLocationObj(baseIdentifier);
                baptism['baptism_date'] = extractDateReferenceObj(baseIdentifier);

                return baptism;
            },
            extractBirth = function () {
                var baseIdentifier = '#final .birth-container';

                var birth = {};

                birth['id'] = parseInt($(baseIdentifier + ' input[name="id"]').val());
                birth['origin_country'] = extractCountryObj(baseIdentifier + ' .origin-country');
                birth['origin_territory'] = extractTerritoryObj(baseIdentifier + ' .origin-territory');
                birth['origin_location'] = extractLocationObj(baseIdentifier + ' .origin-location');
                birth['birth_country'] = extractCountryObj(baseIdentifier + ' .country');
                birth['birth_territory'] = extractTerritoryObj(baseIdentifier + ' .territory');
                birth['birth_location'] = extractLocationObj(baseIdentifier + ' .location');
                birth['birth_date'] = extractDateReferenceObj(baseIdentifier + ' .birth-date');
                birth['comment'] = $(baseIdentifier + ' input[name="comment"]').val();

                return birth;
            },
            extractDeath = function () {
                var baseIdentifier = '#final .death-container';

                var death = {};

                death['id'] = parseInt($(baseIdentifier + ' input[name="id"]').val());
                death['death_country'] = extractCountryObj(baseIdentifier + ' .country');
                death['death_territory'] = extractTerritoryObj(baseIdentifier + ' .territory');
                death['death_location'] = extractLocationObj(baseIdentifier + ' .location');
                death['death_date'] = extractDateReferenceObj(baseIdentifier + ' .death-date');
                death['cause_of_death'] = $(baseIdentifier + ' input[name="causeOfDeath"]').val();
                death['graveyard'] = $(baseIdentifier + ' input[name="graveyard"]').val();
                death['funeral_location'] = extractLocationObj(baseIdentifier + ' .funeral-location');
                death['funeral_date'] = extractDateReferenceObj(baseIdentifier + ' .funeral-date');
                death['comment'] = $(baseIdentifier + ' input[name="comment"]').val();

                return death;
            },
            extractArrayData = function (type, containerClass, rowClass) {
                var baseIdentifier = '#final .' + containerClass;

                //@TODO: Changed from {} to [], does still everything work?
                var array = [];

                var rows = $(baseIdentifier + ' .' + rowClass);

                for (var i = 0; i < rows.length; i++) {

                    switch (type) {
                        case 'education':
                            array[i] = extractEducationObj(rows[i]);
                            break;
                        case 'honour':
                            array[i] = extractHonourObj(rows[i]);
                            break;
                        case 'property':
                            array[i] = extractPropertyObj(rows[i]);
                            break;
                        case 'rank':
                            array[i] = extractRankObj(rows[i]);
                            break;
                        case 'religion':
                            array[i] = extractReligionObj(rows[i]);
                            break;
                        case 'residence':
                            array[i] = extractResidenceObj(rows[i]);
                            break;
                        case 'road_of_life':
                            array[i] = extractRoadOfLifeObj(rows[i]);
                            break;
                        case 'source':
                            array[i] = extractSourceObj(rows[i]);
                            break;
                        case 'status':
                            array[i] = extractStatusObj(rows[i]);
                            break;
                        case 'works':
                            array[i] = extractWorksObj(rows[i]);
                            break;
                        case 'wedding':
                            array[i] = extractWeddingObj(rows[i]);
                    }

                }

                return array;
            },
           
            extractEducationObj = function (element) {
                console.log("Education: ", element, $(element));

                var $element = $(element);

                var educationObj = {};

                educationObj['id'] = parseInt($element.find('input[name="id"]').val());
                educationObj['education_order'] = parseInt($element.find('input[name="order"]').val());
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
           
            extractHonourObj = function (element) {
                console.log("Honour: ", element, $(element));

                var $element = $(element);

                var honourObj = {};
                
                honourObj['id'] = parseInt($element.find('input[name="id"]').val());
                honourObj['honour_order'] = parseInt($element.find('input[name="order"]').val());
                honourObj['label'] = $element.find('input[name="label"]').val();
                honourObj['country'] = extractCountryObj($element.find('.country'));
                honourObj['territory'] = extractTerritoryObj($element.find('.territory'));
                honourObj['location'] = extractLocationObj($element.find('.location'));
                honourObj['from_date'] = extractDateReferenceObj($element.find('.from-date'));
                honourObj['to_date'] = extractDateReferenceObj($element.find('.to-date'));
                honourObj['proven_date'] = extractDateReferenceObj($element.find('.proven-date'));
                honourObj['comment'] = $element.find('input[name="comment"]').val();

                return honourObj;
            },
            
            extractPropertyObj = function (element) {
                console.log("Property: ", element, $(element));

                var $element = $(element);

                var propertyObj = {};

                propertyObj['id'] = parseInt($element.find('input[name="id"]').val());
                propertyObj['property_order'] = parseInt($element.find('input[name="order"]').val());
                propertyObj['label'] = $element.find('input[name="label"]').val();
                propertyObj['country'] = extractCountryObj($element.find('.country'));
                propertyObj['territory'] = extractTerritoryObj($element.find('.territory'));
                propertyObj['location'] = extractLocationObj($element.find('.location'));
                propertyObj['from_date'] = extractDateReferenceObj($element.find('.from-date'));
                propertyObj['to_date'] = extractDateReferenceObj($element.find('.to-date'));
                propertyObj['proven_date'] = extractDateReferenceObj($element.find('.proven-date'));
                propertyObj['comment'] = $element.find('input[name="comment"]').val();

                return propertyObj;
            },
            
            extractRankObj = function (element) {
                console.log("Rank: ", element, $(element));

                var $element = $(element);

                var rankObj = {};

                rankObj['id'] = parseInt($element.find('input[name="id"]').val());
                rankObj['rank_order'] = parseInt($element.find('input[name="order"]').val());
                rankObj['label'] = $element.find('input[name="label"]').val();
                rankObj['class'] = $element.find('input[name="class"]').val();
                rankObj['country'] = extractCountryObj($element.find('.country'));
                rankObj['territory'] = extractTerritoryObj($element.find('.territory'));
                rankObj['location'] = extractLocationObj($element.find('.location'));
                rankObj['from_date'] = extractDateReferenceObj($element.find('.from-date'));
                rankObj['to_date'] = extractDateReferenceObj($element.find('.to-date'));
                rankObj['proven_date'] = extractDateReferenceObj($element.find('.proven-date'));
                rankObj['comment'] = $element.find('input[name="comment"]').val();

                return rankObj;
            },
            
            extractReligionObj = function (element) {
                console.log("Religion: ", element, $(element));

                var $element = $(element);

                var religionObj = {};

                religionObj['id'] = parseInt($element.find('input[name="id"]').val());
                religionObj['religion_order'] = parseInt($element.find('input[name="order"]').val());
                religionObj['name'] = $element.find('input[name="name"]').val();
                religionObj['change_of_religion'] = $element.find('input[name="changeOfReligion"]').val();
                religionObj['from_date'] = extractDateReferenceObj($element.find('.from-date'));
                religionObj['to_date'] = extractDateReferenceObj($element.find('.to-date'));
                religionObj['comment'] = $element.find('input[name="comment"]').val();

                return religionObj;
            },

            extractResidenceObj = function (element) {
                console.log("Residence: ", element, $(element));

                var $element = $(element);

                var residenceObj = {};
                
                residenceObj['id'] = parseInt($element.find('input[name="id"]').val());
                residenceObj['residence_country'] = extractCountryObj($element.find('.country'));
                residenceObj['residence_territory'] = extractTerritoryObj($element.find('.territory'));
                residenceObj['residence_location'] = extractLocationObj($element.find('.location'));

                return residenceObj;
            },
            //@TODO: Check this, it does not work correctly
            extractRoadOfLifeObj = function (element) {
                console.log("RoadOfLife: ", element, $(element));

                var $element = $(element);

                var roadOfLifeObj = {};

                roadOfLifeObj['id'] = parseInt($element.find('input[name="id"]').val());
                roadOfLifeObj['road_of_life_order'] = parseInt($element.find('input[name="order"]').val());
                roadOfLifeObj['origin_country'] = extractCountryObj($element.find('.origin-country'));
                roadOfLifeObj['origin_territory'] = extractTerritoryObj($element.find('.origin-territory'));
                roadOfLifeObj['job'] = extractJobObj($element.find('.job'));
                roadOfLifeObj['country'] = extractCountryObj($element.find('.country'));
                roadOfLifeObj['territory'] = extractTerritoryObj($element.find('.territory'));
                roadOfLifeObj['location'] = extractLocationObj($element.find('.location'));
                roadOfLifeObj['from_date'] = extractDateReferenceObj($element.find('.from-date'));
                roadOfLifeObj['to_date'] = extractDateReferenceObj($element.find('.to-date'));
                roadOfLifeObj['proven_date'] = extractDateReferenceObj($element.find('.proven-date'));
                roadOfLifeObj['comment'] = $element.find('input[name="comment"]').val();

                return roadOfLifeObj;
            },
            extractSourceObj = function (element) {
                console.log("Source: ", element, $(element));

                var $element = $(element);

                var sourceObj = {};
                
                sourceObj['id'] = parseInt($element.find('input[name="id"]').val());
                sourceObj['source_order'] = parseInt($element.find('input[name="order"]').val());
                sourceObj['label'] = $element.find('input[name="label"]').val();
                sourceObj['place_of_discovery'] = $element.find('input[name="placeOfDiscovery"]').val();
                sourceObj['remark'] = $element.find('input[name="remark"]').val();
                sourceObj['comment'] = $element.find('input[name="comment"]').val();

                return sourceObj;
            },
            extractStatusObj = function (element) {
                console.log("Status: ", element, $(element));

                var $element = $(element);

                var statusObj = {};

                statusObj['id'] = parseInt($element.find('input[name="id"]').val());
                statusObj['status_order'] = parseInt($element.find('input[name="order"]').val());
                statusObj['label'] = $element.find('input[name="label"]').val();
                statusObj['country'] = extractCountryObj($element.find('.country'));
                statusObj['territory'] = extractTerritoryObj($element.find('.territory'));
                statusObj['location'] = extractLocationObj($element.find('.location'));
                statusObj['from_date'] = extractDateReferenceObj($element.find('.from-date'));
                statusObj['to_date'] = extractDateReferenceObj($element.find('.to-date'));
                statusObj['proven_date'] = extractDateReferenceObj($element.find('.proven-date'));
                statusObj['comment'] = $element.find('input[name="comment"]').val();

                return statusObj;
            },
            extractWorksObj = function (element) {
                console.log("Works: ", element, $(element));

                var $element = $(element);

                var worksObj = {};

                worksObj['id'] = parseInt($element.find('input[name="id"]').val());
                worksObj['works_order'] = parseInt($element.find('input[name="order"]').val());
                worksObj['label'] = $element.find('input[name="label"]').val();
                worksObj['country'] = extractCountryObj($element.find('.country'));
                worksObj['territory'] = extractTerritoryObj($element.find('.territory'));
                worksObj['location'] = extractLocationObj($element.find('.location'));
                worksObj['from_date'] = extractDateReferenceObj($element.find('.from-date'));
                worksObj['to_date'] = extractDateReferenceObj($element.find('.to-date'));
                worksObj['proven_date'] = extractDateReferenceObj($element.find('.proven-date'));
                worksObj['comment'] = $element.find('input[name="comment"]').val();

                return worksObj;
            },
            extractWeddingObj = function (element) {
                console.log("Wedding: ", element, $(element));

                var $element = $(element);

                var weddingObj = {};

                weddingObj['id'] = parseInt($element.find('input[name="id"]').val());
                weddingObj['wedding_order'] = parseInt($element.find('input[name="order"]').val());
                weddingObj['husband_id'] = parseInt($element.find('input[name="husband_id"]').val());
                weddingObj['wife_id'] = parseInt($element.find('input[name="wife_id"]').val());
                weddingObj['wedding_territory'] = extractTerritoryObj($element.find('.wedding-territory'));
                weddingObj['wedding_location'] = extractLocationObj($element.find('.wedding-location'));
                weddingObj['wedding_date'] = extractDateReferenceObj($element.find('.wedding-date'));
                weddingObj['banns_date'] = extractDateReferenceObj($element.find('.banns-date'));
                weddingObj['breakup_reason'] = $element.find('input[name="breakupReason"]').val();
                weddingObj['breakup_date'] = extractDateReferenceObj($element.find('.breakup-date'));
                weddingObj['proven_date'] = extractDateReferenceObj($element.find('.proven-date'));
                weddingObj['wedding_comment'] = $element.find('input[name="weddingComment"]').val();
                weddingObj['before_after'] = $element.find('input[name="beforeAfter"]').val();
                weddingObj['comment'] = $element.find('input[name="comment"]').val();

                return weddingObj;
            },
            extractNationObj = function (identifier) {
                if ($.type(identifier) === "string") {
                    //if it is an identifier
                    if ($(identifier + " .nation-container ").length > 0) {
                        var nationObj = {};

                        nationObj['name'] = $(identifier + ' .nation-container input[name="name"]').val();
                        nationObj['comment'] = $(identifier + ' .nation-container input[name="comment"]').val();

                        return nationObj;
                    }
                } else {
                    //if it is an element
                    if ($(identifier).find('.nation-container').length > 0) {
                        var nationObj = {};

                        nationObj['name'] = $(identifier).find('.nation-container input[name="name"]').val();
                        nationObj['comment'] = $(identifier).find('.nation-container input[name="comment"]').val();

                        return nationObj;
                    }
                }

            },
            extractJobObj = function (identifier) {
                if ($.type(identifier) === "string") {
                    if ($(identifier + " .job-container ").length > 0) {
                        var jobObj = {};

                        jobObj['label'] = $(identifier + ' .job-container input[name="label"]').val();
                        jobObj['comment'] = $(identifier + ' .job-container input[name="comment"]').val();

                        return jobObj;
                    }
                } else {
                    if ($(identifier).find('.job-container').length > 0) {
                        var jobObj = {};

                        jobObj['label'] = $(identifier).find('.job-container input[name="label"]').val();
                        jobObj['comment'] = $(identifier).find('.job-container input[name="comment"]').val();

                        return jobObj;
                    }
                }
            },
            extractJobClassObj = function (identifier) {
                if ($.type(identifier) === "string") {
                    if ($(identifier + " .job-class-container ").length > 0) {
                        var jobObj = {};

                        jobObj['label'] = $(identifier + ' .job-class-container input[name="label"]').val();
                        jobObj['comment'] = $(identifier + ' .job-class-container input[name="comment"]').val();

                        return jobObj;
                    }
                } else {
                    if ($(identifier).find('.country-container').length > 0) {
                        var jobObj = {};

                        jobObj['label'] = $(identifier).find('.job-class-container input[name="label"]').val();
                        jobObj['comment'] = $(identifier).find('.job-class-container input[name="comment"]').val();

                        return jobObj;
                    }
                }
            },
            //@TODO: Rendering empty objects
            extractDateReferenceObj = function (identifier) {
                if ($.type(identifier) === "string") {
                    //if it is an identifier
                    var children = $(identifier + " .date-reference-container").children();

                    console.log("DateReferences: ", children);

                    if (children.length > 0) {
                        var dateReferenceArray = [];

                        for (var i = 0; i < children.length; i++) {
                            var childElement = children[i];

                            if (childElement.className.indexOf('date-range-container') !== -1) {
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
                    if (children.length > 0) {
                        var dateReferenceArray = [];

                        for (var i = 0; i < children.length; i++) {
                            var childElement = children[i];

                            if (childElement.className.indexOf('date-range-container') !== -1) {
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
            extractDateRangeObj = function (element) {
                console.log("DateRange: ", element, $(element));

                var $element = $(element);
                var children = $element.children();

                var dateRangeObj = {};

                dateRangeObj['from'] = extractDateObj(children[0]);
                dateRangeObj['to'] = extractDateObj(children[1]);

                return dateRangeObj;
            },
            extractDateObj = function (element) {
                console.log("Date: ", element, $(element));

                var $element = $(element);
                
                
                var day = $element.find('input[name="day"]').val();
                
                if(day && day != parseInt(day)){
                    throw new DateException("Ungültiger Wert für den Tag");
                }
                
                var month = $element.find('input[name="month"]').val();
                
                if(month && month != parseInt(month)){
                    throw new DateException("Ungültiger Wert für den Monat");
                }
                
                var year = $element.find('input[name="year"]').val();
                
                if(year && year != parseInt(year)){
                    throw new DateException("Ungültiger Wert für das Jahr");
                }

                var dateObj = {};

                dateObj['id'] = parseInt($element.find('input[name="id"]').val());
                dateObj['day'] = day;
                dateObj['month'] = month;
                dateObj['year'] = year;
                dateObj['before_date'] = $element.find('input[name="beforeDate"]').is(':checked');
                dateObj['after_date'] = $element.find('input[name="afterDate"]').is(':checked');
                dateObj['comment'] = $element.find('input[name="comment"]').val();

                return dateObj;
            },
            
            extractCountryObj = function (identifier) {
                if ($.type(identifier) === "string") {
                    //if it is an identifier
                    if ($(identifier + " .country-container").length > 0) {
                        var countryObj = {};

                        countryObj['name'] = $(identifier + ' .country-container input[name="name"]').val();
                        countryObj['comment'] = $(identifier + ' .country-container input[name="comment"]').val();

                        return countryObj;
                    }
                } else {
                    if ($(identifier).find('.country-container').length > 0) {
                        var countryObj = {};

                        countryObj['name'] = $(identifier).find('.country-container input[name="name"]').val();
                        countryObj['comment'] = $(identifier).find('.country-container input[name="comment"]').val();

                        return countryObj;
                    }
                }
            },
            extractTerritoryObj = function (identifier) {
                if ($.type(identifier) === "string") {
                    //if it is an identifier
                    if ($(identifier + " .territory-container").length > 0) {
                        var territoryObj = {};

                        territoryObj['name'] = $(identifier + ' .territory-container input[name="name"]').val();
                        territoryObj['comment'] = $(identifier + ' .territory-container input[name="comment"]').val();

                        return territoryObj;
                    }
                } else {
                    if ($(identifier).find('.territory-container').length > 0) {
                        var territoryObj = {};

                        territoryObj['name'] = $(identifier).find('.territory-container input[name="name"]').val();
                        territoryObj['comment'] = $(identifier).find('.territory-container input[name="comment"]').val();

                        return territoryObj;
                    }
                }
            },
            extractLocationObj = function (identifier) {
                if ($.type(identifier) === "string") {
                    //if it is an identifier
                    if ($(identifier + " .location-container").length > 0) {
                        var locationObj = {};

                        locationObj['name'] = $(identifier + ' .location-container input[name="name"]').val();
                        locationObj['comment'] = $(identifier + ' .location-container input[name="comment"]').val();
                        locationObj['latitude'] = $(identifier + ' .location-container input[name="latitude"]').val();
                        locationObj['longitude'] = $(identifier + ' .location-container input[name="longitude"]').val();

                        return locationObj;
                    }
                } else {
                    if ($(identifier).find('.location-container').length > 0) {
                        var locationObj = {};

                        locationObj['name'] = $(identifier).find('.location-container input[name="name"]').val();
                        locationObj['comment'] = $(identifier).find('.location-container input[name="comment"]').val();
                        locationObj['latitude'] = $(identifier).find('.location-container input[name="latitude"]').val();
                        locationObj['longitude'] = $(identifier).find('.location-container input[name="longitude"]').val();

                        return locationObj;
                    }
                }
            };


    that.init = init;
    that.displayPerson = displayPerson;
    that.displayWeddings = displayWeddings;
    that.extractPersonData = extractPersonData;
    that.extractWeddingData = extractWeddingData;
    that.displayGNDResult = displayGNDResult;

    return that;
})();


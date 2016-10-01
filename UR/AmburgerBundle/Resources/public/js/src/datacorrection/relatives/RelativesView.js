/* 
 An object used for retrieving information with ajax from the backend.
 */

RelativesCorrection.RelativesView = (function () {
    var that = {},
    
    personData = undefined,
            /* 
             Initialises the object 
             */
            init = function () {


                return that;
            },
            
            displayDirectRelatives = function(data){
                console.log('displayDirectRelatives', data, personData);
                for(var i = 0; i < data['parents'].length; i++){
                    var template = _.template($("script#directRelationTemplate").html());

                    var templateData = [];
                    templateData['person_first_name'] = personData['first_name'];
                    templateData['person_last_name'] = personData['last_name'];
                    templateData['person_patronym'] = personData['patronym'];
                    templateData['relative_first_name'] = data['parents'][i]['person']['first_name'];
                    templateData['relative_last_name'] = data['parents'][i]['person']['last_name'];
                    templateData['relative_patronym'] = data['parents'][i]['person']['patronym'];


                    $(".existing-relations-container").append(template(templateData));
                }
                
                for(var i = 0; i < data['children'].length; i++){
                    var template = _.template($("script#directRelationTemplate").html());

                    var templateData = [];
                    templateData['person_first_name'] = personData['first_name'];
                    templateData['person_last_name'] = personData['last_name'];
                    templateData['person_patronym'] = personData['patronym'];
                    templateData['relative_first_name'] = data['children'][i]['person']['first_name'];
                    templateData['relative_last_name'] = data['children'][i]['person']['last_name'];
                    templateData['relative_patronym'] = data['children'][i]['person']['patronym'];


                    $(".existing-relations-container").append(template(templateData));
                }
                
                for(var i = 0; i < data['siblings'].length; i++){
                    var template = _.template($("script#directRelationTemplate").html());

                    var templateData = [];
                    templateData['person_first_name'] = personData['first_name'];
                    templateData['person_last_name'] = personData['last_name'];
                    templateData['person_patronym'] = personData['patronym'];
                    templateData['relative_first_name'] = data['siblings'][i]['person']['first_name'];
                    templateData['relative_last_name'] = data['siblings'][i]['person']['last_name'];
                    templateData['relative_patronym'] = data['siblings'][i]['person']['patronym'];


                    $(".existing-relations-container").append(template(templateData));
                }
                
                for(var i = 0; i < data['marriagePartners'].length; i++){
                    var template = _.template($("script#directRelationTemplate").html());

                    var templateData = [];
                    templateData['person_first_name'] = personData['first_name'];
                    templateData['person_last_name'] = personData['last_name'];
                    templateData['person_patronym'] = personData['patronym'];
                    templateData['relative_first_name'] = data['marriagePartners'][i]['person']['first_name'];
                    templateData['relative_last_name'] = data['marriagePartners'][i]['person']['last_name'];
                    templateData['relative_patronym'] = data['marriagePartners'][i]['person']['patronym'];


                    $(".existing-relations-container").append(template(templateData));
                }
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
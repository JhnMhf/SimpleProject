
DuplicatePerson.DuplicatePersonView = (function(){
    var that = {},
    
    basePersonViewGenerator = null,
    
    relativesViewGenerator = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        basePersonViewGenerator = DuplicatePerson.BasePersonViewGenerator.init();
        relativesViewGenerator = DuplicatePerson.RelativesViewGenerator.init();
        
        $('#save-button').on("click", save);
        
        return that;
    },
    
    displayDuplicates = function(personData, duplicatesData){
        var personTemplate = generatePersonAndRelativesTemplate(personData);
        
        for(var i = 0; i < duplicatesData.length; i++){
            displayDuplicatePerson(personTemplate, duplicatesData[i]);
        }
        
        Loader.hideLoader();
    },
    
    displayDuplicatePerson = function(personTemplate, duplicate){
        var duplicateTemplate = generatePersonAndRelativesTemplate(duplicate);
        
        var template = _.template($("script#fullDuplicateTemplate").html());

        var data = {}
        
        data['personData'] = personTemplate['person'];
        data['duplicateData'] = duplicateTemplate['person'];
        data['personRelatives'] = personTemplate['relatives'];
        data['duplicateRelatives'] = duplicateTemplate['relatives'];
        
        $('#duplicates-container').append(template(data));
    },
    
    generatePersonAndRelativesTemplate = function(fullPersonData){
      var personTemplate = basePersonViewGenerator.generateTemplate(fullPersonData['person']);
      var relativesTemplate = relativesViewGenerator.generateTemplate(fullPersonData['relatives']);
      
      return {'person': personTemplate, 'relatives':relativesTemplate};
    },
    
    save = function(){
        $(that).trigger('save');
    };


    that.init = init;
    that.displayDuplicates = displayDuplicates;

    return that;
})();


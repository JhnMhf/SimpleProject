
DuplicatePerson.DuplicatePersonView = (function(){
    var that = {},
    
    basePersonViewGenerator = null,
    
    relativesViewGenerator = null,
    
    currentDuplicateRow = null,
    
    currentDuplicate = null,
    
    /* 
        Initialises the object and sets default values.
    */
    init = function() {
        basePersonViewGenerator = DuplicatePerson.BasePersonViewGenerator.init();
        relativesViewGenerator = DuplicatePerson.RelativesViewGenerator.init();
        
        return that;
    },
    
    displayDuplicates = function(personData, duplicatesData){
        var personTemplate = generatePersonAndRelativesTemplate(personData);
        
        for(var i = 0; i < duplicatesData.length; i++){
            displayDuplicatePerson(personTemplate, duplicatesData[i]);
        }
        
        removeUnusedRows();
        Collapsible.register();
        Loader.hideLoader();
    },
    
    removeUnusedRows = function(){
        var higherRows = $('.higher-row');
        
        for(var i = 0; i < higherRows.length; i++){
            var childRows = $(higherRows[i]).find('.row');
            
            if(childRows.length == 0){
                $(higherRows[i]).hide();
            }
        }
    },
    
    displayDuplicatePerson = function(personTemplate, duplicate){
        var duplicateTemplate = generatePersonAndRelativesTemplate(duplicate);
        
        var template = _.template($("script#fullDuplicateTemplate").html());

        var data = {}
        
        data['duplicateId'] = duplicate['person']['id'];
        data['personData'] = personTemplate['person'];
        data['duplicateData'] = duplicateTemplate['person'];
        data['personRelatives'] = personTemplate['relatives'];
        data['duplicateRelatives'] = duplicateTemplate['relatives'];
        
        $('#duplicates-container').append(template(data));
        
        $('.ignore-duplicate').on('click', ignoreDuplicate);
        $('.merge-duplicate').on('click', mergeDuplicate);
    },
    
    generatePersonAndRelativesTemplate = function(fullPersonData){
      var personTemplate = basePersonViewGenerator.generateTemplate(fullPersonData['person']);
      var relativesTemplate = relativesViewGenerator.generateTemplate(fullPersonData['relatives']);
      
      return {'person': personTemplate, 'relatives':relativesTemplate};
    },
    
    mergeDuplicate = function(){
        console.log('mergeDuplicate',$(this));
        var rowContainer = $(this).closest('.row.duplicate');
        
        console.log(rowContainer);
        
        var duplicateId = $(rowContainer).attr('duplicate-id');
        
        console.log(duplicateId);
        
        currentDuplicateRow = rowContainer;
        currentDuplicate = duplicateId;
        
        Loader.showLoader();
        $(that).trigger('mergeDuplicate', duplicateId);
    },
    
    ignoreDuplicate = function(){
        console.log('ignoreDuplicate',$(this));
        var rowContainer = $(this).closest('.row.duplicate');
        
        console.log(rowContainer);
        
        $(rowContainer).hide();
    },
    
    mergeFinished = function(){
        $(currentDuplicateRow).hide();
        Loader.hideLoader();
    };


    that.init = init;
    that.displayDuplicates = displayDuplicates;
    that.mergeFinished = mergeFinished;

    return that;
})();


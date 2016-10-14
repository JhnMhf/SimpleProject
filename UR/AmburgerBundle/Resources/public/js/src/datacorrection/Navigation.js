Navigation = (function () {
    var that = {},
    
    currentStep = 'start',
    
    init = function(){
        $('.navigation .nav-item').on('click', navItemClicked);
        
        extractCurrentStepFromURL();
    },
    
    extractCurrentStepFromURL = function(){
        var currentUrl = window.location.href;
        
        console.log('Checking currentUrl: ', currentUrl);
        
        if(currentUrl.includes('duplicate')){
            currentStep = 'duplicate';
        } else if(currentUrl.includes('person')){
            currentStep = 'person';
        } else if(currentUrl.includes('relatives')){
            currentStep = 'relatives';
        } else if(currentUrl.includes('end')){
            currentStep = 'end';
        } else {
            currentStep = 'start';
        }
        
        
        
        markNavigationItems();
    },
    
    markNavigationItems = function(){
        if(currentStep === 'start'){
            $(".navigation .nav-item").addClass('disabled');
            $(".navigation .nav-item[type='start']").removeClass('disabled');
            $(".navigation .nav-item[type='start']").addClass('active');
        } else {
            $(".navigation .nav-item[type='"+currentStep+"']").addClass('active');
        }
    },
    
    navItemClicked = function(){
        var navigationType = $(this).attr('type');
        console.log('navItemClicked', navigationType);
        
        if($(this).hasClass('disabled')){
            return;
        }
        
        if(navigationType === 'start' && currentStep !== 'start'){
            MessageHelper.showYesNoMessage('Hierdurch verlassen sie die Korrektursitzung. Wollen Sie trotzdem fortfahren?', 
            'Achtung', that, "forwardToStart");
        } else {
            var currentUrl = window.location.href;
            var newUrl = currentUrl.replace(currentStep, navigationType);
            
            if(newUrl.substr(newUrl.length - 1) !== "/"){
                newUrl += "/";
            }
            
            console.log('Forwarding to ', newUrl);
            
            
            window.location.href = newUrl;
        }
        
    },
    
    forwardToStart = function(){
        window.location.href = window.location.origin+"/correction/";
    };
    
    that.init = init;
    that.forwardToStart = forwardToStart;
    
    return that;
})();


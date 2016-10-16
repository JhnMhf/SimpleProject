
Index.SearchView = (function () {
    var that = {},
    simpleSearchDiv = 'simple-search',
    extendedSearchDiv = 'extended-search',
    /* 
     Initialises the object 
     */
    init = function () {

        $('#search-btn').on("click", searchButtonClicked);
        
        $(window).on('keypress', onKeyPress);

        return that;
    },
    
    onKeyPress = function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        
        console.log("KeyPressed", code);
        //13 is enter
        if(code == 13) { 
           $(that).trigger('search', extractSearchData());
        }
    },
    
    searchButtonClicked = function () {
        $(that).trigger('search', extractSearchData());
    },
    
    extractSearchData = function () {
        var data = extractBaseData();

        enrichWithExtendedData(data);

        return data;
    },
    
    extractBaseData = function () {
        var data = {};

        data['searchQuery'] = $("." + simpleSearchDiv + " input[name='searchQuery']").val();
        data['onlyMainPersons'] = $("." + simpleSearchDiv + " input[name='only-main-persons']").is(':checked');

        console.log("extractBaseData", data);

        return data;
    },
    
    enrichWithExtendedData = function (data) {
        var lastname = $("." + extendedSearchDiv + " input[name='lastname']").val();
        var firstname = $("." + extendedSearchDiv + " input[name='firstname']").val();
        var patronym = $("." + extendedSearchDiv + " input[name='patronym']").val();
        var location = $("." + extendedSearchDiv + " input[name='location']").val();
        var territory = $("." + extendedSearchDiv + " input[name='territory']").val();
        var country = $("." + extendedSearchDiv + " input[name='country']").val();
        var date = extractDate();

        var fromDate = extractFromDate();
        var toDate = extractToDate();

        if (lastname) {
            data['lastname'] = lastname;
        }

        if (firstname) {
            data['firstname'] = firstname;
        }

        if (patronym) {
            data['patronym'] = patronym;
        }

        if (location) {
            data['location'] = location;
        }

        if (territory) {
            data['territory'] = territory;
        }


        if (country) {
            data['country'] = country;
        }

        console.log(date);

        if (date) {
            data['date'] = date;
        } else if (fromDate != ".." || toDate != "..") {
            data['fromDate'] = fromDate;
            data['toDate'] = toDate;
        }

        console.log("enrichWithExtendedData", data);

        return data;
    },
    
    extractDate = function(){
        var selectedDay = $("." + extendedSearchDiv + " select[name='date-day']").find('option:selected').attr('value');
        var selectedMonth = $("." + extendedSearchDiv + " select[name='date-month']").find('option:selected').attr('value');
        var year = $("." + extendedSearchDiv + " input[name='date-year']").val();
        
        if(selectedDay.length == 0 && selectedMonth.length == 0 && year.length == 0){
            return undefined;
        }
        
        return selectedDay+"."+selectedMonth+"."+year;
    },
    
    extractFromDate = function(){
        var selectedDay = $("." + extendedSearchDiv + " select[name='from-date-day']").find('option:selected').attr('value');
        var selectedMonth = $("." + extendedSearchDiv + " select[name='from-date-month']").find('option:selected').attr('value');
        var year = $("." + extendedSearchDiv + " input[name='from-date-year']").val();
        
        if(selectedDay.length == 0 && selectedMonth.length == 0 && year.length == 0){
            return "..";
        }
        
        return selectedDay+"."+selectedMonth+"."+year;
    },
    
    extractToDate = function(){
        var selectedDay = $("." + extendedSearchDiv + " select[name='to-date-day']").find('option:selected').attr('value');
        var selectedMonth = $("." + extendedSearchDiv + " select[name='to-date-month']").find('option:selected').attr('value');
        var year = $("." + extendedSearchDiv + " input[name='to-date-year']").val();
        
        if(selectedDay.length == 0 && selectedMonth.length == 0 && year.length == 0){
            return "..";
        }
        
        return selectedDay+"."+selectedMonth+"."+year;
    },
    
    displayPopup = function(message){
        MessageHelper.showInfoMessage(message);
    };


    that.init = init;
    that.displayPopup = displayPopup;
    return that;
})();
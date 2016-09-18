
Index.SearchView = (function () {
    var that = {},
    simpleSearchDiv = 'simple-search',
    extendedSearchDiv = 'extended-search',
    /* 
     Initialises the object 
     */
    init = function () {

        $('#search-btn').on("click", searchButtonClicked);

        return that;
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
        var date = $("." + extendedSearchDiv + " input[name='date']").val();

        var fromDate = $("." + extendedSearchDiv + " input[name='from-date']").val();
        var toDate = $("." + extendedSearchDiv + " input[name='to-date']").val();

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


        if (date) {
            data['date'] = date;
        }

        if (fromDate && toDate) {
            data['fromDate'] = fromDate;
            data['toDate'] = toDate;
        }

        console.log("enrichWithExtendedData", data);

        return data;
    };


    that.init = init;
    return that;
})();
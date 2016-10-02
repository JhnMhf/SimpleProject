
Index.PersonListView = (function(){
    var that = {},

    dateReferenceTransformer = {},

    personListModel = {},

    /* 
        Initialises the object 
    */
    init = function() {
        dateReferenceTransformer = DateReferenceTransformer;
        
        personListModel = Index.PersonListModel.init();
        
        initSelectBox();

        return that;
    },
    
    initSelectBox = function(){
        $('.page-size').val(personListModel.getPageSize());
        $('.page-size').change(onSelectBoxChange);
    },
    
    onSelectBoxChange = function(){
        var selectedValue = $(this).find('option:selected').attr('value');
        
        console.log("SelectedValue: ", selectedValue);
        //update other box
        $('.page-size').val(selectedValue);
        personListModel.setPageSize(selectedValue);
        triggerPersonLoad();
    },
    
    setPersonListIds = function(ids){
        personListModel.setIds(ids);
        triggerPersonLoad();
    },
    
    displayPersonData = function(personData){
        console.log('displayPersonData: ', personData);
        
        $('#personDataList').empty();
        
        for(var i = 0; i < personData.length; i++){
            displayPerson(personData[i]);
        }
        //handle pagination
        updatePagination();
    },
    
    displayPerson = function(person){
        //console.log(person);
        var template = _.template($("script#personDataTemplate").html());

        var data = preparePersonData(person);

        $('#personDataList').append(template(data));
    },
    
    preparePersonData = function(person){
        var preparedData = [];
        
        preparedData['first_name'] = person['first_name']; 
        preparedData['patronym'] = person['patronym']; 
        preparedData['last_name'] = person['last_name'];
        preparedData['birth_date'] = dateReferenceTransformer.dateReferenceToString(person['birth_date']);
        preparedData['baptism_date'] = dateReferenceTransformer.dateReferenceToString(person['baptism_date']);
        preparedData['death_date'] = dateReferenceTransformer.dateReferenceToString(person['death_date']);
        preparedData['funeral_date'] = dateReferenceTransformer.dateReferenceToString(person['funeral_date']);
        preparedData['type'] = person['type'];
        preparedData['id'] = person['id'];
    
        return preparedData;
    },
    
    triggerPersonLoad = function(){
        var idsForCurrentPage = personListModel.getIdsForCurrentPage();
        console.log("IdsForCurrentPage: ", idsForCurrentPage)
        $(that).trigger('loadPersons', {'ids': idsForCurrentPage});
    },
    
    updatePagination = function(){
        console.log("updatePagination");
        $('.pagination-container').empty();
        var currentPage = personListModel.getCurrentPage();
        var pageCount = personListModel.getPageCount();
        
        var template = _.template($("script#paginationTemplate").html());

        console.log("CurrentPage: ", currentPage, "PageCount: ", pageCount);

        var data = [];
        data['currentPage'] = currentPage;
        data['pageCount'] = pageCount;

        $('.pagination-container').append(template(data));
        $('.pagination-container .arrow.previous').on("click", onPrevious);
        $('.pagination-container .arrow.next').on("click", onNext);
        $('.pagination-container .page-marker').on("click", onPageSelected);
    },
    
    onPageSelected = function(){
        var selectedPage = $(this).attr('page');
        console.log("SelectedPage: ", selectedPage);
        if(personListModel.getCurrentPage() != selectedPage){
            personListModel.setCurrentPage(selectedPage);
            triggerPersonLoad();
        }
    },
    
    onNext = function(){
        if(personListModel.getCurrentPage()+1 <= personListModel.getPageCount()){
            personListModel.setCurrentPage(personListModel.getCurrentPage()+1);
            triggerPersonLoad();
        }
    },
    
    onPrevious = function(){
        if(personListModel.getCurrentPage()-1 > 0){
            personListModel.setCurrentPage(personListModel.getCurrentPage()-1);
            triggerPersonLoad();
        }
    };
    
    
    that.init = init;
    that.setPersonListIds = setPersonListIds;
    that.displayPersonData = displayPersonData;
    return that;
})();
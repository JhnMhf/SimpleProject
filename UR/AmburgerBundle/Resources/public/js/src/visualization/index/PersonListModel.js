
Index.PersonListModel = (function () {
    var that = {},
    
    ids = [],
    
    currentPage = 1,
    pageSize = 25,
    

    /* 
     Initialises the object 
     */
    init = function () {

        return that;
    },
    
    setIds = function (newIds){
        ids = newIds;
        console.log(ids);
    },
    
    getIdsForCurrentPage = function(){
        var start = getCurrentStart();
        var end = getCurrentEnd();
        
        console.log("Splicing ids from ",start, " to ",end);
        
        return ids.slice(start, end);
    },
    
    setCurrentPage = function(page){
       currentPage = page;
    },
        
    getCurrentPage = function(){
       return currentPage;
    },
    
    setPageSize = function(newPageSize){
        pageSize = newPageSize;
        
        //changeing currentPage, because its to high
        if(currentPage > getPageCount()){
            currentPage = getPageCount();
        }
    },
    
    getCurrentStart = function(){
        return pageSize * (currentPage -1);
    },
    
    getCurrentEnd = function(){
        var end = pageSize * currentPage;
        
        if(end > ids.length){
            end = ids.length;
        }
        
        return end;
    },
    
    getPageCount = function(){
        return Math.ceil(ids.length/ pageSize);
    },
    
    getPageSize = function(){
       return pageSize
    };


    that.init = init;
    that.setIds = setIds;
    that.getIdsForCurrentPage = getIdsForCurrentPage;
    that.setCurrentPage = setCurrentPage;
    that.setPageSize = setPageSize;
    that.getCurrentStart = getCurrentStart;
    that.getCurrentEnd = getCurrentEnd;
    that.getCurrentPage = getCurrentPage;
    that.getPageCount = getPageCount;
    that.getPageSize = getPageSize;
    return that;
})();
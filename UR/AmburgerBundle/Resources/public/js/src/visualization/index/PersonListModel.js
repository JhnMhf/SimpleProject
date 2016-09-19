
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
    
    setPageSize = function(newPageSize){
        pageSize = newPageSize;
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
    
    getTotalSize = function(){
        return ids.length;
    };


    that.init = init;
    that.setIds = setIds;
    that.getIdsForCurrentPage = getIdsForCurrentPage;
    that.setCurrentPage = setCurrentPage;
    that.setPageSize = setPageSize;
    that.getCurrentStart = getCurrentStart;
    that.getCurrentEnd = getCurrentEnd;
    that.getTotalSize = getTotalSize;
    return that;
})();
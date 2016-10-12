
Detail.FamilyTreeView = (function(){
    var that = {},
  

    /* 
        Initialises the object 
    */
    init = function() {
        return that;
    },
    
    displayFamilyTree = function(data){
        var items = generateItemsFromData(data);
        
        var options = new primitives.famdiagram.Config();
        
        options.items = items;
        options.cursorItem = 2;
        options.linesWidth = 1;
        options.linesColor = "black";
        options.hasSelectorCheckbox = primitives.common.Enabled.False;
        options.normalLevelShift = 20;
        options.dotLevelShift = 20;
        options.lineLevelShift = 20;
        options.normalItemsInterval = 10;
        options.dotItemsInterval = 10;
        options.lineItemsInterval = 10;

        jQuery("#family-tree").famDiagram(options);
    },
    
    generateItemsFromData = function(data){
        var items = [];
        
        for(var i = 0; i < data.length; i++){
            var entry = {};
            
            entry['id'] = data[i]['id'];
            entry['title'] = data[i]['person']['first_name'];
            entry['label'] = data[i]['person']['first_name'];
            entry['spouses'] = data[i]['partners'];
            entry['parents'] = data[i]['parents'];
            
            items.push(entry);
        }
        
        
        /*
         var items = [
                { id: 1, spouses: [2], title: "Thomas Williams", label: "Thomas Williams", description: "1, 1st husband", image: "demo/images/photos/t.png" },
                { id: 2, title: "Mary Spencer", label: "Mary Spencer", description: "2, The Mary",image: "demo/images/photos/m.png" },
                { id: 3, spouses: [2], title: "David Kirby", label: "David Kirby", description: "3, 2nd Husband", image: "demo/images/photos/d.png" },
                { id: 4, parents: [1, 2], title: "Brad Williams", label: "Brad Williams", description: "4, 1st son", image: "demo/images/photos/b.png" },
                { id: 5, parents: [2, 3], title: "Mike Kirby", label: "Mike Kirby", description: "5, 2nd son, having 2 spouses", image: "demo/images/photos/m.png"}
            ];
            */
        
        return items;
    };
    
    
    that.init = init;
    that.displayFamilyTree = displayFamilyTree;
    return that;
})();
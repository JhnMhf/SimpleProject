//http://www.basicprimitives.com/index.php?option=com_content&view=article&id=11&Itemid=10&lang=en
//http://www.basicprimitives.com/index.php?option=com_local&view=local&Itemid=44
Detail.FamilyTreeView = (function(){
    var that = {},
  
    dateReferenceTransformer = {},

    /* 
        Initialises the object 
    */
    init = function() {
        dateReferenceTransformer = DateReferenceTransformer;
        return that;
    },
    
    displayFamilyTree = function(data){
        var items = generateItemsFromData(data);
        
        var options = new primitives.famdiagram.Config();
        
        optionshighlightItem = getCurrentPersonID();
        
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
        options.templates = [getPersonTemplate()];
        options.defaultTemplateName = "personTemplate";
        options.onItemRender = onTemplateRender;

        jQuery("#family-tree").famDiagram(options);
    },
    
    getCurrentPersonID = function(){
      var lastPart = window.location.href.split("/").pop();
      
      return lastPart;
    },
    
    onTemplateRender = function(event, data){

            switch (data.renderingMode) {
                case primitives.common.RenderingMode.Create:
                    /* Initialize widgets here */
                    break;
                case primitives.common.RenderingMode.Update:
                    /* Update widgets here */
                    break;
            }

            var itemConfig = data.context;

            if (data.templateName == "personTemplate") {
                console.log('rendering person Template');
                var fields = ["first_name", "patronym", "last_name", "gender"];
                    //,"job", "job_class", "nation", "educations"
                for (var index = 0; index < fields.length; index++) {
                    var field = fields[index];
                    
                    if(itemConfig[field]){
                        var element = data.element.find("[name=" + field + "] span");
                        if (element.text() != itemConfig[field]) {
                            element.text(itemConfig[field]);
                        }
                    } else {
                        var element = data.element.find("div[name=" + field + "]");
                        
                        element.hide();
                    }
                }
                
                if(itemConfig["birth_date"]){
                    data.element.find("div[name=baptism_date]").hide();  
                    var element = data.element.find("[name=birth_date] span");
                    element.text(itemConfig["birth_date"]);
                } else if(itemConfig["baptism_date"]){
                    data.element.find("div[name=birth_date]").hide();  
                    var element = data.element.find("[name=baptism_date] span");
                    element.text(itemConfig["baptism_date"]);
                } else {
                    data.element.find("div[name=birth_date]").hide();  
                    data.element.find("div[name=baptism_date]").hide();  
                }
                
                if(itemConfig["death_date"]){
                    data.element.find("div[name=funeral_date]").hide();  
                    var element = data.element.find("[name=death_date] span");
                    element.text(itemConfig["death_date"]);
                } else if(itemConfig["funeral_date"]){
                    data.element.find("div[name=death_date]").hide();  
                    var element = data.element.find("[name=funeral_date] span");
                    element.text(itemConfig["funeral_date"]);
                } else {
                    data.element.find("div[name=death_date]").hide();  
                    data.element.find("div[name=funeral_date]").hide();  
                }
                
            }
        
    },
    
    getPersonTemplate = function() {
        var result = new primitives.orgdiagram.TemplateConfig();
        result.name = "personTemplate";

        result.itemSize = new primitives.common.Size(200, 150);
        result.minimizedItemSize = new primitives.common.Size(4, 4);
        result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);

        var itemTemplate = jQuery(
          '<div class="bp-item bp-corner-all bt-item-frame">'
            + '<div class="family-tree-item">'
                + '<div name="last_name"><span class="bold"></span></br></div>'
                + '<div name="first_name"><span></span></br></div>'
                + '<div name="patronym"><span></span></br></div>'
                + '<div name="gender">Geschlecht: <span></span></br></div>'
                + '<div name="birth_date">Geboren: <span></span></br></div>'
                + '<div name="baptism_date">Getauft: <span></span></br></div>'
                + '<div name="death_date">Gestorben: <span></span></br></div>'
                + '<div name="funeral_date">Beerdigt: <span></span></br></div>'
            + '</div>'
        + '</div>'
        ).css({
            width: '100%',
            height: '100%',
            margin: '5px'
        }).addClass("bp-item bp-corner-all bt-item-frame custom-item");
        result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

/*
 *                 + '<div name="job">Job: <span></span></br></div>'
                + '<div name="job_class">Jobklasse: <span></span></br></div>'
                + '<div name="nation">Nation: <span></span></br></div>'
                + '<div name="educations">Ausbildung(en): <span></span></br></div>'
 */

        return result;
    },

    generateItemsFromData = function(data){
        var items = [];
        
        for(var i = 0; i < data.length; i++){
            var entry = {};
            
            var person = data[i]['person'];
            
            entry['id'] = data[i]['id'];
            entry['first_name'] = person['first_name'];
            entry['last_name'] = person['last_name'];
            entry['patronym'] = person['patronym'];
            entry['gender'] = extractGender(person);
            entry['birth_date'] = extractBirthDate(person);
            entry['baptism_date'] = extractBaptismDate(person);
            entry['death_date'] = extractDeathDate(person);
            entry['funeral_date'] = extractFuneralDate(person);
            entry['job'] = extractJob(person);
            entry['job_class'] = extractJobClass(person);
            entry['nation'] = extractNation(person);
            entry['educations'] = extractEducations(person);
            
            entry['spouses'] = data[i]['partners'];
            entry['parents'] = data[i]['parents'];
            
            items.push(entry);
        }
        return items;
    },

    extractGender = function(data){
      switch(data['gender']){
            case 0:
                return "keine Angabe";
            case 1:
                return "männlich";
            case 2:
                return "weiblich";
        }
        return "";

    },

    extractBirthDate = function(data){
        if(data['birth']){
          return dateReferenceTransformer.dateReferenceToString(data['birth']['birth_date']);
        }
        return "";
    },

    extractBaptismDate = function(data){
        if(data['baptism']){
          return dateReferenceTransformer.dateReferenceToString(data['baptism']['baptism_date']);
        }
        return "";
    },

    extractDeathDate = function(data){
        if(data['death']){
          return dateReferenceTransformer.dateReferenceToString(data['death']['death_date']);
        }
        return "";
    },

    extractFuneralDate = function(data){
        if(data['death']){
          return dateReferenceTransformer.dateReferenceToString(data['death']['funeral_date']);
        }
        return "";
    },

    extractJob = function(data){
        if(data['job']){
            return data['job']['label'];
        }
        return "";
    },

    extractJobClass = function(data){
        if(data['job_class']){
            return data['job_class']['label'];
        }
        return "";
    },

    extractNation = function(data){
        if(data['nation']){
            return data['nation']['name'];
        }
        return "";
    },

    extractEducations = function(data){
        if(data['educations']){
            var educationsString = "";
            for(var i = 0; i < data['educations'].length; i++){
                if(data['educations'][i]['label'] !== undefined){
                    if(educationsString !== ""){
                        educationsString += ",";
                    }
                    
                    educationsString += data['educations'][i]['label'];
                }
            }

            return educationsString;
        }

        return "";
    };
    
    
    that.init = init;
    that.displayFamilyTree = displayFamilyTree;
    return that;
})();
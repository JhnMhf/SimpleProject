AdminOverview = (function () {
    var that = {},
    
    init = function(){
        $('.create').on('click', onCreateUser);
        $('.change-password').on('click', onChangePassword);
        $('.nominate').on('click', onNominateAdmin);
        $('.revoke').on('click', onRevokeAdmin);
        $('.delete').on('click', onDeleteUser);
    },
    
    onCreateUser = function(){
        window.location.href = window.location.origin+"/admin/user/create";
    },
    
    getUserId = function(element){
        var userId = element.parent().parent().attr('userid');
        
        console.log('Extracted UserId: ', userId);
        
        return userId;
    },
    
    onChangePassword = function(){
        var userId = getUserId($(this));

        window.location.href = window.location.origin+"/admin/user/"+userId+"/change";
    },
    
    onNominateAdmin = function(){
        var userId = getUserId($(this));
        $.ajax({
            type: "POST",
            url: window.location.origin+"/admin/user/"+userId+'/nominate'
        }).always(function (data, textStatus, jqXHR) {
            MessageHelper.showInfoMessage("Der Nutzer wurde erfolgreich zum Administrator ernannt.");
        });
    },
    
    onRevokeAdmin = function(){
        var userId = getUserId($(this));
        $.ajax({
            type: "POST",
            url: window.location.origin+"/admin/user/"+userId+'/revoke'
        }).always(function (data, textStatus, jqXHR) {
            MessageHelper.showInfoMessage("Dem Nutzer wurden die Administratorrechte entzogen");
        });
    },
    
    onDeleteUser = function(){
        var userId = getUserId($(this));
        $.ajax({
            type: "POST",
            url: window.location.origin+"/admin/user/"+userId+'/delete'
        }).always(function (data, textStatus, jqXHR) {
            MessageHelper.showInfoMessage("Der Nutzer wurde erfolgreich gelöscht.");
            $("tr[userid="+userId+"]").hide();
        });
    };
    
    that.init = init;
    
    return that;
})();


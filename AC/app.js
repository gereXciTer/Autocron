Ext.application({
    name: 'AC',

    requires: [
        'AC.helper.Config', 'AC.helper.Fn',
        'Ext.form.Panel','Ext.form.FieldSet','Ext.field.Email','Ext.field.Password',
        'Ext.data.proxy.SessionStorage','Ext.data.Store','Ext.MessageBox','Ext.SegmentedButton',
        'Ext.field.Select','Ext.Img'
    ],

    models: ['User','CarMake','CarModel','CarModelVersion','CarModelImage','CarModelVariant'],
    views: ['Main','Login','Register'],
    controllers: ['Sessions'],

    icon: {
        '57': 'resources/icons/Icon.png',
        '72': 'resources/icons/Icon~ipad.png',
        '114': 'resources/icons/Icon@2x.png',
        '144': 'resources/icons/Icon~ipad@2x.png'
    },

    isIconPrecomposed: true,

    startupImage: {
        '320x460': 'resources/startup/320x460.jpg',
        '640x920': 'resources/startup/640x920.png',
        '768x1004': 'resources/startup/768x1004.png',
        '748x1024': 'resources/startup/748x1024.png',
        '1536x2008': 'resources/startup/1536x2008.png',
        '1496x2048': 'resources/startup/1496x2048.png'
    },

    viewport: {
        autoMaximize: true
    },

    launch: function() {
        // Destroy the #appLoadingIndicator element
        Ext.fly('appLoadingIndicator').destroy();

        //var UserSession = sessionStorage.getItem('ACUserKey');
        //UserSession = UserSession.getModel();
        if(!AC.app.userAuth()){
            AC.app.viewRoute('login');
        }else{
            var User = Ext.ModelMgr.getModel('User');
            AC.app.viewRoute('home');
        }

    },

    viewRoute: function(name){
        AC.app.getHistory().add(Ext.create('Ext.app.Action', {
            url: name
        }));
    },

    switchView: function(name, type, direction){
        if(!type)
            type = 'slideOut';
        if(!direction)
            direction = 'left';

        var currentview = Ext.Viewport.getActiveItem();
        if(currentview){
            currentview.hide({type: type, direction: direction}).destroy();
        }
        Ext.Viewport.add(Ext.create('AC.view.' + name)).show();
    },

    userAuth: function(){
        var UserSession = sessionStorage.getItem('ACUserKey');
        var UserId = sessionStorage.getItem('uid');
        if(UserSession && UserId){
            var authResult = false;
            Ext.Ajax.request({
                url: AC.helper.Config.apiUrl + 'site/userAuth',
                params: {
                    uid: UserId,
                    token: UserSession
                },
                async: false,
                withCredentials: false,
                useDefaultXhrHeader: false,
                callback: function(response) {
                    //console.log(response.responseText)
                },
                success: function(response){
                    //var text = response.responseText;
                    var data = Ext.JSON.decode(response.responseText);
                    if(UserId == data.uid && UserSession == data.token){
                        authResult = true;
                    }else{
                        authResult = false;
                    }
                },
                failure: function(response){
                    //console.log(response.responseText);
                    Ext.Msg.alert('Error', response.responseText, function(){
                        sessionStorage.removeItem('ACUserKey');
                        sessionStorage.removeItem('uid');
                        AC.app.viewRoute('login');
                    });
                    authResult = false;
                }
            });
            return authResult;
        }else{
            return false;
        }
    },

    onUpdated: function() {
        Ext.Msg.confirm(
            "Application Update",
            "This application has just successfully been updated to the latest version. Reload now?",
            function(buttonId) {
                if (buttonId === 'yes') {
                    window.location.reload();
                }
            }
        );
    }
});

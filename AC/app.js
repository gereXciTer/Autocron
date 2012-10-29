Ext.application({
    name: 'AC',

    requires: [
        'Ext.form.Panel','Ext.form.FieldSet','Ext.field.Email','Ext.field.Password',
        'Ext.data.proxy.SessionStorage','Ext.data.Store'
    ],

    models: ['User'],
    views: ['Main','Login'],

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

    launch: function() {
        // Destroy the #appLoadingIndicator element
        Ext.fly('appLoadingIndicator').destroy();

        var UserSession = new Ext.data.proxy.SessionStorage({id:'ACUserKey'});
        UserSession = UserSession.getModel();
        if(!UserSession){
            Ext.Viewport.add(Ext.create('AC.view.Login'));
        }else{
            var User = Ext.ModelMgr.getModel('User');
            Ext.Viewport.add(Ext.create('AC.view.Main'));
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

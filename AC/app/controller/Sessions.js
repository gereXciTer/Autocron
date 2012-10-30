Ext.define('AC.controller.Sessions', {
    extend: 'Ext.app.Controller',

    config: {
        routes: {
            'login': 'showLogin'
        },
        refs: {
            loginForm: '#loginForm',
            loginPanel: '#loginpanel',
        },
        control: {
            '#loginForm button': {
                tap: 'doLogin'
            },
            '#logoutbutton': {
                tap: 'doLogout'
            }
        }
    },

    doLogin: function() {
        var form   = this.getLoginForm(),
            values = form.getValues();

        //AC.authenticate(values);
        Ext.Ajax.request({
            url: AC.app.apiUrl + '?r=site/login',
            params: {
                email: values.email,
                password: values.password
            },
            withCredentials: false,
            useDefaultXhrHeader: false,
            callback: function(response) {
                //console.log(values);
                //console.log(response.data);
            },
            success: function(response){
                //var text = response.responseText;
                var data = Ext.JSON.decode(response.responseText);
                sessionStorage.removeItem('ACUserKey');
                sessionStorage.removeItem('uid');
                sessionStorage.setItem('ACUserKey', data.sessionKey);
                sessionStorage.setItem('uid', data.uid);
                Ext.Viewport.getActiveItem().hide({type: 'slide', direction: 'bottom'}).destroy();
                Ext.Viewport.add(Ext.create('AC.view.Main'));
           },
            failure: function(response){
                var text = response.responseText;
                Ext.Msg.alert('Error', response.responseText);
            }
        });

    },

    doLogout: function(){
        sessionStorage.removeItem('ACUserKey');
        sessionStorage.removeItem('uid');
        Ext.Viewport.getActiveItem().hide({type: 'slide', direction: 'bottom'}).destroy();
        Ext.Viewport.add(Ext.create('AC.view.Login'));

    }
});
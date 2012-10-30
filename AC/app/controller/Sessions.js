Ext.define('AC.controller.Sessions', {
    extend: 'Ext.app.Controller',

    config: {
        routes: {
            'login': 'showLogin',
            'register': 'showRegister',
            'home': 'showHomePage'
        },
        refs: {
            loginForm: '#loginForm',
            loginPanel: '#loginpanel'
        },
        control: {
            'button[action=register]': {
                tap: 'goRegister'
            },
            '#registerpanel button[ui=back]': {
                tap: 'showLogin'
            },
            '#loginpanel button[action=login]': {
                tap: 'doLogin'
            },
            'button[action=logout]': {
                tap: 'doLogout'
            }
        }
    },

    showLogin: function(){
        if(AC.app.userAuth()){
            AC.app.getHistory().add(Ext.create('Ext.app.Action', {
                url: 'home'
            }));
        }
        var currentview = Ext.Viewport.getActiveItem();
        if(currentview){
            currentview.hide({type: 'slideOut', direction: 'left'}).destroy();
        }
        Ext.Viewport.add(Ext.create('AC.view.Login')).show();
    },

    showRegister: function(){
        if(AC.app.userAuth()){
            AC.app.getHistory().add(Ext.create('Ext.app.Action', {
                url: 'home'
            }));
        }
        var currentview = Ext.Viewport.getActiveItem();
        if(currentview){
            currentview.hide({type: 'slideOut', direction: 'left'}).destroy();
        }
        Ext.Viewport.add(Ext.create('AC.view.Register')).show();
    },

    showHomePage: function(){
        AC.app.userAuth();

        var currentview = Ext.Viewport.getActiveItem();
        if(currentview){
            currentview.hide({type: 'slideOut', direction: 'left'}).destroy();
        }
        Ext.Viewport.add(Ext.create('AC.view.Main')).show();
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
                //console.log(response.responseText);
            },
            success: function(response){
                //var text = response.responseText;
                console.log(response.responseText);
                var data = Ext.JSON.decode(response.responseText);
                sessionStorage.removeItem('ACUserKey');
                sessionStorage.removeItem('uid');
                sessionStorage.setItem('ACUserKey', data.sessionKey);
                sessionStorage.setItem('uid', data.uid);
                AC.app.getHistory().add(Ext.create('Ext.app.Action', {
                    url: 'home'
                }));
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
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {
            url: 'login'
        }));

    },

    goRegister: function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {
            url: 'register'
        }));
    }
});
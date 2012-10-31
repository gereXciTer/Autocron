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
                tap: 'goLogin'
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
            AC.app.viewRoute('home');
        }
        AC.app.switchView('Login');
    },

    showRegister: function(){
        var CarMakeStore = Ext.create('Ext.data.Store', {
            autoLoad: true,
            autoSync: true,
            model: 'AC.model.CarMake',
            id: 'CarMakeStore'
        });
        // var CarMake = Ext.ModelMgr.getModel('AC.model.CarMake');
        // CarMake.load(0, {
        //     callback: function(carmake){
        //         console.log(carmake);
        //     }
        // });

        if(AC.app.userAuth()){
            AC.app.viewRoute('home');
        }
        AC.app.switchView('Register');
    },

    showHomePage: function(){
        AC.app.userAuth();

        AC.app.switchView('Main');
    },

    doLogin: function() {
        var form   = this.getLoginForm(),
            values = form.getValues();

        //AC.authenticate(values);
        Ext.Ajax.request({
            url: AC.app.apiUrl + 'site/login',
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
                AC.app.viewRoute('home');
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
        AC.app.viewRoute('login');
    },

    goRegister: function(){
        AC.app.viewRoute('register');
    },

    goLogin: function(){
        history.back();
        //AC.app.viewRoute('login');
    }
});
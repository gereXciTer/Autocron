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
            loginPanel: '#loginpanel',
            carMakeField: 'selectfield[action=choosemake]',
            carModelField: 'selectfield[action=choosemodel]',
            carModelVersionField: 'selectfield[action=choosemodelversion]',
            carModelVariantField: 'selectfield[action=choosemodelvariant]'
        },
        control: {
            'button[action=register]': {
                tap: 'goRegister'
            },
            'selectfield[action=choosemake]': {
                change: 'showCarModels'
            },
            'selectfield[action=choosemodel]': {
                change: 'showCarModelVersions'
            },
            'selectfield[action=choosemodelversion]': {
                change: 'showCarModelVariants'
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
            model: 'AC.model.CarMake',
            sorters: [
                'name'
            ],
            //groupField: 'primary',
            groupDir: 'DESC',
            autoLoad: true,
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

    showCarModels: function(selectbox,newValue,oldValue){
        if(newValue!=null&&!selectbox.initdata){
            var CarModelStore = Ext.create('Ext.data.Store', {
                model: 'AC.model.CarModel',
                sorters: [
                    'name'
                ],
                filters: [
                    {
                        property: 'manufacturer_id',
                        value   : this.getCarMakeField().getValue(),
                        exactMatch: true
                    }
                ],
                autoLoad: false,
                proxy: {
                    type: 'jsonp',
                    params: {
                        uid: sessionStorage.getItem('uid'),
                        token: sessionStorage.getItem('ACUserKey')
                    },
                    extraParams: {
                        manufacturer_id: this.getCarMakeField().getValue()
                    },
                    url : AC.app.apiUrl + 'api/CarModel',
                    reader: {
                        type: 'json',
                        rootProperty: 'items'
                    }
                },
                id: 'CarModelStore'
            });
            this.getCarModelField().disable();
            CarModelStore.load();
            this.getCarModelField().setStore('CarModelStore').enable();
            this.getCarModelVersionField().disable();
            this.getCarModelVariantField().disable();
        }
    },

    showCarModelVersions: function(selectbox,newValue,oldValue){
        if(newValue!=null&&!selectbox.initdata){
            var CarModelVersionStore = Ext.create('Ext.data.Store', {
                model: 'AC.model.CarModelVersion',
                sorters: [
                    'name'
                ],
                filters: [
                    {
                        property: 'model_id',
                        value   : this.getCarModelField().getValue(),
                        exactMatch: true
                    }
                ],
                autoLoad: false,
                proxy: {
                    type: 'jsonp',
                    params: {
                        uid: sessionStorage.getItem('uid'),
                        token: sessionStorage.getItem('ACUserKey')
                    },
                    extraParams: {
                        model_id: this.getCarModelField().getValue()
                    },
                    url : AC.app.apiUrl + 'api/CarModelVersion',
                    reader: {
                        type: 'json',
                        rootProperty: 'items'
                    }
                },
                id: 'CarModelVersionStore'
            });
            this.getCarModelVersionField().disable();
            CarModelVersionStore.load();
            this.getCarModelVersionField().setStore('CarModelVersionStore').enable();
            this.getCarModelVariantField().disable();
        }
    },

    showCarModelVariants: function(selectbox,newValue,oldValue){
        if(newValue!=null&&!selectbox.initdata){
            var CarModelVariantStore = Ext.create('Ext.data.Store', {
                model: 'AC.model.CarModelVariant',
                sorters: [
                    'name'
                ],
                filters: [
                    {
                        property: 'model_id',
                        value   : this.getCarModelVersionField().getValue(),
                        exactMatch: true
                    }
                ],
                autoLoad: false,
                proxy: {
                    type: 'jsonp',
                    params: {
                        uid: sessionStorage.getItem('uid'),
                        token: sessionStorage.getItem('ACUserKey')
                    },
                    extraParams: {
                        version_id: this.getCarModelVersionField().getValue()
                    },
                    url : AC.app.apiUrl + 'api/CarModelVariant',
                    reader: {
                        type: 'json',
                        rootProperty: 'items'
                    }
                },
                id: 'CarModelVariantStore'
            });
            this.getCarModelVariantField().disable();
            CarModelVariantStore.load(function(records, operation, success) {
                    // the operation object contains all of the details of the load operation
                    if(!records.length){
                        this.getCarModelVariantField().hide();
                    }else{
                        this.getCarModelVariantField().show();
                        this.getCarModelVariantField().setStore('CarModelVariantStore').enable();
                        console.log(success);
                    }
            }, this);
        }
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
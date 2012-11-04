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
            carModelVariantField: 'selectfield[action=choosemodelvariant]',
            carModelImage: '#registerForm image',
            registerbutton: 'button[action=register]',
            doregisterbutton: 'button[action=doregister]',
            registrationData: '#registrationData',
            registrationForm: '#registerForm'
        },
        control: {
            'selectfield[action=choosemake]': {
                change: 'showCarModels'
            },
            'selectfield[action=choosemodel]': {
                change: 'showCarModelVersions'
            },
            'selectfield[action=choosemodelversion]': {
                change: 'showCarModelVariants'
            },
            'selectfield[action=choosemodelvariant]': {
                change: 'showCarModelImage'
            },
            '#registerForm image': {
                tap: 'nextImage'
            },
            'button[action=doregister]': {
                tap: 'doRegister'
            },
            'button[action=register]': {
                tap: 'goRegister'
            },
            'button[ui=back]': {
                tap: 'goBack'
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

        if(AC.app.userAuth()){
            AC.app.viewRoute('home');
        }
        AC.app.switchView('Register');
    },

    showCarModels: function(selectbox,newValue,oldValue){
        if(newValue!=null&&!selectbox.initdata){
            this.loadCarInfo('CarModel', this.getCarModelField(), {
                manufacturer_id: this.getCarMakeField().getValue()
            });
            this.getCarModelImage().setSrc('').setData(null).hide();
        }
    },

    showCarModelVersions: function(selectbox,newValue,oldValue){
        if(newValue!=null&&!selectbox.initdata){
            this.loadCarInfo('CarModelVersion', this.getCarModelVersionField(), {
                model_id: this.getCarModelField().getValue()
            });
            this.getCarModelImage().setSrc('').setData(null).hide();
        }
    },

    showCarModelVariants: function(selectbox,newValue,oldValue){
        if(newValue!=null&&!selectbox.initdata){
            this.loadCarInfo('CarModelVariant', this.getCarModelVariantField(), {
                version_id: this.getCarModelVersionField().getValue()
            });
            this.getCarModelImage().setSrc('').setData(null).hide();
        }
    },

    showCarModelImage: function(selectbox,newValue,oldValue){
        if(newValue!=null&&!selectbox.initdata){
            var Store = Ext.create('Ext.data.Store', {
                model: 'AC.model.CarModelImage',
                autoLoad: false,
                proxy: {
                    type: 'jsonp',
                    extraParams: {
                        car_model_version_id: this.getCarModelVersionField().getValue()
                    },
                    url : AC.helper.Config.apiUrl + 'api/CarModelVersionImage',
                    reader: {
                        type: 'json',
                        rootProperty: 'items'
                    }
                },
                id: 'CarModelImageStore'
            });
            var image = this.getCarModelImage();
            image.setSrc('').setData(null);
            
            Store.load(function(data){
                image.setData(data);
                if(data.length){
                    image.setSrc(AC.helper.Config.carImagesUrl + data[0].data.url).show();
                }
                setTimeout(function(){
                    Ext.getCmp('registerForm').getScrollable().getScroller().scrollToEnd(true);
                }, 100);
            });
            this.showRegistrationData();
        }
    },

    nextImage: function(){        
        var images = this.getCarModelImage().getData();
        this.getCarModelImage().setSrc(AC.helper.Config.carImagesUrl + images[Math.floor(Math.random() * images.length)].data.url);
        Ext.getCmp('registerForm').getScrollable().getScroller().scrollToEnd(true);
    },

    showRegistrationData: function(){
        this.getRegistrationData().show(true);
        this.getDoregisterbutton().enable();
    },

    loadCarInfo: function(model, formfield, extraParams){
        var filterProperty, filterPropertyValue;
        for (filterProperty in extraParams) {
            if (!extraParams.hasOwnProperty(filterProperty)) {  // Never forget this check!
                continue;
            }

            filterPropertyValue = extraParams[filterProperty];
        }

        var Store = Ext.create('Ext.data.Store', {
            model: 'AC.model.' + model,
            sorters: [
                'name'
            ],
            filters: [
                {
                    property: filterProperty,
                    value   : filterPropertyValue,
                    exactMatch: true
                }
            ],
            autoLoad: false,
            proxy: {
                type: 'jsonp',
                extraParams: extraParams,
                url : AC.helper.Config.apiUrl + 'api/' + model,
                reader: {
                    type: 'json',
                    rootProperty: 'items'
                }
            },
            id: model + 'Store'
        });
        formfield.disable();
        var idnumber = parseInt(formfield._itemId[formfield._itemId.length - 1]);
        for(i = idnumber + 1; i < idnumber + 5; i++){
            var selector = formfield._itemId;
            selector = selector.substring(0,selector.length - 1) + i;
            if(Ext.ComponentManager.get(selector))
                Ext.ComponentManager.get(selector).disable();
        }
        // this.getCarModelField().disable();
        // this.getCarModelVersionField().disable();
        // this.getCarModelVariantField().disable();
        Store.load(function(records, operation, success) {
                // the operation object contains all of the details of the load operation
                if(!records.length){
                    formfield.hide();
                }else{
                    formfield.show();
                    formfield.setStore(model + 'Store').enable();
                }
        }, this);
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
            url: AC.helper.Config.apiUrl + 'site/login',
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
                //console.log(response.responseText);
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

    doRegister: function(){
        var regData = this.getRegistrationForm().getComponent('registrationData');
        if(!regData.getComponent('password').getValue().length){
            Ext.Msg.alert('Error', 'Password can not be blank');
        }else if(regData.getComponent('password').getValue() == regData.getComponent('password_repeat').getValue()){

            Ext.Ajax.request({
                url: AC.helper.Config.apiUrl + 'site/register',
                params: this.getRegistrationForm().getValues(),
                withCredentials: false,
                useDefaultXhrHeader: false,
                success: function(response) {
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

        }else{
            Ext.Msg.alert('Error', 'Password do not match');
        }
    },

    goBack: function(){
        history.back();
    }
});
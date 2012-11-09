Ext.define('AC.controller.Main', {
    extend: 'Ext.app.Controller',

    config: {
        routes: {
            // 'cardetail/:id': 'loadCarDatail',
            // 'usercarslist': 'loadCarsList'
        },
        refs: {
            profileForm: '#profileForm',
            profilePassword: '#password',
            profilePassword2: '#password_repeat',
            homePanel: '#homePanel',
            usercars: 'usercars',
            usercarslist: 'usercarslist'
        },
        control: {
            'button[action=updateprofile]': {
                tap: 'doUpdateProfile'
            },
            'button[action=changepassword]': {
                tap: 'showPassword'
            },
            'tabpanel#mainTabPanel': {
                activeitemchange: 'switchTabs'
            },
            '#homePanel': {
                initialize: 'showHome'
            },
            'usercarslist': {
                // disclose: 'goCarDatail'
                disclose: 'showCarDatail',
                initialize: 'updateCarsList'
            },
            'usercars': {
                back: 'goBack'
            }
        }
    },

    updateCarsList: function(list){
        Ext.create('AC.store.UserCars');
        list.refresh();
    },

    loadCarsList: function(){
        // AC.app.viewRoute('home');
        AC.app.switchView('Main');
    },

    showHome: function(){
    },

    loadCarDatail: function(id){
        Ext.Viewport.add(Ext.create('AC.view.Main'));
        // var UserCars = Ext.create('AC.store.UserCars');
        var usercars = this.getUsercars();
        var UserCar = Ext.ModelManager.getModel('AC.model.UserCar');
        UserCar.load(id, function(record){
            // console.log(usercars);
            usercars.push({
                xtype: 'usercardetail',
                title: record.data.name,
                data: record.getData()
            })
        });
    },

    showCarDatail: function(list, record){
        // AC.app.viewRoute('cardetail/' + record.data.id);
        this.getUsercars().push({
            xtype: 'usercardetail',
            title: record.data.name,
            data: record.getData()
        })
        AC.app.getHistory().add(Ext.create('Ext.app.Action', {
            url: 'cardetail/' + record.data.id
        }));
    },

    switchTabs: function(el, value, oldValue, eOpts){
        if(value.id == "profileForm"){
            this.fillProfileForm();
        }
    },

    showPassword: function(el){
        if(this.getProfilePassword().isHidden()){
            this.getProfilePassword().show('slideIn');
            this.getProfilePassword2().show('slideIn');
            el.setData(el.getText());
            el.setText('Hide Passwords');
        }else{
            this.getProfilePassword().hide('slideOut');
            this.getProfilePassword2().hide('slideOut');
            el.setText(el.getData());
        }
    },

    fillProfileForm: function(){
        var loadingMask = new Ext.LoadMask({
            message: "Loading..."
        });
        Ext.Viewport.add(loadingMask);
        var User = Ext.ModelManager.getModel('AC.model.User');
        var profileForm = this.getProfileForm();
        User.load(sessionStorage.getItem('uid'), {
            success: function(data){
                profileForm.setValues(data.data);
            },
            callback: function(){
                loadingMask.hide();
            }
        });
    },

    doUpdateProfile: function(){
        var loadingMask = new Ext.LoadMask({
            message: "Loading..."
        });
        Ext.Viewport.add(loadingMask);

        var User = Ext.ModelManager.getModel('AC.model.User');
        var profileForm = this.getProfileForm();
        User.load(sessionStorage.getItem('uid'), {
            success: function(user) {
                user.set('name', profileForm.getValues().name);
                user.set('email', profileForm.getValues().email);
                user.save({
                    success: function(response) {
                    },
                    failure: function(response) {
                        loadingMask.hide();
                        var text = response;
                        Ext.Msg.alert('Error', text);
                    },
                    callback: function(){
                        loadingMask.hide();
                    }
                });
            }
        });
    },

    goBack: function(el){
        AC.app.getHistory().add(Ext.create('Ext.app.Action', {
            url: el.getActiveItem().id
        }));
        console.log(el.getActiveItem().id);
        return true;
    }

});
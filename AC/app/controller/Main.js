Ext.define('AC.controller.Main', {
    extend: 'Ext.app.Controller',

    config: {
        routes: {
        },
        refs: {
            profileForm: '#profileForm',
            profilePassword: '#password',
            profilePassword2: '#password_repeat',
            homePanel: '#homePanel',
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
            }
        }
    },

    showHome: function(){
        var loadingMask = new Ext.LoadMask({
            message: "Loading..."
        });
        // Ext.Viewport.add(loadingMask);
        // this.getUsercarslist().refresh();

        // var User = Ext.ModelManager.getModel('AC.model.User');
        // User.load(sessionStorage.getItem('uid'), {
        //     success: function(user){
        //         sessionStorage.setItem('userData', user.data);
        //         // var usercars = user.cars();
        //         // usercars.load(function(records, operation, success) {
        //         //     console.log(records);
        //         // }, this);
        //     },
        //     callback: function(){
        //         loadingMask.hide();
        //     }
        // });

        // var userCar = Ext.ModelManager.getModel('AC.model.UserCar');
        // var homePanel = this.getHomePanel();
        // userCar.load(sessionStorage.getItem('uid'), {
        //     success: function(data){
        //         profileForm.setValues(data.data);
        //     },
        //     callback: function(){
        //         loadingMask.hide();
        //     }
        // });
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
    }

});
Ext.define('AC.controller.Main', {
    extend: 'Ext.app.Controller',

    config: {
        routes: {
        },
        refs: {
            profileForm: '#profileForm',
        },
        control: {
            'button[action=updateprofile]': {
                tap: 'doUpdateProfile'
            },
            '#profileForm': {
                show: 'fillProfileForm'
            }
        }
    },

    fillProfileForm: function(){
        var User = Ext.ModelManager.getModel('AC.model.User');
        var profileForm = this.getProfileForm();
        User.load(sessionStorage.getItem('uid'), {
            success: function(data){
                
                profileForm.setValues(data.data);
                console.log(data.data);
            }
        });
    },

    doUpdateProfile: function(){
        console.log(this.getProfileForm().getValues());
    }

});
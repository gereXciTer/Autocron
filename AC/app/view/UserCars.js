Ext.define('AC.view.UserCars', {
    extend: 'Ext.navigation.View',
    xtype: 'usercars',
    requires: [
        'AC.view.UserCarsList'
    ],

    config: {
        items: [{
            xtype: 'usercarslist'
        }]
    }
});
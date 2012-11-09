Ext.define('AC.view.UserCars', {
    extend: 'Ext.navigation.View',
    xtype: 'usercars',
    requires: [
        'AC.view.UserCarsList',
        'AC.view.UserCarDetail'
    ],

    config: {
        // navigationBar: false,
        items: [{
            xtype: 'usercarslist'
        }]
    }
});
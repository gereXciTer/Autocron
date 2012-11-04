Ext.define('AC.model.User', {

    extend: 'Ext.data.Model',

    config: {
        // proxy: {
        //     type: 'ajax',
        //     url : AC.helper.Config.apiUrl + 'user/' + model,
        //     reader: {
        //         type: 'json',
        //         rootProperty: 'items'
        //     }
        // },

        fields: ['id', 'name', 'email', 'password']
    }
});

/*
// Uses the User Model's Proxy
Ext.create('Ext.data.Store', {
    model: 'User',
    proxy: {
        type: 'sessionstorage',
        id : 'ACUserKey'
    },
});
*/
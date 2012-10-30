Ext.define('AC.model.User', {

    extend: 'Ext.data.Model',

    config: {
        proxy: {
            type: 'ajax',
            url : 'users.json',
            reader: {
                type: 'json',
                rootProperty: 'users'
            }
        },

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
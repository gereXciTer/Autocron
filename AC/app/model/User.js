Ext.define('AC.model.User', {

    extend: 'Ext.data.Model',

    config: {
        fields: ['id', 'name', 'email', 'password', 'password_repeat'],
        proxy: {
            type: 'rest',
            headers: {
                autocrontoken : sessionStorage.getItem('ACUserKey'),
                autocronuserid: sessionStorage.getItem('uid')
            },
            url : AC.helper.Config.apiUrl + 'api/user'
            // url : AC.helper.Config.apiUrl + 'test.php'
            // url : 'http://localhost/autocron/test.php'
            // reader: {
            //     type: 'json',
            //     rootProperty: 'items'
            // }
        }
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
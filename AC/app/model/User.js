Ext.define('AC.model.User', {

    extend: 'Ext.data.Model',

    config: {
        fields: ['id', 'name', 'email', 'password'],
        hasMany  : {model: 'AC.model.UserCar', name: 'cars'},
        proxy: {
            type: 'rest',
            headers: {
                autocrontoken : sessionStorage.getItem('ACUserKey'),
                autocronuserid: sessionStorage.getItem('uid')
            },
            // autoLoad: true,
            url : AC.helper.Config.apiUrl + 'api/user'
        }
    }
});

Ext.define('AC.model.CarMake', {

    extend: 'Ext.data.Model',

    config: {
        fields: ['id', 'name', 'name_ru', 'code', 'primary'],
        proxy: {
            type: 'jsonp',
                params: {
                    uid: sessionStorage.getItem('uid'),
                    token: sessionStorage.getItem('ACUserKey')
                },
            url : AC.app.apiUrl + 'api/CarMake',
            reader: {
                type: 'json',
                rootProperty: 'items'
            }
        }
    }
});


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
            withCredentials: false,
            useDefaultXhrHeader: false,
            cors: true,
            headers: {
                'HTTP_X_AUTOCRON_USERID': sessionStorage.getItem('uid'),
                'HTTP_X_AUTOCRON_TOKEN':sessionStorage.getItem('ACUserKey')
            },
            reader: {
                type: 'json',
                rootProperty: 'items'
            }
        }
    }
});


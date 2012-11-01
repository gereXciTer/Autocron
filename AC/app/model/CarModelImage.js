Ext.define('AC.model.CarModelImage', {

    extend: 'Ext.data.Model',

    config: {
        // proxy: {
        //     type: 'jsonp',
        //     params: {
        //         uid: sessionStorage.getItem('uid'),
        //         token: sessionStorage.getItem('ACUserKey')
        //     },
        //     url : AC.helper.Config.apiUrl + 'api/CarModelImage',
        //     reader: {
        //         type: 'json',
        //         rootProperty: 'items'
        //     }
        // },
        fields: ['id', 'car_model_version_id', 'url']
    }
});


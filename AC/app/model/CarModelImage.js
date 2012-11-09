Ext.define('AC.model.CarModelImage', {

    extend: 'Ext.data.Model',

    config: {
        proxy: {
            type: 'rest',
            headers: {
                autocrontoken : sessionStorage.getItem('ACUserKey'),
                autocronuserid: sessionStorage.getItem('uid')
            },
            url : AC.helper.Config.apiUrl + 'api/CarModelVersionImage'
        },
        fields: ['id', 'car_model_version_id', 'url']
    }
});


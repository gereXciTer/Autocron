Ext.define('AC.model.UserCar', {

    extend: 'Ext.data.Model',

    config: {
        fields: ['id', 'uid', 'car_id', 'car_variant', 'name', 'date_added', 'mileage_initial', 'mileage', 'image', 'year_built'],
        hasMany  : {model: 'AC.model.CarModelImage', name: 'images', filterProperty: 'car_id'},
        proxy: {
            type: 'rest',
            headers: {
                autocrontoken : sessionStorage.getItem('ACUserKey'),
                autocronuserid: sessionStorage.getItem('uid')
            },
            url : AC.helper.Config.apiUrl + 'api/userCar'
        }
    }
});

Ext.define('AC.model.CarModelVersion', {

    extend: 'Ext.data.Model',

    config: {
        fields: ['id', 'model_id', 'name', 'image']
//        hasMany: {model: 'AC.model.CarModelImage', name: 'images'}
    }
});


Ext.define('AC.model.CarModelVariant', {

    extend: 'Ext.data.Model',

    config: {
        fields: ['id', 'name', 'code', 'model_name', 'model_img', 'model_id', 'version_id', 'doors', 'power', 'maxspeed', 'acceleration', 'capacity', 'start', 'stop']
    }
});


Ext.define('AC.store.UserCars', {

    extend: 'Ext.data.Store',

    config: {
        model: 'AC.model.UserCar',
        belongsTo: 'AC.model.User',
        sorters: [
            'name'
        ],
        autoLoad: true,
        id: 'UserCars',
        storeId: 'UserCars',
        listeners: {
            load: function(data, records){
                for(i = 0; i < records.length; i++){
                    var record = records[i];                    
                    if(!record.data.image.length){
                        record.images().load(function(images){
                            if(images.length){
                                record.data.image = AC.helper.Config.carImagesUrl + images[0].data.url;
                            }
                        });                    
                    }
                }
            }
        }
    }
});

Ext.define('AC.store.UserCars', {

    extend: 'Ext.data.Store',

    config: {
        model: 'AC.model.UserCar',
        belongsTo: 'AC.model.User',
        sorters: [
            'name'
        ],
        autoLoad: true,
        id: 'UserCars'
    }
});

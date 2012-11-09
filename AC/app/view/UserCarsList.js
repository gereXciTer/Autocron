Ext.define('AC.view.UserCarsList', {
    extend: 'Ext.List',
    xtype: 'usercarslist',
    id: 'usercarslist',
    requires: ['AC.store.UserCars'],
    
    config: {
        title: 'Your cars',
        // grouped: true,
        itemTpl: '{name}',
        store: 'UserCars',
        onItemDisclosure: true,
        autoDestroy: true
    }
});
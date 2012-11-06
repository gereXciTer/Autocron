Ext.define('AC.view.UserCarsList', {
    extend: 'Ext.List',
    xtype: 'usercarslist',
    requires: ['AC.store.UserCars'],
    
    config: {
        // grouped: true,
        itemTpl: '{name}',
        store: 'UserCars',
        onItemDisclosure: true,
        autoDestroy: true
    }
});
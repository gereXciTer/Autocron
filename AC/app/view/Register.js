Ext.define("AC.view.Register", {
    extend: 'Ext.Panel',
    config: {
        id: 'registerpanel',
        layout: 'fit',
        items: [
            {
                docked: 'top',
                xtype: 'titlebar',
                title: AC.app.title,
                items: [
                    {
                        xtype: 'button',
                        text: 'Back',
                        ui: 'back'
                    }
                ]
            },
            {
                xtype: 'formpanel',
                id: 'registerForm',
                items: [
                    {
                        xtype: 'fieldset',
                        title: 'Choose a car',
                        instructions: '(all fields are required)',
                        items: [
                            {
                                xtype: 'selectfield',
                                name: 'make',
                                label: 'Choose Make',
                                placeHolder: 'Choose one...',
                                store: 'CarMakeStore',
                                displayField: 'name'
                            }
                        ]
                    },
                    {
                        xtype: 'button',
                        text: 'Register',
                        ui: 'action'
                    }
                ]
            }
        ]
    }
});

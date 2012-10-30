Ext.define("AC.view.Login", {
    extend: 'Ext.Panel',
    config: {
        id: 'loginpanel',
        layout: 'fit',
        items: [
            {
                docked: 'top',
                xtype: 'titlebar',
                title: 'Login to Autocron'
            },
            {
                xtype: 'formpanel',
                id: 'loginForm',
                items: [
                    {
                        xtype: 'fieldset',
                        title: 'Login',
                        instructions: '(all fields are required)',
                        items: [
                            {
                                xtype: 'emailfield',
                                name: 'email',
                                label: 'Email'
                            },
                            {
                                xtype: 'passwordfield',
                                name: 'password',
                                label: 'Password'
                            }
                        ]
                    },
                    {
                        xtype: 'button',
                        text: 'Login',
                        ui: 'confirm'
                    }
                ]
            }
        ]
    }
});

Ext.define("AC.view.Login", {
    extend: 'Ext.Panel',
    config: {
        layout: 'fit',
        items: [
            {
                docked: 'top',
                xtype: 'titlebar',
                title: 'Login to Autocron'
            },
            {
                xtype: 'formpanel',
                items: [
                    {
                        xtype: 'fieldset',
                        title: 'Login',
                        instructions: '(all fields are required)',
                        items: [
                            {
                                xtype: 'emailfield',
                                label: 'Email'
                            },
                            {
                                xtype: 'passwordfield',
                                label: 'Password'
                            }
                        ]
                    },
                    {
                        xtype: 'button',
                        text: 'Login',
                        ui: 'confirm',
                        handler: function() {
                            this.up('formpanel').submit();
                        }
                    }
                ]
            }
        ]
    }
});

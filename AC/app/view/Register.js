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
                            },
                            {
                                xtype: 'passwordfield',
                                name: 'password',
                                label: 'Password Repeat'
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

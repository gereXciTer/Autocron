Ext.define("AC.view.Login", {
    extend: 'Ext.Panel',
    config: {
        id: 'loginpanel',
        layout: 'fit',
        items: [
            {
                docked: 'top',
                xtype: 'titlebar',
                title: AC.helper.Config.title
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
                        xtype: 'segmentedbutton',
//                        docked: 'bottom',
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [
                            {
                                flex: 1,
                                xtype: 'button',
                                text: 'Login',
                                action: 'login',
                                ui: 'confirm'
                            },
                            {
                                flex: 1,
                                xtype: 'button',
                                text: 'Register',
                                action: 'register',
                                ui: 'action'
                            }
                        ]
                    }
                ]
            }
        ]
    }
});

Ext.define("AC.view.Main", {
    extend: 'Ext.tab.Panel',
    requires: [
        'Ext.TitleBar',
        'AC.view.UserCars'
    ],
    id: 'mainTabPanel',
    config: {
        tabBarPosition: 'bottom',
        items: [
            {
                docked: 'top',
                xtype: 'titlebar',
                title: AC.helper.Config.title,
                items: [
                    {
                        xtype: 'button',
                        action: 'logout',
                        text: 'Logout',
                    }
                ]
            },
            {
                title: 'Home',
                iconCls: 'home',
                id: 'homePanel',
                xtype: 'usercarslist',
                // styleHtmlContent: true,
                scrollable: true

            },
            {
                title: 'Profile',
                iconCls: 'user',
                xtype: 'formpanel',
                id: 'profileForm',
                items: [
                    {
                        xtype: 'fieldset',
                        id: 'profileData',
                        hidden: false,
                        title: 'Profile data',
                        items: [
                            {
                                xtype: 'textfield',
                                name: 'name',
                                required: true,
                                label: 'Name'
                            },
                            {
                                xtype: 'emailfield',
                                name: 'email',
                                required: true,
                                label: 'Email'
                            },
                    //     ]
                    // },
                    // {
                    //     xtype: 'fieldset',
                    //     id: 'profilePassword',
                    //     hidden: true,
                    //     items: [
                            {
                                xtype: 'passwordfield',
                                id: 'password',
                                name: 'password',
                                hidden: true,
                                required: false,
                                label: 'New Password'
                            },
                            {
                                xtype: 'passwordfield',
                                id: 'password_repeat',
                                name: 'password_repeat',
                                hidden: true,
                                required: false,
                                label: 'Confirm Password'
                            },
                            {
                                xtype: 'button',
                                text: 'Change password',
                                action: 'changepassword',
                                ui: 'default'
                            }
                        ]
                    },
                    {
                        xtype: 'button',
                        action: 'updateprofile',
                        text: 'Update',
                        ui: 'action'
                    }
                ]
            }
        ]
    }
});

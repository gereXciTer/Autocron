Ext.define("AC.view.Main", {
    extend: 'Ext.tab.Panel',
    requires: [
        'Ext.TitleBar'
    ],
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

                styleHtmlContent: true,
                scrollable: true,

                items: [
                ],

                html: [
                    "You've just generated a new Sencha Touch 2 project. What you're looking at right now is the ",
                    "contents of <a target='_blank' href=\"app/view/Main.js\">app/view/Main.js</a> - edit that file ",
                    "and refresh to change what's rendered here."
                ].join("")
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
                            {
                                xtype: 'passwordfield',
                                id: 'password',
                                name: 'password',
                                required: true,
                                label: 'New Password'
                            },
                            {
                                xtype: 'passwordfield',
                                id: 'password_repeat',
                                name: 'password_repeat',
                                required: true,
                                label: 'Confirm Password'
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

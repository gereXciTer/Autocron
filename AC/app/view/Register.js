Ext.define('AC.field.Select', {
  override: 'Ext.field.Select',

  onStoreDataChanged: function(store) {
      var initialConfig = this.getInitialConfig(),
          value = this.getValue();

      if (Ext.isDefined(value)) {
          this.updateValue(this.applyValue(value));
      }
      if (this.getValue() === null && !this.getPlaceHolder()) {
          if (initialConfig.hasOwnProperty('value')) {
              this.setValue(initialConfig.value);
          }

          if (this.getValue() === null) {
              if (store.getCount() > 0) {
                  this.setValue(store.getAt(0));
              }
          }
      }
  },

  showPicker: function() {
        var store = this.getStore();
        //check if the store is empty, if it is, return
        if (!store || store.getCount() === 0) {
            return;
        }

        if (this.getReadOnly()) {
            return;
        }

        this.isFocused = true;

        if (this.getUsePicker()) {
            var picker = this.getPhonePicker(),
                name   = this.getName(),
                value  = {};

            if (!this.getPlaceHolder()) {
                value[name] = this.record.get(this.getValueField());
                picker.setValue(value);
            }
            if (!picker.getParent()) {
                Ext.Viewport.add(picker);
            }
            picker.show();
        } else {
            var listPanel = this.getTabletPicker(),
                list = listPanel.down('list'),
                store = list.getStore(),
                index = store.find(this.getValueField(), this.getValue(), null, null, null, true),
                record = store.getAt((index == -1) ? 0 : index);

            if (!listPanel.getParent()) {
                Ext.Viewport.add(listPanel);
            }

            listPanel.showBy(this.getComponent());
            if (!this.getPlaceHolder()) {
                list.select(record, null, true);
            }
        }
    },
    updateStore: function(newStore) {
        if (newStore) {
            this.onStoreDataChanged(newStore);
        }

        if (this.getUsePicker() && this.picker) {
            this.picker.down('pickerslot').setStore(newStore);
        } else if (this.listPanel) {
            this.listPanel.down('dataview').setStore(newStore);
        }
    }
});
Ext.define("AC.view.Register", {
    extend: 'Ext.Panel',
    config: {
        id: 'registerpanel',
        layout: 'fit',
        fullscreen: true,
        items: [
            {
                docked: 'top',
                xtype: 'titlebar',
                title: AC.helper.Config.title,
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
                                label: 'Make',
                                action: 'choosemake',
                                placeHolder: 'Choose one...',
                                store: 'CarMakeStore',
                                displayField: 'name',
                                valueField: 'id',
                                listeners: {
                                    initialize:function(selectbox)
                                    {                                           
                                        selectbox.initdata = false; //set the custom variable to false once the selectbox options have been set
                                    }
                                }
                            },
                            {
                                xtype: 'selectfield',
                                name: 'model',
                                disabled: true,
                                label: 'Model',
                                action: 'choosemodel',
                                placeHolder: 'Choose one...',
                                displayField: 'name',
                                valueField: 'id',
                                listeners: {
                                    initialize:function(selectbox)
                                    {                                           
                                        selectbox.initdata = false; //set the custom variable to false once the selectbox options have been set
                                    }
                                }
                            },
                            {
                                xtype: 'selectfield',
                                name: 'modelversion',
                                disabled: true,
                                label: 'Version',
                                action: 'choosemodelversion',
                                placeHolder: 'Choose one...',
                                displayField: 'name',
                                valueField: 'id',
                                listeners: {
                                    initialize:function(selectbox)
                                    {                                           
                                        selectbox.initdata = false; //set the custom variable to false once the selectbox options have been set
                                    }
                                }
                            },
                            {
                                xtype: 'selectfield',
                                name: 'modelvariant',
                                disabled: true,
                                label: 'Variant',
                                action: 'choosemodelvariant',
                                placeHolder: 'Choose one...',
                                displayField: 'name',
                                valueField: 'id',
                                listeners: {
                                    initialize:function(selectbox)
                                    {                                           
                                        selectbox.initdata = false; //set the custom variable to false once the selectbox options have been set
                                    },
                                    tap:function(){
                                        console.log('tap');
                                    }
                                }
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


Ext.define('AC.view.UserCarDetail', {
    extend: 'Ext.Panel',
    xtype: 'usercardetail',

    config: {
        title: 'Car Details',
        cls: 'car-detail',
        styleHtmlContent: true,
        scrollable: 'vertical',
        tpl: [
            '<div>',
            '    <img src="{image}" class="carImage" /> <h4>{name}</h4>',
            '    <small>Added: {date_added} at {mileage_initial}</small>',
            '</div>'

        ]
    }
});
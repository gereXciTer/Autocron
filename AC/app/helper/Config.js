Ext.define('AC.helper.Config', {
	singleton : true,
	
    title: 'TestApp',
    apiUrl: (window.location.href.search('autocron.ru') > 0) ? 'http://autocron.ru/api/' : 'http://localhost/api/',
    carImagesUrl: 'http://autocron.ru/images/cars/',
    headersAppId: 'AUTOCRON'

});
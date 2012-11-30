Ext.define('AC.helper.Config', {
	singleton : true,
	
    title: 'TestApp',
    apiUrl: (window.location.href.search('autocron.ru') > 0) ? 'http://autocron.ru/ac/api/' : 'http://localhost/ac/api/',
    carImagesUrl: 'http://autocron.ru/images/cars/',
    headersAppId: 'AUTOCRON',
    autocrontoken : sessionStorage.getItem('ACUserKey'),
    autocronuserid: sessionStorage.getItem('uid')

});
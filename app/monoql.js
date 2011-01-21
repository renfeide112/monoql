Ext.ns('monoql', 'ui');
Ext.QuickTips.init();
Ext.onReady(function(){
	Ext.apply(monoql, {
		webroot:'/monoql',
		url:function(path) {
			return (this.webroot + path).replace(/\/{2,}/i, '/');
		}
	});
	Ext.BLANK_IMAGE_URL = 'ext/resources/images/default/s.gif';
	ui = new monoql.panel.viewport({
		id:'viewport'
	});
	ui.store = new monoql.data.connectionstore();
});
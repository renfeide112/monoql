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
	ui.connectionstore = new monoql.data.connectionstore();
	ui.connectionstore.load();
	Ext.apply(ui, new monoql.panel.viewport({
		id:'viewport'
	}));
	ui.tree.initListeners();
	ui.tabs.initListeners();
});
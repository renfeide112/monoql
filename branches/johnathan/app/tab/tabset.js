Ext.ns('monoql.tab');
monoql.tab.tabset = function() {
	var cls = 'monoql-tab-tabset';
	var Class = Ext.extend(Ext.TabPanel, {
		resizeTabs:true,
		tabWidth:100,
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();
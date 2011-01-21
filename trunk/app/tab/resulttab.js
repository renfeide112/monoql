Ext.ns('monoql.tab');
monoql.tab.resulttab = function() {
	var cls = 'monoql-tab-resulttab';
	var Class = Ext.extend(monoql.tab.tab, {
		title:'Results',
		layout:'fit',
		border:false,
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();
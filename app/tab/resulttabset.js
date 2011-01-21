Ext.ns('monoql.tab');
monoql.tab.resulttabset = function() {
	var cls = 'monoql-tab-resulttabset';
	var Class = Ext.extend(monoql.tab.tabset, {
		activeTab:0,
		border:false,
		initComponent: function() {
			this.resulttab = new monoql.tab.resulttab();
			this.messagetab = new monoql.tab.messagetab();
			this.items = [this.resulttab, this.messagetab];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();
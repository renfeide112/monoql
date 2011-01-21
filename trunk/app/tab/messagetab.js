Ext.ns('monoql.tab');
monoql.tab.messagetab = function() {
	var cls = 'monoql-tab-messagetab';
	var Class = Ext.extend(monoql.tab.tab, {
		title:'Messages',
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
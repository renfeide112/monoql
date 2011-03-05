Ext.ns('monoql.tab');
monoql.tab.tab = function() {
	var cls = 'monoql-tab-tab';
	var Class = Ext.extend(Ext.Panel, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();
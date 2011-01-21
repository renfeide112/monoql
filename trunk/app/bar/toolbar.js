Ext.ns('monoql.bar');
monoql.bar.toolbar = function() {
	var cls = 'monoql-bar-toolbar';
	var Class = Ext.extend(monoql.bar.bar, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();
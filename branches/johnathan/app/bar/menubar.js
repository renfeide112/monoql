Ext.ns('monoql.bar');
monoql.bar.menubar = function() {
	var cls = 'monoql-bar-menubar';
	var Class = Ext.extend(monoql.bar.bar, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();